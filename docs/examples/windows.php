<?php
$data = 'https://raw.githubusercontent.com'
    .'/phpstats/datasets/main/csv';
$weather = read_csv("$data/weather.csv", columns: [
    'origin', 'month', 'day', 'hour', 'temp',
])
    ->filter(
        col('month')->eq(1),
        col('day')->eq(1),
        col('hour')->le(3),
    );

$temp = col('temp');
echo $weather->select(
    'origin',
    'hour',
    'temp',
    $temp->mean()->over('origin')->round(1)
        ->alias('origin_mean'),
    $temp->diff()->over('origin', orderBy: 'hour')
        ->alias('change'),
    $temp->shift()->over('origin', orderBy: 'hour')
        ->alias('previous'),
    $temp->rank('dense', descending: true)->alias('rank'),
);
