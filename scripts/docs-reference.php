<?php
// php -d extension=... scripts/docs-reference.php: writes docs/reference/_api-<name>.md,
// signature lists generated from the extension's reflection data with an anchor per
// function and method, docs/reference/_index-<page>.md, a method index linking to them,
// and docs/reference/_api-index-*.md, the Functions and methods index of every function,
// selector, and class. Functions and the methods of the larger classes are grouped by
// category, and frequently used or easily misread functions and methods get a note.

$out = dirname(__DIR__).'/docs/reference';

function sig(ReflectionFunctionAbstract $f): string
{
    $params = array_map(function (ReflectionParameter $p) {
        $s = ($p->getType() ? $p->getType().' ' : '').($p->isVariadic() ? '...' : '').'$'.$p->getName();

        return $p->isOptional() && ! $p->isVariadic() ? $s.' = null' : $s;
    }, $f->getParameters());
    $ret = $f->getReturnType() ? ': '.$f->getReturnType() : '';

    return $f->getShortName().'('.implode(', ', $params).')'.$ret;
}

// Notes on frequently used functions and methods, and on those whose behavior differs
// from what PHP or SQL habits suggest.
const NOTES = [
    'fn-read_csv' => 'reads the whole file into memory; `columns` parses only the named columns, and `schemaOverrides` sets types',
    'fn-scan_csv' => 'reads nothing until the query is collected; filters and column selections then apply while the file is read',
    'fn-read_parquet' => 'reads the whole file into memory; `columns` reads only the named columns',
    'fn-scan_parquet' => 'reads nothing until the query is collected; filters use the row-group statistics',
    'fn-read_database' => 'any PDO connection; `params` binds placeholders, and `schemaOverrides` casts columns afterwards',
    'fn-col' => "one or more column names, `'*'` for every column, or a regular expression written `'^…$'`",
    'fn-lit' => 'a literal value; a string is a value, not a column name',
    'fn-when' => 'rows that match no condition get the `otherwise()` value, or null without one',
    'fn-len' => 'counts rows, including nulls',
    'fn-concat' => "`how`: `'vertical'` (the default), `'vertical_relaxed'`, `'horizontal'`, `'diagonal'`, or `'diagonal_relaxed'`; the relaxed strategies cast to a common type",
    'fn-sql' => 'returns a `LazyFrame` unless `eager: true`',
    'dataframe-select' => 'keeps only the given columns and expressions; strings name columns',
    'dataframe-withColumns' => 'adds or replaces columns and keeps the others',
    'dataframe-filter' => 'keeps rows where every condition is true; a null condition drops the row',
    'dataframe-head' => 'the first `n` rows, 5 by default',
    'dataframe-lazy' => 'a `LazyFrame` over the data, for an optimized query',
    'dataframe-writeCsv' => 'writes the whole frame; `LazyFrame::sinkCsv()` streams a query result instead',
    'lazyframe-select' => 'keeps only the given columns and expressions; strings name columns',
    'lazyframe-withColumns' => 'adds or replaces columns and keeps the others',
    'lazyframe-filter' => 'keeps rows where every condition is true; a null condition drops the row',
    'lazyframe-explain' => 'the optimized query plan as text; `optimized: false` shows the plan as written',
    'groupby-agg' => 'one row per group: the key columns, then one column per expression; an expression that keeps several values per group gives a list',
    'lazygroupby-agg' => 'one row per group: the key columns, then one column per expression; an expression that keeps several values per group gives a list',
    'expr-alias' => 'names the output column',
    'expr-over' => 'computes within each group of the given columns and returns one value per row',
    'expr-isNull' => 'true for null; NaN is a float value, not null',
    'expr-isIn' => 'a null value gives null unless `nullsEqual: true`',
    'expr-sum' => 'skips nulls; the sum of no values is 0',
    'expr-mean' => 'skips nulls',
    'expr-count' => 'counts non-null values; `len()` counts rows',
    'expr-len' => 'counts rows, including nulls',
    'expr-round' => "rounds half to even by default; `mode: 'half_away_from_zero'` rounds as PHP's `round()` does",
    'expr-mod' => "takes the sign of the divisor, as in Python: `-7` mod `2` is `1`, where PHP's `%` gives `-1`",
    'expr-div' => 'the same as `truediv()`, so integer columns divide to floats; `floordiv()` floors',
    'expr-cast' => "strict: a value that cannot be converted raises `InvalidOperationError`; `strict: false` gives `null`",
    'expr-replace' => 'values without a mapping stay as they are',
    'expr-replaceStrict' => 'values without a mapping raise `InvalidOperationError` unless `default` is given',
    'expr-std' => 'the sample statistic (`ddof` 1)',
    'expr-var' => 'the sample statistic (`ddof` 1)',
    'expr-fillNull' => "a `value`, or a `strategy`: `'forward'`, `'backward'`, `'mean'`, `'min'`, `'max'`, `'zero'`, or `'one'`",
    'expr-sort' => 'nulls sort first unless `nullsLast: true`',
    'expr-unique' => 'the order is not kept unless `maintainOrder: true`',
    'expr-filter' => 'inside `agg()` or `over()`, keeps only the matching values of each group',
    'dataframe-count' => 'counts non-null values in each column',
    'dataframe-groupBy' => 'the order of the groups is not kept unless `maintainOrder: true`',
    'dataframe-join' => 'null keys match nothing unless `nullsEqual: true`',
    'dataframe-sort' => 'nulls sort first unless `nullsLast: true`',
    'dataframe-unique' => 'the order is not kept unless `maintainOrder: true`',
    'dataframe-rows' => 'builds PHP arrays, which count against `memory_limit`',
    'dataframe-toDicts' => 'builds PHP arrays, which count against `memory_limit`',
    'dataframe-mapRows' => 'runs a PHP callable for each row, on the PHP thread',
    'lazyframe-collect' => "`engine: 'streaming'` runs the query in batches",
    'lazyframe-groupBy' => 'the order of the groups is not kept unless `maintainOrder: true`',
    'lazyframe-join' => 'null keys match nothing unless `nullsEqual: true`',
    'lazyframe-sort' => 'nulls sort first unless `nullsLast: true`',
    'lazyframe-unique' => 'the order is not kept unless `maintainOrder: true`',
    'series-count' => 'counts non-null values; `len()` counts all values',
];

