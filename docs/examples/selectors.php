<?php
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
