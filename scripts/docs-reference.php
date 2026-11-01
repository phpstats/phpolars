<?php
// php -d extension=... scripts/docs-reference.php: writes docs/reference/_api-<name>.md,
// signature lists generated from the extension's reflection data with an anchor per
// method, and docs/reference/_index-<page>.md, a method index linking to them. The
// methods of the larger classes are grouped by category, and a few methods whose
// behavior is easy to misread get a one-line note.

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

// Notes on methods whose behavior differs from what PHP or SQL habits suggest.
const NOTES = [
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

$prefixes = [
    'Phpolars\\DataFrame' => 'dataframe', 'Phpolars\\LazyFrame' => 'lazyframe', 'Phpolars\\GroupBy' => 'groupby', 'Phpolars\\LazyGroupBy' => 'lazygroupby',
    'Phpolars\\Expr' => 'expr', 'Phpolars\\When' => 'when', 'Phpolars\\Then' => 'then', 'Phpolars\\StrNameSpace' => 'str', 'Phpolars\\DtNameSpace' => 'dt',
    'Phpolars\\ListNameSpace' => 'list', 'Phpolars\\StructNameSpace' => 'struct', 'Phpolars\\NameNameSpace' => 'name', 'Phpolars\\CatNameSpace' => 'cat',
    'Phpolars\\ArrayNameSpace' => 'arr', 'Phpolars\\BinaryNameSpace' => 'bin', 'Phpolars\\Series' => 'series',
];
$pages = [
    'dataframe' => ['Phpolars\\DataFrame', 'Phpolars\\LazyFrame', 'Phpolars\\GroupBy', 'Phpolars\\LazyGroupBy'],
    'expressions' => ['Phpolars\\Expr', 'Phpolars\\When', 'Phpolars\\Then', 'Phpolars\\StrNameSpace', 'Phpolars\\DtNameSpace', 'Phpolars\\ListNameSpace', 'Phpolars\\StructNameSpace', 'Phpolars\\NameNameSpace', 'Phpolars\\CatNameSpace', 'Phpolars\\ArrayNameSpace', 'Phpolars\\BinaryNameSpace'],
    'series' => ['Phpolars\\Series'],
];
$indexes = [];
foreach ($pages as $page => $classes) {
    $md = '';
    $indexes[$page] = '';
    foreach ($classes as $class) {
        $prefix = $prefixes[$class];
        $lines = methods($class);
        $md .= "### `{$class}` {#{$prefix}}\n\n";
        if (isset(GROUPED[$prefix])) {
            $indexes[$page] .= "- [`{$class}`](#{$prefix})\n";
            foreach (categorize($lines, GROUPED[$prefix]) as $title => $group) {
                $anchor = $prefix.'--'.slug($title);
                $md .= "#### {$title} {#{$anchor}}\n\n".items($prefix, $group)."\n";
                $indexes[$page] .= "    - [{$title}](#{$anchor}): ".index($prefix, array_keys($group))."\n";
            }
        } else {
            $md .= items($prefix, $lines)."\n";
            $indexes[$page] .= "- [`{$class}`](#{$prefix}): ".index($prefix, array_keys($lines))."\n";
        }
    }
    file_put_contents("{$out}/_api-{$page}.md", $md);
}

$functions = array_filter(get_extension_funcs('php_polars'), fn ($f) => str_starts_with(strtolower($f), 'phpolars\\'));
$io = ['read_', 'scan_'];
$expr = [];
$read = [];
$selectors = [];
foreach ($functions as $f) {
    $r = new ReflectionFunction($f);
    $line = sig($r);
    if ($r->getNamespaceName() === 'Phpolars\\cs') {
        $selectors[$r->getShortName()] = 'cs\\'.$line;
    } elseif (array_filter($io, fn ($p) => str_starts_with($r->getShortName(), $p)) !== []) {
        $read[$r->getShortName()] = $line;
    } else {
        $expr[$r->getShortName()] = $line;
    }
}
ksort($expr, SORT_STRING | SORT_FLAG_CASE);
ksort($read, SORT_STRING | SORT_FLAG_CASE);
ksort($selectors, SORT_STRING | SORT_FLAG_CASE);
$indexes['expressions'] = '- [Functions](#functions): '.index('fn', array_keys($expr))."\n"
    .'- [Selectors](#selectors): '.index('cs', array_keys($selectors))."\n"
    .$indexes['expressions'];
foreach ($indexes as $page => $md) {
    file_put_contents("{$out}/_index-{$page}.md", $md);
}
file_put_contents("{$out}/_api-selectors.md", items('cs', $selectors));
file_put_contents("{$out}/_api-functions.md", items('fn', $expr));
file_put_contents("{$out}/_api-io.md", items('fn', $read));
echo "wrote reference signatures\n";
