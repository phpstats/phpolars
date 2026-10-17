# Site content

This folder holds phpolars' content for phpolars.phpstats.org. The site reads
`site.json` and the files it lists at a pinned commit, and it reads the rendered
docs HTML next to each `.qmd` in `docs.nav`.

- `benchmarks/*.json` and `verifications/*.json` are generated. Run
  `scripts/site-content` after a change that affects them; the benchmarks need
  uv for py-polars and take a few minutes.
- `vignettes/<slug>.php` returns `['inputs' => [...], 'run' => fn (array $input, string $data): array]`.
  `run()` returns the page's blocks in order: text, code (`chain`, optional `pipe`),
  metrics, table, chart (a Vega-Lite spec), output, and note. The site runs the
  script on each request, so keep it deterministic, under a second at the default
  inputs, free of global functions and classes, and limited to writing under
  `sys_get_temp_dir()`.
- `scripts/site-vignette site/vignettes/<slug>.php [AUTOLOAD] [key=value ...]`
  runs a vignette locally the way the site does. AUTOLOAD is a Composer
  autoloader with phibble and phplyr, for the vignettes that use them.
