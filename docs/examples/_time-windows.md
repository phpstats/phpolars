```php
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
```

```{.text .output}
shape: (3, 2)
┌─────────────────────────┬───────────┐
│ time_hour               ┆ mean_temp │
│ ---                     ┆ ---       │
│ datetime[μs, UTC]       ┆ f64       │
╞═════════════════════════╪═══════════╡
│ 2013-01-01 00:00:00 UTC ┆ 38.9      │
│ 2013-01-02 00:00:00 UTC ┆ 28.5      │
│ 2013-01-03 00:00:00 UTC ┆ 29.8      │
└─────────────────────────┴───────────┘
shape: (4, 3)
┌─────────────────────────┬───────┬──────────┐
│ time_hour               ┆ temp  ┆ last_24h │
│ ---                     ┆ ---   ┆ ---      │
│ datetime[μs, UTC]       ┆ f64   ┆ f64      │
╞═════════════════════════╪═══════╪══════════╡
│ 2013-01-01 06:00:00 UTC ┆ 39.02 ┆ 39.0     │
│ 2013-01-01 07:00:00 UTC ┆ 39.02 ┆ 39.0     │
│ 2013-01-01 08:00:00 UTC ┆ 39.92 ┆ 39.3     │
│ 2013-01-01 09:00:00 UTC ┆ 39.92 ┆ 39.5     │
└─────────────────────────┴───────┴──────────┘
```
