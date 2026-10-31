```php
<?php
use Phpolars\DataFrame;
use function Phpolars\col;

// Daily unit sales, with no rows for March 3 and March 7.
$sales = (new DataFrame([
    'date' => ['2024-03-01', '2024-03-02', '2024-03-04', '2024-03-05', '2024-03-06', '2024-03-08'],
    'units' => [12, 15, 9, 20, 14, 18],
]))->withColumns(col('date')->str()->toDate());

// List prices, each in force from its date until the next change.
$prices = (new DataFrame([
    'from' => ['2024-03-01', '2024-03-05'],
    'price' => [4.50, 4.95],
]))->withColumns(col('from')->str()->toDate());

$priced = $sales
    ->joinAsof($prices, leftOn: 'date', rightOn: 'from')
    ->withColumns(
        col('units')->mul(col('price'))->alias('revenue'),
        col('units')->rollingMeanBy('date', '3d')->round(2)->alias('units_3d'),
    );

echo $priced;
```

```{.text .output}
shape: (6, 6)
┌────────────┬───────┬────────────┬───────┬─────────┬──────────┐
│ date       ┆ units ┆ from       ┆ price ┆ revenue ┆ units_3d │
│ ---        ┆ ---   ┆ ---        ┆ ---   ┆ ---     ┆ ---      │
│ date       ┆ i64   ┆ date       ┆ f64   ┆ f64     ┆ f64      │
╞════════════╪═══════╪════════════╪═══════╪═════════╪══════════╡
│ 2024-03-01 ┆ 12    ┆ 2024-03-01 ┆ 4.5   ┆ 54.0    ┆ 12.0     │
│ 2024-03-02 ┆ 15    ┆ 2024-03-01 ┆ 4.5   ┆ 67.5    ┆ 13.5     │
│ 2024-03-04 ┆ 9     ┆ 2024-03-01 ┆ 4.5   ┆ 40.5    ┆ 12.0     │
│ 2024-03-05 ┆ 20    ┆ 2024-03-05 ┆ 4.95  ┆ 99.0    ┆ 14.5     │
│ 2024-03-06 ┆ 14    ┆ 2024-03-05 ┆ 4.95  ┆ 69.3    ┆ 14.33    │
│ 2024-03-08 ┆ 18    ┆ 2024-03-05 ┆ 4.95  ┆ 89.1    ┆ 16.0     │
└────────────┴───────┴────────────┴───────┴─────────┴──────────┘
```
