<?php
// php compare.php A.json B.json: the two results agree row by row (counts exactly, sums to 1e-9 relative).
[$a, $b] = array_map(fn ($f) => json_decode(file_get_contents($f), true)['rows'], array_slice($argv, 1, 2));
if (count($a) !== count($b)) { exit("row counts differ: ".count($a)." vs ".count($b)."\n"); }
$worst = 0.0;
foreach ($a as $i => $r) {
    foreach ($r as $j => $v) {
        $w = $b[$i][$j];
        if (is_float($v) || is_float($w)) {
            $rel = abs($v - $w) / max(abs($w), 1e-300);
            $worst = max($worst, $rel);
            if ($rel > 1e-9) { exit("row $i col $j: $v vs $w\n"); }
        } elseif ($v !== $w) { exit("row $i col $j: ".var_export($v, true)." vs ".var_export($w, true)."\n"); }
    }
}
printf("%d rows agree; largest relative difference %.2e\n", count($a), $worst);
