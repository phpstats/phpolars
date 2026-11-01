# CSV pipeline benchmark

One pipeline on a seeded 1 GB order-events CSV, with phpolars and with plain
PHP. Both keep the completed orders and compute orders, units, revenue, and
the mean amount for each region and month. `run.php` runs each script in fresh
processes (one warm-up each, then alternating runs), checks that the results
agree, and prints the record that `site/benchmarks/csv-pipeline.json` holds.

```sh
php bench/csv-pipeline/make-orders.php 20000000 /tmp/orders.csv
PHPOLARS_EXTENSION=$PWD/target/release/libphp_polars.dylib \
  php -d extension=$PWD/target/release/libphp_polars.dylib \
  bench/csv-pipeline/run.php /tmp/orders.csv 7 > site/benchmarks/csv-pipeline.json
```

On Linux the extension is `php_polars.so`. The record keeps the default
`php_polars.max_threads` (2), measures time inside each script from opening the
file to holding the result as PHP values, and reports the peak resident set
size of each process. The file stays in the page cache between runs.
