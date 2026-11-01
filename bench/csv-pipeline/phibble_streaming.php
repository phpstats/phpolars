<?php

use Phibble\DataFrame;

use function Phibble\Expr\{col, is_na, n, str_sub, sum};

$path = $argv[1];
$start = hrtime(true);

$totals = [];
$add = function (DataFrame $chunk) use (&$totals) {
    $part = $chunk
        ->filter(col('status')->eq('completed'))
        ->mutate([
            'month' => str_sub(col('placed_on'), 1, 7),
        ])
        ->groupBy(['region', 'month'])
        ->summarize([
            'orders' => n(),
            'units' => sum(col('quantity')),
            'revenue' => sum(col('amount'), na_rm: true),
            'amounts' => sum(is_na(col('amount'))->not()),
        ]);
    foreach ($part->toRows() as $r) {
        $key = $r['region']."\0".$r['month'];
        $t = $totals[$key] ?? [
            'orders' => 0, 'units' => 0,
            'revenue' => 0.0, 'amounts' => 0,
        ];
        foreach ($t as $field => $total) {
            $t[$field] = $total + $r[$field];
        }
        $totals[$key] = $t;
    }
};
DataFrame::fromCsvChunked(
    $path,
    $add,
    chunkSize: 100_000,
    types: ['placed_on' => 'c'],
);
ksort($totals, SORT_STRING);
$rows = [];
foreach ($totals as $key => $t) {
    [$region, $month] = explode("\0", $key);
    $mean = $t['amounts'] > 0
        ? $t['revenue'] / $t['amounts']
        : null;
    $rows[] = [
        $region, $month,
        $t['orders'], $t['units'], $t['revenue'], $mean,
    ];
}

$ms = (hrtime(true) - $start) / 1e6;
echo json_encode(['ms' => $ms, 'maxrss' => getrusage()['ru_maxrss'], 'rows' => $rows, 'reader' => getenv('PHIBBLE_NATIVE_CSV') !== '0' && extension_loaded('phit') ? 'phit' : 'php']), "\n";
