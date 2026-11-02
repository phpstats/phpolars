<?php
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
