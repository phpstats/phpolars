<?php
use Phpolars\DataFrame;
use function Phpolars\{col, len};

$orders = new DataFrame([
    'region' => ['north', 'north', 'south', 'south', 'south', 'west'],
    'customer' => ['ana', 'ben', 'ana', 'cy', 'cy', 'di'],
    'amount' => [120.0, 80.0, 45.5, 300.0, 60.0, 95.0],
    'returned' => [false, true, false, false, true, false],
]);

$byRegion = $orders
    ->groupBy('region', maintainOrder: true)
    ->agg(
        len()->alias('orders'),
        col('amount')->sum()->alias('total'),
        col('amount')->filter(col('returned')->not())->sum()->alias('kept'),
        col('customer')->nUnique()->alias('customers'),
        col('customer')->sortBy('amount', descending: true)->first()->alias('top_customer'),
    );

echo $byRegion;
