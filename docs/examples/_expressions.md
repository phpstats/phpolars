```php
<?php
use Phpolars\DataFrame;
use function Phpolars\{col, len};

$orders = new DataFrame([
    'region' => ['north', 'north', 'south', 'south', 'south', 'west'],
    'customer' => ['ana', 'ben', 'ana', 'cy', 'cy', 'di'],
    'amount' => [120.0, 80.0, 45.5, 300.0, 60.0, 95.0],
    'returned' => [false, true, false, false, true, false],
]);

$byRegion = $orders
    ->groupBy('region', maintainOrder: true)
    ->agg(
        len()->alias('orders'),
        col('amount')->sum()->alias('total'),
        col('amount')->filter(col('returned')->not())->sum()->alias('kept'),
        col('customer')->nUnique()->alias('customers'),
        col('customer')->sortBy('amount', descending: true)->first()->alias('top_customer'),
    );

echo $byRegion;
```

```{.text .output}
shape: (3, 6)
┌────────┬────────┬───────┬───────┬───────────┬──────────────┐
│ region ┆ orders ┆ total ┆ kept  ┆ customers ┆ top_customer │
│ ---    ┆ ---    ┆ ---   ┆ ---   ┆ ---       ┆ ---          │
│ str    ┆ u32    ┆ f64   ┆ f64   ┆ u32       ┆ str          │
╞════════╪════════╪═══════╪═══════╪═══════════╪══════════════╡
│ north  ┆ 2      ┆ 200.0 ┆ 120.0 ┆ 2         ┆ ana          │
│ south  ┆ 3      ┆ 405.5 ┆ 345.5 ┆ 2         ┆ cy           │
│ west   ┆ 1      ┆ 95.0  ┆ 95.0  ┆ 1         ┆ di           │
└────────┴────────┴───────┴───────┴───────────┴──────────────┘
```
