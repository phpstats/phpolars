suppressPackageStartupMessages({
  library(readr)
  library(dplyr)
  library(jsonlite)
})

path <- commandArgs(trailingOnly = TRUE)[1]
start <- Sys.time()

parts <- read_csv_chunked(
  path,
  DataFrameCallback$new(function(chunk, pos) {
    chunk |>
      filter(status == "completed") |>
      group_by(region, month = substr(placed_on, 1, 7)) |>
      summarise(
        orders = n(),
        units = sum(quantity),
        revenue = sum(amount, na.rm = TRUE),
        amounts = sum(!is.na(amount)),
        .groups = "drop"
      )
  }),
  chunk_size = 100000,
  col_types = cols_only(
    placed_on = col_character(),
    region = col_character(),
    status = col_character(),
    quantity = col_integer(),
    amount = col_double()
  ),
  progress = FALSE
)
summary <- parts |>
  group_by(region, month) |>
  summarise(
    orders = sum(orders),
    units = sum(units),
    revenue = sum(revenue),
    mean_amount = sum(revenue) / sum(amounts),
    .groups = "drop"
  ) |>
  arrange(region, month)

rows <- lapply(seq_len(nrow(summary)), function(i) unname(as.list(summary[i, ])))
ms <- as.numeric(difftime(Sys.time(), start, units = "secs")) * 1000
cat(toJSON(list(ms = ms, maxrss = NA, rows = rows), auto_unbox = TRUE, digits = NA, na = "null"), "\n")