// Categories for the larger classes, in display order. A method goes to the first
// category whose list names it or whose pattern matches it, and otherwise to Other.
const TABLE = [
    'Create and convert' => ['fromDict', 'fromDicts', 'fromRows', 'fromPhibble', 'lazy', 'collect', 'fetch', 'profile', 'cache', 'clone', 'clear', 'toPhibble', 'toColumns', 'toDict', 'toDicts', 'toDummies', 'toSeries', 'toStruct', 'rows', 'row', 'rowsByKey', 'item', 'getColumn', 'getColumnIndex', 'getColumns', 'iterColumns', 'iterRows', 'iterSlices', 'mapRows', '__toString'],
    'Inspect' => ['columns', 'dtypes', 'schema', 'collectSchema', 'shape', 'height', 'width', 'estimatedSize', 'describe', 'explain', 'glimpse', 'isEmpty', 'equals', 'nChunks', 'rechunk', 'shrinkToFit'],
    'Select and add columns' => ['select', 'selectSeq', 'withColumns', 'withColumnsSeq', 'withRowIndex', 'drop', 'rename', 'cast', 'insertColumn', 'replaceColumn', 'hstack'],
    'Filter, sort, and slice rows' => ['filter', 'remove', 'head', 'tail', 'first', 'last', 'limit', 'slice', 'gather', 'gatherEvery', 'sample', 'sort', 'topK', 'bottomK', 'reverse', 'unique', 'isDuplicated', 'isUnique', 'shift'],
    'Missing values' => ['fillNull', 'fillNan', 'dropNulls', 'dropNans', 'interpolate', 'nullCount'],
    'Group and aggregate' => ['groupBy', 'groupByDynamic', 'rolling', 'upsample', 'count', 'sum', 'mean', 'median', 'min', 'max', 'std', 'var', 'product', 'quantile', 'nUnique', 'approxNUnique', 'corr', 'sumHorizontal', 'meanHorizontal', 'minHorizontal', 'maxHorizontal'],
    'Join and combine' => ['join', 'joinAsof', 'joinWhere', 'vstack', 'extend', 'mergeSorted', 'update'],
    'Reshape' => ['pivot', 'unpivot', 'melt', 'explode', 'unnest', 'transpose', 'partitionBy'],
    'SQL' => ['sql'],
    'Write' => ['/^(write|sink)/'],
];
const COLUMN = [
    'Arithmetic and comparison' => ['abs', 'add', 'sub', 'mul', 'div', 'truediv', 'floordiv', 'mod', 'pow', 'neg', 'sign', 'dot', 'eq', 'ne', 'gt', 'ge', 'lt', 'le', 'eqMissing', 'neMissing', 'isBetween', 'isClose', 'isIn'],
    'Logic and bits' => ['and', 'or', 'xor', 'not', '/^bitwise/'],
    'Missing values' => ['fillNull', 'fillNan', 'dropNulls', 'dropNans', 'isNull', 'isNotNull', 'isNan', 'isNotNan', 'forwardFill', 'backwardFill', 'interpolate', 'interpolateBy', 'nullCount', 'hasNulls'],
    'Aggregate' => ['sum', 'mean', 'median', 'min', 'max', 'std', 'var', 'count', 'len', 'nUnique', 'approxNUnique', 'product', 'quantile', 'mode', 'first', 'last', 'item', 'skew', 'kurtosis', 'entropy', 'maxBy', 'minBy', 'nanMax', 'nanMin', 'argMax', 'argMin', 'any', 'all', 'implode', 'isEmpty', 'describe', 'valueCounts', 'uniqueCounts'],
    'Math' => ['sqrt', 'cbrt', 'exp', '/^log/', '/^(arc)?(sin|cos|tan|cot)h?$/', 'degrees', 'radians', 'ceil', 'floor', 'round', 'roundSigFigs', 'clip', 'isFinite', 'isInfinite'],
    'Windows, cumulative, and rolling' => ['over', '/^cum/', '/^rolling/', '/^ewm/', 'diff', 'pctChange', 'shift', 'rank', 'rle', 'rleId', 'peakMax', 'peakMin'],
    'Sort, select, and deduplicate' => ['sort', 'sortBy', 'argSort', 'reverse', 'topK', 'topKBy', 'bottomK', 'bottomKBy', 'head', 'tail', 'limit', 'slice', 'gather', 'gatherEvery', 'get', 'filter', 'sample', 'shuffle', 'searchSorted', 'lowerBound', 'upperBound', 'indexOf', 'argTrue', 'argUnique', 'unique', 'isUnique', 'isDuplicated', 'isFirstDistinct', 'isLastDistinct', 'isSorted'],
    'Combine and reshape' => ['append', 'extend', 'extendConstant', 'repeatBy', 'explode', 'flatten', 'reshape', 'zipWith'],
    'Types, names, and replacing values' => ['cast', 'reinterpret', 'toPhysical', 'alias', 'rename', 'exclude', 'cut', 'qcut', 'hist', 'replace', 'replaceStrict', 'dtype', 'set', 'scatter'],
    'Namespaces' => ['str', 'dt', 'list', 'arr', 'struct', 'cat', 'bin', 'name', 'meta'],
    'Create and convert' => ['toList', 'toFrame', 'toDummies', 'newFromIndex', 'clone', 'clear', 'equals', 'estimatedSize', 'nChunks', 'rechunk', 'shrinkToFit', 'chunkLengths', 'shape', '__call', '__toString'],
];
const GROUPED = ['dataframe' => TABLE, 'lazyframe' => TABLE, 'expr' => COLUMN, 'series' => COLUMN];

