suppressPackageStartupMessages({
  library(readr)
  library(dplyr)
  library(jsonlite)
})

path <- commandArgs(trailingOnly = TRUE)[1]
start <- Sys.time()

summary <- read_csv(
  path,
  col_select = c(placed_on, region, status, quantity, amount),
  col_types = cols(placed_on = col_character()),
  progress = FALSE,
  num_threads = 2
) |>
  filter(status == "completed") |>
  group_by(region, month = substr(placed_on, 1, 7)) |>
  summarise(
    orders = n(),
    units = sum(quantity),
    revenue = sum(amount, na.rm = TRUE),
    mean_amount = mean(amount, na.rm = TRUE),
    .groups = "drop"
  ) |>
  arrange(region, month)

rows <- lapply(seq_len(nrow(summary)), function(i) unname(as.list(summary[i, ])))
ms <- as.numeric(difftime(Sys.time(), start, units = "secs")) * 1000
cat(toJSON(list(ms = ms, maxrss = NA, rows = rows), auto_unbox = TRUE, digits = NA, na = "null"), "\n")
