<?php
// php -d extension=... run.php CSV [REPS]: runs the CSV pipeline two ways, as
// whole-file reads and as streaming reads, with phpolars, phibble (with the
// phit reader and with its PHP reader), tibble (R), pandas (Python), and, for
// streaming, plain PHP. Each run is a fresh process: one warm-up each, then
// REPS alternating rounds. It checks that every result agrees with plain PHP
// and prints the benchmark record as JSON.
//
// Each script times its own work, from opening the file to holding the
// result. Memory is the process's peak memory footprint from /usr/bin/time -l
// on macOS, and its peak anonymous memory from peakmem.c (cc -O2 -o peakmem
// peakmem.c, on the PATH) on Linux, with the peak resident set size beside it. The file is read from
// the OS page cache: the warm-up runs load it, and no run reads it from disk.
require __DIR__.'/vendor/autoload.php';

[$_, $csv, $reps] = $argv + [null, null, '7'];
$php = PHP_BINARY;
$python = getenv('PYTHON') ?: 'python3';
$polars = '-dextension='.getenv('PHPOLARS_EXTENSION');
$phit = '-dextension='.getenv('PHIT_EXTENSION');
$autoload = '-dauto_prepend_file='.__DIR__.'/vendor/autoload.php';
$threads = (int) (getenv('PHPOLARS_THREADS') ?: 2);
$file = fn (string $name) => __DIR__.'/'.$name;

// Each case's implementations: the script, its command, and its environment.
$phpolars = fn (string $script) => [$script, [$php, '-dmemory_limit=-1', $polars, "-dphp_polars.max_threads={$threads}", $file($script), $csv], []];
$phibble = fn (string $script, bool $native) => [$script, [$php, '-dmemory_limit=-1', $autoload, ...($native ? [$phit] : []), $file($script), $csv], $native ? [] : ['PHIBBLE_NATIVE_CSV' => '0']];
$cases = [
    'Whole file' => [
        'phpolars' => $phpolars('phpolars_whole.php'),
        'phibble (phit reader)' => $phibble('phibble_whole.php', true),
        'phibble (PHP reader)' => $phibble('phibble_whole.php', false),
        'tibble (R)' => ['tibble_whole.R', ['Rscript', $file('tibble_whole.R'), $csv], []],
        'pandas (Python)' => ['pandas_whole.py', [$python, $file('pandas_whole.py'), $csv], []],
    ],
    'Streaming' => [
        'phpolars' => $phpolars('phpolars_streaming.php'),
        'phibble (phit reader)' => $phibble('phibble_streaming.php', true),
        'phibble (PHP reader)' => $phibble('phibble_streaming.php', false),
        'plain PHP' => ['plain_streaming.php', [$php, '-dmemory_limit=-1', $file('plain_streaming.php'), $csv], []],
        'tibble (R)' => ['tibble_streaming.R', ['Rscript', $file('tibble_streaming.R'), $csv], []],
        'pandas (Python)' => ['pandas_streaming.py', [$python, $file('pandas_streaming.py'), $csv], []],
    ],
];

$darwin = PHP_OS_FAMILY === 'Darwin';
$once = function (array $spec) use ($darwin): array {
    [, $cmd, $env] = $spec;
    $p = proc_open([$darwin ? '/usr/bin/time' : 'peakmem', '-l', ...$cmd], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null, $env + getenv());
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    if (proc_close($p) !== 0) { throw new RuntimeException($err); }
    $run = json_decode($out, true, flags: JSON_THROW_ON_ERROR);
    $run['footprint'] = preg_match('/(\d+)\s+peak memory footprint/', $err, $m) ? (int) $m[1] : null;
    // The peak RSS comes from /usr/bin/time or peakmem, in bytes, like the footprint. A script's own
    // getrusage() figure is bytes on macOS but KiB on Linux, and R can't report one at all.
    if (preg_match('/(\d+)\s+maximum resident set size/', $err, $m)) {
        $run['maxrss'] = (int) $m[1];
    }
    return $run;
};

$runs = [];
$last = [];
foreach ($cases as $case => $scripts) {
    foreach ($scripts as $name => $spec) { $last[$case][$name] = $once($spec); }
}
for ($i = 0; $i < (int) $reps; $i++) {
    foreach ($cases as $case => $scripts) {
        foreach ($scripts as $name => $spec) { $runs[$case][$name][] = $last[$case][$name] = $once($spec); }
    }
}

// Every result agrees with plain PHP's: the same groups and counts exactly, and sums and means to 1e-9 relative.
$b = $last['Streaming']['plain PHP']['rows'];
$worst = 0.0;
foreach ($last as $case => $byName) {
    foreach ($byName as $name => $run) {
        $a = $run['rows'];
        if (count($a) !== count($b)) { throw new RuntimeException("$case $name: row counts differ"); }
        foreach ($a as $i => $row) {
            foreach ($row as $j => $v) {
                $w = $b[$i][$j];
                if (is_float($v) || is_float($w)) {
                    $worst = max($worst, abs($v - $w) / max(abs($w), 1e-300));
                } elseif ($v !== $w) { throw new RuntimeException("$case $name: row $i column $j differs"); }
            }
        }
    }
    if (($byName['phibble (phit reader)']['reader'] ?? null) !== 'phit' || ($byName['phibble (PHP reader)']['reader'] ?? null) !== 'php') {
        throw new RuntimeException("$case: a phibble run used the other CSV reader");
    }
}
if ($worst > 1e-9) { throw new RuntimeException("sums differ by $worst"); }

