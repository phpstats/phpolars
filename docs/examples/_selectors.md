```php
use Phpolars\cs;

$penguins = read_csv('penguins.csv', nullValues: 'NA')
    ->head(3);

echo $penguins->select(
    cs\numeric()->sub(cs\by_name('rownames', 'year')),
), "\n";
echo $penguins->select(cs\string()), "\n";
echo $penguins
    ->withColumns(
        cs\float()->round(0)->name()->suffix('_rounded'),
    )
    ->select(cs\ends_with('_rounded'));
```

```{.text .output}
shape: (3, 5)
┌────────────────┬───────────────┬───────────────────┬─────────────┬──────┐
│ bill_length_mm ┆ bill_depth_mm ┆ flipper_length_mm ┆ body_mass_g ┆ year │
│ ---            ┆ ---           ┆ ---               ┆ ---         ┆ ---  │
│ f64            ┆ f64           ┆ i64               ┆ i64         ┆ i64  │
╞════════════════╪═══════════════╪═══════════════════╪═════════════╪══════╡
│ 39.1           ┆ 18.7          ┆ 181               ┆ 3750        ┆ 2007 │
│ 39.5           ┆ 17.4          ┆ 186               ┆ 3800        ┆ 2007 │
│ 40.3           ┆ 18.0          ┆ 195               ┆ 3250        ┆ 2007 │
└────────────────┴───────────────┴───────────────────┴─────────────┴──────┘
shape: (3, 3)
┌─────────┬───────────┬────────┐
│ species ┆ island    ┆ sex    │
│ ---     ┆ ---       ┆ ---    │
│ str     ┆ str       ┆ str    │
╞═════════╪═══════════╪════════╡
│ Adelie  ┆ Torgersen ┆ male   │
│ Adelie  ┆ Torgersen ┆ female │
│ Adelie  ┆ Torgersen ┆ female │
└─────────┴───────────┴────────┘
shape: (3, 2)
┌────────────────────────┬───────────────────────┐
│ bill_length_mm_rounded ┆ bill_depth_mm_rounded │
│ ---                    ┆ ---                   │
│ f64                    ┆ f64                   │
╞════════════════════════╪═══════════════════════╡
│ 39.0                   ┆ 19.0                  │
│ 40.0                   ┆ 17.0                  │
│ 40.0                   ┆ 18.0                  │
└────────────────────────┴───────────────────────┘
```
