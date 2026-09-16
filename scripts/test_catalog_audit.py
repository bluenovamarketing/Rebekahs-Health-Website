import unittest
from importlib.util import spec_from_file_location, module_from_spec
from pathlib import Path

spec = spec_from_file_location('audit', Path(__file__).with_name('audit-staging-catalog.py'))
module = module_from_spec(spec)
spec.loader.exec_module(module)


class CatalogAuditTests(unittest.TestCase):
    def row(self, **changes):
        row = dict(ID='1', Type='simple', SKU='00123', Published='1', Stock='', Description='', Brands='', Images='')
        row.update({'Short description': '', 'Weight (oz)': ''})
        row.update(changes)
        return row

    def test_blank_stock_is_not_zero(self):
        result = module.audit([self.row(), self.row(ID='2', SKU='00456', Stock='0')])
        self.assertEqual(result['published_blank_stock'], 1)
        self.assertEqual(result['published_zero_stock'], 1)

    def test_drafts_and_variations_excluded_from_parent_readiness(self):
        result = module.audit([self.row(Published='-1'), self.row(ID='2', Type='variation')])
        self.assertEqual(result['published_parent_products'], 0)

    def test_marker_and_duplicates_detected(self):
        row = self.row(**{'Short description': 'RHN-20260914'})
        result = module.audit([row, dict(row)])
        self.assertEqual(result['published_test_marker'], 2)
        self.assertEqual(result['duplicate_ids'], 1)
        self.assertEqual(result['duplicate_nonempty_skus'], 1)

    def test_empty_input_rejected(self):
        with self.assertRaises(ValueError):
            module.audit([])

    def test_ambiguous_weight_units_rejected(self):
        with self.assertRaises(ValueError):
            module.audit([self.row(**{'Weight (kg)': '1'})])


if __name__ == '__main__':
    unittest.main()
