# CSV pipeline benchmark

`phpstats.php` and `plain.php` run the same query on a CSV file of order events. Each keeps the completed orders and computes orders, units, revenue, and the mean amount for each region and month (192 groups). phpolars scans the file lazily and collects with the streaming engine. Plain PHP reads one row at a time with `fgetcsv()`. phpstats.org shows both scripts verbatim from the record's `code` field, so they have no comments.

Make the data. 20 million rows give a 1.03 GB file:

```sh
php bench/csv-pipeline/make-orders.php 20000000 /tmp/orders.csv
```

Run one warm-up of each script and then 7 alternating runs, each in a fresh process. `run.php` checks that the two results agree (counts exactly, sums and means to a relative 1e-9) and prints the record:

```sh
ext=target/release/libphp_polars.so   # .dylib on macOS
PHPOLARS_EXTENSION=$ext php -d extension=$ext bench/csv-pipeline/run.php /tmp/orders.csv 7 \
    > site/benchmarks/csv-pipeline.json
```

Run it from a committed tree, so that `environment.commit` names the code that ran. `PHPOLARS_THREADS` sets `php_polars.max_threads` (default 2). `compare.php A.json B.json` compares two saved script outputs the same way.