// The functions by task, each with a one-line purpose, for the reference index and the
// Functions section. Every function of the extension must be listed; the I/O category
// is documented on the Reading and writing page.
const IO_CATEGORY = 'Read files and databases';
const FUNCTIONS = [
    IO_CATEGORY => [
        'read_csv' => 'Read a CSV file into a `DataFrame`.',
        'scan_csv' => 'Scan a CSV file lazily as a `LazyFrame`.',
        'read_parquet' => 'Read a Parquet file into a `DataFrame`.',
        'scan_parquet' => 'Scan a Parquet file lazily as a `LazyFrame`.',
        'read_parquet_schema' => 'Read the schema of a Parquet file.',
        'read_ipc' => 'Read an Arrow IPC file into a `DataFrame`.',
        'scan_ipc' => 'Scan an Arrow IPC file lazily as a `LazyFrame`.',
        'read_ipc_schema' => 'Read the schema of an Arrow IPC file.',
        'read_ipc_stream' => 'Read an Arrow IPC stream into a `DataFrame`.',
        'read_ndjson' => 'Read newline-delimited JSON into a `DataFrame`.',
        'scan_ndjson' => 'Scan newline-delimited JSON lazily as a `LazyFrame`.',
        'read_json' => 'Read a JSON array of rows into a `DataFrame`.',
        'read_database' => 'Read the result of a PDO query into a `DataFrame`.',
    ],
    'Create and combine data frames' => [
        'from_dict' => 'Build a `DataFrame` from an array of columns.',
        'from_dicts' => 'Build a `DataFrame` from a list of associative rows.',
        'from_records' => 'Build a `DataFrame` from a list of rows.',
        'json_normalize' => 'Flatten nested arrays into a `DataFrame` with one column per path.',
        'concat' => 'Concatenate frames or series vertically, horizontally, or diagonally.',
        'union' => 'Concatenate frames without guaranteeing row order.',
        'select' => 'Evaluate expressions into a `DataFrame` without an input frame.',
        'sql' => 'Run a SQL query against the given frames.',
        'collect_all' => 'Collect several lazy frames in parallel.',
    ],
    'Columns and literals' => [
        'col' => 'Refer to columns by name, by regular expression, or by data type.',
        'lit' => 'A literal value, optionally of a given data type.',
        'all' => 'Every column.',
        'exclude' => 'Every column except the given ones.',
        'nth' => 'Columns by position.',
        'first' => 'The first value of the given columns.',
        'last' => 'The last value of the given columns.',
        'len' => 'The number of rows.',
        'row_index' => 'A row index column, starting at 0.',
        'element' => 'The current element inside `list()->eval()` and `arr()` operations.',
        'field' => 'A struct field inside `struct()->withFields()`.',
        'struct_' => 'Combine expressions into a struct column.',
        'repeat' => 'A value repeated `n` times.',
        'ones' => 'A column of `n` ones.',
        'zeros' => 'A column of `n` zeros.',
        'sql_expr' => 'Parse a SQL expression into an `Expr`.',
    ],
    'Conditions' => [
        'when' => 'Start a `when()->then()->otherwise()` conditional expression.',
        'coalesce' => 'The first non-null value across expressions, row by row.',
    ],
    'Aggregates' => [
        'sum' => 'Sum of the given columns.',
        'mean' => 'Mean of the given columns.',
        'median' => 'Median of the given columns.',
        'min' => 'Minimum of the given columns.',
        'max' => 'Maximum of the given columns.',
        'count' => 'Number of non-null values in the given columns.',
        'n_unique' => 'Number of distinct values in the given columns.',
        'approx_n_unique' => 'Approximate number of distinct values (HyperLogLog).',
        'std' => 'Sample standard deviation of a column.',
        'var_' => 'Sample variance of a column.',
        'quantile' => 'A quantile of a column.',
        'any' => 'Whether any value of the given boolean columns is true.',
        'implode' => 'Collect the values of the given columns into one list.',
        'head' => 'The first `n` values of the given columns.',
        'tail' => 'The last `n` values of the given columns.',
        'corr' => 'Correlation of two columns (Pearson or Spearman).',
        'cov' => 'Covariance of two columns.',
        'rolling_corr' => 'Rolling correlation of two columns.',
        'rolling_cov' => 'Rolling covariance of two columns.',
        'cum_count' => 'Cumulative count of non-null values.',
        'cum_sum' => 'Cumulative sum of the given columns.',
    ],
    'Across columns' => [
        'all_horizontal' => 'Logical AND across expressions, row by row.',
        'any_horizontal' => 'Logical OR across expressions, row by row.',
        'sum_horizontal' => 'Sum across expressions, row by row.',
        'mean_horizontal' => 'Mean across expressions, row by row.',
        'min_horizontal' => 'Minimum across expressions, row by row.',
        'max_horizontal' => 'Maximum across expressions, row by row.',
        'cum_sum_horizontal' => 'Cumulative sum across expressions, as a struct.',
        'concat_str' => 'Concatenate expressions as strings, row by row.',
        'concat_list' => 'Combine expressions into a list column, row by row.',
        'concat_arr' => 'Combine expressions into a fixed-width array column.',
        'format' => 'Format expressions into a string with `{}` placeholders.',
        'arctan2' => 'Two-argument arctangent of `y` and `x`.',
    ],
    'Dates, times, and ranges' => [
        'date' => 'A date from year, month, and day expressions.',
        'datetime' => 'A datetime from its component expressions.',
        'time' => 'A time of day from its component expressions.',
        'duration' => 'A duration from weeks, days, hours, and smaller units.',
        'from_epoch' => 'Convert Unix timestamps to datetimes or dates.',
        'business_day_count' => 'Number of business days between two dates.',
        'date_range' => 'A column of dates at a fixed interval.',
        'date_ranges' => 'A list of dates per row, between two date columns.',
        'datetime_range' => 'A column of datetimes at a fixed interval.',
        'datetime_ranges' => 'A list of datetimes per row, between two columns.',
        'time_range' => 'A column of times of day at a fixed interval.',
        'time_ranges' => 'A list of times per row, between two columns.',
        'int_range' => 'A column of integers from `start` to `end`.',
        'int_ranges' => 'A list of integers per row, between two columns.',
        'arange' => 'A column of integers from `start` to `end`, as `int_range()`.',
        'linear_space' => 'Evenly spaced values between two bounds.',
        'linear_spaces' => 'Evenly spaced values per row, as a list or array.',
    ],
    'Sorting and positions' => [
        'arg_sort_by' => 'Row indices that sort by the given expressions.',
        'arg_where' => 'Row indices where a condition is true.',
        'escape_regex' => 'Escape regular expression metacharacters in a string.',
    ],
    'Runtime' => [
        'version' => 'The phpolars extension version.',
        'polars_version' => 'The version of the embedded Polars Rust crate.',
        'thread_pool_size' => 'The number of threads in the Polars thread pool.',
        'set_random_seed' => 'Seed Polars\' random number generator for `sample()` and `shuffle()`.',
    ],
];

