<?php

// A lazy query over a large file. The site runs this script on each request;
// see site/README.md for the format.

use Phpolars\DataFrame;
use Phpolars\LazyFrame;

use function Phpolars\{col, int_range, scan_csv, scan_parquet, read_csv, read_parquet};

return [
    'inputs' => [
        'rows' => ['type' => 'select', 'label' => 'Rows in the file', 'options' => [1_000_000 => '1 million', 5_000_000 => '5 million'], 'default' => 1_000_000],
        'format' => ['type' => 'radio', 'label' => 'File format', 'options' => ['parquet' => 'Parquet', 'csv' => 'CSV'], 'default' => 'parquet'],
        'min_amount' => ['type' => 'number', 'label' => 'Keep orders with an amount above', 'min' => 0, 'max' => 990, 'step' => 10, 'default' => 500],
    ],
    'run' => function (array $input, string $data): array {
        $rows = (int) $input['rows'];
        $format = $input['format'];
        $min = (float) $input['min_amount'];
        $path = sys_get_temp_dir()."/phpolars-vignette-orders-$rows.$format";

        // Build the file once per size and format. The columns are computed from the
        // row number, so every run sees the same data.
        if (! is_file($path)) {
            $orders = (new DataFrame(['i' => [0]]))
                ->select(int_range(0, $rows)->alias('order_id'))
                ->withColumns(
                    col('order_id')->mul(7919)->mod(20)->alias('region_id'),
                    col('order_id')->mul(104729)->mod(4999)->alias('customer_id'),
                    col('order_id')->mul(2654435761)->mod(100_000)->truediv(100)->alias('base'),
                    col('order_id')->mul(40503)->mod(7)->eq(0)->alias('returned'),
                    col('order_id')->mul(31)->mod(365)->alias('day'),
                    col('order_id')->mul(613)->mod(3)->alias('channel_id'),
                )
                ->withColumns(
                    col('base')->mul(col('region_id')->mul(0.04)->add(0.6))->round(2)->alias('amount'),
                    col('region_id')->cast('String')->str()->zfill(2)->str()->replace('^', 'region-')->alias('region'),
                    col('channel_id')->replaceStrict([0, 1, 2], ['web', 'store', 'phone'], returnDtype: 'String')->alias('channel'),
                    col('order_id')->cast('String')->str()->replace('^', 'gift wrap requested for order ')->alias('note'),
                )
                ->select('order_id', 'region', 'customer_id', 'amount', 'returned', 'day', 'channel', 'note');
            $tmp = "$path.tmp";
            $format === 'parquet' ? $orders->writeParquet($tmp) : $orders->writeCsv($tmp);
            rename($tmp, $path);
        }

        $scan = fn (): LazyFrame => $format === 'parquet' ? scan_parquet($path) : scan_csv($path);
        $query = fn (LazyFrame $orders): LazyFrame => $orders
            ->filter(col('amount')->gt($min)->and(col('returned')->not()))
            ->groupBy('region')
            ->agg(
                col('order_id')->count()->alias('orders'),
                col('amount')->sum()->round(2)->alias('revenue'),
                col('customer_id')->nUnique()->alias('customers'),
            )
            ->sort('revenue', descending: true);

        // Best of three runs, so the first read of the file doesn't count.
        $time = function (callable $f): array {
            $best = INF;
            for ($i = 0; $i < 3; $i++) {
                $t = hrtime(true);
                $out = $f();
                $best = min($best, (hrtime(true) - $t) / 1e6);
            }
            return [$out, $best];
        };
        [$result, $lazyMs] = $time(fn () => $query($scan())->collect());
        [, $eagerMs] = $time(fn () => $query(($format === 'parquet' ? read_parquet($path) : read_csv($path))->lazy())->collect());
        $plan = str_replace($path, "orders.$format", $query($scan())->explain());
        $columns = count($scan()->collectSchema());

        return [
            ['type' => 'text', 'content' => sprintf(
                'A lazy query describes the work before any data is read. `scan_%s()` returns a `LazyFrame`, the verbs add steps to its plan, and `collect()` hands the whole plan to Polars, which optimizes it and runs it in parallel. '
                .'Here the file holds %s orders with %d columns, and the query uses five of them.',
                $format, number_format($rows), $columns)],
            ['type' => 'code', 'chain' => <<<PHP
                use function Phpolars\\{col, scan_$format};

                \$top = scan_$format('orders.$format')
                    ->filter(col('amount')->gt($min)->and(col('returned')->not()))
                    ->groupBy('region')
                    ->agg(
                        col('order_id')->count()->alias('orders'),
                        col('amount')->sum()->round(2)->alias('revenue'),
                        col('customer_id')->nUnique()->alias('customers'),
                    )
                    ->sort('revenue', descending: true)
                    ->collect();
                PHP],
            ['type' => 'metrics', 'items' => [
                ['label' => 'Lazy scan and query', 'value' => $lazyMs, 'format' => '%.0f ms'],
                ['label' => 'Read the whole file, then query', 'value' => $eagerMs, 'format' => '%.0f ms'],
                ['label' => 'Regions', 'value' => $result->height(), 'format' => '%d'],
            ]],
            ['type' => 'table', 'title' => 'Revenue by region', 'columns' => $result->columns(), 'rows' => $result->rows(), 'digits' => 2],
            ['type' => 'text', 'content' => '`explain()` prints the optimized plan. Polars pushes the filter into the scan, so rows are dropped while the file is read, and it reads only the columns the query uses.'],
            ['type' => 'output', 'content' => $plan],
            ['type' => 'note', 'content' => 'The eager version reads every column of every row into memory before filtering. The lazy version skips that work, and the gap grows with the file. Parquet stores columns separately, so leaving columns out saves more than it does with CSV.'],
        ];
    },
];
