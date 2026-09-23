"""Exercise the homepage product cards in a real browser using the shipped seed and assets."""
import json
import unittest
from pathlib import Path

from playwright.sync_api import sync_playwright


ROOT = Path(__file__).resolve().parents[1]
ASSETS = ROOT / 'wp-content' / 'themes' / 'tio2' / 'assets'
HOME = json.loads((ROOT / 'data' / 'pages' / 'home.json').read_text(encoding='utf-8'))


class HomeProductCardsTest(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.playwright = sync_playwright().start()
        cls.browser = cls.playwright.chromium.launch()

    @classmethod
    def tearDownClass(cls):
        cls.browser.close()
        cls.playwright.stop()

    def page(self, width):
        page = self.browser.new_page(viewport={'width': width, 'height': 900})
        css = (ASSETS / 'hub-home.css').read_text(encoding='utf-8')
        js = (ASSETS / 'hubs.js').read_text(encoding='utf-8')
        page.set_content(
            f'<html><head><style>{css}</style></head><body>'
            f'<main class="{HOME["main_class"]}">{HOME["content"]}</main>'
            f'<script>{js}</script></body></html>',
            wait_until='domcontentloaded',
        )
        return page

    def test_desktop_group_stays_visible_when_title_is_clicked(self):
        page = self.page(1440)
        try:
            group = page.locator('.product-group').first
            self.assertTrue(group.locator('.product-body').is_visible())
            group.locator('summary').click()
            self.assertTrue(group.locator('.product-body').is_visible())
            self.assertIsNotNone(group.get_attribute('open'))
        finally:
            page.close()

    def test_mobile_group_can_open_and_close(self):
        page = self.page(390)
        try:
            group = page.locator('.product-group').first
            self.assertFalse(group.locator('.product-body').is_visible())
            group.locator('summary').click()
            self.assertTrue(group.locator('.product-body').is_visible())
            group.locator('summary').click()
            self.assertFalse(group.locator('.product-body').is_visible())
        finally:
            page.close()

    def test_all_fourteen_grades_link_to_their_detail_pages(self):
        page = self.page(1440)
        try:
            expected = {
                'M-350': '/products/m-350/', 'M-510': '/products/m-510/',
                'M-896': '/products/m-896/', 'M-996': '/products/m-996/',
                'M-2196': '/products/m-2196/', 'M-895': '/products/m-895/',
                'M-200': '/products/m-200/', 'M-108': '/products/m-108/',
                'M-210': '/products/m-210/', 'M-340': '/products/m-340/',
                'M-886': '/products/m-886/', 'M-52': '/products/m-52/',
                'M-2377': '/products/m-2377/', 'CR-901': '/products/cr-901/',
            }
            links = page.locator('.product-grid .grades a')
            self.assertEqual(links.count(), 14)
            actual = {link.inner_text(): link.get_attribute('href') for link in links.all()}
            self.assertEqual(actual, expected)
        finally:
            page.close()


if __name__ == '__main__':
    unittest.main()
