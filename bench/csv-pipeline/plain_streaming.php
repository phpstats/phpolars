<?php

$path = $argv[1];
$start = hrtime(true);

$in = fopen($path, 'r');
$header = fgetcsv($in, escape: '');
$groups = [];
while (($row = fgetcsv($in, escape: '')) !== false) {
    [, $placedOn, $region, , $status, $quantity, $amount] = $row;
    if ($status !== 'completed') {
        continue;
    }
    $key = $region."\0".substr($placedOn, 0, 7);
    $g = $groups[$key] ?? ['orders' => 0, 'units' => 0, 'revenue' => 0.0, 'amounts' => 0];
    $g['orders']++;
    $g['units'] += (int) $quantity;
    if ($amount !== '') {
        $g['revenue'] += (float) $amount;
        $g['amounts']++;
    }
    $groups[$key] = $g;
}
fclose($in);
ksort($groups, SORT_STRING);
$rows = [];
foreach ($groups as $key => $g) {
    [$region, $month] = explode("\0", $key);
    $rows[] = [$region, $month, $g['orders'], $g['units'], $g['revenue'], $g['amounts'] > 0 ? $g['revenue'] / $g['amounts'] : null];
}

$ms = (hrtime(true) - $start) / 1e6;
echo json_encode(['ms' => $ms, 'maxrss' => getrusage()['ru_maxrss'], 'rows' => $rows]), "\n";
