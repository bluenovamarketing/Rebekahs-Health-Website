"""Read-only Woo export readiness audit. Outputs counts, never raw catalog data."""
import argparse
import csv
import json
from collections import Counter


def audit(rows):
    required = {'ID', 'Type', 'SKU', 'Published', 'Stock', 'Description', 'Short description', 'Brands', 'Images'}
    if not rows or not required.issubset(rows[0]):
        raise ValueError('Empty export or missing required WooCommerce columns')
    weight_fields = [key for key in rows[0] if key.startswith('Weight (')]
    if len(weight_fields) != 1:
        raise ValueError('Expected exactly one explicitly labelled weight-unit column')
    published = [row for row in rows if row['Published'] == '1' and row['Type'] != 'variation']
    skus = Counter(row['SKU'] for row in rows if row['SKU'])
    marker = 'RHN-20260914'
    return {
        'export_rows': len(rows),
        'published_parent_products': len(published),
        'duplicate_ids': len(rows) - len({row['ID'] for row in rows}),
        'duplicate_nonempty_skus': sum(count > 1 for count in skus.values()),
        'published_blank_stock': sum(not row['Stock'].strip() for row in published),
        'published_zero_stock': sum(row['Stock'].strip() in ('0', '0.0', '0.00') for row in published),
        'published_blank_weight': sum(not row[weight_fields[0]].strip() for row in published),
        'weight_column': weight_fields[0],
        'published_blank_brand': sum(not row['Brands'].strip() for row in published),
        'published_blank_images': sum(not row['Images'].strip() for row in published),
        'published_test_marker': sum(marker in row['Description'] or marker in row['Short description'] for row in published),
        'limitations': 'Snapshot only. Blank is not zero. Nonblank fields are not proof of accuracy or approved content. No current-site or Revel verification.',
    }


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('export')
    args = parser.parse_args()
    with open(args.export, encoding='utf-8-sig', newline='') as source:
        print(json.dumps(audit(list(csv.DictReader(source))), indent=2))
