<?php
$data = 'https://raw.githubusercontent.com'
    .'/phpstats/datasets/main/csv';
$flights = scan_csv("$data/flights.csv", nullValues: 'NA')
    ->filter(
        col('origin')->eq('JFK'),
        col('month')->eq(1),
        col('day')->eq(1),
    )
    ->select('carrier', 'flight', 'dest')
    ->collect();
$airports = read_csv("$data/airports.csv")
    ->select('faa', 'name');

echo $flights
    ->join($airports, leftOn: 'dest', rightOn: 'faa',
        how: 'left', maintainOrder: 'left')
    ->head(4), "\n";
echo $flights
    ->join($airports, leftOn: 'dest', rightOn: 'faa',
        how: 'anti')
    ->select('dest')->unique()->sort('dest'), "\n";
echo $flights
    ->join($airports, leftOn: 'dest', rightOn: 'faa')
    ->height(), " of ", $flights->height(),
    " flights matched\n";
