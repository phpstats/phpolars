<?php
$query = scan_csv('penguins.csv', nullValues: 'NA')
    ->filter(col('body_mass_g')->gt(4500))
    ->groupBy('species')
    ->agg(len()->alias('penguins'))
    ->sort('species');

echo $query->explain(), "\n\n";
echo $query->collect();
