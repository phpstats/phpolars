<?php
// php -d extension=... scripts/docs-reference.php: writes docs/reference/_api-<name>.md,
// signature lists generated from the extension's reflection data with an anchor per
// method, and docs/reference/_index-<page>.md, a method index linking to them.

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

/** Each signature as a list item with its own anchor, such as #expr-abs. */
function items(string $prefix, array $lines): string
{
    $md = '';
    foreach ($lines as $name => $line) {
        $md .= "- []{#{$prefix}-{$name}} `{$line}`\n";
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
        $md .= "### `{$class}` {#{$prefix}}\n\n".items($prefix, $lines)."\n";
        $indexes[$page] .= "- [`{$class}`](#{$prefix}): ".index($prefix, array_keys($lines))."\n";
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