$median = function (array $xs): float { sort($xs); $n = count($xs); return $n % 2 ? $xs[intdiv($n, 2)] : ($xs[$n / 2 - 1] + $xs[$n / 2]) / 2; };
$rows = [];
foreach ($runs as $case => $byName) {
    foreach ($byName as $name => $rs) {
        $ms = array_map(fn ($r) => round($r['ms'], 1), $rs);
        $rss = array_map(fn ($r) => $r['maxrss'] === null ? null : round($r['maxrss'] / 2 ** 20, 1), $rs);
        $footprint = array_map(fn ($r) => $r['footprint'] === null ? null : round($r['footprint'] / 2 ** 20, 1), $rs);
        $memory = in_array(null, $footprint, true) ? $rss : $footprint;
        $rows[] = ['case' => $case, 'implementation' => $name, 'value' => round($median($ms), 1), 'reps' => $ms,
            'peak_memory_mib' => round($median($memory), 1), 'reps_peak_memory_mib' => $memory,
            'peak_rss_mib' => in_array(null, $rss, true) ? null : round($median($rss), 1), 'reps_peak_rss_mib' => $rss];
    }
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
$code = [];
$languages = [];
foreach ($cases as $case => $scripts) {
    foreach ($scripts as $name => [$script]) {
        $label = str_starts_with($name, 'phibble') ? 'phibble' : $name;
        $code[$case][$label] = $timed($file($script));
        $languages[$label] = match (pathinfo($script, PATHINFO_EXTENSION)) { 'R' => 'r', 'py' => 'python', default => 'php' };
    }
}

$sh = fn (string $cmd) => trim((string) shell_exec($cmd.' 2>/dev/null'));
echo json_encode([
    'title' => 'A '.round(filesize($csv) / 1e9, 1).' GB CSV file, whole and streaming: phpolars, phibble, plain PHP, tibble, and pandas',
    'description' => 'Each script reads a seeded order-events CSV, keeps the completed orders, and computes orders, units, revenue, and the mean amount for each region and month. In the "Whole file" case each library loads the columns it needs into a table and then summarizes it: phpolars with read_csv(), phibble with fromCsv(), tibble with readr\'s read_csv() and dplyr, and pandas with read_csv(). In the "Streaming" case each reads the file in pieces: phpolars with scan_csv() and the streaming engine, phibble with fromCsvChunked() in chunks of 100,000 rows, plain PHP one row at a time with fgetcsv, readr with read_csv_chunked() in chunks of 100,000, and pandas with read_csv(chunksize=100000); the chunked ones add up each chunk\'s totals. phibble runs once with the phit extension\'s reader and once with its PHP reader (PHIBBLE_NATIVE_CSV=0). Threads: phpolars uses 2 (its default), readr\'s read_csv() 2 (num_threads), and the rest one. Each run is a fresh process. Time is measured inside the script, from opening the file to holding the result. '.($darwin
        ? 'Memory is the process\'s peak memory footprint as macOS reports it (/usr/bin/time -l): the most memory it held at once, including native allocations, without file pages the system can drop; peak_rss_mib gives the peak resident set size.'
        : 'Memory is the process\'s peak anonymous memory (RssAnon in /proc, sampled every millisecond by peakmem.c): the most memory it allocated at once, including native allocations, without the file pages it maps; peak_rss_mib gives the peak resident set size.').' The file was in the operating system\'s page cache in every run. Values are medians of '.$reps.' runs after one warm-up run of each.',
    'measure' => 'Elapsed time',
    'unit' => 'ms',
    'statistic' => 'median',
    'memory_measure' => $darwin ? 'peak memory footprint' : 'peak anonymous memory',
    'environment' => [
        'cpu' => $darwin ? $sh('sysctl -n machdep.cpu.brand_string') : $sh("lscpu | sed -n 's/^Model name: *//p'"),
        'cores' => (int) ($darwin ? $sh('sysctl -n hw.ncpu') : $sh('nproc')),
        'memory_gb' => round((int) ($darwin ? $sh('sysctl -n hw.memsize') : 1024 * (int) $sh("awk '/MemTotal/ {print $2}' /proc/meminfo")) / 2 ** 30),
        'os' => $darwin ? 'macOS '.$sh('sw_vers -productVersion') : 'Linux, '.$sh('. /etc/os-release && echo $PRETTY_NAME').' ('.php_uname('m').')',
        'php' => PHP_VERSION,
        'phpolars' => phpversion('php_polars'),
        'phibble' => \Composer\InstalledVersions::getPrettyVersion('phpstats/phibble'),
        'phit' => trim((string) shell_exec(escapeshellarg($php).' '.escapeshellarg($phit).' -r "echo phpversion(\'phit\');"')),
        'R' => $sh("Rscript -e 'cat(R.version\$major, R.version\$minor, sep=\".\")'"),
        'readr' => $sh("Rscript -e 'cat(as.character(packageVersion(\"readr\")))'"),
        'dplyr' => $sh("Rscript -e 'cat(as.character(packageVersion(\"dplyr\")))'"),
        'pandas' => $sh(escapeshellarg($python).' -c "import pandas; print(pandas.__version__)"'),
        'polars' => Phpolars\polars_version(),
        'threads' => ['phpolars' => $last['Streaming']['phpolars']['threads'], 'readr read_csv' => 2, 'others' => 1],
        'cache' => 'warm',
        'repeats' => (int) $reps,
        'date' => gmdate('Y-m-d'),
        'commit' => $sh('git -C '.escapeshellarg(__DIR__).' rev-parse HEAD'),
    ],
    'dataset' => ['rows' => (int) $sh('tail -n +2 '.escapeshellarg($csv).' | wc -l'), 'bytes' => filesize($csv),
        'sha256' => hash_file('sha256', $csv), 'missing_amounts' => 'about 1%'],
    'check' => ['groups' => count($b), 'max_rel_difference' => $worst],
    'code' => $code,
    'languages' => $languages,
    'rows' => $rows,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n";
