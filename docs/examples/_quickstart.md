```php
$penguins = read_csv('penguins.csv', nullValues: 'NA');

$bySpecies = $penguins
    ->filter(col('body_mass_g')->isNotNull())
    ->withColumns(
        col('body_mass_g')->truediv(1000)->alias('mass_kg'),
    )
    ->groupBy('species', maintainOrder: true)
    ->agg(
        ['penguins' => len()],
        col('mass_kg')->mean()->round(2),
        col('flipper_length_mm')->mean()->round(1)
            ->alias('flipper_mm'),
    )
    ->sort('mass_kg', descending: true);

echo $bySpecies;
```

```{.text .output}
shape: (3, 4)
┌───────────┬──────────┬─────────┬────────────┐
│ species   ┆ penguins ┆ mass_kg ┆ flipper_mm │
│ ---       ┆ ---      ┆ ---     ┆ ---        │
│ str       ┆ u32      ┆ f64     ┆ f64        │
╞═══════════╪══════════╪═════════╪════════════╡
│ Gentoo    ┆ 123      ┆ 5.08    ┆ 217.2      │
│ Chinstrap ┆ 68       ┆ 3.73    ┆ 195.8      │
│ Adelie    ┆ 151      ┆ 3.7     ┆ 190.0      │
└───────────┴──────────┴─────────┴────────────┘
```
