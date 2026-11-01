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
require __DIR__.'/vendor/autoload.php';

[$_, $csv, $reps] = $argv + [null, null, '7'];
$php = PHP_BINARY;
$polars = '-dextension='.getenv('PHPOLARS_EXTENSION');
$phit = '-dextension='.getenv('PHIT_EXTENSION');
$autoload = '-dauto_prepend_file='.__DIR__.'/vendor/autoload.php';
$threads = (int) (getenv('PHPOLARS_THREADS') ?: 2);
// Each implementation: its command and the environment it runs in.
$scripts = [
    'phpolars' => [[$php, '-dmemory_limit=-1', $polars, "-dphp_polars.max_threads={$threads}", __DIR__.'/phpstats.php', $csv], []],
    'phibble (phit reader)' => [[$php, '-dmemory_limit=-1', $autoload, $phit, __DIR__.'/phibble.php', $csv], []],
    'phibble (PHP reader)' => [[$php, '-dmemory_limit=-1', $autoload, __DIR__.'/phibble.php', $csv], ['PHIBBLE_NATIVE_CSV' => '0']],
    'plain PHP' => [[$php, '-dmemory_limit=-1', __DIR__.'/plain.php', $csv], []],
    'tibble (R)' => [['Rscript', __DIR__.'/tibble_pipeline.R', $csv], []],
    'pandas (Python)' => [[getenv('PYTHON') ?: 'python3', __DIR__.'/pandas_pipeline.py', $csv], []],
];
// On macOS each run goes through /usr/bin/time -l, which reports the
// process's peak memory footprint: its own memory, without the clean file
// pages the CSV readers map, which the system can drop at any time.
$darwin = PHP_OS_FAMILY === 'Darwin';
$once = function (array $spec) use ($darwin): array {
    [$cmd, $env] = $spec;
    $p = proc_open($darwin ? ['/usr/bin/time', '-l', ...$cmd] : $cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null, $env + getenv());
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    if (proc_close($p) !== 0) { throw new RuntimeException($err); }
    $run = json_decode($out, true, flags: JSON_THROW_ON_ERROR);
    $run['footprint'] = preg_match('/(\d+)\s+peak memory footprint/', $err, $m) ? (int) $m[1] : null;
    // R can't report its own peak RSS, so it comes from /usr/bin/time like the footprint.
    if (($run['maxrss'] ?? null) === null && preg_match('/(\d+)\s+maximum resident set size/', $err, $m)) {
        $run['maxrss'] = (int) $m[1];
    }
    return $run;
};
$runs = [];
foreach ($scripts as $name => $cmd) { $last[$name] = $once($cmd); }
for ($i = 0; $i < (int) $reps; $i++) {
    foreach ($scripts as $name => $cmd) { $runs[$name][] = $last[$name] = $once($cmd); }
}
// Every implementation agrees with plain PHP: same groups and counts exactly, sums and means to 1e-9 relative.
$b = $last['plain PHP']['rows'];
$worst = 0.0;
foreach ($last as $name => $run) {
    $a = $run['rows'];
    if (count($a) !== count($b)) { throw new RuntimeException("$name: row counts differ"); }
    foreach ($a as $i => $row) {
        foreach ($row as $j => $v) {
            $w = $b[$i][$j];
            if (is_float($v) || is_float($w)) {
                $worst = max($worst, abs($v - $w) / max(abs($w), 1e-300));
            } elseif ($v !== $w) { throw new RuntimeException("$name: row $i column $j differs"); }
        }
    }
}
if (($last['phibble (phit reader)']['reader'] ?? null) !== 'phit' || ($last['phibble (PHP reader)']['reader'] ?? null) !== 'php') {
    throw new RuntimeException('a phibble run used the other CSV reader');
}
if ($worst > 1e-9) { throw new RuntimeException("sums differ by $worst"); }
$median = function (array $xs): float { sort($xs); $n = count($xs); return $n % 2 ? $xs[intdiv($n, 2)] : ($xs[$n / 2 - 1] + $xs[$n / 2]) / 2; };
$case = 'Filter, group, and summarize a '.round(filesize($csv) / 1e9, 1).' GB CSV file';
$rows = [];
foreach ($runs as $name => $rs) {
    $ms = array_map(fn ($r) => round($r['ms'], 1), $rs);
    $rss = array_map(fn ($r) => round($r['maxrss'] / 2 ** 20, 1), $rs);
    $footprint = array_map(fn ($r) => $r['footprint'] === null ? null : round($r['footprint'] / 2 ** 20, 1), $rs);
    $memory = in_array(null, $footprint, true) ? $rss : $footprint;
    $rows[] = ['case' => $case, 'implementation' => $name, 'value' => round($median($ms), 1), 'reps' => $ms,
        'peak_memory_mib' => round($median($memory), 1), 'reps_peak_memory_mib' => $memory,
        'peak_rss_mib' => round($median($rss), 1), 'reps_peak_rss_mib' => $rss];
}
// The code a record shows: each script's imports and the lines between
// starting and stopping its timer, which is exactly the work that is timed.
$timed = function (string $file): string {
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    $imports = array_filter($lines, fn (string $l) => preg_match('/^(use |import |from \S+ import |\s*library\()/', $l));
    $start = array_key_first(array_filter($lines, fn (string $l) => preg_match('/^\$?start\s*(=|<-)/', $l)));
    $stop = array_key_first(array_filter($lines, fn (string $l) => preg_match('/^(\$ms|ms|rows)\s*(=|<-)/', $l)));
    $body = array_slice($lines, $start + 1, $stop - $start - 1);
    // Imports that only serve the measurement are left out.
    $imports = array_filter(array_map(fn (string $l) => trim($l), $imports), fn (string $l) => ! preg_match('/^(import (json|resource|sys|time)|library\(jsonlite\))$/', $l));

    return ltrim(trim(implode("\n", $imports))."\n\n".trim(implode("\n", $body)))."\n";
};
$sh = fn (string $cmd) => trim((string) shell_exec($cmd.' 2>/dev/null'));
echo json_encode([
    'title' => 'A '.round(filesize($csv) / 1e9, 1).' GB CSV file: phpolars, phibble, plain PHP, tibble, and pandas',
    'description' => 'Each script reads every row of a seeded order-events CSV, keeps the completed orders, and computes orders, units, revenue, and the mean amount for each region and month. phpolars scans the file lazily and runs the query with the streaming engine. phibble reads the file in chunks of 100,000 rows with fromCsvChunked(), filters, groups, and summarizes each chunk, and adds the chunk totals together; with the phit extension loaded, the chunks are parsed by Phit\\CsvReader (phibble (phit reader)), and with PHIBBLE_NATIVE_CSV=0 by phibble\'s PHP reader (phibble (PHP reader)). Plain PHP reads one row at a time with fgetcsv and adds each row to the totals of its group. tibble (R) reads the file with readr and summarizes it with dplyr, and pandas reads it with read_csv and groupby; both read the whole file, as is usual for them. Each run is a fresh process. Time is measured inside the script, from opening the file to holding the result as PHP values. Memory is the process\'s peak memory footprint as macOS reports it (/usr/bin/time -l): its own allocations, including Polars\' native ones, without the clean pages of the file that a CSV reader maps, which the system can drop at any time. peak_rss_mib gives the peak resident set size, which counts those pages. The file was in the operating system\'s page cache in every run. The value is the median of '.$reps.' runs after one warm-up run of each.',
    'measure' => 'Elapsed time',
    'unit' => 'ms',
    'statistic' => 'median',
    'memory_measure' => $darwin ? 'peak memory footprint' : 'peak resident set size',
    'environment' => [
        'cpu' => $darwin ? $sh('sysctl -n machdep.cpu.brand_string') : $sh("lscpu | sed -n 's/^Model name: *//p'"),
        'cores' => (int) ($darwin ? $sh('sysctl -n hw.ncpu') : $sh('nproc')),
        'memory_gb' => round((int) ($darwin ? $sh('sysctl -n hw.memsize') : 1024 * (int) $sh("awk '/MemTotal/ {print $2}' /proc/meminfo")) / 2 ** 30),
        'os' => $darwin ? 'macOS '.$sh('sw_vers -productVersion') : $sh('uname -sr'),
        'php' => PHP_VERSION,
        'phpolars' => phpversion('php_polars'),
        'phibble' => \Composer\InstalledVersions::getPrettyVersion('phpstats/phibble'),
        'phit' => trim((string) shell_exec(escapeshellarg($php).' '.escapeshellarg($phit).' -r "echo phpversion(\'phit\');"')),
        'R' => $sh("Rscript -e 'cat(R.version\$major, R.version\$minor, sep=\".\")'"),
        'readr' => $sh("Rscript -e 'cat(as.character(packageVersion(\"readr\")))'"),
        'dplyr' => $sh("Rscript -e 'cat(as.character(packageVersion(\"dplyr\")))'"),
        'pandas' => $sh(escapeshellarg(getenv('PYTHON') ?: 'python3').' -c "import pandas; print(pandas.__version__)"'),
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
    'check' => ['groups' => count($b), 'max_rel_difference' => $worst],
    'code' => [
        'phpolars' => $timed(__DIR__.'/phpstats.php'),
        'phibble' => $timed(__DIR__.'/phibble.php'),
        'plain PHP' => $timed(__DIR__.'/plain.php'),
        'tibble (R)' => $timed(__DIR__.'/tibble_pipeline.R'),
        'pandas (Python)' => $timed(__DIR__.'/pandas_pipeline.py'),
    ],
    'languages' => ['phpolars' => 'php', 'phibble' => 'php', 'plain PHP' => 'php', 'tibble (R)' => 'r', 'pandas (Python)' => 'python'],
    'rows' => $rows,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n";
