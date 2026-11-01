<?php
// php -d extension=... bench/pipeline.php CSV [REPS]
// read_csv -> filter x > 500 -> group_by g -> mean(y), count(x) -> toDicts.
//
// Runs phpolars with 1, 2, and 4 Polars threads and a plain PHP fgetcsv loop,
// each in a fresh process: one untimed warm-up, then REPS timed runs. Each run
// is timed from reading the file through building the PHP arrays. Memory is
// the process's peak memory footprint from /usr/bin/time -l on macOS, and its
// peak anonymous memory from bench/csv-pipeline/peakmem.c on Linux, with the
// peak resident set size beside it. Checks that every result matches plain
// PHP's and prints the record as JSON, with every run's time and the median.
//
// Make the CSV with: php bench/make-csv.php 1000000 bench/data.csv
use function Phpolars\col;

[$_, $csv, $reps, $only] = $argv + [null, __DIR__.'/data.csv', '7', null];

$runs = [
    'phpolars' => fn () => Phpolars\read_csv($csv)
        ->filter(col('x')->gt(500))
        ->groupBy('g')
        ->agg(col('y')->mean()->alias('mean_y'), col('x')->count()->alias('n'))
        ->sort('g')
        ->toDicts(),
    'plain PHP' => function () use ($csv) {
        $in = fopen($csv, 'r');
        fgetcsv($in, escape: '');
        $acc = [];
        while (($r = fgetcsv($in, escape: '')) !== false) {
            if ($r[1] === '' || (int) $r[1] <= 500) {
                continue;
            }
            $acc[$r[0]][0] = ($acc[$r[0]][0] ?? 0) + (float) $r[2];
            $acc[$r[0]][1] = ($acc[$r[0]][1] ?? 0) + 1;
        }
        ksort($acc);
        $rows = [];
        foreach ($acc as $g => [$sum, $n]) {
            $rows[] = ['g' => $g, 'mean_y' => $sum / $n, 'n' => $n];
        }
        return $rows;
    },
];

$median = function (array $xs): float {
    sort($xs);
    $n = count($xs);
    return $n % 2 ? $xs[intdiv($n, 2)] : ($xs[$n / 2 - 1] + $xs[$n / 2]) / 2;
};

// A worker process: time one implementation and print its runs as JSON.
if ($only !== null) {
    $f = $runs[$only];
    $out = $f();
    $ms = [];
    for ($i = 0; $i < (int) $reps; $i++) {
        gc_collect_cycles();
        $t = hrtime(true);
        $out = $f();
        $ms[] = round((hrtime(true) - $t) / 1e6, 1);
    }
    echo json_encode(['ms' => $ms, 'rows' => $out, 'threads' => extension_loaded('php_polars') ? Phpolars\thread_pool_size() : null]), "\n";
    exit;
}

$darwin = PHP_OS_FAMILY === 'Darwin';
$ext = getenv('PHPOLARS_EXTENSION');
if (! $ext) {
    fwrite(STDERR, "Set PHPOLARS_EXTENSION to the php_polars library.\n");
    exit(2);
}
$cases = [
    'phpolars, 1 thread' => ['phpolars', ['-dextension='.$ext, '-dphp_polars.max_threads=1']],
    'phpolars, 2 threads (default)' => ['phpolars', ['-dextension='.$ext, '-dphp_polars.max_threads=2']],
    'phpolars, 4 threads' => ['phpolars', ['-dextension='.$ext, '-dphp_polars.max_threads=4']],
    'plain PHP arrays (fgetcsv loop)' => ['plain PHP', []],
];
$results = [];
foreach ($cases as $case => [$impl, $flags]) {
    $cmd = [$darwin ? '/usr/bin/time' : 'peakmem', '-l', PHP_BINARY, '-n', '-dmemory_limit=-1', ...$flags, __FILE__, $csv, $reps, $impl];
    $p = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    if (proc_close($p) !== 0) {
        throw new RuntimeException("{$case}: {$err}");
    }
    $run = json_decode($out, true, flags: JSON_THROW_ON_ERROR);
    $bytes = fn (string $label) => preg_match('/(\d+)\s+'.$label.'/', $err, $m) ? round((int) $m[1] / 2 ** 20, 1) : null;
    $results[$case] = $run + ['footprint_mib' => $bytes('peak memory footprint'), 'maxrss_mib' => $bytes('maximum resident set size')];
}

// Every result matches plain PHP's: the same groups and counts, and means to 1e-9.
$b = $results['plain PHP arrays (fgetcsv loop)']['rows'];
foreach ($results as $case => $run) {
    $a = $run['rows'];
    $same = count($a) === count($b);
    foreach ($a as $i => $r) {
        $same = $same && $r['g'] === $b[$i]['g'] && $r['n'] === $b[$i]['n'] && abs($r['mean_y'] - $b[$i]['mean_y']) < 1e-9;
    }
    if (! $same) {
        throw new RuntimeException("{$case}: the result differs from plain PHP's");
    }
}

$sh = fn (string $cmd) => trim((string) shell_exec($cmd.' 2>/dev/null'));
echo json_encode([
    'workload' => 'read_csv, filter, group, and aggregate a '.round(filesize($csv) / 1e6).' MB CSV, then build PHP arrays',
    'statistic' => 'median of '.$reps.' runs in one process, after one untimed warm-up',
    'environment' => [
        'cpu' => $darwin ? $sh('sysctl -n machdep.cpu.brand_string') : $sh("lscpu | sed -n 's/^Model name: *//p'"),
        'cores' => (int) ($darwin ? $sh('sysctl -n hw.ncpu') : $sh('nproc')),
        'os' => $darwin ? 'macOS '.$sh('sw_vers -productVersion') : $sh('uname -sr'),
        'php' => PHP_VERSION,
        'date' => gmdate('Y-m-d'),
        'commit' => $sh('git -C '.escapeshellarg(__DIR__).' rev-parse --short HEAD'),
        'memory' => $darwin ? 'peak memory footprint (/usr/bin/time -l)' : 'peak anonymous memory (peakmem)',
    ],
    'dataset' => ['rows' => (int) $sh('tail -n +2 '.escapeshellarg($csv).' | wc -l'), 'bytes' => filesize($csv), 'sha256' => hash_file('sha256', $csv)],
    'results' => array_map(fn (array $r) => [
        'median_ms' => $median($r['ms']), 'ms' => $r['ms'], 'threads' => $r['threads'],
        'memory_mib' => $r['footprint_mib'], 'maxrss_mib' => $r['maxrss_mib'],
    ], $results),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n";
