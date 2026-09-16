"""Read-only Kosmos/Woo reconciliation. Prints no URLs, credentials or payloads."""
import argparse
import csv
import json
import re
from pathlib import Path


def reconcile(log_path, before_path, after_path):
    raw = Path(log_path).read_text(encoding="utf-8-sig")
    start = re.search(r"(?m)^\s*\[\s*$", raw)
    if not start:
        raise ValueError("No report array found")
    entries = json.loads(raw[start.start():])
    def catalog(path):
        with open(path, encoding="utf-8-sig", newline="") as stream:
            rows = list(csv.DictReader(stream))
        ids = [r["ID"] for r in rows]
        if len(ids) != len(set(ids)):
            raise ValueError("Duplicate catalog IDs")
        return {r["ID"]: r for r in rows}
    before, after = catalog(before_path), catalog(after_path)
    updated = set()
    errors = 0
    for entry in entries:
        method = entry.get("method", "") or ""
        errors += bool(re.search(r"\s[45]\d\d$", method))
        if method == "PUT 200":
            match = re.search(r"/products/(\d+)(?:\?.*)?$", entry.get("url", ""))
            if match:
                updated.add(match.group(1))
    rows = []
    for product_id in sorted(updated, key=int):
        previous, current = before.get(product_id), after.get(product_id)
        stock = current.get("Stock", "") if current else None
        rows.append({
            "id": product_id,
            "present_after": current is not None,
            "before_stock": previous.get("Stock") if previous else None,
            "after_stock": stock,
            "published": current.get("Published") if current else None,
            "quantity_missing_after_success": stock in (None, ""),
            "publication_changed": bool(previous and current and previous["Published"] != current["Published"]),
        })
    return {
        "successful_update_ids": len(updated),
        "http_errors": errors,
        "missing_quantity_after_success": sum(r["quantity_missing_after_success"] for r in rows),
        "all_publication_changes": sum(before[k]["Published"] != after[k]["Published"] for k in before.keys() & after.keys()),
        "published_blank_stock_after": sum(r["Published"] == "1" and r["Stock"] == "" for r in after.values()),
        "rows": rows,
        "limitation": "Confirms exported Woo state, not current Revel accuracy or frontend purchase behavior.",
    }


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--log", required=True)
    parser.add_argument("--before", required=True)
    parser.add_argument("--after", required=True)
    args = parser.parse_args()
    print(json.dumps(reconcile(args.log, args.before, args.after), indent=2))