// The selectors in Phpolars\cs, each with a one-line purpose.
const SELECTORS = [
    'all' => 'Every column.', 'by_name' => 'Columns with the given names.', 'by_index' => 'Columns at the given positions.',
    'by_dtype' => 'Columns of the given data types.', 'first' => 'The first column.', 'last' => 'The last column.',
    'exclude' => 'Every column except the given ones.', 'starts_with' => 'Columns whose names start with a prefix.',
    'ends_with' => 'Columns whose names end with a suffix.', 'contains' => 'Columns whose names contain a substring.',
    'matches' => 'Columns whose names match a regular expression.', 'numeric' => 'Integer, float, and decimal columns.',
    'integer' => 'Integer columns.', 'signed_integer' => 'Signed integer columns.', 'unsigned_integer' => 'Unsigned integer columns.',
    'float' => 'Float columns.', 'decimal' => 'Decimal columns.', 'string' => 'String columns.', 'boolean' => 'Boolean columns.',
    'binary' => 'Binary columns.', 'categorical' => 'Categorical columns.', 'enum_' => 'Enum columns.',
    'temporal' => 'Date, datetime, duration, and time columns.', 'date' => 'Date columns.', 'datetime' => 'Datetime columns, optionally of a time unit and zone.',
    'duration' => 'Duration columns, optionally of a time unit.', 'time' => 'Time columns.', 'list_' => 'List columns, optionally of an inner type.',
    'array_' => 'Array columns, optionally of an inner type and width.', 'struct_' => 'Struct columns.', 'nested' => 'List, array, and struct columns.',
    'empty_' => 'No columns.',
];

