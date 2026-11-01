## `DataFrame` {#dataframe}

### Create and convert {#dataframe--create-and-convert}

- []{#dataframe-__toString} `__toString(): string`
- []{#dataframe-clear} `clear(?int $n = null): Phpolars\DataFrame`
- []{#dataframe-clone} `clone(): Phpolars\DataFrame`
- []{#dataframe-fromDict} `static fromDict(array $data, ?array $schema = null): Phpolars\DataFrame`
- []{#dataframe-fromDicts} `static fromDicts(array $data, ?array $schema = null): Phpolars\DataFrame`
- []{#dataframe-fromPhibble} `static fromPhibble(mixed $table): Phpolars\DataFrame`
- []{#dataframe-fromRows} `static fromRows(array $data, ?array $schema = null): Phpolars\DataFrame`
- []{#dataframe-getColumn} `getColumn(string $name): Phpolars\Series`
- []{#dataframe-getColumnIndex} `getColumnIndex(string $name): int`
- []{#dataframe-getColumns} `getColumns(): array`
- []{#dataframe-item} `item(?int $row = null, mixed $column = null): mixed`
- []{#dataframe-iterColumns} `iterColumns(): array`
- []{#dataframe-iterRows} `iterRows(?bool $named = null): array`
- []{#dataframe-iterSlices} `iterSlices(?int $nRows = null): array`
- []{#dataframe-lazy} `lazy(): Phpolars\LazyFrame`: a `LazyFrame` over the data, for an optimized query
- []{#dataframe-mapRows} `mapRows(mixed $function): Phpolars\DataFrame`: runs a PHP callable for each row, on the PHP thread
- []{#dataframe-row} `row(int $index, ?bool $named = null): mixed`
- []{#dataframe-rows} `rows(?bool $named = null): array`: builds PHP arrays, which count against `memory_limit`
- []{#dataframe-rowsByKey} `rowsByKey(string $key, ?bool $named = null, ?bool $includeKey = null, ?bool $unique = null): array`
- []{#dataframe-toColumns} `toColumns(): array`
- []{#dataframe-toDict} `toDict(?bool $asSeries = null): array`
- []{#dataframe-toDicts} `toDicts(): array`: builds PHP arrays, which count against `memory_limit`
- []{#dataframe-toDummies} `toDummies(mixed $columns = null, ?string $separator = null, ?bool $dropFirst = null, ?bool $dropNulls = null): Phpolars\DataFrame`
- []{#dataframe-toPhibble} `toPhibble(): mixed`
- []{#dataframe-toSeries} `toSeries(?int $index = null): Phpolars\Series`
- []{#dataframe-toStruct} `toStruct(?string $name = null): Phpolars\Series`

### Inspect {#dataframe--inspect}

- []{#dataframe-collectSchema} `collectSchema(): array`
- []{#dataframe-columns} `columns(): array`
- []{#dataframe-describe} `describe(?array $percentiles = null): Phpolars\DataFrame`
- []{#dataframe-dtypes} `dtypes(): array`
- []{#dataframe-equals} `equals(Phpolars\DataFrame $other, ?bool $nullEqual = null): bool`
- []{#dataframe-estimatedSize} `estimatedSize(): int`
- []{#dataframe-glimpse} `glimpse(?int $maxItemsPerColumn = null): string`
- []{#dataframe-height} `height(): int`
- []{#dataframe-isEmpty} `isEmpty(): bool`
- []{#dataframe-nChunks} `nChunks(): int`
- []{#dataframe-rechunk} `rechunk(): Phpolars\DataFrame`
- []{#dataframe-schema} `schema(): array`
- []{#dataframe-shape} `shape(): array`
- []{#dataframe-shrinkToFit} `shrinkToFit(): Phpolars\DataFrame`
- []{#dataframe-width} `width(): int`

### Select and add columns {#dataframe--select-and-add-columns}

- []{#dataframe-cast} `cast(mixed $dtypes, ?bool $strict = null): Phpolars\DataFrame`
- []{#dataframe-drop} `drop(mixed ...$columns): Phpolars\DataFrame`
- []{#dataframe-hstack} `hstack(mixed $columns): Phpolars\DataFrame`
- []{#dataframe-insertColumn} `insertColumn(int $index, Phpolars\Series $column): Phpolars\DataFrame`
- []{#dataframe-rename} `rename(array $mapping, ?bool $strict = null): Phpolars\DataFrame`
- []{#dataframe-replaceColumn} `replaceColumn(int $index, Phpolars\Series $column): Phpolars\DataFrame`
- []{#dataframe-select} `select(mixed ...$exprs): Phpolars\DataFrame`: keeps only the given columns and expressions; strings name columns
- []{#dataframe-selectSeq} `selectSeq(mixed ...$exprs): Phpolars\DataFrame`
- []{#dataframe-withColumns} `withColumns(mixed ...$exprs): Phpolars\DataFrame`: adds or replaces columns and keeps the others
- []{#dataframe-withColumnsSeq} `withColumnsSeq(mixed ...$exprs): Phpolars\DataFrame`
- []{#dataframe-withRowIndex} `withRowIndex(?string $name = null, ?int $offset = null): Phpolars\DataFrame`

### Filter, sort, and slice rows {#dataframe--filter-sort-and-slice-rows}

- []{#dataframe-bottomK} `bottomK(int $k, mixed $by, mixed $reverse = null): Phpolars\DataFrame`
- []{#dataframe-filter} `filter(mixed ...$predicates): Phpolars\DataFrame`: keeps rows where every condition is true; a null condition drops the row
- []{#dataframe-first} `first(): Phpolars\DataFrame`
- []{#dataframe-gather} `gather(mixed $indices): Phpolars\DataFrame`
- []{#dataframe-gatherEvery} `gatherEvery(int $n, ?int $offset = null): Phpolars\DataFrame`
- []{#dataframe-head} `head(?int $n = null): Phpolars\DataFrame`: the first `n` rows, 5 by default
- []{#dataframe-isDuplicated} `isDuplicated(): Phpolars\Series`
- []{#dataframe-isUnique} `isUnique(): Phpolars\Series`
- []{#dataframe-last} `last(): Phpolars\DataFrame`
- []{#dataframe-limit} `limit(?int $n = null): Phpolars\DataFrame`
- []{#dataframe-remove} `remove(mixed ...$predicates): Phpolars\DataFrame`
- []{#dataframe-reverse} `reverse(): Phpolars\DataFrame`
- []{#dataframe-sample} `sample(?int $n = null, ?float $fraction = null, ?bool $withReplacement = null, ?bool $shuffle = null, ?int $seed = null): Phpolars\DataFrame`
- []{#dataframe-shift} `shift(?int $n = null, mixed $fillValue = null): Phpolars\DataFrame`
- []{#dataframe-slice} `slice(int $offset, ?int $length = null): Phpolars\DataFrame`
- []{#dataframe-sort} `sort(mixed $by, mixed $descending = null, mixed $nullsLast = null, ?bool $maintainOrder = null): Phpolars\DataFrame`: nulls sort first unless `nullsLast: true`
- []{#dataframe-tail} `tail(?int $n = null): Phpolars\DataFrame`
- []{#dataframe-topK} `topK(int $k, mixed $by, mixed $reverse = null): Phpolars\DataFrame`
- []{#dataframe-unique} `unique(mixed $subset = null, ?string $keep = null, ?bool $maintainOrder = null): Phpolars\DataFrame`: the order is not kept unless `maintainOrder: true`

### Missing values {#dataframe--missing-values}

- []{#dataframe-dropNans} `dropNans(mixed $subset = null): Phpolars\DataFrame`
- []{#dataframe-dropNulls} `dropNulls(mixed $subset = null): Phpolars\DataFrame`
- []{#dataframe-fillNan} `fillNan(mixed $value): Phpolars\DataFrame`
- []{#dataframe-fillNull} `fillNull(mixed $value = null, ?string $strategy = null, ?int $limit = null): Phpolars\DataFrame`
- []{#dataframe-interpolate} `interpolate(): Phpolars\DataFrame`
- []{#dataframe-nullCount} `nullCount(): Phpolars\DataFrame`

### Group and aggregate {#dataframe--group-and-aggregate}

- []{#dataframe-approxNUnique} `approxNUnique(): Phpolars\DataFrame`
- []{#dataframe-corr} `corr(): Phpolars\DataFrame`
- []{#dataframe-count} `count(): Phpolars\DataFrame`: counts non-null values in each column
- []{#dataframe-groupBy} `groupBy(mixed $by, ?bool $maintainOrder = null): Phpolars\GroupBy`: the order of the groups is not kept unless `maintainOrder: true`
- []{#dataframe-groupByDynamic} `groupByDynamic(mixed $indexColumn, string $every, ?string $period = null, ?string $offset = null, ?bool $includeBoundaries = null, ?string $closed = null, ?string $label = null, mixed $groupBy = null, ?string $startBy = null): Phpolars\GroupBy`
- []{#dataframe-max} `max(): Phpolars\DataFrame`
- []{#dataframe-maxHorizontal} `maxHorizontal(): Phpolars\Series`
- []{#dataframe-mean} `mean(): Phpolars\DataFrame`
- []{#dataframe-meanHorizontal} `meanHorizontal(?bool $ignoreNulls = null): Phpolars\Series`
- []{#dataframe-median} `median(): Phpolars\DataFrame`
- []{#dataframe-min} `min(): Phpolars\DataFrame`
- []{#dataframe-minHorizontal} `minHorizontal(): Phpolars\Series`
- []{#dataframe-nUnique} `nUnique(): Phpolars\DataFrame`
- []{#dataframe-product} `product(): Phpolars\DataFrame`
- []{#dataframe-quantile} `quantile(float $quantile, ?string $interpolation = null): Phpolars\DataFrame`
- []{#dataframe-rolling} `rolling(mixed $indexColumn, string $period, ?string $offset = null, ?string $closed = null, mixed $groupBy = null): Phpolars\GroupBy`
- []{#dataframe-std} `std(?int $ddof = null): Phpolars\DataFrame`
- []{#dataframe-sum} `sum(): Phpolars\DataFrame`
- []{#dataframe-sumHorizontal} `sumHorizontal(?bool $ignoreNulls = null): Phpolars\Series`
- []{#dataframe-upsample} `upsample(string $timeColumn, string $every, mixed $groupBy = null, ?bool $maintainOrder = null): Phpolars\DataFrame`
- []{#dataframe-var} `var(?int $ddof = null): Phpolars\DataFrame`

### Join and combine {#dataframe--join-and-combine}

- []{#dataframe-extend} `extend(Phpolars\DataFrame $other): Phpolars\DataFrame`
- []{#dataframe-join} `join(mixed $other, mixed $on = null, ?string $how = null, mixed $leftOn = null, mixed $rightOn = null, ?string $suffix = null, ?bool $coalesce = null, ?string $maintainOrder = null, ?bool $nullsEqual = null): Phpolars\DataFrame`: null keys match nothing unless `nullsEqual: true`
- []{#dataframe-joinAsof} `joinAsof(mixed $other, mixed $on = null, mixed $leftOn = null, mixed $rightOn = null, mixed $by = null, mixed $byLeft = null, mixed $byRight = null, ?string $strategy = null, ?string $suffix = null, mixed $tolerance = null, ?bool $allowExactMatches = null, ?bool $checkSortedness = null, ?bool $coalesce = null): Phpolars\DataFrame`
- []{#dataframe-joinWhere} `joinWhere(mixed $other, mixed ...$predicates): Phpolars\DataFrame`
- []{#dataframe-mergeSorted} `mergeSorted(mixed $other, mixed $key, ?bool $maintainOrder = null): Phpolars\DataFrame`
- []{#dataframe-update} `update(mixed $other, mixed $on = null, ?string $how = null, mixed $leftOn = null, mixed $rightOn = null, ?bool $includeNulls = null, ?string $maintainOrder = null): Phpolars\DataFrame`
- []{#dataframe-vstack} `vstack(Phpolars\DataFrame $other): Phpolars\DataFrame`

### Reshape {#dataframe--reshape}

- []{#dataframe-explode} `explode(mixed ...$columns): Phpolars\DataFrame`
- []{#dataframe-melt} `melt(mixed $idVars = null, mixed $valueVars = null, ?string $variableName = null, ?string $valueName = null): Phpolars\DataFrame`
- []{#dataframe-partitionBy} `partitionBy(mixed $by, ?bool $maintainOrder = null, ?bool $includeKey = null, ?bool $asDict = null): array`
- []{#dataframe-pivot} `pivot(mixed $on, mixed $index = null, mixed $values = null, mixed $aggregateFunction = null, ?bool $sortColumns = null, ?string $separator = null, ?bool $maintainOrder = null): Phpolars\DataFrame`
- []{#dataframe-transpose} `transpose(?bool $includeHeader = null, ?string $headerName = null, mixed $columnNames = null): Phpolars\DataFrame`
- []{#dataframe-unnest} `unnest(mixed ...$columns): Phpolars\DataFrame`
- []{#dataframe-unpivot} `unpivot(mixed $on = null, mixed $index = null, ?string $variableName = null, ?string $valueName = null): Phpolars\DataFrame`

### SQL {#dataframe--sql}

- []{#dataframe-sql} `sql(string $query, ?string $tableName = null): Phpolars\DataFrame`

### Write {#dataframe--write}

- []{#dataframe-writeCsv} `writeCsv(string $file, ?bool $includeHeader = null, ?string $separator = null, ?string $nullValue = null, ?string $quoteChar = null, ?string $dateFormat = null, ?string $datetimeFormat = null, ?string $timeFormat = null, ?bool $floatScientific = null, ?int $floatPrecision = null, ?bool $decimalComma = null, ?string $lineTerminator = null, ?string $quoteStyle = null, ?bool $includeBom = null): void`: writes the whole data frame; `LazyFrame::sinkCsv()` streams a query result instead
- []{#dataframe-writeDatabase} `writeDatabase(string $tableName, mixed $connection, ?string $ifTableExists = null): int`
- []{#dataframe-writeIpc} `writeIpc(string $file): void`
- []{#dataframe-writeIpcStream} `writeIpcStream(string $file): void`
- []{#dataframe-writeJson} `writeJson(string $file): void`
- []{#dataframe-writeNdjson} `writeNdjson(string $file): void`
- []{#dataframe-writeParquet} `writeParquet(string $file, ?string $compression = null, ?int $compressionLevel = null, ?bool $statistics = null, ?int $rowGroupSize = null): void`

## `LazyFrame` {#lazyframe}

### Create and convert {#lazyframe--create-and-convert}

- []{#lazyframe-__toString} `__toString(): string`
- []{#lazyframe-cache} `cache(): Phpolars\LazyFrame`
- []{#lazyframe-clear} `clear(?int $n = null): Phpolars\LazyFrame`
- []{#lazyframe-clone} `clone(): Phpolars\LazyFrame`
- []{#lazyframe-collect} `collect(?string $engine = null): Phpolars\DataFrame`: `engine: 'streaming'` runs the query in batches
- []{#lazyframe-fetch} `fetch(?int $n = null): Phpolars\DataFrame`
- []{#lazyframe-lazy} `lazy(): Phpolars\LazyFrame`
- []{#lazyframe-profile} `profile(): array`

### Inspect {#lazyframe--inspect}

- []{#lazyframe-collectSchema} `collectSchema(): array`
- []{#lazyframe-columns} `columns(): array`
- []{#lazyframe-describe} `describe(?array $percentiles = null): Phpolars\DataFrame`
- []{#lazyframe-dtypes} `dtypes(): array`
- []{#lazyframe-explain} `explain(?bool $optimized = null): string`: the optimized query plan as text; `optimized: false` shows the plan as written
- []{#lazyframe-schema} `schema(): array`
- []{#lazyframe-width} `width(): int`

### Select and add columns {#lazyframe--select-and-add-columns}

- []{#lazyframe-cast} `cast(mixed $dtypes, ?bool $strict = null): Phpolars\LazyFrame`
- []{#lazyframe-drop} `drop(mixed ...$columns): Phpolars\LazyFrame`
- []{#lazyframe-rename} `rename(array $mapping, ?bool $strict = null): Phpolars\LazyFrame`
- []{#lazyframe-select} `select(mixed ...$exprs): Phpolars\LazyFrame`: keeps only the given columns and expressions; strings name columns
- []{#lazyframe-selectSeq} `selectSeq(mixed ...$exprs): Phpolars\LazyFrame`
- []{#lazyframe-withColumns} `withColumns(mixed ...$exprs): Phpolars\LazyFrame`: adds or replaces columns and keeps the others
- []{#lazyframe-withColumnsSeq} `withColumnsSeq(mixed ...$exprs): Phpolars\LazyFrame`
- []{#lazyframe-withRowIndex} `withRowIndex(?string $name = null, ?int $offset = null): Phpolars\LazyFrame`

### Filter, sort, and slice rows {#lazyframe--filter-sort-and-slice-rows}

- []{#lazyframe-bottomK} `bottomK(int $k, mixed $by, mixed $reverse = null): Phpolars\LazyFrame`
- []{#lazyframe-filter} `filter(mixed ...$predicates): Phpolars\LazyFrame`: keeps rows where every condition is true; a null condition drops the row
- []{#lazyframe-first} `first(): Phpolars\LazyFrame`
- []{#lazyframe-gather} `gather(mixed $indices): Phpolars\LazyFrame`
- []{#lazyframe-gatherEvery} `gatherEvery(int $n, ?int $offset = null): Phpolars\LazyFrame`
- []{#lazyframe-head} `head(?int $n = null): Phpolars\LazyFrame`
- []{#lazyframe-last} `last(): Phpolars\LazyFrame`
- []{#lazyframe-limit} `limit(?int $n = null): Phpolars\LazyFrame`
- []{#lazyframe-remove} `remove(mixed ...$predicates): Phpolars\LazyFrame`
- []{#lazyframe-reverse} `reverse(): Phpolars\LazyFrame`
- []{#lazyframe-shift} `shift(?int $n = null, mixed $fillValue = null): Phpolars\LazyFrame`
- []{#lazyframe-slice} `slice(int $offset, ?int $length = null): Phpolars\LazyFrame`
- []{#lazyframe-sort} `sort(mixed $by, mixed $descending = null, mixed $nullsLast = null, ?bool $maintainOrder = null): Phpolars\LazyFrame`: nulls sort first unless `nullsLast: true`
- []{#lazyframe-tail} `tail(?int $n = null): Phpolars\LazyFrame`
- []{#lazyframe-topK} `topK(int $k, mixed $by, mixed $reverse = null): Phpolars\LazyFrame`
- []{#lazyframe-unique} `unique(mixed $subset = null, ?string $keep = null, ?bool $maintainOrder = null): Phpolars\LazyFrame`: the order is not kept unless `maintainOrder: true`

### Missing values {#lazyframe--missing-values}

- []{#lazyframe-dropNans} `dropNans(mixed $subset = null): Phpolars\LazyFrame`
- []{#lazyframe-dropNulls} `dropNulls(mixed $subset = null): Phpolars\LazyFrame`
- []{#lazyframe-fillNan} `fillNan(mixed $value): Phpolars\LazyFrame`
- []{#lazyframe-fillNull} `fillNull(mixed $value = null, ?string $strategy = null, ?int $limit = null): Phpolars\LazyFrame`
- []{#lazyframe-interpolate} `interpolate(): Phpolars\LazyFrame`
- []{#lazyframe-nullCount} `nullCount(): Phpolars\LazyFrame`

### Group and aggregate {#lazyframe--group-and-aggregate}

- []{#lazyframe-approxNUnique} `approxNUnique(): Phpolars\LazyFrame`
- []{#lazyframe-count} `count(): Phpolars\LazyFrame`
- []{#lazyframe-groupBy} `groupBy(mixed $by, ?bool $maintainOrder = null): Phpolars\LazyGroupBy`: the order of the groups is not kept unless `maintainOrder: true`
- []{#lazyframe-groupByDynamic} `groupByDynamic(mixed $indexColumn, string $every, ?string $period = null, ?string $offset = null, ?bool $includeBoundaries = null, ?string $closed = null, ?string $label = null, mixed $groupBy = null, ?string $startBy = null): Phpolars\LazyGroupBy`
- []{#lazyframe-max} `max(): Phpolars\LazyFrame`
- []{#lazyframe-mean} `mean(): Phpolars\LazyFrame`
- []{#lazyframe-median} `median(): Phpolars\LazyFrame`
- []{#lazyframe-min} `min(): Phpolars\LazyFrame`
- []{#lazyframe-nUnique} `nUnique(): Phpolars\LazyFrame`
- []{#lazyframe-product} `product(): Phpolars\LazyFrame`
- []{#lazyframe-quantile} `quantile(float $quantile, ?string $interpolation = null): Phpolars\LazyFrame`
- []{#lazyframe-rolling} `rolling(mixed $indexColumn, string $period, ?string $offset = null, ?string $closed = null, mixed $groupBy = null): Phpolars\LazyGroupBy`
- []{#lazyframe-std} `std(?int $ddof = null): Phpolars\LazyFrame`
- []{#lazyframe-sum} `sum(): Phpolars\LazyFrame`
- []{#lazyframe-var} `var(?int $ddof = null): Phpolars\LazyFrame`

### Join and combine {#lazyframe--join-and-combine}

- []{#lazyframe-join} `join(mixed $other, mixed $on = null, ?string $how = null, mixed $leftOn = null, mixed $rightOn = null, ?string $suffix = null, ?bool $coalesce = null, ?string $maintainOrder = null, ?bool $nullsEqual = null): Phpolars\LazyFrame`: null keys match nothing unless `nullsEqual: true`
- []{#lazyframe-joinAsof} `joinAsof(mixed $other, mixed $on = null, mixed $leftOn = null, mixed $rightOn = null, mixed $by = null, mixed $byLeft = null, mixed $byRight = null, ?string $strategy = null, ?string $suffix = null, mixed $tolerance = null, ?bool $allowExactMatches = null, ?bool $checkSortedness = null, ?bool $coalesce = null): Phpolars\LazyFrame`
- []{#lazyframe-joinWhere} `joinWhere(mixed $other, mixed ...$predicates): Phpolars\LazyFrame`
- []{#lazyframe-mergeSorted} `mergeSorted(mixed $other, mixed $key, ?bool $maintainOrder = null): Phpolars\LazyFrame`
- []{#lazyframe-update} `update(mixed $other, mixed $on = null, ?string $how = null, mixed $leftOn = null, mixed $rightOn = null, ?bool $includeNulls = null, ?string $maintainOrder = null): Phpolars\LazyFrame`

### Reshape {#lazyframe--reshape}

- []{#lazyframe-explode} `explode(mixed ...$columns): Phpolars\LazyFrame`
- []{#lazyframe-unnest} `unnest(mixed ...$columns): Phpolars\LazyFrame`
- []{#lazyframe-unpivot} `unpivot(mixed $on = null, mixed $index = null, ?string $variableName = null, ?string $valueName = null): Phpolars\LazyFrame`

### SQL {#lazyframe--sql}

- []{#lazyframe-sql} `sql(string $query, ?string $tableName = null): Phpolars\LazyFrame`

### Write {#lazyframe--write}

- []{#lazyframe-sinkCsv} `sinkCsv(string $file, ?bool $includeHeader = null, ?string $separator = null, ?string $nullValue = null, ?string $quoteChar = null, ?string $dateFormat = null, ?string $datetimeFormat = null, ?string $timeFormat = null, ?bool $floatScientific = null, ?int $floatPrecision = null, ?bool $decimalComma = null, ?string $lineTerminator = null, ?string $quoteStyle = null, ?bool $includeBom = null): void`
- []{#lazyframe-sinkIpc} `sinkIpc(string $file): void`
- []{#lazyframe-sinkNdjson} `sinkNdjson(string $file): void`
- []{#lazyframe-sinkParquet} `sinkParquet(string $file, ?string $compression = null, ?int $compressionLevel = null, ?bool $statistics = null, ?int $rowGroupSize = null): void`

## `GroupBy` {#groupby}

- []{#groupby-agg} `agg(mixed ...$aggs): Phpolars\DataFrame`: one row per group: the key columns, then one column per expression; an expression that keeps several values per group gives a list
- []{#groupby-all} `all(): Phpolars\DataFrame`
- []{#groupby-count} `count(): Phpolars\DataFrame`
- []{#groupby-first} `first(): Phpolars\DataFrame`
- []{#groupby-getIterator} `getIterator(): Phpolars\GroupIterator`
- []{#groupby-having} `having(mixed ...$predicates): Phpolars\GroupBy`
- []{#groupby-head} `head(?int $n = null): Phpolars\DataFrame`
- []{#groupby-last} `last(): Phpolars\DataFrame`
- []{#groupby-len} `len(?string $name = null): Phpolars\DataFrame`
- []{#groupby-max} `max(): Phpolars\DataFrame`
- []{#groupby-mean} `mean(): Phpolars\DataFrame`
- []{#groupby-median} `median(): Phpolars\DataFrame`
- []{#groupby-min} `min(): Phpolars\DataFrame`
- []{#groupby-nUnique} `nUnique(): Phpolars\DataFrame`
- []{#groupby-quantile} `quantile(float $quantile, ?string $interpolation = null): Phpolars\DataFrame`
- []{#groupby-sum} `sum(): Phpolars\DataFrame`
- []{#groupby-tail} `tail(?int $n = null): Phpolars\DataFrame`

## `LazyGroupBy` {#lazygroupby}

- []{#lazygroupby-agg} `agg(mixed ...$aggs): Phpolars\LazyFrame`: one row per group: the key columns, then one column per expression; an expression that keeps several values per group gives a list
- []{#lazygroupby-all} `all(): Phpolars\LazyFrame`
- []{#lazygroupby-count} `count(): Phpolars\LazyFrame`
- []{#lazygroupby-first} `first(): Phpolars\LazyFrame`
- []{#lazygroupby-getIterator} `getIterator(): Phpolars\GroupIterator`
- []{#lazygroupby-having} `having(mixed ...$predicates): Phpolars\LazyGroupBy`
- []{#lazygroupby-head} `head(?int $n = null): Phpolars\LazyFrame`
- []{#lazygroupby-last} `last(): Phpolars\LazyFrame`
- []{#lazygroupby-len} `len(?string $name = null): Phpolars\LazyFrame`
- []{#lazygroupby-max} `max(): Phpolars\LazyFrame`
- []{#lazygroupby-mean} `mean(): Phpolars\LazyFrame`
- []{#lazygroupby-median} `median(): Phpolars\LazyFrame`
- []{#lazygroupby-min} `min(): Phpolars\LazyFrame`
- []{#lazygroupby-nUnique} `nUnique(): Phpolars\LazyFrame`
- []{#lazygroupby-quantile} `quantile(float $quantile, ?string $interpolation = null): Phpolars\LazyFrame`
- []{#lazygroupby-sum} `sum(): Phpolars\LazyFrame`
- []{#lazygroupby-tail} `tail(?int $n = null): Phpolars\LazyFrame`

## `GroupIterator` {#groupiterator}

- []{#groupiterator-current} `current(): ?Phpolars\DataFrame`
- []{#groupiterator-key} `key(): mixed`
- []{#groupiterator-next} `next(): void`
- []{#groupiterator-rewind} `rewind(): void`
- []{#groupiterator-valid} `valid(): bool`

## `SQLContext` {#sqlcontext}

- []{#sqlcontext-execute} `execute(string $query, ?bool $eager = null): mixed`
- []{#sqlcontext-register} `register(string $name, mixed $dataFrame): void`
- []{#sqlcontext-registerMany} `registerMany(array $dataFrames): void`
- []{#sqlcontext-tables} `tables(): array`
- []{#sqlcontext-unregister} `unregister(mixed $names): void`

