<?php
$penguins = read_csv('penguins.csv', nullValues: 'NA');

// The predicates combine with and; null counts as false.
echo $penguins->filter(
    col('species')->isIn(['Adelie', 'Chinstrap']),
    col('flipper_length_mm')->isBetween(195, 200),
    col('island')->ne('Torgersen'),
)->select('species', 'island', 'flipper_length_mm', 'sex')
    ->head(5);
