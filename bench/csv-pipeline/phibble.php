<?php

use Phibble\DataFrame;

use function Phibble\Expr\{col, mean, n, str_sub, sum};

$path = $argv[1];
$start = hrtime(true);

$summary = DataFrame::fromCsv($path, types: ['placed_on' => 'c'])
    ->filter(col('status')->eq('completed'))
    ->mutate(['month' => str_sub(col('placed_on'), 1, 7)])
    ->groupBy(['region', 'month'])
    ->summarize([
        'orders' => n(),
        'units' => sum(col('quantity')),
        'revenue' => sum(col('amount'), na_rm: true),
        'mean_amount' => mean(col('amount'), na_rm: true),
    ])
    ->arrange(['region', 'month']);

$rows = array_map(array_values(...), $summary->toRows());
$ms = (hrtime(true) - $start) / 1e6;
echo json_encode(['ms' => $ms, 'maxrss' => getrusage()['ru_maxrss'], 'rows' => $rows, 'reader' => getenv('PHIBBLE_NATIVE_CSV') !== '0' && extension_loaded('phit') ? 'phit' : 'php']), "\n";
