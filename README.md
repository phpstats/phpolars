# phpolars

phpolars is a PHP extension that wraps [Polars](https://pola.rs), the Rust data frame library. It is written in Rust with [ext-php-rs](https://github.com/davidcole1340/ext-php-rs) and calls the Polars crates directly. The API is modeled on py-polars, Polars' Python package: the same classes and methods, with camelCase method names and snake_case functions.

```php
use function Phpolars\{col, len, read_csv};

// Palmer penguins: https://github.com/phpstats/datasets#penguins
$penguins = read_csv(
    'https://raw.githubusercontent.com/phpstats/datasets/main/csv/penguins.csv',
    nullValues: 'NA',
);

$summary = $penguins
    ->filter(col('body_mass_g')->isNotNull())
    ->groupBy(['species', 'island'], maintainOrder: true)
    ->agg(
        len()->alias('penguins'),
        col('body_mass_g')->mean()->round()->alias('mass_g'),
    )
    ->sort('mass_g', descending: true);

print_r($summary->toDicts());
```

phpolars works on its own or through [phibble](https://phibble.phpstats.org) and [phplyr](https://phplyr.phpstats.org). phibble's `Phibble\Polars\PolarsTable` adapter, part of phibble, runs phibble tables and phplyr pipelines on this extension with dplyr's semantics.

## Features

- `DataFrame` and `LazyFrame` with py-polars' methods, including `select`,
  `withColumns`, `filter`, `sort`, `groupBy`/`agg`, `join`, `pivot`, and
  `unpivot`.
- Expressions with arithmetic, three-valued logic, aggregations, window
  functions, `when`/`then`/`otherwise`, and the `str`, `dt`, `list`, `struct`,
  `arr`, `cat`, and `name` namespaces.
- `Series`, with the same expression methods.
- CSV, Parquet, JSON, NDJSON, and Arrow IPC reading and writing, with lazy
  scans and streaming sinks, and reading and writing through PDO.
- SQL queries over data frames.
- A thread-pool cap for PHP-FPM (`php_polars.max_threads`).

## Requirements

- PHP 8.3, 8.4, or 8.5
- A non-thread-safe (NTS) PHP build
- Linux or macOS
- [PIE](https://github.com/php/pie)

Prebuilt binaries cover Linux with glibc 2.34 or later on x86_64 and arm64. Other platforms, including macOS, build from source, which needs a stable Rust toolchain and libclang.

## Installation

```sh
pie install phpstats/phpolars
php -r 'echo phpversion("php_polars"), PHP_EOL;'
```

The second command prints the installed version. Restart PHP-FPM and queue workers after installing. [Deployment](https://phpolars.phpstats.org/docs/deployment) covers manual installs, Ubuntu and Laravel Forge, Laravel Herd, threads under PHP-FPM, and memory.

## Documentation

- [Getting started](https://phpolars.phpstats.org/docs/getting-started)
- [Coming from py-polars](https://phpolars.phpstats.org/docs/coming-from-python)
- [phibble and phplyr](https://phpolars.phpstats.org/docs/phibble), including how Polars' missing values differ from dplyr's
- [Functions and methods](https://phpolars.phpstats.org/docs/reference/index)
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

## Development

[Development](https://phpolars.phpstats.org/docs/development) covers building from a checkout, the test suites, the py-polars fixtures, the docs, and the IDE stubs in `stubs/phpolars.php`.

## License

phpolars is released under the MIT license; see [LICENSE](LICENSE). Polars and the other Rust libraries compiled into the extension keep their own licenses, which each release archive lists in `THIRD_PARTY_NOTICES.txt`.
