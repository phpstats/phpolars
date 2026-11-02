```php
use function Phpolars\{col, len, read_csv};

$data = 'https://raw.githubusercontent.com'
    .'/phpstats/datasets/main/csv';
$penguins = read_csv(
    "$data/penguins.csv",
    nullValues: 'NA',
);

$bySpecies = $penguins
    ->groupBy('species', maintainOrder: true)
    ->agg(
        len()->alias('penguins'),
        col('body_mass_g')->mean()->round()
            ->alias('mass_g'),
        col('body_mass_g')
            ->filter(col('sex')->eq('female'))
            ->mean()
            ->round()
            ->alias('female_g'),
        col('island')->nUnique()->alias('islands'),
        col('island')
            ->sortBy(
                'body_mass_g',
                descending: true,
                nullsLast: true,
            )
            ->first()
            ->alias('heaviest_on'),
    );

echo $bySpecies;
```

```{.text .output}
shape: (3, 6)
┌───────────┬──────────┬────────┬──────────┬─────────┬─────────────┐
│ species   ┆ penguins ┆ mass_g ┆ female_g ┆ islands ┆ heaviest_on │
│ ---       ┆ ---      ┆ ---    ┆ ---      ┆ ---     ┆ ---         │
│ str       ┆ u32      ┆ f64    ┆ f64      ┆ u32     ┆ str         │
╞═══════════╪══════════╪════════╪══════════╪═════════╪═════════════╡
│ Adelie    ┆ 152      ┆ 3701.0 ┆ 3369.0   ┆ 3       ┆ Biscoe      │
│ Gentoo    ┆ 124      ┆ 5076.0 ┆ 4680.0   ┆ 1       ┆ Biscoe      │
│ Chinstrap ┆ 68       ┆ 3733.0 ┆ 3527.0   ┆ 1       ┆ Dream       │
└───────────┴──────────┴────────┴──────────┴─────────┴─────────────┘
```