// Every public class: its reference page, its anchor prefix, and a one-line purpose.
const CLASSES = [
    'Data frames' => [
        'Phpolars\\DataFrame' => ['dataframe', 'dataframe', 'Data in memory; each method runs immediately.'],
        'Phpolars\\LazyFrame' => ['dataframe', 'lazyframe', 'A query plan that runs, optimized, on `collect()`.'],
        'Phpolars\\GroupBy' => ['dataframe', 'groupby', 'The result of `DataFrame::groupBy()`: `agg()` and per-group summaries.'],
        'Phpolars\\LazyGroupBy' => ['dataframe', 'lazygroupby', 'The result of `LazyFrame::groupBy()`.'],
        'Phpolars\\GroupIterator' => ['dataframe', 'groupiterator', 'Iterates over a `GroupBy` with `foreach`: group keys and rows.'],
        'Phpolars\\SQLContext' => ['dataframe', 'sqlcontext', 'Registers frames as tables and runs SQL queries.'],
    ],
    'Columns and expressions' => [
        'Phpolars\\Series' => ['series', 'series', 'One named column of values.'],
        'Phpolars\\Expr' => ['expressions', 'expr', 'A computation on columns, evaluated by `select()`, `withColumns()`, `filter()`, and `agg()`.'],
        'Phpolars\\When' => ['expressions', 'when', 'A condition from `when()`, awaiting `then()`.'],
        'Phpolars\\Then' => ['expressions', 'then', 'A conditional expression, continued by `when()` or `otherwise()`.'],
        'Phpolars\\StrNameSpace' => ['expressions', 'str', 'String operations: `col(...)->str()`.'],
        'Phpolars\\DtNameSpace' => ['expressions', 'dt', 'Date, datetime, and duration operations: `col(...)->dt()`.'],
        'Phpolars\\ListNameSpace' => ['expressions', 'list', 'List operations: `col(...)->list()`.'],
        'Phpolars\\ArrayNameSpace' => ['expressions', 'arr', 'Fixed-width array operations: `col(...)->arr()`.'],
        'Phpolars\\StructNameSpace' => ['expressions', 'struct', 'Struct field operations: `col(...)->struct()`.'],
        'Phpolars\\CatNameSpace' => ['expressions', 'cat', 'Categorical operations: `col(...)->cat()`.'],
        'Phpolars\\BinaryNameSpace' => ['expressions', 'bin', 'Binary operations: `col(...)->bin()`.'],
        'Phpolars\\NameNameSpace' => ['expressions', 'name', 'Output column naming: `col(...)->name()`.'],
        'Phpolars\\MetaNameSpace' => ['expressions', 'meta', 'Inspection of an expression itself: `col(...)->meta()`.'],
    ],
    'Types and settings' => [
        'Phpolars\\DataType' => ['types', 'datatype', 'Data type names as constants, and builders for parameterized types.'],
        'Phpolars\\Config' => ['types', 'config', 'Settings for how data frames print.'],
    ],
];

