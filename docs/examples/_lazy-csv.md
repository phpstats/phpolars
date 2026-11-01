```php
use function Phpolars\{col, len, scan_csv};

$query = scan_csv('orders.csv')
    ->filter(col('amount')->gt(500)->and(col('returned')->not()))
    ->groupBy('region')
    ->agg(
        len()->alias('orders'),
        col('amount')->mean()->round(2)->alias('avg_amount'),
    )
    ->sort('region');

echo $query->explain(), "\n\n";
echo $query->collect();
```

```{.text .output}
SORT BY [col("region")]
  AGGREGATE[maintain_order: false]
    [len().alias("orders"), col("amount").mean().round().alias("avg_amount")] BY [col("region")]
    FROM
    simple π 2/2 ["region", "amount"]
      Csv SCAN [orders.csv]
      PROJECT 3/6 COLUMNS
      SELECTION: (col("amount") > 500.0) & col("returned").not()
      ESTIMATED ROWS: 1481549

shape: (4, 3)
┌────────┬────────┬────────────┐
│ region ┆ orders ┆ avg_amount │
│ ---    ┆ ---    ┆ ---        │
│ str    ┆ u32    ┆ f64        │
╞════════╪════════╪════════════╡
│ east   ┆ 107143 ┆ 750.0      │
│ north  ┆ 107133 ┆ 750.0      │
│ south  ┆ 107142 ┆ 749.99     │
│ west   ┆ 107141 ┆ 750.01     │
└────────┴────────┴────────────┘
```
