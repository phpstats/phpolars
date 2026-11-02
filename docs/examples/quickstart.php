<?php
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
