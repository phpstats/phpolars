```php
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
```

```{.text .output}
shape: (9, 7)
┌────────┬──────┬───────┬─────────────┬────────┬──────────┬──────┐
│ origin ┆ hour ┆ temp  ┆ origin_mean ┆ change ┆ previous ┆ rank │
│ ---    ┆ ---  ┆ ---   ┆ ---         ┆ ---    ┆ ---      ┆ ---  │
│ str    ┆ i64  ┆ f64   ┆ f64         ┆ f64    ┆ f64      ┆ u32  │
╞════════╪══════╪═══════╪═════════════╪════════╪══════════╪══════╡
│ EWR    ┆ 1    ┆ 39.02 ┆ 39.0        ┆ null   ┆ null     ┆ 3    │
│ EWR    ┆ 2    ┆ 39.02 ┆ 39.0        ┆ 0.0    ┆ 39.02    ┆ 3    │
│ EWR    ┆ 3    ┆ 39.02 ┆ 39.0        ┆ 0.0    ┆ 39.02    ┆ 3    │
│ JFK    ┆ 1    ┆ 39.02 ┆ 39.3        ┆ null   ┆ null     ┆ 3    │
│ JFK    ┆ 2    ┆ 39.02 ┆ 39.3        ┆ 0.0    ┆ 39.02    ┆ 3    │
│ JFK    ┆ 3    ┆ 39.92 ┆ 39.3        ┆ 0.9    ┆ 39.02    ┆ 2    │
│ LGA    ┆ 1    ┆ 39.92 ┆ 40.6        ┆ null   ┆ null     ┆ 2    │
│ LGA    ┆ 2    ┆ 41.0  ┆ 40.6        ┆ 1.08   ┆ 39.92    ┆ 1    │
│ LGA    ┆ 3    ┆ 41.0  ┆ 40.6        ┆ 0.0    ┆ 41.0     ┆ 1    │
└────────┴──────┴───────┴─────────────┴────────┴──────────┴──────┘
```
