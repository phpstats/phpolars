<?php
use function Phpolars\{col, len, scan_csv};

$query = scan_csv('orders.csv')
    ->filter(
        col('amount')->gt(500)
            ->and(col('returned')->not()),
    )
    ->groupBy('region')
    ->agg(
        len()->alias('orders'),
        col('amount')->mean()->round(2)->alias('mean'),
    )
    ->sort('region');

echo $query->explain(), "\n\n";
echo $query->collect();
