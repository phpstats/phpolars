| Class | Purpose | Methods |
| --- | --- | --- |
| **Data frames** | | |
| [`DataFrame`](dataframe.qmd#dataframe) | Data in memory; each method runs immediately. | [Create and convert](dataframe.qmd#dataframe--create-and-convert) · [Inspect](dataframe.qmd#dataframe--inspect) · [Select and add columns](dataframe.qmd#dataframe--select-and-add-columns) · [Filter, sort, and slice rows](dataframe.qmd#dataframe--filter-sort-and-slice-rows) · [Missing values](dataframe.qmd#dataframe--missing-values) · [Group and aggregate](dataframe.qmd#dataframe--group-and-aggregate) · [Join and combine](dataframe.qmd#dataframe--join-and-combine) · [Reshape](dataframe.qmd#dataframe--reshape) · [SQL](dataframe.qmd#dataframe--sql) · [Write](dataframe.qmd#dataframe--write) |
| [`LazyFrame`](dataframe.qmd#lazyframe) | A query plan that runs, optimized, on `collect()`. | [Create and convert](dataframe.qmd#lazyframe--create-and-convert) · [Inspect](dataframe.qmd#lazyframe--inspect) · [Select and add columns](dataframe.qmd#lazyframe--select-and-add-columns) · [Filter, sort, and slice rows](dataframe.qmd#lazyframe--filter-sort-and-slice-rows) · [Missing values](dataframe.qmd#lazyframe--missing-values) · [Group and aggregate](dataframe.qmd#lazyframe--group-and-aggregate) · [Join and combine](dataframe.qmd#lazyframe--join-and-combine) · [Reshape](dataframe.qmd#lazyframe--reshape) · [SQL](dataframe.qmd#lazyframe--sql) · [Write](dataframe.qmd#lazyframe--write) |
| [`GroupBy`](dataframe.qmd#groupby) | The result of `DataFrame::groupBy()`: `agg()` and per-group summaries. | 17 methods |
| [`LazyGroupBy`](dataframe.qmd#lazygroupby) | The result of `LazyFrame::groupBy()`. | 17 methods |
| [`GroupIterator`](dataframe.qmd#groupiterator) | Iterates over a `GroupBy` with `foreach`: group keys and rows. | 5 methods |
| [`SQLContext`](dataframe.qmd#sqlcontext) | Registers frames as tables and runs SQL queries. | 5 methods |
| **Columns and expressions** | | |
| [`Series`](series.qmd#series) | One named column of values. | [Arithmetic and comparison](series.qmd#series--arithmetic-and-comparison) · [Logic and bits](series.qmd#series--logic-and-bits) · [Missing values](series.qmd#series--missing-values) · [Aggregate](series.qmd#series--aggregate) · [Math](series.qmd#series--math) · [Windows, cumulative, and rolling](series.qmd#series--windows-cumulative-and-rolling) · [Sort, select, and deduplicate](series.qmd#series--sort-select-and-deduplicate) · [Combine and reshape](series.qmd#series--combine-and-reshape) · [Types, names, and replacing values](series.qmd#series--types-names-and-replacing-values) · [Namespaces](series.qmd#series--namespaces) · [Create and convert](series.qmd#series--create-and-convert) |
| [`Expr`](expressions.qmd#expr) | A computation on columns, evaluated by `select()`, `withColumns()`, `filter()`, and `agg()`. | [Arithmetic and comparison](expressions.qmd#expr--arithmetic-and-comparison) · [Logic and bits](expressions.qmd#expr--logic-and-bits) · [Missing values](expressions.qmd#expr--missing-values) · [Aggregate](expressions.qmd#expr--aggregate) · [Math](expressions.qmd#expr--math) · [Windows, cumulative, and rolling](expressions.qmd#expr--windows-cumulative-and-rolling) · [Sort, select, and deduplicate](expressions.qmd#expr--sort-select-and-deduplicate) · [Combine and reshape](expressions.qmd#expr--combine-and-reshape) · [Types, names, and replacing values](expressions.qmd#expr--types-names-and-replacing-values) · [Namespaces](expressions.qmd#expr--namespaces) · [Create and convert](expressions.qmd#expr--create-and-convert) |
| [`When`](expressions.qmd#when) | A condition from `when()`, awaiting `then()`. | 1 method |
| [`Then`](expressions.qmd#then) | A conditional expression, continued by `when()` or `otherwise()`. | 3 methods |
| [`StrNameSpace`](expressions.qmd#str) | String operations: `col(...)->str()`. | 46 methods |
| [`DtNameSpace`](expressions.qmd#dt) | Date, datetime, and duration operations: `col(...)->dt()`. | 47 methods |
| [`ListNameSpace`](expressions.qmd#list) | List operations: `col(...)->list()`. | 43 methods |
| [`ArrayNameSpace`](expressions.qmd#arr) | Fixed-width array operations: `col(...)->arr()`. | 31 methods |
| [`StructNameSpace`](expressions.qmd#struct) | Struct field operations: `col(...)->struct()`. | 5 methods |
| [`CatNameSpace`](expressions.qmd#cat) | Categorical operations: `col(...)->cat()`. | 6 methods |
| [`BinaryNameSpace`](expressions.qmd#bin) | Binary operations: `col(...)->bin()`. | 11 methods |
| [`NameNameSpace`](expressions.qmd#name) | Output column naming: `col(...)->name()`. | 8 methods |
| [`MetaNameSpace`](expressions.qmd#meta) | Inspection of an expression itself: `col(...)->meta()`. | 9 methods |
| **Types and settings** | | |
| [`DataType`](types.qmd#datatype) | Data type names as constants, and builders for parameterized types. | 3 methods |
| [`Config`](types.qmd#config) | Settings for how data frames print. | 20 methods |
