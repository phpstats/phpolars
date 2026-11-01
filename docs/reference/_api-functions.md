### Create and combine data frames {#fn--create-and-combine-data-frames}

- []{#fn-from_dict} `from_dict(array $data, ?array $schema = null): Phpolars\DataFrame`
- []{#fn-from_dicts} `from_dicts(array $data, ?array $schema = null): Phpolars\DataFrame`
- []{#fn-from_records} `from_records(array $data, ?array $schema = null): Phpolars\DataFrame`
- []{#fn-json_normalize} `json_normalize(array $data, ?string $separator = null, ?int $maxLevel = null): Phpolars\DataFrame`
- []{#fn-concat} `concat(array $items, ?string $how = null): mixed`: `how`: `'vertical'` (the default), `'vertical_relaxed'`, `'horizontal'`, `'diagonal'`, or `'diagonal_relaxed'`; the relaxed strategies cast to a common type
- []{#fn-union} `union(array $items, ?string $how = null): mixed`
- []{#fn-select} `select(mixed ...$exprs): Phpolars\DataFrame`
- []{#fn-sql} `sql(string $query, ?array $dataFrames = null, ?bool $eager = null): mixed`: returns a `LazyFrame` unless `eager: true`
- []{#fn-collect_all} `collect_all(array $lazyFrames, ?string $engine = null): array`

### Columns and literals {#fn--columns-and-literals}

- []{#fn-col} `col(mixed ...$names): Phpolars\Expr`: one or more column names, `'*'` for every column, or a regular expression written `'^…$'`
- []{#fn-lit} `lit(mixed $value, ?string $dtype = null): Phpolars\Expr`: a literal value; a string is a value, not a column name
- []{#fn-all} `all(): Phpolars\Expr`
- []{#fn-exclude} `exclude(mixed ...$names): Phpolars\Expr`
- []{#fn-nth} `nth(mixed ...$indices): Phpolars\Expr`
- []{#fn-first} `first(mixed ...$names): Phpolars\Expr`
- []{#fn-last} `last(mixed ...$names): Phpolars\Expr`
- []{#fn-len} `len(): Phpolars\Expr`: counts rows, including nulls
- []{#fn-row_index} `row_index(?string $name = null): Phpolars\Expr`
- []{#fn-element} `element(): Phpolars\Expr`
- []{#fn-field} `field(mixed $name): Phpolars\Expr`
- []{#fn-struct_} `struct_(mixed ...$exprs): Phpolars\Expr`
- []{#fn-repeat} `repeat(mixed $value, mixed $n, ?string $dtype = null): Phpolars\Expr`
- []{#fn-ones} `ones(mixed $n, ?string $dtype = null): Phpolars\Expr`
- []{#fn-zeros} `zeros(mixed $n, ?string $dtype = null): Phpolars\Expr`
- []{#fn-sql_expr} `sql_expr(string $sql): Phpolars\Expr`

### Conditions {#fn--conditions}

- []{#fn-when} `when(mixed ...$conditions): Phpolars\When`: rows that match no condition get the `otherwise()` value, or null without one
- []{#fn-coalesce} `coalesce(mixed ...$exprs): Phpolars\Expr`

### Aggregates {#fn--aggregates}

- []{#fn-sum} `sum(mixed ...$names): Phpolars\Expr`
- []{#fn-mean} `mean(mixed ...$names): Phpolars\Expr`
- []{#fn-median} `median(mixed ...$names): Phpolars\Expr`
- []{#fn-min} `min(mixed ...$names): Phpolars\Expr`
- []{#fn-max} `max(mixed ...$names): Phpolars\Expr`
- []{#fn-count} `count(mixed ...$names): Phpolars\Expr`
- []{#fn-n_unique} `n_unique(mixed ...$names): Phpolars\Expr`
- []{#fn-approx_n_unique} `approx_n_unique(mixed ...$names): Phpolars\Expr`
- []{#fn-std} `std(mixed $column, ?int $ddof = null): Phpolars\Expr`
- []{#fn-var_} `var_(mixed $column, ?int $ddof = null): Phpolars\Expr`
- []{#fn-quantile} `quantile(mixed $column, float $quantile, ?string $interpolation = null): Phpolars\Expr`
- []{#fn-any} `any(mixed $names, ?bool $ignoreNulls = null): Phpolars\Expr`
- []{#fn-implode} `implode(mixed ...$names): Phpolars\Expr`
- []{#fn-head} `head(mixed $names, ?int $n = null): Phpolars\Expr`
- []{#fn-tail} `tail(mixed $names, ?int $n = null): Phpolars\Expr`
- []{#fn-corr} `corr(mixed $a, mixed $b, ?string $method = null, ?bool $propagateNans = null): Phpolars\Expr`
- []{#fn-cov} `cov(mixed $a, mixed $b, ?int $ddof = null): Phpolars\Expr`
- []{#fn-rolling_corr} `rolling_corr(mixed $a, mixed $b, int $windowSize, ?int $minPeriods = null, ?int $ddof = null): Phpolars\Expr`
- []{#fn-rolling_cov} `rolling_cov(mixed $a, mixed $b, int $windowSize, ?int $minPeriods = null, ?int $ddof = null): Phpolars\Expr`
- []{#fn-cum_count} `cum_count(mixed $names, ?bool $reverse = null): Phpolars\Expr`
- []{#fn-cum_sum} `cum_sum(mixed $names, ?bool $reverse = null): Phpolars\Expr`

### Across columns {#fn--across-columns}

- []{#fn-all_horizontal} `all_horizontal(mixed ...$exprs): Phpolars\Expr`
- []{#fn-any_horizontal} `any_horizontal(mixed ...$exprs): Phpolars\Expr`
- []{#fn-sum_horizontal} `sum_horizontal(mixed ...$exprs): Phpolars\Expr`
- []{#fn-mean_horizontal} `mean_horizontal(mixed ...$exprs): Phpolars\Expr`
- []{#fn-min_horizontal} `min_horizontal(mixed ...$exprs): Phpolars\Expr`
- []{#fn-max_horizontal} `max_horizontal(mixed ...$exprs): Phpolars\Expr`
- []{#fn-cum_sum_horizontal} `cum_sum_horizontal(mixed ...$exprs): Phpolars\Expr`
- []{#fn-concat_str} `concat_str(mixed $exprs, ?string $separator = null, ?bool $ignoreNulls = null): Phpolars\Expr`
- []{#fn-concat_list} `concat_list(mixed ...$exprs): Phpolars\Expr`
- []{#fn-concat_arr} `concat_arr(mixed ...$exprs): Phpolars\Expr`
- []{#fn-format} `format(string $fString, mixed ...$args): Phpolars\Expr`
- []{#fn-arctan2} `arctan2(mixed $y, mixed $x): Phpolars\Expr`

### Dates, times, and ranges {#fn--dates-times-and-ranges}

- []{#fn-date} `date(mixed $year, mixed $month, mixed $day): Phpolars\Expr`
- []{#fn-datetime} `datetime(mixed $year, mixed $month, mixed $day, mixed $hour = null, mixed $minute = null, mixed $second = null, mixed $microsecond = null, ?string $timeUnit = null, ?string $timeZone = null): Phpolars\Expr`
- []{#fn-time} `time(mixed $hour = null, mixed $minute = null, mixed $second = null, mixed $microsecond = null): Phpolars\Expr`
- []{#fn-duration} `duration(mixed $weeks = null, mixed $days = null, mixed $hours = null, mixed $minutes = null, mixed $seconds = null, mixed $milliseconds = null, mixed $microseconds = null, mixed $nanoseconds = null, ?string $timeUnit = null): Phpolars\Expr`
- []{#fn-from_epoch} `from_epoch(mixed $column, ?string $timeUnit = null): Phpolars\Expr`
- []{#fn-business_day_count} `business_day_count(mixed $start, mixed $end, ?array $weekMask = null, mixed $holidays = null): Phpolars\Expr`
- []{#fn-date_range} `date_range(mixed $start = null, mixed $end = null, ?string $interval = null, ?int $numSamples = null, ?string $closed = null): Phpolars\Expr`
- []{#fn-date_ranges} `date_ranges(mixed $start = null, mixed $end = null, ?string $interval = null, ?string $closed = null): Phpolars\Expr`
- []{#fn-datetime_range} `datetime_range(mixed $start = null, mixed $end = null, ?string $interval = null, ?int $numSamples = null, ?string $closed = null, ?string $timeUnit = null, ?string $timeZone = null): Phpolars\Expr`
- []{#fn-datetime_ranges} `datetime_ranges(mixed $start = null, mixed $end = null, ?string $interval = null, ?string $closed = null, ?string $timeUnit = null, ?string $timeZone = null): Phpolars\Expr`
- []{#fn-time_range} `time_range(mixed $start = null, mixed $end = null, ?string $interval = null, ?string $closed = null): Phpolars\Expr`
- []{#fn-time_ranges} `time_ranges(mixed $start = null, mixed $end = null, ?string $interval = null, ?string $closed = null): Phpolars\Expr`
- []{#fn-int_range} `int_range(mixed $start, mixed $end = null, ?int $step = null): Phpolars\Expr`
- []{#fn-int_ranges} `int_ranges(mixed $start, mixed $end = null, mixed $step = null): Phpolars\Expr`
- []{#fn-arange} `arange(mixed $start, mixed $end = null, ?int $step = null): Phpolars\Expr`
- []{#fn-linear_space} `linear_space(mixed $start, mixed $end, mixed $numSamples, ?string $closed = null): Phpolars\Expr`
- []{#fn-linear_spaces} `linear_spaces(mixed $start, mixed $end, mixed $numSamples, ?string $closed = null, ?bool $asArray = null): Phpolars\Expr`

### Sorting and positions {#fn--sorting-and-positions}

- []{#fn-arg_sort_by} `arg_sort_by(mixed $exprs, mixed $descending = null, mixed $nullsLast = null, ?bool $maintainOrder = null): Phpolars\Expr`
- []{#fn-arg_where} `arg_where(mixed $condition): Phpolars\Expr`
- []{#fn-escape_regex} `escape_regex(string $s): string`

### Runtime {#fn--runtime}

- []{#fn-version} `version(): string`
- []{#fn-polars_version} `polars_version(): string`
- []{#fn-thread_pool_size} `thread_pool_size(): int`
- []{#fn-set_random_seed} `set_random_seed(int $seed): void`