function slug(string $s): string
{
    return trim((string) preg_replace('/[^a-z]+/', '-', strtolower($s)), '-');
}

/** @return array<string, array<string, string>> the signatures of each category, in category order */
function categorize(array $lines, array $categories): array
{
    $groups = array_fill_keys([...array_keys($categories), 'Other'], []);
    foreach ($lines as $name => $line) {
        $category = 'Other';
        foreach ($categories as $title => $rules) {
            foreach ($rules as $rule) {
                if ($rule === $name || ($rule[0] === '/' && preg_match($rule, $name) === 1)) {
                    $category = $title;
                    break 2;
                }
            }
        }
        $groups[$category][$name] = $line;
    }

    return array_filter($groups);
}

/** Each signature as a list item with its own anchor, such as #expr-abs, and its note if it has one. */
function items(string $prefix, array $lines): string
{
    $md = '';
    foreach ($lines as $name => $line) {
        $note = NOTES["{$prefix}-{$name}"] ?? null;
        $md .= "- []{#{$prefix}-{$name}} `{$line}`".($note === null ? '' : ": {$note}")."\n";
    }

    return $md;
}

/** Index links to the anchors that items() writes. */
function index(string $prefix, array $names): string
{
    return implode(' · ', array_map(fn ($n) => "[`{$n}`](#{$prefix}-{$n})", $names));
}

