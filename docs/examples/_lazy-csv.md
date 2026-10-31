```php
<?php
use Phpolars\DataFrame;
use function Phpolars\{col, int_range, len, scan_csv};

// Write a CSV file of one million orders to query.
(new DataFrame(['i' => [0]]))
    ->select(int_range(0, 1_000_000)->alias('order_id'))
    ->withColumns(
        col('order_id')->mod(4)->replaceStrict([0, 1, 2, 3], ['north', 'south', 'east', 'west'], returnDtype: 'String')->alias('region'),
        col('order_id')->mul(7919)->mod(5000)->alias('customer_id'),
        col('order_id')->mul(104729)->mod(100_000)->truediv(100)->alias('amount'),
        col('order_id')->mul(31)->mod(7)->eq(0)->alias('returned'),
        col('order_id')->cast('String')->str()->replace('^', 'note for order ')->alias('note'),
    )
    ->writeCsv('orders.csv');

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

unlink('orders.csv');
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
