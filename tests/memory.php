<?php
// Memory of a filtered streaming scan follows its output, not its input.
// Generates a 100 MB CSV, so scripts/check runs it and scripts/test does not.
// PHPOLARS_EXT names the extension that each case loads.
//
// Each case runs in its own PHP process and reports that process's peak RSS.
// Polars maps the CSV into memory, so RSS includes the file's pages. The test
// therefore compares a filter that keeps 4% of rows with one that keeps none on
// the same file. Before phpolars compacted String columns, the kept rows held on
// to every input buffer, and the difference was about the size of the file. With
// compaction it is about 20% of the file on macOS and 30% on Linux at this size.
use function Phpolars\{col, scan_csv};

if (($argv[1] ?? '') === 'case') {
    [, , $file, $pattern] = $argv;
    $df = scan_csv($file, inferSchemaLength: 0)
        ->filter(col('id')->str()->contains($pattern))
        ->collect(engine: 'streaming');
    $rss = getrusage()['ru_maxrss'] * (PHP_OS_FAMILY === 'Darwin' ? 1 : 1024);
    echo json_encode(['rows' => $df->height(), 'rss' => $rss]);
    exit;
}

require __DIR__ . '/harness.php';

$ext = getenv('PHPOLARS_EXT') ?: throw new RuntimeException('set PHPOLARS_EXT to the extension to test');
$dir = sys_get_temp_dir() . '/phpolars-memory-' . getmypid();
@mkdir($dir);
$file = "$dir/wide.csv";
$fh = fopen($file, 'w');
fwrite($fh, 'id,' . implode(',', array_map(fn ($i) => "c$i", range(0, 19))) . "\n");
$tail = implode(',', array_map(fn ($i) => sprintf('value%02d-abcdefgh', $i), range(0, 19)));
for ($i = 0; $i < 280000; $i++) {
    fwrite($fh, "$i,$tail\n");
}
fclose($fh);
$mb = filesize($file) / 1048576;

$run = function (string $pattern) use ($ext, $file): array {
    $cmd = [PHP_BINARY, '-n', '-d', "extension=$ext", '-d', 'php_polars.max_threads=2', __FILE__, 'case', $file, $pattern];
    $out = (string) shell_exec(implode(' ', array_map('escapeshellarg', $cmd)));
    return json_decode($out, true) ?? throw new RuntimeException("case failed: $out");
};

$none = $run('^x');
$some = $run('[0-3]7$');
$extra = ($some['rss'] - $none['rss']) / 1048576;
same('the filter keeps 4% of rows', $some['rows'], 11199);
check(sprintf('a 4%% streaming filter of a %.0f MB CSV adds %.0f MB of RSS, under half the file', $mb, $extra), $extra < $mb / 2, true);

unlink($file);
rmdir($dir);
