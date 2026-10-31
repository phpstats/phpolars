```php
<?php
use function Phpolars\{col, len, read_csv};

$sales = read_csv('sales.csv', tryParseDates: true);

$byProduct = $sales
    ->filter(col('units')->gt(0))
    ->withColumns(col('units')->mul(col('price'))->alias('revenue'))
    ->groupBy('product', maintainOrder: true)
    ->agg(col('revenue')->sum()->round(2), ['orders' => len()])
    ->sort('revenue', descending: true);

echo $byProduct, "\n";
print_r($byProduct->row(0, named: true));
```

```{.text .output}
shape: (4, 3)
┌─────────┬─────────┬────────┐
│ product ┆ revenue ┆ orders │
│ ---     ┆ ---     ┆ ---    │
│ str     ┆ f64     ┆ u32    │
╞═════════╪═════════╪════════╡
│ cherry  ┆ 1307.38 ┆ 44     │
│ banana  ┆ 1285.76 ┆ 48     │
│ apple   ┆ 1227.68 ┆ 42     │
│ date    ┆ 1153.58 ┆ 41     │
└─────────┴─────────┴────────┘
Array
(
    [product] => cherry
    [revenue] => 1307.38
    [orders] => 44
)
```
