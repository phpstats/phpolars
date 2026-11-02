```php
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
```

```{.text .output}
SORT BY [col("origin")]
  AGGREGATE[maintain_order: false]
    [len().alias("flights"), col("arr_delay").mean().round()] BY [col("origin")]
    FROM
    simple π 2/2 ["origin", "arr_delay"]
      Csv SCAN [flights.csv]
      PROJECT 3/20 COLUMNS
      SELECTION: col("dep_delay") > 60
      ESTIMATED ROWS: 368967

shape: (3, 3)
┌────────┬─────────┬───────────┐
│ origin ┆ flights ┆ arr_delay │
│ ---    ┆ ---     ┆ ---       │
│ str    ┆ u32     ┆ f64       │
╞════════╪═════════╪═══════════╡
│ EWR    ┆ 10940   ┆ 117.5     │
│ JFK    ┆ 8401    ┆ 117.8     │
│ LGA    ┆ 7240    ┆ 122.8     │
└────────┴─────────┴───────────┘
```
