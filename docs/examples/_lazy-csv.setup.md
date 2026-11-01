```php
use Phpolars\DataFrame;
use function Phpolars\{col, int_range};

if (file_exists('orders.csv')) {
    fwrite(STDERR, "orders.csv already exists.\n");
    exit(1);
}

$id = col('order_id');
(new DataFrame(['i' => [0]]))
    ->select(int_range(0, 1_000_000)->alias('order_id'))
    ->withColumns(
        $id->mod(4)
            ->replaceStrict(
                [0, 1, 2, 3],
                ['north', 'south', 'east', 'west'],
                returnDtype: 'String',
            )
            ->alias('region'),
        $id->mul(7919)->mod(5000)->alias('customer_id'),
        $id->mul(104729)->mod(100_000)->truediv(100)
            ->alias('amount'),
        $id->mul(31)->mod(7)->eq(0)->alias('returned'),
        $id->cast('String')->str()
            ->replace('^', 'note for order ')
            ->alias('note'),
    )
    ->writeCsv('orders.csv');
```
