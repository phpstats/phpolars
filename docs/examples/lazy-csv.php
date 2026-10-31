<?php
use Phpolars\DataFrame;
use function Phpolars\{col, int_range, len, scan_csv};

// Write a CSV file of one million orders to query.
(new DataFrame(['i' => [0]]))
    ->select(int_range(0, 1_000_000)->alias('order_id'))
    ->withColumns(
        col('order_id')->mod(4)->replaceStrict([0, 1, 2, 3], ['north', 'south', 'east', 'west'], returnDtype: 'String')->alias('region'),
        col('order_id')->mul(7919)->mod(5000)->alias('customer_id'),
        col('order_id')->mul(104729)->mod(100_000)->truediv(100)->alias('amount'),
        col('order_id')->mul(31)->mod(7)->eq(0)->alias('returned'),
        col('order_id')->cast('String')->str()->replace('^', 'note for order ')->alias('note'),
    )
    ->writeCsv('orders.csv');

$query = scan_csv('orders.csv')
    ->filter(col('amount')->gt(500)->and(col('returned')->not()))
    ->groupBy('region')
    ->agg(
        len()->alias('orders'),
        col('amount')->mean()->round(2)->alias('avg_amount'),
    )
    ->sort('region');

echo $query->explain(), "\n\n";
echo $query->collect();

unlink('orders.csv');
