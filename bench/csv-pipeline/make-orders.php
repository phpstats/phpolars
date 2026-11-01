<?php
// php make-orders.php ROWS PATH: seeded order events.
// order_id,placed_on,region,channel,status,quantity,amount (amount empty in ~1% of rows)
[$_, $rows, $path] = $argv + [null, 1_000_000, 'orders.csv'];
mt_srand(20261101);
$regions = ['north', 'south', 'east', 'west', 'central', 'coastal', 'mountain', 'island'];
$channels = ['web', 'app', 'store', 'marketplace'];
$start = strtotime('2024-01-01 UTC');
$out = fopen($path, 'w');
fwrite($out, "order_id,placed_on,region,channel,status,quantity,amount\n");
$buffer = '';
for ($i = 1; $i <= (int) $rows; $i++) {
    $s = mt_rand(1, 100);
    $status = $s <= 90 ? 'completed' : ($s <= 96 ? 'refunded' : 'cancelled');
    $amount = mt_rand(1, 100) === 1 ? '' : sprintf('%.2f', mt_rand(500, 50000) / 100);
    $buffer .= $i.','.gmdate('Y-m-d', $start + mt_rand(0, 730) * 86400).','.$regions[mt_rand(0, 7)].','
        .$channels[mt_rand(0, 3)].','.$status.','.mt_rand(1, 9).','.$amount."\n";
    if ($i % 100_000 === 0) { fwrite($out, $buffer); $buffer = ''; }
}
fwrite($out, $buffer);
fclose($out);
