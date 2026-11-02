```php
// nycflights13's flights table: 336,776 rows, 33 MB.
$url = 'https://raw.githubusercontent.com/phpstats/datasets'
    .'/main/csv/flights.csv';

if (file_exists('flights.csv')) {
    fwrite(STDERR, "flights.csv already exists.\n");
    exit(1);
}
copy($url, 'flights.csv');
echo filesize('flights.csv'), " bytes\n";
```

```{.text .output}
33206996 bytes
```
