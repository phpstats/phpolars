```php
$penguins = read_csv('penguins.csv', nullValues: 'NA');

echo $penguins
    ->groupBy(['species', 'island'], maintainOrder: true)
    ->agg(
        len()->alias('rows'),
        col('body_mass_g')->mean()->round()
            ->alias('mass_g'),
        col('sex')->nullCount()->alias('missing_sex'),
        col('flipper_length_mm')->max()
            ->alias('max_flipper'),
    )
    ->sort(['species', 'island']), "\n";

// Shorthands aggregate every non-key column.
echo $penguins
    ->select('species', 'bill_length_mm', 'body_mass_g')
    ->groupBy('species')
    ->mean()
    ->sort('species');
```

```{.text .output}
shape: (5, 6)
┌───────────┬───────────┬──────┬────────┬─────────────┬─────────────┐
│ species   ┆ island    ┆ rows ┆ mass_g ┆ missing_sex ┆ max_flipper │
│ ---       ┆ ---       ┆ ---  ┆ ---    ┆ ---         ┆ ---         │
│ str       ┆ str       ┆ u32  ┆ f64    ┆ u32         ┆ i64         │
╞═══════════╪═══════════╪══════╪════════╪═════════════╪═════════════╡
│ Adelie    ┆ Biscoe    ┆ 44   ┆ 3710.0 ┆ 0           ┆ 203         │
│ Adelie    ┆ Dream     ┆ 56   ┆ 3688.0 ┆ 1           ┆ 208         │
│ Adelie    ┆ Torgersen ┆ 52   ┆ 3706.0 ┆ 5           ┆ 210         │
│ Chinstrap ┆ Dream     ┆ 68   ┆ 3733.0 ┆ 0           ┆ 212         │
│ Gentoo    ┆ Biscoe    ┆ 124  ┆ 5076.0 ┆ 5           ┆ 231         │
└───────────┴───────────┴──────┴────────┴─────────────┴─────────────┘
shape: (3, 3)
┌───────────┬────────────────┬─────────────┐
│ species   ┆ bill_length_mm ┆ body_mass_g │
│ ---       ┆ ---            ┆ ---         │
│ str       ┆ f64            ┆ f64         │
╞═══════════╪════════════════╪═════════════╡
│ Adelie    ┆ 38.791391      ┆ 3700.662252 │
│ Chinstrap ┆ 48.833824      ┆ 3733.088235 │
│ Gentoo    ┆ 47.504878      ┆ 5076.01626  │
└───────────┴────────────────┴─────────────┘
```
