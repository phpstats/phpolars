## `Expr` {#expr}

### Arithmetic and comparison {#expr--arithmetic-and-comparison}

- []{#expr-abs} `abs(): Phpolars\Expr`
- []{#expr-add} `add(mixed $other): Phpolars\Expr`
- []{#expr-div} `div(mixed $other): Phpolars\Expr`: the same as `truediv()`, so integer columns divide to floats; `floordiv()` floors
- []{#expr-dot} `dot(mixed $other): Phpolars\Expr`
- []{#expr-eq} `eq(mixed $other): Phpolars\Expr`
- []{#expr-eqMissing} `eqMissing(mixed $other): Phpolars\Expr`
- []{#expr-floordiv} `floordiv(mixed $other): Phpolars\Expr`
- []{#expr-ge} `ge(mixed $other): Phpolars\Expr`
- []{#expr-gt} `gt(mixed $other): Phpolars\Expr`
- []{#expr-isBetween} `isBetween(mixed $lower, mixed $upper, ?string $closed = null): Phpolars\Expr`
- []{#expr-isClose} `isClose(mixed $other, ?float $absTol = null, ?float $relTol = null, ?bool $nansEqual = null): Phpolars\Expr`
- []{#expr-isIn} `isIn(mixed $other, ?bool $nullsEqual = null): Phpolars\Expr`: a null value gives null unless `nullsEqual: true`
- []{#expr-le} `le(mixed $other): Phpolars\Expr`
- []{#expr-lt} `lt(mixed $other): Phpolars\Expr`
- []{#expr-mod} `mod(mixed $other): Phpolars\Expr`: takes the sign of the divisor, as in Python: `-7` mod `2` is `1`, where PHP's `%` gives `-1`
- []{#expr-mul} `mul(mixed $other): Phpolars\Expr`
- []{#expr-ne} `ne(mixed $other): Phpolars\Expr`
- []{#expr-neg} `neg(): Phpolars\Expr`
- []{#expr-neMissing} `neMissing(mixed $other): Phpolars\Expr`
- []{#expr-pow} `pow(mixed $exponent): Phpolars\Expr`
- []{#expr-sign} `sign(): Phpolars\Expr`
- []{#expr-sub} `sub(mixed $other): Phpolars\Expr`
- []{#expr-truediv} `truediv(mixed $other): Phpolars\Expr`

### Logic and bits {#expr--logic-and-bits}

- []{#expr-and} `and(mixed $other): Phpolars\Expr`
- []{#expr-bitwiseAnd} `bitwiseAnd(): Phpolars\Expr`
- []{#expr-bitwiseCountOnes} `bitwiseCountOnes(): Phpolars\Expr`
- []{#expr-bitwiseCountZeros} `bitwiseCountZeros(): Phpolars\Expr`
- []{#expr-bitwiseLeadingOnes} `bitwiseLeadingOnes(): Phpolars\Expr`
- []{#expr-bitwiseLeadingZeros} `bitwiseLeadingZeros(): Phpolars\Expr`
- []{#expr-bitwiseOr} `bitwiseOr(): Phpolars\Expr`
- []{#expr-bitwiseTrailingOnes} `bitwiseTrailingOnes(): Phpolars\Expr`
- []{#expr-bitwiseTrailingZeros} `bitwiseTrailingZeros(): Phpolars\Expr`
- []{#expr-bitwiseXor} `bitwiseXor(): Phpolars\Expr`
- []{#expr-not} `not(): Phpolars\Expr`
- []{#expr-or} `or(mixed $other): Phpolars\Expr`
- []{#expr-xor} `xor(mixed $other): Phpolars\Expr`

### Missing values {#expr--missing-values}

- []{#expr-backwardFill} `backwardFill(?int $limit = null): Phpolars\Expr`
- []{#expr-dropNans} `dropNans(): Phpolars\Expr`
- []{#expr-dropNulls} `dropNulls(): Phpolars\Expr`
- []{#expr-fillNan} `fillNan(mixed $value): Phpolars\Expr`
- []{#expr-fillNull} `fillNull(mixed $value = null, ?string $strategy = null, ?int $limit = null): Phpolars\Expr`: a `value`, or a `strategy`: `'forward'`, `'backward'`, `'mean'`, `'min'`, `'max'`, `'zero'`, or `'one'`
- []{#expr-forwardFill} `forwardFill(?int $limit = null): Phpolars\Expr`
- []{#expr-hasNulls} `hasNulls(): Phpolars\Expr`
- []{#expr-interpolate} `interpolate(?string $method = null): Phpolars\Expr`
- []{#expr-interpolateBy} `interpolateBy(mixed $by): Phpolars\Expr`
- []{#expr-isNan} `isNan(): Phpolars\Expr`
- []{#expr-isNotNan} `isNotNan(): Phpolars\Expr`
- []{#expr-isNotNull} `isNotNull(): Phpolars\Expr`
- []{#expr-isNull} `isNull(): Phpolars\Expr`: true for null; NaN is a float value, not null
- []{#expr-nullCount} `nullCount(): Phpolars\Expr`

### Aggregate {#expr--aggregate}

- []{#expr-all} `all(?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-any} `any(?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-approxNUnique} `approxNUnique(): Phpolars\Expr`
- []{#expr-argMax} `argMax(): Phpolars\Expr`
- []{#expr-argMin} `argMin(): Phpolars\Expr`
- []{#expr-count} `count(): Phpolars\Expr`: counts non-null values; `len()` counts rows
- []{#expr-entropy} `entropy(?float $base = null, ?bool $normalize = null): Phpolars\Expr`
- []{#expr-first} `first(?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-implode} `implode(): Phpolars\Expr`
- []{#expr-isEmpty} `isEmpty(?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-item} `item(?bool $allowEmpty = null): Phpolars\Expr`
- []{#expr-kurtosis} `kurtosis(?bool $fisher = null, ?bool $bias = null): Phpolars\Expr`
- []{#expr-last} `last(?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-len} `len(): Phpolars\Expr`: counts rows, including nulls
- []{#expr-max} `max(): Phpolars\Expr`
- []{#expr-maxBy} `maxBy(mixed $by): Phpolars\Expr`
- []{#expr-mean} `mean(): Phpolars\Expr`: skips nulls
- []{#expr-median} `median(): Phpolars\Expr`
- []{#expr-min} `min(): Phpolars\Expr`
- []{#expr-minBy} `minBy(mixed $by): Phpolars\Expr`
- []{#expr-mode} `mode(): Phpolars\Expr`
- []{#expr-nanMax} `nanMax(): Phpolars\Expr`
- []{#expr-nanMin} `nanMin(): Phpolars\Expr`
- []{#expr-nUnique} `nUnique(): Phpolars\Expr`
- []{#expr-product} `product(): Phpolars\Expr`
- []{#expr-quantile} `quantile(mixed $quantile, ?string $interpolation = null): Phpolars\Expr`
- []{#expr-skew} `skew(?bool $bias = null): Phpolars\Expr`
- []{#expr-std} `std(?int $ddof = null): Phpolars\Expr`: the sample statistic (`ddof` 1)
- []{#expr-sum} `sum(): Phpolars\Expr`: skips nulls; the sum of no values is 0
- []{#expr-uniqueCounts} `uniqueCounts(): Phpolars\Expr`
- []{#expr-valueCounts} `valueCounts(?bool $sort = null, ?string $name = null, ?bool $normalize = null): Phpolars\Expr`
- []{#expr-var} `var(?int $ddof = null): Phpolars\Expr`: the sample statistic (`ddof` 1)

### Math {#expr--math}

- []{#expr-arccos} `arccos(): Phpolars\Expr`
- []{#expr-arccosh} `arccosh(): Phpolars\Expr`
- []{#expr-arcsin} `arcsin(): Phpolars\Expr`
- []{#expr-arcsinh} `arcsinh(): Phpolars\Expr`
- []{#expr-arctan} `arctan(): Phpolars\Expr`
- []{#expr-arctanh} `arctanh(): Phpolars\Expr`
- []{#expr-cbrt} `cbrt(): Phpolars\Expr`
- []{#expr-ceil} `ceil(): Phpolars\Expr`
- []{#expr-clip} `clip(mixed $lowerBound = null, mixed $upperBound = null): Phpolars\Expr`
- []{#expr-cos} `cos(): Phpolars\Expr`
- []{#expr-cosh} `cosh(): Phpolars\Expr`
- []{#expr-cot} `cot(): Phpolars\Expr`
- []{#expr-degrees} `degrees(): Phpolars\Expr`
- []{#expr-exp} `exp(): Phpolars\Expr`
- []{#expr-floor} `floor(): Phpolars\Expr`
- []{#expr-isFinite} `isFinite(): Phpolars\Expr`
- []{#expr-isInfinite} `isInfinite(): Phpolars\Expr`
- []{#expr-log} `log(?float $base = null): Phpolars\Expr`
- []{#expr-log10} `log10(): Phpolars\Expr`
- []{#expr-log1P} `log1P(): Phpolars\Expr`
- []{#expr-radians} `radians(): Phpolars\Expr`
- []{#expr-round} `round(?int $decimals = null, ?string $mode = null): Phpolars\Expr`: rounds half to even by default; `mode: 'half_away_from_zero'` rounds as PHP's `round()` does
- []{#expr-roundSigFigs} `roundSigFigs(int $digits): Phpolars\Expr`
- []{#expr-sin} `sin(): Phpolars\Expr`
- []{#expr-sinh} `sinh(): Phpolars\Expr`
- []{#expr-sqrt} `sqrt(): Phpolars\Expr`
- []{#expr-tan} `tan(): Phpolars\Expr`
- []{#expr-tanh} `tanh(): Phpolars\Expr`

### Windows, cumulative, and rolling {#expr--windows-cumulative-and-rolling}

- []{#expr-cumCount} `cumCount(?bool $reverse = null): Phpolars\Expr`
- []{#expr-cumMax} `cumMax(?bool $reverse = null): Phpolars\Expr`
- []{#expr-cumMin} `cumMin(?bool $reverse = null): Phpolars\Expr`
- []{#expr-cumProd} `cumProd(?bool $reverse = null): Phpolars\Expr`
- []{#expr-cumSum} `cumSum(?bool $reverse = null): Phpolars\Expr`
- []{#expr-cumulativeEval} `cumulativeEval(mixed $expr, ?int $minPeriods = null): Phpolars\Expr`
- []{#expr-diff} `diff(?int $n = null, ?string $nullBehavior = null): Phpolars\Expr`
- []{#expr-ewmMean} `ewmMean(?float $com = null, ?float $span = null, ?float $halfLife = null, ?float $alpha = null, ?bool $adjust = null, ?int $minPeriods = null, ?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-ewmMeanBy} `ewmMeanBy(mixed $by, string $halfLife): Phpolars\Expr`
- []{#expr-ewmStd} `ewmStd(?float $com = null, ?float $span = null, ?float $halfLife = null, ?float $alpha = null, ?bool $adjust = null, ?bool $bias = null, ?int $minPeriods = null, ?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-ewmSum} `ewmSum(?float $com = null, ?float $span = null, ?float $halfLife = null, ?float $alpha = null, ?int $minPeriods = null, ?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-ewmSumBy} `ewmSumBy(mixed $by, string $halfLife): Phpolars\Expr`
- []{#expr-ewmVar} `ewmVar(?float $com = null, ?float $span = null, ?float $halfLife = null, ?float $alpha = null, ?bool $adjust = null, ?bool $bias = null, ?int $minPeriods = null, ?bool $ignoreNulls = null): Phpolars\Expr`
- []{#expr-over} `over(mixed $partitionBy, mixed $orderBy = null, ?bool $descending = null, ?bool $nullsLast = null, ?string $mappingStrategy = null): Phpolars\Expr`: computes within each group of the given columns and returns one value per row
- []{#expr-pctChange} `pctChange(?int $n = null): Phpolars\Expr`
- []{#expr-peakMax} `peakMax(): Phpolars\Expr`
- []{#expr-peakMin} `peakMin(): Phpolars\Expr`
- []{#expr-rank} `rank(?string $method = null, ?bool $descending = null, ?int $seed = null): Phpolars\Expr`
- []{#expr-rle} `rle(): Phpolars\Expr`
- []{#expr-rleId} `rleId(): Phpolars\Expr`
- []{#expr-rolling} `rolling(mixed $indexColumn, string $period, ?string $offset = null, ?string $closed = null): Phpolars\Expr`
- []{#expr-rollingKurtosis} `rollingKurtosis(int $windowSize, ?bool $fisher = null, ?bool $bias = null, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingMax} `rollingMax(int $windowSize, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingMaxBy} `rollingMaxBy(mixed $by, string $windowSize, ?int $minPeriods = null, ?string $closed = null): Phpolars\Expr`
- []{#expr-rollingMean} `rollingMean(int $windowSize, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingMeanBy} `rollingMeanBy(mixed $by, string $windowSize, ?int $minPeriods = null, ?string $closed = null): Phpolars\Expr`
- []{#expr-rollingMedian} `rollingMedian(int $windowSize, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingMedianBy} `rollingMedianBy(mixed $by, string $windowSize, ?int $minPeriods = null, ?string $closed = null): Phpolars\Expr`
- []{#expr-rollingMin} `rollingMin(int $windowSize, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingMinBy} `rollingMinBy(mixed $by, string $windowSize, ?int $minPeriods = null, ?string $closed = null): Phpolars\Expr`
- []{#expr-rollingQuantile} `rollingQuantile(float $quantile, int $windowSize, ?string $interpolation = null, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingQuantileBy} `rollingQuantileBy(mixed $by, string $windowSize, float $quantile, ?string $interpolation = null, ?int $minPeriods = null, ?string $closed = null): Phpolars\Expr`
- []{#expr-rollingRank} `rollingRank(int $windowSize, ?string $method = null, ?int $seed = null, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingRankBy} `rollingRankBy(mixed $by, string $windowSize, ?string $method = null, ?int $seed = null, ?int $minPeriods = null, ?string $closed = null): Phpolars\Expr`
- []{#expr-rollingSkew} `rollingSkew(int $windowSize, ?bool $bias = null, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingStd} `rollingStd(int $windowSize, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingStdBy} `rollingStdBy(mixed $by, string $windowSize, ?int $minPeriods = null, ?string $closed = null, ?int $ddof = null): Phpolars\Expr`
- []{#expr-rollingSum} `rollingSum(int $windowSize, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingSumBy} `rollingSumBy(mixed $by, string $windowSize, ?int $minPeriods = null, ?string $closed = null): Phpolars\Expr`
- []{#expr-rollingVar} `rollingVar(int $windowSize, ?int $minPeriods = null, ?bool $center = null): Phpolars\Expr`
- []{#expr-rollingVarBy} `rollingVarBy(mixed $by, string $windowSize, ?int $minPeriods = null, ?string $closed = null, ?int $ddof = null): Phpolars\Expr`
- []{#expr-shift} `shift(?int $n = null, mixed $fillValue = null): Phpolars\Expr`

### Sort, select, and deduplicate {#expr--sort-select-and-deduplicate}

- []{#expr-argSort} `argSort(?bool $descending = null, ?bool $nullsLast = null): Phpolars\Expr`
- []{#expr-argTrue} `argTrue(): Phpolars\Expr`
- []{#expr-argUnique} `argUnique(): Phpolars\Expr`
- []{#expr-bottomK} `bottomK(?int $k = null): Phpolars\Expr`
- []{#expr-bottomKBy} `bottomKBy(mixed $by, ?int $k = null, mixed $reverse = null): Phpolars\Expr`
- []{#expr-filter} `filter(mixed $predicate): Phpolars\Expr`: inside `agg()` or `over()`, keeps only the matching values of each group
- []{#expr-gather} `gather(mixed $indices): Phpolars\Expr`
- []{#expr-gatherEvery} `gatherEvery(int $n, ?int $offset = null): Phpolars\Expr`
- []{#expr-get} `get(mixed $index, ?bool $nullOnOob = null): Phpolars\Expr`
- []{#expr-head} `head(?int $n = null): Phpolars\Expr`
- []{#expr-indexOf} `indexOf(mixed $element): Phpolars\Expr`
- []{#expr-isDuplicated} `isDuplicated(): Phpolars\Expr`
- []{#expr-isFirstDistinct} `isFirstDistinct(): Phpolars\Expr`
- []{#expr-isLastDistinct} `isLastDistinct(): Phpolars\Expr`
- []{#expr-isUnique} `isUnique(): Phpolars\Expr`
- []{#expr-limit} `limit(?int $n = null): Phpolars\Expr`
- []{#expr-lowerBound} `lowerBound(): Phpolars\Expr`
- []{#expr-reverse} `reverse(): Phpolars\Expr`
- []{#expr-sample} `sample(?int $n = null, ?float $fraction = null, ?bool $withReplacement = null, ?bool $shuffle = null, ?int $seed = null): Phpolars\Expr`
- []{#expr-searchSorted} `searchSorted(mixed $element, ?string $side = null, ?bool $descending = null): Phpolars\Expr`
- []{#expr-shuffle} `shuffle(?int $seed = null): Phpolars\Expr`
- []{#expr-slice} `slice(int $offset, ?int $length = null): Phpolars\Expr`
- []{#expr-sort} `sort(?bool $descending = null, ?bool $nullsLast = null): Phpolars\Expr`: nulls sort first unless `nullsLast: true`
- []{#expr-sortBy} `sortBy(mixed $by, mixed $descending = null, mixed $nullsLast = null): Phpolars\Expr`
- []{#expr-tail} `tail(?int $n = null): Phpolars\Expr`
- []{#expr-topK} `topK(?int $k = null): Phpolars\Expr`
- []{#expr-topKBy} `topKBy(mixed $by, ?int $k = null, mixed $reverse = null): Phpolars\Expr`
- []{#expr-unique} `unique(?bool $maintainOrder = null): Phpolars\Expr`: the order is not kept unless `maintainOrder: true`
- []{#expr-upperBound} `upperBound(): Phpolars\Expr`

### Combine and reshape {#expr--combine-and-reshape}

- []{#expr-append} `append(mixed $other, ?bool $upcast = null): Phpolars\Expr`
- []{#expr-explode} `explode(): Phpolars\Expr`
- []{#expr-extendConstant} `extendConstant(mixed $value, mixed $n): Phpolars\Expr`
- []{#expr-flatten} `flatten(): Phpolars\Expr`
- []{#expr-repeatBy} `repeatBy(mixed $by): Phpolars\Expr`
- []{#expr-reshape} `reshape(array $dimensions): Phpolars\Expr`

### Types, names, and replacing values {#expr--types-names-and-replacing-values}

- []{#expr-alias} `alias(string $name): Phpolars\Expr`: names the output column
- []{#expr-cast} `cast(string $dtype, ?bool $strict = null): Phpolars\Expr`: strict: a value that cannot be converted raises `InvalidOperationError`; `strict: false` gives `null`
- []{#expr-cut} `cut(mixed $breaks, ?array $labels = null, ?bool $leftClosed = null, ?bool $includeBreaks = null): Phpolars\Expr`
- []{#expr-exclude} `exclude(mixed ...$columns): Phpolars\Expr`
- []{#expr-hist} `hist(mixed $bins = null, ?int $binCount = null, ?bool $includeCategory = null, ?bool $includeBreakpoint = null): Phpolars\Expr`
- []{#expr-qcut} `qcut(mixed $quantiles, ?array $labels = null, ?bool $leftClosed = null, ?bool $allowDuplicates = null, ?bool $includeBreaks = null): Phpolars\Expr`
- []{#expr-reinterpret} `reinterpret(?bool $signed = null): Phpolars\Expr`
- []{#expr-replace} `replace(mixed $old, mixed $new = null): Phpolars\Expr`: values without a mapping stay as they are
- []{#expr-replaceStrict} `replaceStrict(mixed $old, mixed $new = null, mixed $default = null, ?string $returnDtype = null): Phpolars\Expr`: values without a mapping raise `InvalidOperationError` unless `default` is given
- []{#expr-toPhysical} `toPhysical(): Phpolars\Expr`

### Namespaces {#expr--namespaces}

- []{#expr-arr} `arr(): Phpolars\ArrayNameSpace`
- []{#expr-bin} `bin(): Phpolars\BinaryNameSpace`
- []{#expr-cat} `cat(): Phpolars\CatNameSpace`
- []{#expr-dt} `dt(): Phpolars\DtNameSpace`
- []{#expr-list} `list(): Phpolars\ListNameSpace`
- []{#expr-meta} `meta(): Phpolars\MetaNameSpace`
- []{#expr-name} `name(): Phpolars\NameNameSpace`
- []{#expr-str} `str(): Phpolars\StrNameSpace`
- []{#expr-struct} `struct(): Phpolars\StructNameSpace`

### Create and convert {#expr--create-and-convert}

- []{#expr-__toString} `__toString(): string`

## `When` {#when}

- []{#when-then} `then(mixed $value): Phpolars\Then`

## `Then` {#then}

- []{#then-alias} `alias(string $name): Phpolars\Expr`
- []{#then-otherwise} `otherwise(mixed $value): Phpolars\Expr`
- []{#then-when} `when(mixed ...$condition): Phpolars\When`

## `StrNameSpace` {#str}

- []{#str-contains} `contains(mixed $pattern, ?bool $literal = null, ?bool $strict = null): mixed`
- []{#str-containsAny} `containsAny(mixed $patterns, ?bool $asciiCaseInsensitive = null): mixed`
- []{#str-countMatches} `countMatches(mixed $pattern, ?bool $literal = null): mixed`
- []{#str-decode} `decode(string $encoding, ?bool $strict = null): mixed`
- []{#str-encode} `encode(string $encoding): mixed`
- []{#str-endsWith} `endsWith(mixed $suffix): mixed`
- []{#str-escapeRegex} `escapeRegex(): mixed`
- []{#str-extract} `extract(mixed $pattern, ?int $groupIndex = null): mixed`
- []{#str-extractAll} `extractAll(mixed $pattern): mixed`
- []{#str-extractGroups} `extractGroups(string $pattern): mixed`
- []{#str-extractMany} `extractMany(mixed $patterns, ?bool $asciiCaseInsensitive = null, ?bool $overlapping = null, ?bool $leftmost = null): mixed`
- []{#str-find} `find(mixed $pattern, ?bool $literal = null, ?bool $strict = null): mixed`
- []{#str-findMany} `findMany(mixed $patterns, ?bool $asciiCaseInsensitive = null, ?bool $overlapping = null, ?bool $leftmost = null): mixed`
- []{#str-head} `head(int $n): mixed`
- []{#str-join} `join(?string $delimiter = null, ?bool $ignoreNulls = null): mixed`
- []{#str-jsonDecode} `jsonDecode(string $dtype): mixed`
- []{#str-jsonPathMatch} `jsonPathMatch(mixed $jsonPath): mixed`
- []{#str-lenBytes} `lenBytes(): mixed`
- []{#str-lenChars} `lenChars(): mixed`
- []{#str-normalize} `normalize(?string $form = null): mixed`
- []{#str-padEnd} `padEnd(mixed $length, ?string $fillChar = null): mixed`
- []{#str-padStart} `padStart(mixed $length, ?string $fillChar = null): mixed`
- []{#str-replace} `replace(mixed $pattern, mixed $value, ?bool $literal = null, ?int $n = null): mixed`
- []{#str-replaceAll} `replaceAll(mixed $pattern, mixed $value, ?bool $literal = null): mixed`
- []{#str-replaceMany} `replaceMany(mixed $patterns, mixed $replaceWith = null, ?bool $asciiCaseInsensitive = null, ?bool $leftmost = null): mixed`
- []{#str-reverse} `reverse(): mixed`
- []{#str-slice} `slice(mixed $offset, mixed $length = null): mixed`
- []{#str-split} `split(mixed $by, ?bool $inclusive = null, ?bool $literal = null): mixed`
- []{#str-splitExact} `splitExact(mixed $by, int $n, ?bool $inclusive = null): mixed`
- []{#str-splitn} `splitn(mixed $by, int $n): mixed`
- []{#str-startsWith} `startsWith(mixed $prefix): mixed`
- []{#str-stripChars} `stripChars(?string $characters = null): mixed`
- []{#str-stripCharsEnd} `stripCharsEnd(?string $characters = null): mixed`
- []{#str-stripCharsStart} `stripCharsStart(?string $characters = null): mixed`
- []{#str-stripPrefix} `stripPrefix(mixed $prefix): mixed`
- []{#str-stripSuffix} `stripSuffix(mixed $suffix): mixed`
- []{#str-strptime} `strptime(string $dtype, ?string $format = null, ?bool $strict = null, ?bool $exact = null): mixed`
- []{#str-tail} `tail(int $n): mixed`
- []{#str-toDate} `toDate(?string $format = null, ?bool $strict = null, ?bool $exact = null): mixed`
- []{#str-toDatetime} `toDatetime(?string $format = null, ?string $timeUnit = null, ?string $timeZone = null, ?bool $strict = null, ?bool $exact = null): mixed`
- []{#str-toDecimal} `toDecimal(int $scale): mixed`
- []{#str-toInteger} `toInteger(?int $base = null, ?string $dtype = null, ?bool $strict = null): mixed`
- []{#str-toLowercase} `toLowercase(): mixed`
- []{#str-toTime} `toTime(?string $format = null, ?bool $strict = null): mixed`
- []{#str-toUppercase} `toUppercase(): mixed`
- []{#str-zfill} `zfill(int $length): mixed`

## `DtNameSpace` {#dt}

- []{#dt-addBusinessDays} `addBusinessDays(mixed $n, ?array $weekMask = null, mixed $holidays = null, ?string $roll = null): mixed`
- []{#dt-baseUtcOffset} `baseUtcOffset(): mixed`
- []{#dt-castTimeUnit} `castTimeUnit(string $timeUnit): mixed`
- []{#dt-century} `century(): mixed`
- []{#dt-combine} `combine(mixed $time, ?string $timeUnit = null): mixed`
- []{#dt-convertTimeZone} `convertTimeZone(string $timeZone): mixed`
- []{#dt-date} `date(): mixed`
- []{#dt-datetime} `datetime(): mixed`
- []{#dt-day} `day(): mixed`
- []{#dt-daysInMonth} `daysInMonth(): mixed`
- []{#dt-dstOffset} `dstOffset(): mixed`
- []{#dt-epoch} `epoch(?string $timeUnit = null): mixed`
- []{#dt-hour} `hour(): mixed`
- []{#dt-isBusinessDay} `isBusinessDay(?array $weekMask = null, mixed $holidays = null): mixed`
- []{#dt-isLeapYear} `isLeapYear(): mixed`
- []{#dt-isoYear} `isoYear(): mixed`
- []{#dt-microsecond} `microsecond(): mixed`
- []{#dt-millennium} `millennium(): mixed`
- []{#dt-millisecond} `millisecond(): mixed`
- []{#dt-minute} `minute(): mixed`
- []{#dt-month} `month(): mixed`
- []{#dt-monthEnd} `monthEnd(): mixed`
- []{#dt-monthStart} `monthStart(): mixed`
- []{#dt-nanosecond} `nanosecond(): mixed`
- []{#dt-offsetBy} `offsetBy(string $by): mixed`
- []{#dt-ordinalDay} `ordinalDay(): mixed`
- []{#dt-quarter} `quarter(): mixed`
- []{#dt-replace} `replace(mixed $year = null, mixed $month = null, mixed $day = null, mixed $hour = null, mixed $minute = null, mixed $second = null, mixed $microsecond = null, ?string $ambiguous = null): mixed`
- []{#dt-replaceTimeZone} `replaceTimeZone(?string $timeZone = null, ?string $ambiguous = null, ?string $nonExistent = null): mixed`
- []{#dt-round} `round(string $every): mixed`
- []{#dt-second} `second(): mixed`
- []{#dt-strftime} `strftime(string $format): mixed`
- []{#dt-time} `time(): mixed`
- []{#dt-timestamp} `timestamp(?string $timeUnit = null): mixed`
- []{#dt-toString} `toString(?string $format = null): mixed`
- []{#dt-totalDays} `totalDays(?bool $fractional = null): mixed`
- []{#dt-totalHours} `totalHours(?bool $fractional = null): mixed`
- []{#dt-totalMicroseconds} `totalMicroseconds(?bool $fractional = null): mixed`
- []{#dt-totalMilliseconds} `totalMilliseconds(?bool $fractional = null): mixed`
- []{#dt-totalMinutes} `totalMinutes(?bool $fractional = null): mixed`
- []{#dt-totalNanoseconds} `totalNanoseconds(?bool $fractional = null): mixed`
- []{#dt-totalSeconds} `totalSeconds(?bool $fractional = null): mixed`
- []{#dt-truncate} `truncate(string $every): mixed`
- []{#dt-week} `week(): mixed`
- []{#dt-weekday} `weekday(): mixed`
- []{#dt-withTimeUnit} `withTimeUnit(string $timeUnit): mixed`
- []{#dt-year} `year(): mixed`

## `ListNameSpace` {#list}

- []{#list-agg} `agg(mixed $expr): mixed`
- []{#list-all} `all(?bool $ignoreNulls = null): mixed`
- []{#list-any} `any(?bool $ignoreNulls = null): mixed`
- []{#list-argMax} `argMax(): mixed`
- []{#list-argMin} `argMin(): mixed`
- []{#list-concat} `concat(mixed $other): mixed`
- []{#list-contains} `contains(mixed $item, ?bool $nullsEqual = null): mixed`
- []{#list-countMatches} `countMatches(mixed $element): mixed`
- []{#list-diff} `diff(?int $n = null): mixed`
- []{#list-dropNulls} `dropNulls(): mixed`
- []{#list-eval} `eval(mixed $expr): mixed`
- []{#list-explode} `explode(): mixed`
- []{#list-filter} `filter(mixed $predicate): mixed`
- []{#list-first} `first(): mixed`
- []{#list-gather} `gather(mixed $indices, ?bool $nullOnOob = null): mixed`
- []{#list-gatherEvery} `gatherEvery(mixed $n, mixed $offset = null): mixed`
- []{#list-get} `get(mixed $index, ?bool $nullOnOob = null): mixed`
- []{#list-head} `head(?int $n = null): mixed`
- []{#list-item} `item(?bool $allowEmpty = null): mixed`
- []{#list-join} `join(mixed $separator, ?bool $ignoreNulls = null): mixed`
- []{#list-last} `last(): mixed`
- []{#list-len} `len(): mixed`
- []{#list-max} `max(): mixed`
- []{#list-mean} `mean(): mixed`
- []{#list-median} `median(): mixed`
- []{#list-min} `min(): mixed`
- []{#list-nUnique} `nUnique(): mixed`
- []{#list-reverse} `reverse(): mixed`
- []{#list-sample} `sample(mixed $n = null, mixed $fraction = null, ?bool $withReplacement = null, ?bool $shuffle = null, ?int $seed = null): mixed`
- []{#list-setDifference} `setDifference(mixed $other): mixed`
- []{#list-setIntersection} `setIntersection(mixed $other): mixed`
- []{#list-setSymmetricDifference} `setSymmetricDifference(mixed $other): mixed`
- []{#list-setUnion} `setUnion(mixed $other): mixed`
- []{#list-shift} `shift(?int $n = null): mixed`
- []{#list-slice} `slice(int $offset, ?int $length = null): mixed`
- []{#list-sort} `sort(?bool $descending = null, ?bool $nullsLast = null): mixed`
- []{#list-std} `std(?int $ddof = null): mixed`
- []{#list-sum} `sum(): mixed`
- []{#list-tail} `tail(?int $n = null): mixed`
- []{#list-toArray} `toArray(int $width): mixed`
- []{#list-toStruct} `toStruct(mixed $fields = null): mixed`
- []{#list-unique} `unique(?bool $maintainOrder = null): mixed`
- []{#list-var} `var(?int $ddof = null): mixed`

## `ArrayNameSpace` {#arr}

- []{#arr-agg} `agg(mixed $expr): mixed`
- []{#arr-all} `all(?bool $ignoreNulls = null): mixed`
- []{#arr-any} `any(?bool $ignoreNulls = null): mixed`
- []{#arr-argMax} `argMax(): mixed`
- []{#arr-argMin} `argMin(): mixed`
- []{#arr-contains} `contains(mixed $item, ?bool $nullsEqual = null): mixed`
- []{#arr-countMatches} `countMatches(mixed $element): mixed`
- []{#arr-eval} `eval(mixed $expr, ?bool $asList = null): mixed`
- []{#arr-explode} `explode(): mixed`
- []{#arr-first} `first(): mixed`
- []{#arr-get} `get(mixed $index, ?bool $nullOnOob = null): mixed`
- []{#arr-head} `head(?int $n = null, ?bool $asArray = null): mixed`
- []{#arr-join} `join(mixed $separator, ?bool $ignoreNulls = null): mixed`
- []{#arr-last} `last(): mixed`
- []{#arr-len} `len(): mixed`
- []{#arr-max} `max(): mixed`
- []{#arr-mean} `mean(): mixed`
- []{#arr-median} `median(): mixed`
- []{#arr-min} `min(): mixed`
- []{#arr-nUnique} `nUnique(): mixed`
- []{#arr-reverse} `reverse(): mixed`
- []{#arr-shift} `shift(mixed $n = null): mixed`
- []{#arr-slice} `slice(mixed $offset, mixed $length = null, ?bool $asArray = null): mixed`
- []{#arr-sort} `sort(?bool $descending = null, ?bool $nullsLast = null): mixed`
- []{#arr-std} `std(?int $ddof = null): mixed`
- []{#arr-sum} `sum(): mixed`
- []{#arr-tail} `tail(?int $n = null, ?bool $asArray = null): mixed`
- []{#arr-toList} `toList(): mixed`
- []{#arr-toStruct} `toStruct(): mixed`
- []{#arr-unique} `unique(?bool $maintainOrder = null): mixed`
- []{#arr-var} `var(?int $ddof = null): mixed`

## `StructNameSpace` {#struct}

- []{#struct-field} `field(string $name): mixed`
- []{#struct-jsonEncode} `jsonEncode(): mixed`
- []{#struct-renameFields} `renameFields(mixed $names): mixed`
- []{#struct-unnest} `unnest(): mixed`
- []{#struct-withFields} `withFields(mixed ...$exprs): mixed`

## `CatNameSpace` {#cat}

- []{#cat-endsWith} `endsWith(string $suffix): mixed`
- []{#cat-getCategories} `getCategories(): mixed`
- []{#cat-lenBytes} `lenBytes(): mixed`
- []{#cat-lenChars} `lenChars(): mixed`
- []{#cat-slice} `slice(int $offset, ?int $length = null): mixed`
- []{#cat-startsWith} `startsWith(string $prefix): mixed`

## `BinaryNameSpace` {#bin}

- []{#bin-contains} `contains(mixed $literal): mixed`
- []{#bin-decode} `decode(string $encoding, ?bool $strict = null): mixed`
- []{#bin-encode} `encode(string $encoding): mixed`
- []{#bin-endsWith} `endsWith(mixed $suffix): mixed`
- []{#bin-get} `get(mixed $index, ?bool $nullOnOob = null): mixed`
- []{#bin-head} `head(?int $n = null): mixed`
- []{#bin-reinterpret} `reinterpret(string $dtype, ?string $endianness = null): mixed`
- []{#bin-size} `size(?string $unit = null): mixed`
- []{#bin-slice} `slice(mixed $offset, mixed $length = null): mixed`
- []{#bin-startsWith} `startsWith(mixed $prefix): mixed`
- []{#bin-tail} `tail(?int $n = null): mixed`

## `NameNameSpace` {#name}

- []{#name-keep} `keep(): Phpolars\Expr`
- []{#name-prefix} `prefix(string $prefix): Phpolars\Expr`
- []{#name-prefixFields} `prefixFields(string $prefix): Phpolars\Expr`
- []{#name-replace} `replace(string $pattern, string $value, ?bool $literal = null): Phpolars\Expr`
- []{#name-suffix} `suffix(string $suffix): Phpolars\Expr`
- []{#name-suffixFields} `suffixFields(string $suffix): Phpolars\Expr`
- []{#name-toLowercase} `toLowercase(): Phpolars\Expr`
- []{#name-toUppercase} `toUppercase(): Phpolars\Expr`

## `MetaNameSpace` {#meta}

- []{#meta-eq} `eq(Phpolars\Expr $other): bool`
- []{#meta-hasMultipleOutputs} `hasMultipleOutputs(): bool`
- []{#meta-isColumn} `isColumn(): bool`
- []{#meta-isColumnSelection} `isColumnSelection(?bool $allowAliasing = null): bool`
- []{#meta-isLiteral} `isLiteral(?bool $allowAliasing = null): bool`
- []{#meta-isRegexProjection} `isRegexProjection(): bool`
- []{#meta-outputName} `outputName(): string`
- []{#meta-rootNames} `rootNames(): array`
- []{#meta-undoAliases} `undoAliases(): Phpolars\Expr`