/** @return array<string, string> signatures keyed by method name, sorted */
function methods(string $class): array
{
    $r = new ReflectionClass($class);
    $lines = [];
    foreach ($r->getMethods() as $m) {
        if ($m->getDeclaringClass()->getName() !== $class || $m->getName() === '__construct') {
            continue;
        }
        $lines[$m->getName()] = ($m->isStatic() ? 'static ' : '').sig($m);
    }
    ksort($lines, SORT_STRING | SORT_FLAG_CASE);

    return $lines;
}

/** One row of the reference index: the callable as code, linked to its entry, and its purpose. */
function row(string $label, string $href, string $purpose): string
{
    return "| [`{$label}`]({$href}) | {$purpose} |\n";
}

// Each page's classes, in page order. A class is a level-2 heading and its categories
// level-3 headings, so the page's table of contents lists both.
$pages = [];
foreach (CLASSES as $classes) {
    foreach ($classes as $class => [$page, $prefix]) {
        $pages[$page][$class] = $prefix;
    }
}
$documented = array_merge(...array_values(array_map('array_keys', CLASSES)));
foreach (get_declared_classes() as $class) {
    if (str_starts_with($class, 'Phpolars\\') && ! str_starts_with($class, 'Phpolars\\Exception\\') && $class !== 'Phpolars\\PolarsError' && ! in_array($class, $documented, true)) {
        throw new RuntimeException("{$class} is not in CLASSES");
    }
}

$indexes = [];
$classIndex = [];
$methodCount = 0;
foreach ($pages as $page => $classes) {
    $md = '';
    $indexes[$page] = '';
    foreach ($classes as $class => $prefix) {
        $short = substr($class, strlen('Phpolars\\'));
        $lines = methods($class);
        $methodCount += count($lines);
        $md .= "## `{$short}` {#{$prefix}}\n\n";
        if (isset(GROUPED[$prefix])) {
            $indexes[$page] .= "- [`{$short}`](#{$prefix})\n";
            $links = [];
            foreach (categorize($lines, GROUPED[$prefix]) as $title => $group) {
                $anchor = $prefix.'--'.slug($title);
                $md .= "### {$title} {#{$anchor}}\n\n".items($prefix, $group)."\n";
                $indexes[$page] .= "    - [{$title}](#{$anchor}): ".index($prefix, array_keys($group))."\n";
                $links[] = "[{$title}]({$page}.qmd#{$anchor})";
            }
            $classIndex[$class] = implode(' · ', $links);
        } else {
            $md .= items($prefix, $lines)."\n";
            $indexes[$page] .= "- [`{$short}`](#{$prefix}): ".index($prefix, array_keys($lines))."\n";
            $classIndex[$class] = count($lines).(count($lines) === 1 ? ' method' : ' methods');
        }
    }
    file_put_contents("{$out}/_api-{$page}.md", $md);
}

