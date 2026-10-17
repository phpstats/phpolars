<?php

// phplyr verbs on phibble's in-memory DataFrame and on a PolarsTable. The site
// runs this script on each request; see site/README.md for the format.

use Phibble\Contracts\Table;
use Phibble\DataFrame;
use Phibble\Polars\PolarsTable;
use Phpolars\DataFrame as PolarsFrame;

use function Phplyr\{col, desc, mean, n, phplyr, sum};

return [
    'inputs' => [
        'rows' => ['type' => 'select', 'label' => 'Sales rows', 'options' => [10_000 => '10,000', 50_000 => '50,000', 100_000 => '100,000'], 'default' => 50_000],
        'min_units' => ['type' => 'number', 'label' => 'Keep sales with more units than', 'min' => 0, 'max' => 15, 'step' => 1, 'default' => 0],
    ],
    'run' => function (array $input, string $data): array {
        $rows = (int) $input['rows'];
        $minUnits = (int) $input['min_units'];

        // Simulated sales: the values are computed from the row number, so every run
        // sees the same data. Polars builds them; phibble gets a copy.
        $sales = (new PolarsFrame(['i' => [0]]))
            ->select(Phpolars\int_range(0, $rows)->alias('id'))
            ->withColumns(
                Phpolars\col('id')->mul(7919)->mod(100)->add(1)->alias('store'),
                Phpolars\col('id')->mul(104729)->mod(21)->alias('units'),
                Phpolars\col('id')->mul(2654435761)->mod(9000)->truediv(100)->add(1)->alias('base'),
            )
            ->withColumns(Phpolars\col('base')->add(Phpolars\col('store')->truediv(4))->round(2)->alias('price'))
            ->drop('base');
        $stores = (new PolarsFrame(['store' => range(1, 100)]))
            ->withColumns(Phpolars\col('store')->sub(1)->mod(5)->add(1)->cast('String')->str()->replace('^', 'region-')->alias('region'));

        $pipeline = fn (Table $sales, Table $stores): Table => phplyr($sales)
            ->filter(col('units')->gt($minUnits))
            ->left_join($stores, by: 'store')
            ->group_by('region')
            ->summarise(sales: n(), revenue: sum(col('units')->mul(col('price'))), avg_price: mean('price'))
            ->arrange(desc('revenue'))
            ->get();

        $time = function (callable $f): array {
            $best = INF;
            for ($i = 0; $i < 3; $i++) {
                $t = hrtime(true);
                $out = $f();
                $best = min($best, (hrtime(true) - $t) / 1e6);
            }
            return [$out, $best];
        };

        $inMemory = [DataFrame::fromColumns($sales->toColumns()), DataFrame::fromColumns($stores->toColumns())];
        $inPolars = [PolarsTable::fromPolars($sales), PolarsTable::fromPolars($stores)];
        [$memoryResult, $memoryMs] = $time(fn () => $pipeline(...$inMemory)->collect());
        [$polarsResult, $polarsMs] = $time(fn () => $pipeline(...$inPolars)->collect());

        $round = fn (array $columns) => array_map(fn ($values) => array_map(fn ($v) => is_float($v) ? round($v, 6) : $v, $values), $columns);
        $same = $round($memoryResult->toColumns()) === $round($polarsResult->toColumns());

        return [
            ['type' => 'text', 'content' => sprintf(
                'phplyr verbs work on any phibble table. `PolarsTable` keeps its data in a Polars frame from phpolars, and phplyr verbs given column names and `col()` expressions run there as Polars expressions. '
                .'This page runs the same pipeline on phibble\'s in-memory `DataFrame` and on a `PolarsTable`, over %s sales rows and a 100-row store table.',
                number_format($rows))],
            ['type' => 'code',
                'chain' => <<<PHP
                    use Phibble\\Polars\\PolarsTable;
                    use function Phplyr\\{col, desc, mean, n, phplyr, sum};

                    \$sales = PolarsTable::fromPolars(Phpolars\\read_parquet('sales.parquet'));
                    \$stores = PolarsTable::fromPolars(Phpolars\\read_csv('stores.csv'));

                    \$revenue = phplyr(\$sales)
                        ->filter(col('units')->gt($minUnits))
                        ->left_join(\$stores, by: 'store')
                        ->group_by('region')
                        ->summarise(sales: n(), revenue: sum(col('units')->mul(col('price'))), avg_price: mean('price'))
                        ->arrange(desc('revenue'))
                        ->get();
                    PHP,
                'pipe' => <<<PHP
                    use Phibble\\Polars\\PolarsTable;
                    use function Phplyr\\{arrange, col, desc, filter, group_by, left_join, mean, n, sum, summarise};

                    \$sales = PolarsTable::fromPolars(Phpolars\\read_parquet('sales.parquet'));
                    \$stores = PolarsTable::fromPolars(Phpolars\\read_csv('stores.csv'));

                    \$revenue = \$sales
                        |> filter(col('units')->gt($minUnits))
                        |> left_join(\$stores, by: 'store')
                        |> group_by('region')
                        |> summarise(sales: n(), revenue: sum(col('units')->mul(col('price'))), avg_price: mean('price'))
                        |> arrange(desc('revenue'));
                    PHP],
            ['type' => 'metrics', 'items' => [
                ['label' => 'phibble DataFrame', 'value' => $memoryMs, 'format' => '%.1f ms'],
                ['label' => 'PolarsTable', 'value' => $polarsMs, 'format' => '%.1f ms'],
                ['label' => 'Speedup', 'value' => $memoryMs / $polarsMs, 'format' => '%.1f×'],
            ]],
            ['type' => 'table', 'title' => 'Revenue by region (PolarsTable)', 'data' => $polarsResult, 'digits' => 2],
            ['type' => 'note', 'content' => $same
                ? 'Both backends return the same table. phplyr follows dplyr\'s rules on either one, so a pipeline can move to Polars without changing its results.'
                : 'The two backends returned different tables. Please report this at https://github.com/phpstats/phpolars/issues.'],
            ['type' => 'text', 'content' => 'A verb given a PHP closure, such as `filter(fn ($r) => $r->units > 0)`, cannot run on Polars threads. `PolarsTable` copies the table to an in-memory `DataFrame`, runs the closure there, and copies the result back, so use `col()` expressions to keep the work in Polars.'],
        ];
    },
];
