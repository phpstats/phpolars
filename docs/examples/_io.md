```php
$dir = sys_get_temp_dir().'/phpolars-docs';
@mkdir($dir);
$penguins = read_csv('penguins.csv', nullValues: 'NA');

$penguins->writeParquet("$dir/penguins.parquet");
$penguins->writeNdjson("$dir/penguins.ndjson");
$penguins->lazy()
    ->filter(col('island')->eq('Biscoe'))
    ->sinkCsv("$dir/biscoe.csv");

$back = read_parquet("$dir/penguins.parquet");
echo $back->equals($penguins)
    ? "parquet round-trip ok\n" : "parquet differs\n";
echo scan_parquet("$dir/penguins.parquet")
    ->select(col('body_mass_g')->mean()->round())
    ->collect()->item(), " g mean body mass\n";
echo read_csv("$dir/biscoe.csv")->height(),
    " penguins on Biscoe\n";
echo read_ndjson("$dir/penguins.ndjson")
    ->schema()['body_mass_g'], "\n";
```

```{.text .output}
parquet round-trip ok
4202 g mean body mass
168 penguins on Biscoe
Int64
```
