"""py-polars side of the site benchmarks: python bench/site.py DATA_DIR [REPS].

Runs the cases of bench/site.php with py-polars on the same files and prints
one JSON object. Set POLARS_MAX_THREADS to match phpolars' pool.
"""
import gc, json, sys, time

import polars as pl

data, reps = sys.argv[1], int(sys.argv[2]) if len(sys.argv) > 2 else 5
facts, dim = f"{data}/facts.csv", f"{data}/dim.csv"


def timed(f):
    f()
    times = []
    for _ in range(reps):
        gc.collect()
        t = time.perf_counter_ns()
        f()
        times.append(round((time.perf_counter_ns() - t) / 1e6, 3))
    return times


pf, pd = pl.read_csv(facts), pl.read_csv(dim)
results = {"py-polars": {
    "read": timed(lambda: pl.read_csv(facts)),
    "group_by": timed(lambda: pf.group_by("g").agg(
        pl.col("y").mean().alias("mean_y"), pl.col("x").sum().alias("sum_x"), pl.col("x").count().alias("n"))),
    "join": timed(lambda: pf.join(pd, on="k", how="inner")),
}}
check = {"py-polars": {"groups": pf.group_by("g").agg(pl.col("y").mean()).height,
                       "joined": pf.join(pd, on="k", how="inner").height}}
print(json.dumps({"results": results, "check": check, "polars": pl.__version__,
                  "python": sys.version.split()[0], "threads": pl.thread_pool_size()}))
