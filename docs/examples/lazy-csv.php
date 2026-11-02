<?php
use function Phpolars\{col, len, scan_csv};

$query = scan_csv('flights.csv', nullValues: 'NA')
    ->filter(col('dep_delay')->gt(60))
    ->groupBy('origin')
    ->agg(
        len()->alias('flights'),
        col('arr_delay')->mean()->round(1)
            ->alias('arr_delay'),
    )
    ->sort('origin');

echo $query->explain(), "\n\n";
echo $query->collect();
