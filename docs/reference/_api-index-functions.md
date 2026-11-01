### Read files and databases {#index-read-files-and-databases}

| Function | Purpose |
| --- | --- |
| [`read_csv()`](io.qmd#fn-read_csv) | Read a CSV file into a `DataFrame`. |
| [`scan_csv()`](io.qmd#fn-scan_csv) | Scan a CSV file lazily as a `LazyFrame`. |
| [`read_parquet()`](io.qmd#fn-read_parquet) | Read a Parquet file into a `DataFrame`. |
| [`scan_parquet()`](io.qmd#fn-scan_parquet) | Scan a Parquet file lazily as a `LazyFrame`. |
| [`read_parquet_schema()`](io.qmd#fn-read_parquet_schema) | Read the schema of a Parquet file. |
| [`read_ipc()`](io.qmd#fn-read_ipc) | Read an Arrow IPC file into a `DataFrame`. |
| [`scan_ipc()`](io.qmd#fn-scan_ipc) | Scan an Arrow IPC file lazily as a `LazyFrame`. |
| [`read_ipc_schema()`](io.qmd#fn-read_ipc_schema) | Read the schema of an Arrow IPC file. |
| [`read_ipc_stream()`](io.qmd#fn-read_ipc_stream) | Read an Arrow IPC stream into a `DataFrame`. |
| [`read_ndjson()`](io.qmd#fn-read_ndjson) | Read newline-delimited JSON into a `DataFrame`. |
| [`scan_ndjson()`](io.qmd#fn-scan_ndjson) | Scan newline-delimited JSON lazily as a `LazyFrame`. |
| [`read_json()`](io.qmd#fn-read_json) | Read a JSON array of rows into a `DataFrame`. |
| [`read_database()`](io.qmd#fn-read_database) | Read the result of a PDO query into a `DataFrame`. |

### Create and combine data frames {#index-create-and-combine-data-frames}

| Function | Purpose |
| --- | --- |
| [`from_dict()`](expressions.qmd#fn-from_dict) | Build a `DataFrame` from an array of columns. |
| [`from_dicts()`](expressions.qmd#fn-from_dicts) | Build a `DataFrame` from a list of associative rows. |
| [`from_records()`](expressions.qmd#fn-from_records) | Build a `DataFrame` from a list of rows. |
| [`json_normalize()`](expressions.qmd#fn-json_normalize) | Flatten nested arrays into a `DataFrame` with one column per path. |
| [`concat()`](expressions.qmd#fn-concat) | Concatenate data frames, LazyFrames, or series vertically, horizontally, or diagonally. |
| [`union()`](expressions.qmd#fn-union) | Concatenate data frames or LazyFrames without guaranteeing row order. |
| [`select()`](expressions.qmd#fn-select) | Evaluate expressions into a `DataFrame` without an input data frame. |
| [`sql()`](expressions.qmd#fn-sql) | Run a SQL query against the given data frames. |
| [`collect_all()`](expressions.qmd#fn-collect_all) | Collect several LazyFrames in parallel. |

### Columns and literals {#index-columns-and-literals}

| Function | Purpose |
| --- | --- |
| [`col()`](expressions.qmd#fn-col) | Refer to columns by name, by regular expression, or by data type. |
| [`lit()`](expressions.qmd#fn-lit) | A literal value, optionally of a given data type. |
| [`all()`](expressions.qmd#fn-all) | Every column. |
| [`exclude()`](expressions.qmd#fn-exclude) | Every column except the given ones. |
| [`nth()`](expressions.qmd#fn-nth) | Columns by position. |
| [`first()`](expressions.qmd#fn-first) | The first value of the given columns. |
| [`last()`](expressions.qmd#fn-last) | The last value of the given columns. |
| [`len()`](expressions.qmd#fn-len) | The number of rows. |
| [`row_index()`](expressions.qmd#fn-row_index) | A row index column, starting at 0. |
| [`element()`](expressions.qmd#fn-element) | The current element inside `list()->eval()` and `arr()` operations. |
| [`field()`](expressions.qmd#fn-field) | A struct field inside `struct()->withFields()`. |
| [`struct_()`](expressions.qmd#fn-struct_) | Combine expressions into a struct column. |
| [`repeat()`](expressions.qmd#fn-repeat) | A value repeated `n` times. |
| [`ones()`](expressions.qmd#fn-ones) | A column of `n` ones. |
| [`zeros()`](expressions.qmd#fn-zeros) | A column of `n` zeros. |
| [`sql_expr()`](expressions.qmd#fn-sql_expr) | Parse a SQL expression into an `Expr`. |

### Conditions {#index-conditions}

| Function | Purpose |
| --- | --- |
| [`when()`](expressions.qmd#fn-when) | Start a `when()->then()->otherwise()` conditional expression. |
| [`coalesce()`](expressions.qmd#fn-coalesce) | The first non-null value across expressions, row by row. |

### Aggregates {#index-aggregates}

| Function | Purpose |
| --- | --- |
| [`sum()`](expressions.qmd#fn-sum) | Sum of the given columns. |
| [`mean()`](expressions.qmd#fn-mean) | Mean of the given columns. |
| [`median()`](expressions.qmd#fn-median) | Median of the given columns. |
| [`min()`](expressions.qmd#fn-min) | Minimum of the given columns. |
| [`max()`](expressions.qmd#fn-max) | Maximum of the given columns. |
| [`count()`](expressions.qmd#fn-count) | Number of non-null values in the given columns. |
| [`n_unique()`](expressions.qmd#fn-n_unique) | Number of distinct values in the given columns. |
| [`approx_n_unique()`](expressions.qmd#fn-approx_n_unique) | Approximate number of distinct values (HyperLogLog). |
| [`std()`](expressions.qmd#fn-std) | Sample standard deviation of a column. |
| [`var_()`](expressions.qmd#fn-var_) | Sample variance of a column. |
| [`quantile()`](expressions.qmd#fn-quantile) | A quantile of a column. |
| [`any()`](expressions.qmd#fn-any) | Whether any value of the given boolean columns is true. |
| [`implode()`](expressions.qmd#fn-implode) | Collect the values of the given columns into one list. |
| [`head()`](expressions.qmd#fn-head) | The first `n` values of the given columns. |
| [`tail()`](expressions.qmd#fn-tail) | The last `n` values of the given columns. |
| [`corr()`](expressions.qmd#fn-corr) | Correlation of two columns (Pearson or Spearman). |
| [`cov()`](expressions.qmd#fn-cov) | Covariance of two columns. |
| [`rolling_corr()`](expressions.qmd#fn-rolling_corr) | Rolling correlation of two columns. |
| [`rolling_cov()`](expressions.qmd#fn-rolling_cov) | Rolling covariance of two columns. |
| [`cum_count()`](expressions.qmd#fn-cum_count) | Cumulative count of non-null values. |
| [`cum_sum()`](expressions.qmd#fn-cum_sum) | Cumulative sum of the given columns. |

### Across columns {#index-across-columns}

| Function | Purpose |
| --- | --- |
| [`all_horizontal()`](expressions.qmd#fn-all_horizontal) | Logical AND across expressions, row by row. |
| [`any_horizontal()`](expressions.qmd#fn-any_horizontal) | Logical OR across expressions, row by row. |
| [`sum_horizontal()`](expressions.qmd#fn-sum_horizontal) | Sum across expressions, row by row. |
| [`mean_horizontal()`](expressions.qmd#fn-mean_horizontal) | Mean across expressions, row by row. |
| [`min_horizontal()`](expressions.qmd#fn-min_horizontal) | Minimum across expressions, row by row. |
| [`max_horizontal()`](expressions.qmd#fn-max_horizontal) | Maximum across expressions, row by row. |
| [`cum_sum_horizontal()`](expressions.qmd#fn-cum_sum_horizontal) | Cumulative sum across expressions, as a struct. |
| [`concat_str()`](expressions.qmd#fn-concat_str) | Concatenate expressions as strings, row by row. |
| [`concat_list()`](expressions.qmd#fn-concat_list) | Combine expressions into a list column, row by row. |
| [`concat_arr()`](expressions.qmd#fn-concat_arr) | Combine expressions into a fixed-width array column. |
| [`format()`](expressions.qmd#fn-format) | Format expressions into a string with `{}` placeholders. |
| [`arctan2()`](expressions.qmd#fn-arctan2) | Two-argument arctangent of `y` and `x`. |

### Dates, times, and ranges {#index-dates-times-and-ranges}

| Function | Purpose |
| --- | --- |
| [`date()`](expressions.qmd#fn-date) | A date from year, month, and day expressions. |
| [`datetime()`](expressions.qmd#fn-datetime) | A datetime from its component expressions. |
| [`time()`](expressions.qmd#fn-time) | A time of day from its component expressions. |
| [`duration()`](expressions.qmd#fn-duration) | A duration from weeks, days, hours, and smaller units. |
| [`from_epoch()`](expressions.qmd#fn-from_epoch) | Convert Unix timestamps to datetimes or dates. |
| [`business_day_count()`](expressions.qmd#fn-business_day_count) | Number of business days between two dates. |
| [`date_range()`](expressions.qmd#fn-date_range) | A column of dates at a fixed interval. |
| [`date_ranges()`](expressions.qmd#fn-date_ranges) | A list of dates per row, between two date columns. |
| [`datetime_range()`](expressions.qmd#fn-datetime_range) | A column of datetimes at a fixed interval. |
| [`datetime_ranges()`](expressions.qmd#fn-datetime_ranges) | A list of datetimes per row, between two columns. |
| [`time_range()`](expressions.qmd#fn-time_range) | A column of times of day at a fixed interval. |
| [`time_ranges()`](expressions.qmd#fn-time_ranges) | A list of times per row, between two columns. |
| [`int_range()`](expressions.qmd#fn-int_range) | A column of integers from `start` to `end`. |
| [`int_ranges()`](expressions.qmd#fn-int_ranges) | A list of integers per row, between two columns. |
| [`arange()`](expressions.qmd#fn-arange) | A column of integers from `start` to `end`, as `int_range()`. |
| [`linear_space()`](expressions.qmd#fn-linear_space) | Evenly spaced values between two bounds. |
| [`linear_spaces()`](expressions.qmd#fn-linear_spaces) | Evenly spaced values per row, as a list or array. |

### Sorting and positions {#index-sorting-and-positions}

| Function | Purpose |
| --- | --- |
| [`arg_sort_by()`](expressions.qmd#fn-arg_sort_by) | Row indices that sort by the given expressions. |
| [`arg_where()`](expressions.qmd#fn-arg_where) | Row indices where a condition is true. |
| [`escape_regex()`](expressions.qmd#fn-escape_regex) | Escape regular expression metacharacters in a string. |

### Runtime {#index-runtime}

| Function | Purpose |
| --- | --- |
| [`version()`](expressions.qmd#fn-version) | The phpolars extension version. |
| [`polars_version()`](expressions.qmd#fn-polars_version) | The version of the embedded Polars Rust crate. |
| [`thread_pool_size()`](expressions.qmd#fn-thread_pool_size) | The number of threads in the Polars thread pool. |
| [`set_random_seed()`](expressions.qmd#fn-set_random_seed) | Seed Polars' random number generator for `sample()` and `shuffle()`. |

### Selectors {#index-selectors}

| Function | Purpose |
| --- | --- |
| [`cs\all()`](expressions.qmd#cs-all) | Every column. |
| [`cs\array_()`](expressions.qmd#cs-array_) | Array columns, optionally of an inner type and width. |
| [`cs\binary()`](expressions.qmd#cs-binary) | Binary columns. |
| [`cs\boolean()`](expressions.qmd#cs-boolean) | Boolean columns. |
| [`cs\by_dtype()`](expressions.qmd#cs-by_dtype) | Columns of the given data types. |
| [`cs\by_index()`](expressions.qmd#cs-by_index) | Columns at the given positions. |
| [`cs\by_name()`](expressions.qmd#cs-by_name) | Columns with the given names. |
| [`cs\categorical()`](expressions.qmd#cs-categorical) | Categorical columns. |
| [`cs\contains()`](expressions.qmd#cs-contains) | Columns whose names contain a substring. |
| [`cs\date()`](expressions.qmd#cs-date) | Date columns. |
| [`cs\datetime()`](expressions.qmd#cs-datetime) | Datetime columns, optionally of a time unit and zone. |
| [`cs\decimal()`](expressions.qmd#cs-decimal) | Decimal columns. |
| [`cs\duration()`](expressions.qmd#cs-duration) | Duration columns, optionally of a time unit. |
| [`cs\empty_()`](expressions.qmd#cs-empty_) | No columns. |
| [`cs\ends_with()`](expressions.qmd#cs-ends_with) | Columns whose names end with a suffix. |
| [`cs\enum_()`](expressions.qmd#cs-enum_) | Enum columns. |
| [`cs\exclude()`](expressions.qmd#cs-exclude) | Every column except the given ones. |
| [`cs\first()`](expressions.qmd#cs-first) | The first column. |
| [`cs\float()`](expressions.qmd#cs-float) | Float columns. |
| [`cs\integer()`](expressions.qmd#cs-integer) | Integer columns. |
| [`cs\last()`](expressions.qmd#cs-last) | The last column. |
| [`cs\list_()`](expressions.qmd#cs-list_) | List columns, optionally of an inner type. |
| [`cs\matches()`](expressions.qmd#cs-matches) | Columns whose names match a regular expression. |
| [`cs\nested()`](expressions.qmd#cs-nested) | List, array, and struct columns. |
| [`cs\numeric()`](expressions.qmd#cs-numeric) | Integer, float, and decimal columns. |
| [`cs\signed_integer()`](expressions.qmd#cs-signed_integer) | Signed integer columns. |
| [`cs\starts_with()`](expressions.qmd#cs-starts_with) | Columns whose names start with a prefix. |
| [`cs\string()`](expressions.qmd#cs-string) | String columns. |
| [`cs\struct_()`](expressions.qmd#cs-struct_) | Struct columns. |
| [`cs\temporal()`](expressions.qmd#cs-temporal) | Date, datetime, duration, and time columns. |
| [`cs\time()`](expressions.qmd#cs-time) | Time columns. |
| [`cs\unsigned_integer()`](expressions.qmd#cs-unsigned_integer) | Unsigned integer columns. |
