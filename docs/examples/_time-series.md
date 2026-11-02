```php
use function Phpolars\{col, datetime, read_csv, scan_csv};

$data = 'https://raw.githubusercontent.com'
    .'/phpstats/datasets/main/csv';
$jfkJan1 = [
    col('origin')->eq('JFK'),
    col('month')->eq(1),
    col('day')->eq(1),
];
$at = fn (string ...$parts) => datetime(
    ...array_map(fn ($p) => col($p), $parts),
);

// Hourly temperature at JFK, with a 3-hour rolling mean.
$weather = read_csv("$data/weather.csv", columns: [
    'origin', 'year', 'month', 'day', 'hour', 'temp',
])
    ->filter(...$jfkJan1)
    ->select($at('year', 'month', 'day', 'hour')
        ->alias('time'), 'temp')
    ->sort('time')
    ->withColumns(
        col('temp')->rollingMeanBy('time', '3h')
            ->round(1)
            ->alias('temp_3h'),
    );

// Departures scheduled from 12:55 to 13:10.
$flights = scan_csv("$data/flights.csv", nullValues: 'NA')
    ->filter(...$jfkJan1)
    ->select('carrier', 'flight', $at(
        'year', 'month', 'day', 'hour', 'minute',
    )->alias('scheduled'))
    ->filter(col('scheduled')->dt()->strftime('%H:%M')
        ->isBetween('12:55', '13:10'))
    ->sort('scheduled')
    ->collect();

echo $flights->joinAsof(
    $weather,
    leftOn: 'scheduled',
    rightOn: 'time',
);
```

```{.text .output}
shape: (4, 6)
┌─────────┬────────┬─────────────────────┬─────────────────────┬───────┬─────────┐
│ carrier ┆ flight ┆ scheduled           ┆ time                ┆ temp  ┆ temp_3h │
│ ---     ┆ ---    ┆ ---                 ┆ ---                 ┆ ---   ┆ ---     │
│ str     ┆ i64    ┆ datetime[μs]        ┆ datetime[μs]        ┆ f64   ┆ f64     │
╞═════════╪════════╪═════════════════════╪═════════════════════╪═══════╪═════════╡
│ B6      ┆ 209    ┆ 2013-01-01 12:58:00 ┆ 2013-01-01 11:00:00 ┆ 41.0  ┆ 40.6    │
│ B6      ┆ 1006   ┆ 2013-01-01 12:59:00 ┆ 2013-01-01 11:00:00 ┆ 41.0  ┆ 40.6    │
│ B6      ┆ 32     ┆ 2013-01-01 12:59:00 ┆ 2013-01-01 11:00:00 ┆ 41.0  ┆ 40.6    │
│ B6      ┆ 991    ┆ 2013-01-01 13:00:00 ┆ 2013-01-01 13:00:00 ┆ 37.94 ┆ 39.5    │
└─────────┴────────┴─────────────────────┴─────────────────────┴───────┴─────────┘
```
