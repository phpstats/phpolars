```php
use function Phpolars\{col, len, read_csv};

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
    )
    ->sort('mass_kg', descending: true);

echo $bySpecies, "\n";
print_r($bySpecies->row(0, named: true));
```

```{.text .output}
shape: (3, 3)
┌───────────┬──────────┬─────────┐
│ species   ┆ penguins ┆ mass_kg │
│ ---       ┆ ---      ┆ ---     │
│ str       ┆ u32      ┆ f64     │
╞═══════════╪══════════╪═════════╡
│ Gentoo    ┆ 123      ┆ 5.08    │
│ Chinstrap ┆ 68       ┆ 3.73    │
│ Adelie    ┆ 151      ┆ 3.7     │
└───────────┴──────────┴─────────┘
Array
(
    [species] => Gentoo
    [penguins] => 123
    [mass_kg] => 5.08
)
```
