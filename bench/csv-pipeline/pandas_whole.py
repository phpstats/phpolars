import json
import resource
import sys
import time

import pandas

path = sys.argv[1]
start = time.perf_counter()

orders = pandas.read_csv(
    path,
    usecols=[
        "placed_on", "region", "status", "quantity", "amount",
    ],
)
completed = orders[orders["status"] == "completed"]
summary = (
    completed
    .assign(month=completed["placed_on"].str.slice(0, 7))
    .groupby(["region", "month"], sort=True)
    .agg(
        orders=("status", "size"),
        units=("quantity", "sum"),
        revenue=("amount", "sum"),
        mean_amount=("amount", "mean"),
    )
    .reset_index()
)

rows = [[r.region, r.month, int(r.orders), int(r.units), float(r.revenue), float(r.mean_amount)] for r in summary.itertuples()]
ms = (time.perf_counter() - start) * 1000
print(json.dumps({"ms": ms, "maxrss": resource.getrusage(resource.RUSAGE_SELF).ru_maxrss, "rows": rows}))
