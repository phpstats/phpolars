<?php
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
