```php
$penguins = read_csv('penguins.csv', nullValues: 'NA');

$mass = col('body_mass_g');
echo $penguins
    ->select(
        'species',
        'island',
        col('sex')->fillNull('unknown'),
        ['bill_ratio' => col('bill_length_mm')
            ->truediv(col('bill_depth_mm'))->round(2)],
        when($mass->ge(4000))->then(lit('large'))
            ->when($mass->lt(4000))->then(lit('small'))
            ->alias('size'),
    )
    ->head(5);
```

```{.text .output}
shape: (5, 5)
┌─────────┬───────────┬─────────┬────────────┬───────┐
│ species ┆ island    ┆ sex     ┆ bill_ratio ┆ size  │
│ ---     ┆ ---       ┆ ---     ┆ ---        ┆ ---   │
│ str     ┆ str       ┆ str     ┆ f64        ┆ str   │
╞═════════╪═══════════╪═════════╪════════════╪═══════╡
│ Adelie  ┆ Torgersen ┆ male    ┆ 2.09       ┆ small │
│ Adelie  ┆ Torgersen ┆ female  ┆ 2.27       ┆ small │
│ Adelie  ┆ Torgersen ┆ female  ┆ 2.24       ┆ small │
│ Adelie  ┆ Torgersen ┆ unknown ┆ null       ┆ null  │
│ Adelie  ┆ Torgersen ┆ female  ┆ 1.9        ┆ small │
└─────────┴───────────┴─────────┴────────────┴───────┘
```
