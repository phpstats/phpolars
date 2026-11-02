```php
$query = scan_csv('penguins.csv', nullValues: 'NA')
    ->filter(col('body_mass_g')->gt(4500))
    ->groupBy('species')
    ->agg(len()->alias('penguins'))
    ->sort('species');

echo $query->explain(), "\n\n";
echo $query->collect();
```

```{.text .output}
SORT BY [col("species")]
  AGGREGATE[maintain_order: false]
    [len().alias("penguins")] BY [col("species")]
    FROM
    simple π 1/1 ["species"]
      Csv SCAN [penguins.csv]
      PROJECT 2/9 COLUMNS
      SELECTION: col("body_mass_g") > 4500
      ESTIMATED ROWS: 343

shape: (3, 2)
┌───────────┬──────────┐
│ species   ┆ penguins │
│ ---       ┆ ---      │
│ str       ┆ u32      │
╞═══════════╪══════════╡
│ Adelie    ┆ 7        │
│ Chinstrap ┆ 2        │
│ Gentoo    ┆ 106      │
└───────────┴──────────┘
```
