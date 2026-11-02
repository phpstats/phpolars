<?php
$data = 'https://raw.githubusercontent.com'
    .'/phpstats/datasets/main/csv';
$jfk = read_csv(
    "$data/weather.csv",
    columns: ['origin', 'time_hour', 'temp'],
    tryParseDates: true,
)
    ->filter(col('origin')->eq('JFK'))
    ->select('time_hour', 'temp')
    ->sort('time_hour');

// Calendar days, in UTC.
echo $jfk->groupByDynamic('time_hour', every: '1d')
    ->agg(col('temp')->mean()->round(1)->alias('mean_temp'))
    ->head(3), "\n";

// The 24 hours ending at each reading.
echo $jfk->withColumns(
    col('temp')->rollingMeanBy('time_hour', '24h')->round(1)
        ->alias('last_24h'),
)->head(4);
