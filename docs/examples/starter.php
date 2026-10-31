<?php
use function Phpolars\{col, len, read_csv};

$sales = read_csv('sales.csv', tryParseDates: true);

$byProduct = $sales
    ->filter(col('units')->gt(0))
    ->withColumns(col('units')->mul(col('price'))->alias('revenue'))
    ->groupBy('product', maintainOrder: true)
    ->agg(col('revenue')->sum()->round(2), ['orders' => len()])
    ->sort('revenue', descending: true);

echo $byProduct, "\n";
print_r($byProduct->row(0, named: true));
