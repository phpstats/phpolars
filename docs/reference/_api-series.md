### `Phpolars\Series` {#series}

#### Arithmetic and comparison {#series--arithmetic-and-comparison}

- []{#series-abs} `abs(): Phpolars\Series`
- []{#series-add} `add(mixed $other): Phpolars\Series`
- []{#series-div} `div(mixed $other): Phpolars\Series`
- []{#series-eq} `eq(mixed $other): Phpolars\Series`
- []{#series-floordiv} `floordiv(mixed $other): Phpolars\Series`
- []{#series-ge} `ge(mixed $other): Phpolars\Series`
- []{#series-gt} `gt(mixed $other): Phpolars\Series`
- []{#series-isBetween} `isBetween(mixed $lower, mixed $upper, ?string $closed = null): Phpolars\Series`
- []{#series-isIn} `isIn(mixed $other, ?bool $nullsEqual = null): Phpolars\Series`
- []{#series-le} `le(mixed $other): Phpolars\Series`
- []{#series-lt} `lt(mixed $other): Phpolars\Series`
- []{#series-mod} `mod(mixed $other): Phpolars\Series`
- []{#series-mul} `mul(mixed $other): Phpolars\Series`
- []{#series-ne} `ne(mixed $other): Phpolars\Series`
- []{#series-pow} `pow(mixed $exponent): Phpolars\Series`
- []{#series-sub} `sub(mixed $other): Phpolars\Series`
- []{#series-truediv} `truediv(mixed $other): Phpolars\Series`

#### Logic and bits {#series--logic-and-bits}

- []{#series-and} `and(mixed $other): Phpolars\Series`
- []{#series-not} `not(): Phpolars\Series`
- []{#series-or} `or(mixed $other): Phpolars\Series`

#### Missing values {#series--missing-values}

- []{#series-dropNulls} `dropNulls(): Phpolars\Series`
- []{#series-fillNan} `fillNan(mixed $value): Phpolars\Series`
- []{#series-fillNull} `fillNull(mixed $value = null, ?string $strategy = null, ?int $limit = null): Phpolars\Series`
- []{#series-hasNulls} `hasNulls(): bool`
- []{#series-isNan} `isNan(): Phpolars\Series`
- []{#series-isNotNull} `isNotNull(): Phpolars\Series`
- []{#series-isNull} `isNull(): Phpolars\Series`
- []{#series-nullCount} `nullCount(): int`

#### Aggregate {#series--aggregate}

- []{#series-all} `all(?bool $ignoreNulls = null): mixed`
- []{#series-any} `any(?bool $ignoreNulls = null): mixed`
- []{#series-argMax} `argMax(): mixed`
- []{#series-argMin} `argMin(): mixed`
- []{#series-count} `count(): int`: counts non-null values; `len()` counts all values
- []{#series-describe} `describe(?array $percentiles = null): Phpolars\DataFrame`
- []{#series-first} `first(): mixed`
- []{#series-isEmpty} `isEmpty(): bool`
- []{#series-item} `item(): mixed`
- []{#series-last} `last(): mixed`
- []{#series-len} `len(): int`
- []{#series-max} `max(): mixed`
- []{#series-mean} `mean(): mixed`
- []{#series-median} `median(): mixed`
- []{#series-min} `min(): mixed`
- []{#series-nUnique} `nUnique(): mixed`
- []{#series-product} `product(): mixed`
- []{#series-quantile} `quantile(float $quantile, ?string $interpolation = null): mixed`
- []{#series-std} `std(?int $ddof = null): mixed`
- []{#series-sum} `sum(): mixed`
- []{#series-valueCounts} `valueCounts(?bool $sort = null, ?string $name = null, ?bool $normalize = null): Phpolars\DataFrame`
- []{#series-var} `var(?int $ddof = null): mixed`

#### Math {#series--math}

- []{#series-clip} `clip(mixed $lowerBound = null, mixed $upperBound = null): Phpolars\Series`
- []{#series-exp} `exp(): Phpolars\Series`
- []{#series-log} `log(?float $base = null): Phpolars\Series`
- []{#series-round} `round(?int $decimals = null): Phpolars\Series`
- []{#series-sqrt} `sqrt(): Phpolars\Series`

#### Windows, cumulative, and rolling {#series--windows-cumulative-and-rolling}

- []{#series-cumMax} `cumMax(?bool $reverse = null): Phpolars\Series`
- []{#series-cumMin} `cumMin(?bool $reverse = null): Phpolars\Series`
- []{#series-cumProd} `cumProd(?bool $reverse = null): Phpolars\Series`
- []{#series-cumSum} `cumSum(?bool $reverse = null): Phpolars\Series`
- []{#series-diff} `diff(?int $n = null): Phpolars\Series`
- []{#series-pctChange} `pctChange(?int $n = null): Phpolars\Series`
- []{#series-rank} `rank(?string $method = null, ?bool $descending = null, ?int $seed = null): Phpolars\Series`
- []{#series-shift} `shift(?int $n = null): Phpolars\Series`

#### Sort, select, and deduplicate {#series--sort-select-and-deduplicate}

- []{#series-argSort} `argSort(?bool $descending = null, ?bool $nullsLast = null): Phpolars\Series`
- []{#series-argTrue} `argTrue(): Phpolars\Series`
- []{#series-filter} `filter(mixed $predicate): Phpolars\Series`
- []{#series-gather} `gather(mixed $indices): Phpolars\Series`
- []{#series-get} `get(int $index): mixed`
- []{#series-head} `head(?int $n = null): Phpolars\Series`
- []{#series-isDuplicated} `isDuplicated(): Phpolars\Series`
- []{#series-isSorted} `isSorted(?bool $descending = null, ?bool $nullsLast = null): bool`
- []{#series-isUnique} `isUnique(): Phpolars\Series`
- []{#series-reverse} `reverse(): Phpolars\Series`
- []{#series-sample} `sample(?int $n = null, ?float $fraction = null, ?bool $withReplacement = null, ?bool $shuffle = null, ?int $seed = null): Phpolars\Series`
- []{#series-slice} `slice(int $offset, ?int $length = null): Phpolars\Series`
- []{#series-sort} `sort(?bool $descending = null, ?bool $nullsLast = null): Phpolars\Series`
- []{#series-tail} `tail(?int $n = null): Phpolars\Series`
- []{#series-unique} `unique(?bool $maintainOrder = null): Phpolars\Series`

#### Combine and reshape {#series--combine-and-reshape}

- []{#series-append} `append(Phpolars\Series $other): Phpolars\Series`
- []{#series-extend} `extend(Phpolars\Series $other): Phpolars\Series`
- []{#series-zipWith} `zipWith(Phpolars\Series $mask, Phpolars\Series $other): Phpolars\Series`

#### Types, names, and replacing values {#series--types-names-and-replacing-values}

- []{#series-alias} `alias(string $name): Phpolars\Series`
- []{#series-cast} `cast(string $dtype, ?bool $strict = null): Phpolars\Series`
- []{#series-dtype} `dtype(): string`
- []{#series-rename} `rename(string $name): Phpolars\Series`
- []{#series-scatter} `scatter(mixed $indices, mixed $values): Phpolars\Series`
- []{#series-set} `set(Phpolars\Series $filter, mixed $value): Phpolars\Series`

#### Namespaces {#series--namespaces}

- []{#series-arr} `arr(): Phpolars\ArrayNameSpace`
- []{#series-bin} `bin(): Phpolars\BinaryNameSpace`
- []{#series-cat} `cat(): Phpolars\CatNameSpace`
- []{#series-dt} `dt(): Phpolars\DtNameSpace`
- []{#series-list} `list(): Phpolars\ListNameSpace`
- []{#series-name} `name(): string`
- []{#series-str} `str(): Phpolars\StrNameSpace`
- []{#series-struct} `struct(): Phpolars\StructNameSpace`

#### Create and convert {#series--create-and-convert}

- []{#series-__call} `__call(string $name, array $arguments): mixed`
- []{#series-__toString} `__toString(): string`
- []{#series-chunkLengths} `chunkLengths(): array`
- []{#series-clear} `clear(?int $n = null): Phpolars\Series`
- []{#series-clone} `clone(): Phpolars\Series`
- []{#series-equals} `equals(Phpolars\Series $other): bool`
- []{#series-estimatedSize} `estimatedSize(): int`
- []{#series-nChunks} `nChunks(): int`
- []{#series-newFromIndex} `newFromIndex(int $index, int $length): Phpolars\Series`
- []{#series-rechunk} `rechunk(): Phpolars\Series`
- []{#series-shape} `shape(): array`
- []{#series-shrinkToFit} `shrinkToFit(): Phpolars\Series`
- []{#series-toDummies} `toDummies(?string $separator = null, ?bool $dropFirst = null, ?bool $dropNulls = null): Phpolars\DataFrame`
- []{#series-toFrame} `toFrame(?string $name = null): Phpolars\DataFrame`
- []{#series-toList} `toList(): array`

