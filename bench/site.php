<?php
// php -d memory_limit=-1 -d extension=... bench/site.php DATA_DIR [REPS]
//
// Times reads, group-bys, and joins for the site benchmarks, with phpolars and
// with plain PHP arrays, and prints one JSON object. bench/site.py runs the
// same cases with py-polars on the same files. scripts/site-content writes the
// data files and combines the results.
//
//   facts.csv  1,000,000 rows: k (0..99,999), g (50 groups), x (int, ~2% null), y (float)
//   dim.csv      100,000 rows: k, label (500 labels), w (float)
declare(strict_types=1);

use function Phpolars\col;

[$_, $dir, $reps] = $argv + [null, null, '5'];
$reps = (int) $reps;
$facts = "$dir/facts.csv";
$dim = "$dir/dim.csv";

/** Times $f $reps times after one warm-up run; returns milliseconds per run. */
function timed(callable $f, int $reps): array
{
    $f();
    $times = [];
    for ($i = 0; $i < $reps; $i++) {
        gc_collect_cycles();
        $t = hrtime(true);
        $f();
        $times[] = round((hrtime(true) - $t) / 1e6, 3);
    }
    return $times;
}

$results = [];

// phpolars. Results stay in Polars, as they do in the py-polars script.
$pf = Phpolars\read_csv($facts);
$pd = Phpolars\read_csv($dim);
$results['phpolars'] = [
    'read' => timed(fn () => Phpolars\read_csv($facts), $reps),
    'group_by' => timed(fn () => $pf->groupBy('g')->agg(
        col('y')->mean()->alias('mean_y'),
        col('x')->sum()->alias('sum_x'),
        col('x')->count()->alias('n'),
    ), $reps),
    'join' => timed(fn () => $pf->join($pd, on: 'k', how: 'inner'), $reps),
];
$check['phpolars'] = [
    'groups' => $pf->groupBy('g')->agg(col('y')->mean())->height(),
    'joined' => $pf->join($pd, on: 'k', how: 'inner')->height(),
];
unset($pf, $pd);

// Plain PHP arrays: rows as lists, read with fgetcsv.
$readRows = function (string $path, callable $cast): array {
    $in = fopen($path, 'r');
    fgetcsv($in, escape: '');
    $rows = [];
    while (($r = fgetcsv($in, escape: '')) !== false) {
        $rows[] = $cast($r);
    }
    fclose($in);
    return $rows;
};
$castFact = fn (array $r) => [(int) $r[0], $r[1], $r[2] === '' ? null : (int) $r[2], (float) $r[3]];
$castDim = fn (array $r) => [(int) $r[0], $r[1], (float) $r[2]];
$af = $readRows($facts, $castFact);
$ad = $readRows($dim, $castDim);
$groupBy = function () use ($af): array {
    $acc = [];
    foreach ($af as [$k, $g, $x, $y]) {
        $a = $acc[$g] ?? [0.0, 0, 0, 0];
        $a[0] += $y;
        $a[1]++;
        if ($x !== null) {
            $a[2] += $x;
            $a[3]++;
        }
        $acc[$g] = $a;
    }
    $out = [];
    foreach ($acc as $g => [$sy, $ny, $sx, $nx]) {
        $out[] = ['g' => $g, 'mean_y' => $sy / $ny, 'sum_x' => $sx, 'n' => $nx];
    }
    return $out;
};
$join = function () use ($af, $ad): array {
    $index = [];
    foreach ($ad as $r) {
        $index[$r[0]][] = $r;
    }
    $out = [];
    foreach ($af as $f) {
        foreach ($index[$f[0]] ?? [] as $d) {
            $out[] = [$f[0], $f[1], $f[2], $f[3], $d[1], $d[2]];
        }
    }
    return $out;
};
$results['plain PHP arrays'] = [
    'read' => timed(fn () => $readRows($facts, $castFact), $reps),
    'group_by' => timed($groupBy, $reps),
    'join' => timed($join, $reps),
];
$check['plain PHP arrays'] = ['groups' => count($groupBy()), 'joined' => count($join())];

echo json_encode(['results' => $results, 'check' => $check, 'php' => PHP_VERSION,
    'phpolars' => phpversion('php_polars'), 'threads' => Phpolars\thread_pool_size()]), "\n";
