<?php

use function Phpolars\{col, len, scan_csv};

$path = $argv[1];
$start = hrtime(true);

$summary = scan_csv($path)
    ->filter(col('status')->eq('completed'))
    ->groupBy(['region', col('placed_on')->str()->slice(0, 7)->alias('month')])
    ->agg(
        len()->alias('orders'),
        col('quantity')->sum()->alias('units'),
        col('amount')->sum()->alias('revenue'),
        col('amount')->mean()->alias('mean_amount'),
    )
    ->sort(['region', 'month'])
    ->collect(engine: 'streaming');

$rows = $summary->rows();
$ms = (hrtime(true) - $start) / 1e6;
echo json_encode(['ms' => $ms, 'maxrss' => getrusage()['ru_maxrss'], 'rows' => $rows, 'threads' => Phpolars\thread_pool_size()]), "\n";
