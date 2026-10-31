# phpolars

phpolars brings [Polars](https://pola.rs) data frames to PHP as a native extension written in Rust with [ext-php-rs](https://github.com/davidcole1340/ext-php-rs). The API follows py-polars: the same classes and methods, with camelCase method names and snake_case functions.

```php
use Phpolars\DataFrame;
use function Phpolars\{col, lit, when};

$df = Phpolars\read_csv('sales.csv');

$summary = $df
    ->filter(col('amount')->gt(0))
    ->withColumns(when(col('region')->isIn(['N', 'S']))->then(lit('core'))->otherwise(lit('other'))->alias('tier'))
    ->groupBy(['tier', 'region'], maintainOrder: true)
    ->agg(col('amount')->sum()->alias('total'), ['orders' => Phpolars\len()])
    ->sort('total', descending: true);

print_r($summary->toDicts());
```

phpolars works on its own or through [phibble](https://phibble.phpstats.org) and [phplyr](https://phplyr.phpstats.org). phibble's `Phibble\Polars\PolarsTable` adapter, part of phibble, runs phibble tables and phplyr pipelines on this extension with dplyr's semantics.

## Requirements

- PHP 8.3, 8.4, or 8.5
- A non-thread-safe (NTS) PHP build
- Linux or macOS
- [PIE](https://github.com/php/pie)

Prebuilt binaries cover Linux with glibc 2.34 or later on x86_64 and arm64. Other platforms, including macOS, build from source, which needs a stable Rust toolchain and libclang.

## Installing

```sh
pie install phpstats/phpolars
php -r 'echo phpversion("php_polars"), PHP_EOL;'
```

The second command prints the installed version. Restart PHP-FPM and queue workers after installing. [Deployment](https://phpolars.phpstats.org/docs/deployment) covers manual installs, Ubuntu and Laravel Forge, Laravel Herd, threads under PHP-FPM, and memory.

## Documentation

- [Getting started](https://phpolars.phpstats.org/docs/getting-started)
- [Coming from py-polars](https://phpolars.phpstats.org/docs/coming-from-python)
- [phibble and phplyr](https://phpolars.phpstats.org/docs/phibble), including how Polars' missing values differ from dplyr's
- [Reference](https://phpolars.phpstats.org/docs/reference/dataframe)
- [Compatibility and limitations](https://phpolars.phpstats.org/docs/compatibility)
- [Performance](https://phpolars.phpstats.org/docs/performance)

## Binary size

Polars is large. Release builds use the `dist` profile, which applies fat LTO, one codegen unit, and symbol stripping. Optional Cargo features gate the formats and data that not every installation needs:

| Feature | Provides |
| --- | --- |
| `parquet` | `read_parquet`, `scan_parquet`, `writeParquet`, `sinkParquet` |
| `json` | `read_json`, `read_ndjson`, `writeJson`, `writeNdjson` |
| `ipc` | Arrow IPC (Feather) reading and writing; also enables `parquet`, which Polars 0.55's IPC reader needs |
| `fmt` | the table layout used by `__toString()` |
| `sql` | `SQLContext`, `DataFrame::sql()`, `LazyFrame::sql()`, and `Phpolars\sql()` |

All features are on by default. Build a smaller extension with, for example, `cargo build --profile dist --no-default-features --features parquet`.

Sizes of the stripped macOS arm64 library with fat LTO and one codegen unit:

<!-- size-table -->
| Build | opt-level | Size | Benchmark, 1 / 4 threads |
| --- | --- | --- | --- |
| all features (`dist` default) | s | 64.5 MB | 47 / 23 ms |
| without `sql` | s | 60.4 MB | |
| v0.1.0, all features, `release` profile (no LTO) | 3 | 117 MB | |
| v0.1.0, all features | 3 | 57.3 MB | 44 / 23 ms |
| v0.1.0, all features (`dist` default) | s | 40.2 MB | 45 / 21 ms |
| v0.1.0, all features | z | 23.9 MB | 86 / 33 ms |
| v0.1.0, without `fmt` | 3 | 57.2 MB | |
| v0.1.0, without `json` | 3 | 56.8 MB | |
| v0.1.0, without `ipc` | 3 | 56.9 MB | |
| v0.1.0, without `parquet` and `ipc` | 3 | 52.4 MB | |
| v0.1.0, CSV only (no optional features) | 3 | 51.8 MB | |
| v0.1.0, CSV only (no optional features) | s | 35.9 MB | |
<!-- /size-table -->

The rows marked v0.1.0 were measured before the py-polars API was filled in: time series windows and asof joins, the extra string, binary, and array kernels, and SQL. Those additions take the default build from 40.2 MB to 64.5 MB; SQL is 4.1 MB of that and can be left out with `--no-default-features --features parquet,json,ipc,fmt`. The other optional features cost little: at v0.1.0 they added about 5 MB together. Most of the size is the Polars query engine, expression set, and data types that the core API needs. The `dist` profile uses `opt-level = "s"`, which is 30% smaller than `opt-level = 3` and as fast on the benchmark above (the `read_csv` → `filter` → `groupBy` pipeline on 1M rows). `opt-level = "z"` halves the size again but doubles single-threaded time. A clean `dist` build takes 4–5 minutes on an Apple M5 Ultra.

## Development

[Development](https://phpolars.phpstats.org/docs/development) covers building from a checkout, the test suites, the py-polars fixtures, the docs, and the IDE stubs in `stubs/phpolars.php`.

## License

MIT
