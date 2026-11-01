<?php
// php -d extension=... run.php CSV [REPS]: runs plain.php and phpstats.php on CSV
// in fresh processes, one warm-up each and then REPS alternating runs, checks
// that the two results agree, and prints the benchmark record as JSON.
//
// Each script times its own work, from opening the file to holding the result
// as PHP values, and reports the process's peak resident set size
// (getrusage ru_maxrss), which counts Polars' native allocations and the
// file pages the CSV reader maps. The file is read from the OS page cache:
// the warm-up runs load it, and no run reads it from disk.
[$_, $csv, $reps] = $argv + [null, null, '7'];
$php = PHP_BINARY;
$ext = '-dextension='.getenv('PHPOLARS_EXTENSION');
$threads = (int) (getenv('PHPOLARS_THREADS') ?: 2);
$scripts = [
    'phpolars' => [$php, '-dmemory_limit=-1', $ext, "-dphp_polars.max_threads={$threads}", __DIR__.'/phpstats.php', $csv],
    'plain PHP' => [$php, '-dmemory_limit=-1', __DIR__.'/plain.php', $csv],
];
$once = function (array $cmd): array {
    $p = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    if (proc_close($p) !== 0) { throw new RuntimeException($err); }
    return json_decode($out, true, flags: JSON_THROW_ON_ERROR);
};
$runs = [];
foreach ($scripts as $name => $cmd) { $last[$name] = $once($cmd); }
for ($i = 0; $i < (int) $reps; $i++) {
    foreach ($scripts as $name => $cmd) { $runs[$name][] = $last[$name] = $once($cmd); }
}
// The results agree: same groups and counts exactly, sums and means to 1e-9 relative.
[$a, $b] = [$last['phpolars']['rows'], $last['plain PHP']['rows']];
$worst = 0.0;
if (count($a) !== count($b)) { throw new RuntimeException('row counts differ'); }
foreach ($a as $i => $row) {
    foreach ($row as $j => $v) {
        $w = $b[$i][$j];
        if (is_float($v) || is_float($w)) {
            $worst = max($worst, abs($v - $w) / max(abs($w), 1e-300));
        } elseif ($v !== $w) { throw new RuntimeException("row $i column $j differs"); }
    }
}
if ($worst > 1e-9) { throw new RuntimeException("sums differ by $worst"); }
$median = function (array $xs): float { sort($xs); $n = count($xs); return $n % 2 ? $xs[intdiv($n, 2)] : ($xs[$n / 2 - 1] + $xs[$n / 2]) / 2; };
$case = 'Filter, group, and summarize a '.round(filesize($csv) / 1e9, 1).' GB CSV file';
$rows = [];
foreach ($runs as $name => $rs) {
    $ms = array_map(fn ($r) => round($r['ms'], 1), $rs);
    $mib = array_map(fn ($r) => round($r['maxrss'] / 2 ** 20, 1), $rs);
    $rows[] = ['case' => $case, 'implementation' => $name, 'value' => $median($ms), 'reps' => $ms,
        'peak_memory_mib' => $median($mib), 'reps_peak_memory_mib' => $mib];
}
$sh = fn (string $cmd) => trim((string) shell_exec($cmd.' 2>/dev/null'));
$darwin = PHP_OS_FAMILY === 'Darwin';
echo json_encode([
    'title' => 'A '.round(filesize($csv) / 1e9, 1).' GB CSV file: phpolars and plain PHP',
    'description' => 'Both scripts read every row of a seeded order-events CSV, keep the completed orders, and compute orders, units, revenue, and the mean amount for each region and month. phpolars scans the file lazily and runs the query with the streaming engine. Plain PHP reads one row at a time with fgetcsv and adds each row to the totals of its group. Each run is a fresh process. Time is measured inside the script, from opening the file to holding the result as PHP values. Memory is the peak resident set size of the process, which includes Polars\' native allocations and the pages of the file that the CSV reader maps. The file was in the operating system\'s page cache in every run. The value is the median of '.$reps.' runs after one warm-up run of each.',
    'measure' => 'Elapsed time',
    'unit' => 'ms',
    'statistic' => 'median',
    'environment' => [
        'cpu' => $darwin ? $sh('sysctl -n machdep.cpu.brand_string') : $sh("lscpu | sed -n 's/^Model name: *//p'"),
        'cores' => (int) ($darwin ? $sh('sysctl -n hw.ncpu') : $sh('nproc')),
        'memory_gb' => round((int) ($darwin ? $sh('sysctl -n hw.memsize') : 1024 * (int) $sh("awk '/MemTotal/ {print $2}' /proc/meminfo")) / 2 ** 30),
        'os' => $darwin ? 'macOS '.$sh('sw_vers -productVersion') : $sh('uname -sr'),
        'php' => PHP_VERSION,
        'phpolars' => phpversion('php_polars'),
        'polars' => Phpolars\polars_version(),
        'threads' => $last['phpolars']['threads'],
        'engine' => 'streaming',
        'cache' => 'warm',
        'repeats' => (int) $reps,
        'date' => gmdate('Y-m-d'),
        'commit' => $sh('git -C '.escapeshellarg(__DIR__).' rev-parse HEAD'),
    ],
    'dataset' => ['rows' => (int) $sh('tail -n +2 '.escapeshellarg($csv).' | wc -l'), 'bytes' => filesize($csv),
        'sha256' => hash_file('sha256', $csv), 'missing_amounts' => 'about 1%'],
    'check' => ['groups' => count($a), 'max_rel_difference' => $worst],
    'code' => ['phpolars' => file_get_contents(__DIR__.'/phpstats.php'), 'plain PHP' => file_get_contents(__DIR__.'/plain.php')],
    'rows' => $rows,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n";
