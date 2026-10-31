# Site content

This folder holds phpolars' content for phpolars.phpstats.org. The site reads
`site.json` and the files it lists at a pinned commit, and it reads the rendered
docs HTML next to each `.qmd` in `docs.nav`.

- `benchmarks/*.json` and `verifications/*.json` are generated. Run
  `scripts/site-content` after a change that affects them; the benchmarks need
  uv for py-polars and take a few minutes.
- `examples` lists the pages in the docs' Examples section, in nav order. Each
  page is `docs/examples/<slug>.qmd`, and its code and output come from
  `docs/examples/<slug>.php` through `scripts/docs-examples`.
