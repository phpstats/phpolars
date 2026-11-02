```php
$data = 'https://raw.githubusercontent.com'
    .'/phpstats/datasets/main/csv';
$weather = read_csv(
    "$data/weather.csv",
    columns: ['time_hour'],
    tryParseDates: true,
)->head(3);

$t = col('time_hour');
echo $weather->withColumns(
    $t->dt()->weekday()->alias('weekday'),
    $t->dt()->hour()->alias('hour'),
    $t->dt()->convertTimeZone('America/New_York')
        ->dt()->strftime('%b %d %H:%M')->alias('new_york'),
    $t->dt()->truncate('1d')->alias('day'),
), "\n";

// Date and Datetime values come back as DateTimeImmutable.
$first = $weather->item(0, 'time_hour');
echo get_class($first), ' ', $first->format('c'), "\n";
```

```{.text .output}
shape: (3, 5)
┌─────────────────────────┬─────────┬──────┬──────────────┬─────────────────────────┐
│ time_hour               ┆ weekday ┆ hour ┆ new_york     ┆ day                     │
│ ---                     ┆ ---     ┆ ---  ┆ ---          ┆ ---                     │
│ datetime[μs, UTC]       ┆ i8      ┆ i8   ┆ str          ┆ datetime[μs, UTC]       │
╞═════════════════════════╪═════════╪══════╪══════════════╪═════════════════════════╡
│ 2013-01-01 06:00:00 UTC ┆ 2       ┆ 6    ┆ Jan 01 01:00 ┆ 2013-01-01 00:00:00 UTC │
│ 2013-01-01 07:00:00 UTC ┆ 2       ┆ 7    ┆ Jan 01 02:00 ┆ 2013-01-01 00:00:00 UTC │
│ 2013-01-01 08:00:00 UTC ┆ 2       ┆ 8    ┆ Jan 01 03:00 ┆ 2013-01-01 00:00:00 UTC │
└─────────────────────────┴─────────┴──────┴──────────────┴─────────────────────────┘
DateTimeImmutable 2013-01-01T06:00:00+00:00
```
