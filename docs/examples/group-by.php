<?php
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
