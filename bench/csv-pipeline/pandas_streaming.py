import json
import resource
import sys
import time

import pandas

path = sys.argv[1]
start = time.perf_counter()

parts = []
for chunk in pandas.read_csv(path, usecols=["placed_on", "region", "status", "quantity", "amount"], chunksize=100_000):
    completed = chunk[chunk["status"] == "completed"]
    parts.append(
        completed.assign(month=completed["placed_on"].str.slice(0, 7), amounts=completed["amount"].notna())
        .groupby(["region", "month"])
        .agg(orders=("status", "size"), units=("quantity", "sum"), revenue=("amount", "sum"), amounts=("amounts", "sum"))
    )
totals = pandas.concat(parts).groupby(level=["region", "month"]).sum()
summary = totals.assign(mean_amount=totals["revenue"] / totals["amounts"]).reset_index().sort_values(["region", "month"])

rows = [[r.region, r.month, int(r.orders), int(r.units), float(r.revenue), float(r.mean_amount)] for r in summary.itertuples()]
ms = (time.perf_counter() - start) * 1000
print(json.dumps({"ms": ms, "maxrss": resource.getrusage(resource.RUSAGE_SELF).ru_maxrss, "rows": rows}))
