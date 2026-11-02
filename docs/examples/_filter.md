```php
$penguins = read_csv('penguins.csv', nullValues: 'NA');

// The predicates combine with and; null counts as false.
echo $penguins->filter(
    col('species')->isIn(['Adelie', 'Chinstrap']),
    col('flipper_length_mm')->isBetween(195, 200),
    col('island')->ne('Torgersen'),
)->select('species', 'island', 'flipper_length_mm', 'sex')
    ->head(5);
```

```{.text .output}
shape: (5, 4)
┌─────────┬────────┬───────────────────┬────────┐
│ species ┆ island ┆ flipper_length_mm ┆ sex    │
│ ---     ┆ ---    ┆ ---               ┆ ---    │
│ str     ┆ str    ┆ i64               ┆ str    │
╞═════════╪════════╪═══════════════════╪════════╡
│ Adelie  ┆ Dream  ┆ 195               ┆ female │
│ Adelie  ┆ Dream  ┆ 196               ┆ male   │
│ Adelie  ┆ Dream  ┆ 195               ┆ male   │
│ Adelie  ┆ Dream  ┆ 196               ┆ male   │
│ Adelie  ┆ Biscoe ┆ 200               ┆ male   │
└─────────┴────────┴───────────────────┴────────┘
```
