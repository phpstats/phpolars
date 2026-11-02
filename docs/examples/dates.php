<?php
$data = 'https://raw.githubusercontent.com'
    .'/phpstats/datasets/main/csv';
$weather = read_csv(
    "$data/weather.csv",
    columns: ['time_hour'],
    tryParseDates: true,
)->head(3);

$t = col('time_hour');
echo $weather->withColumns(
    $t->dt()->weekday()->alias('weekday'),
    $t->dt()->hour()->alias('hour'),
    $t->dt()->convertTimeZone('America/New_York')
        ->dt()->strftime('%b %d %H:%M')->alias('new_york'),
    $t->dt()->truncate('1d')->alias('day'),
), "\n";

// Date and Datetime values come back as DateTimeImmutable.
$first = $weather->item(0, 'time_hour');
echo get_class($first), ' ', $first->format('c'), "\n";
