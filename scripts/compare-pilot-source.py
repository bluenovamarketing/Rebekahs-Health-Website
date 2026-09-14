"""Read-only comparison of supplied catalog workbook and recorded Revel map."""
import json
import argparse
import csv
from decimal import Decimal
from pathlib import Path
import openpyxl

root = Path(__file__).resolve().parents[1]
parser = argparse.ArgumentParser()
parser.add_argument('--woo-csv', type=Path, help='WooCommerce native product export; read only')
args = parser.parse_args()
source = root / 'client-inputs/phase-two/Product_Export_Establishment_3 (70)_results.xlsx'
book = openpyxl.load_workbook(source, read_only=True, data_only=True)
sheet = book['Sheet1']
values = list(sheet.values)
headers = values[0]
recorded = {}
for line in (root / 'PHASE-TWO-REVEL-KOSMOS-WOOCOMMERCE-DATA-MAP-2026-09-14.md').read_text(encoding='utf-8').splitlines():
    cells = [x.strip() for x in line.strip('|').split('|')]
    if len(cells) == 9 and cells[0].isdigit() and cells[1].isdigit():
        barcode = cells[2].strip('`')
        if barcode in recorded:
            raise ValueError('Duplicate map barcode')
        recorded[barcode] = cells
results = []
seen = set()
for row_number, values_row in enumerate(values[1:], 2):
    row = dict(zip(headers, values_row))
    barcode = str(row['Barcode']).strip()
    if barcode in seen:
        raise ValueError('Duplicate workbook barcode')
    seen.add(barcode)
    match = recorded.get(barcode)
    diffs = []
    if match:
        if ' '.join(str(row['Product Name']).split()) != ' '.join(match[4].split()):
            diffs.append('name')
        if Decimal(row['Price']) != Decimal(match[5].replace('$', '').replace(',', '')):
            diffs.append('price')
        if row['Product Category'] != match[7]:
            diffs.append('category')
        live_sku = '' if match[3] == 'blank' else match[3].strip('`')
        if str(row['SKU'] or '') != live_sku:
            diffs.append('SKU: workbook blank; recorded Revel ' + live_sku)
    results.append({'excel_row': row_number, 'barcode': barcode,
                    'recorded_revel_id': match[1] if match else None,
                    'description_present': bool(row['Product Description']),
                    'differences_from_recorded_map': diffs})
woo_audit = None
if args.woo_csv:
    with args.woo_csv.open(encoding='utf-8-sig', newline='') as handle:
        reader = csv.DictReader(handle)
        required = {'ID', 'SKU', 'Name', 'Published', 'Regular price'}
        if not required.issubset(reader.fieldnames or []):
            raise ValueError('Not a recognized WooCommerce export: required headers missing')
        products = list(reader)
    by_sku = {}
    for product in products:
        by_sku.setdefault(product['SKU'].strip(), []).append(product)
    woo_audit = []
    for result in results:
        matches = by_sku.get(result['barcode'], [])
        woo_audit.append({'barcode': result['barcode'], 'match_count': len(matches),
                          'matches': [{key: product.get(key, '') for key in
                                      ['ID', 'SKU', 'Name', 'Published', 'Regular price',
                                       'Stock', 'In stock?', 'Description', 'Short description',
                                       'Weight (oz)', 'Weight (lbs)', 'Weight (kg)', 'Categories', 'Images']}
                                     for product in matches]})
print(json.dumps({'workbook_rows': len(results), 'unique_barcodes': len(seen),
                  'matched_recorded_ids': sum(bool(x['recorded_revel_id']) for x in results),
                  'descriptions_present': sum(x['description_present'] for x in results),
                  'rows': results,
                  'woo_audit': woo_audit,
                  'limitation': 'Map comparison is not a fresh live audit and does not prove workbook changes were imported into Revel.'}, indent=2))