$functions = array_filter(get_extension_funcs('php_polars'), fn ($f) => str_starts_with(strtolower($f), 'phpolars\\'));
$lines = [];
$selectors = [];
foreach ($functions as $f) {
    $r = new ReflectionFunction($f);
    if ($r->getNamespaceName() === 'Phpolars\\cs') {
        $selectors[$r->getShortName()] = 'cs\\'.sig($r);
    } else {
        $lines[$r->getShortName()] = sig($r);
    }
}
$listed = array_merge(...array_values(array_map('array_keys', FUNCTIONS)));
foreach ([array_diff(array_keys($lines), $listed), array_diff($listed, array_keys($lines)), array_diff(array_keys($selectors), array_keys(SELECTORS)), array_diff(array_keys(SELECTORS), array_keys($selectors))] as $diff) {
    if ($diff !== []) {
        throw new RuntimeException('FUNCTIONS or SELECTORS do not match the extension: '.implode(', ', $diff));
    }
}
ksort($selectors, SORT_STRING | SORT_FLAG_CASE);

// The Functions section of the Expressions page by category, and the I/O functions.
$md = '';
$fnIndex = '';
foreach (FUNCTIONS as $title => $names) {
    $group = array_map(fn ($n) => $lines[$n], array_combine(array_keys($names), array_keys($names)));
    if ($title === IO_CATEGORY) {
        file_put_contents("{$out}/_api-io.md", items('fn', $group));
        continue;
    }
    $anchor = 'fn--'.slug($title);
    $md .= "### {$title} {#{$anchor}}\n\n".items('fn', $group)."\n";
    $fnIndex .= "    - [{$title}](#{$anchor}): ".index('fn', array_keys($names))."\n";
}
file_put_contents("{$out}/_api-functions.md", $md);
$indexes['expressions'] = "- [Functions](#functions)\n".$fnIndex
    .'- [Selectors](#selectors): '.index('cs', array_keys($selectors))."\n"
    .$indexes['expressions'];
foreach ($indexes as $page => $index) {
    if ($page !== 'types') {
        file_put_contents("{$out}/_index-{$page}.md", $index);
    }
}
file_put_contents("{$out}/_api-selectors.md", items('cs', $selectors));

// The reference index: every function and selector with its purpose, and every class
// with its purpose and links to its method categories.
$index = '';
foreach (FUNCTIONS as $title => $names) {
    $page = $title === IO_CATEGORY ? 'io' : 'expressions';
    $index .= "### {$title} {#index-".slug($title)."}\n\n| Function | Purpose |\n| --- | --- |\n";
    foreach ($names as $name => $purpose) {
        $index .= row("{$name}()", "{$page}.qmd#fn-{$name}", $purpose);
    }
    $index .= "\n";
}
$index .= "### Selectors {#index-selectors}\n\n| Function | Purpose |\n| --- | --- |\n";
foreach ($selectors as $name => $_) {
    $index .= row("cs\\{$name}()", "expressions.qmd#cs-{$name}", SELECTORS[$name]);
}
$classes = "| Class | Purpose | Methods |\n| --- | --- | --- |\n";
foreach (CLASSES as $title => $group) {
    $classes .= "| **{$title}** | | |\n";
    foreach ($group as $class => [$page, $prefix, $purpose]) {
        $classes .= '| [`'.substr($class, strlen('Phpolars\\'))."`]({$page}.qmd#{$prefix}) | {$purpose} | {$classIndex[$class]} |\n";
    }
}
file_put_contents("{$out}/_api-index-functions.md", $index);
file_put_contents("{$out}/_api-index-classes.md", $classes);
printf("wrote reference signatures: %d functions, %d selectors, %d methods of %d classes\n", count($lines), count($selectors), $methodCount, count($documented));
