"""Check the Home visual contract with the shipped WordPress content and styles."""
import json
import unittest
from pathlib import Path

from playwright.sync_api import sync_playwright


ROOT = Path(__file__).resolve().parents[1]
ASSETS = ROOT / 'wp-content' / 'themes' / 'tio2' / 'assets'
HOME = json.loads((ROOT / 'data' / 'pages' / 'home.json').read_text(encoding='utf-8'))


class HomeVisualLayoutTest(unittest.TestCase):
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
        styles = ''.join(f'<style>{(ASSETS / name).read_text(encoding="utf-8")}</style>'
                         for name in ('site.css', 'hub-home.css', 'hubs.css'))
        page.set_content(f'<html><head>{styles}</head><body>'
                         f'<main class="{HOME["main_class"]}">{HOME["content"]}</main>'
                         '</body></html>', wait_until='domcontentloaded')
        return page

    def test_desktop_material_hero_reaches_the_right_edge(self):
        page = self.page(1440)
        try:
            hero = page.locator('main .hero')
            background = hero.evaluate('(element) => getComputedStyle(element).backgroundColor')
            self.assertEqual(background, 'rgb(3, 27, 58)')
            self.assertGreaterEqual(page.locator('.hero-media').bounding_box()['x']
                                    + page.locator('.hero-media').bounding_box()['width'], 1438)
        finally:
            page.close()

    def test_narrow_desktop_hero_image_reaches_the_bottom(self):
        page = self.page(1024)
        try:
            hero = page.locator('main .hero').bounding_box()
            media = page.locator('.hero-media').bounding_box()
            self.assertLessEqual(abs((hero['y'] + hero['height'])
                                     - (media['y'] + media['height'])), 1)
        finally:
            page.close()

    def test_mobile_rfq_remains_a_visible_closing_action(self):
        for width in (320, 390):
            with self.subTest(width=width):
                page = self.page(width)
                try:
                    self.assertTrue(page.locator('.page-rfq').is_visible())
                    self.assertTrue(page.locator('.page-rfq .primary').is_visible())
                    self.assertEqual(page.locator('.page-rfq .primary').get_attribute('href'),
                                     '/request-a-quote/')
                    bounds = page.locator('.page-rfq').bounding_box()
                    self.assertEqual(bounds['x'], 0)
                    self.assertEqual(bounds['width'], width)
                finally:
                    page.close()

    def test_common_viewports_have_no_horizontal_overflow(self):
        for width in (320, 390, 768, 1024, 1440):
            with self.subTest(width=width):
                page = self.page(width)
                try:
                    overflow = page.evaluate('document.documentElement.scrollWidth '
                                             '- document.documentElement.clientWidth')
                    self.assertLessEqual(overflow, 0)
                finally:
                    page.close()

    def test_small_phone_hero_heading_uses_three_lines(self):
        page = self.page(320)
        try:
            lines = page.locator('h1').evaluate('''heading => Math.round(
                heading.getBoundingClientRect().height /
                parseFloat(getComputedStyle(heading).lineHeight))''')
            self.assertLessEqual(lines, 3)
        finally:
            page.close()

    def test_small_phone_hero_actions_have_equal_heights(self):
        page = self.page(320)
        try:
            actions = page.locator('.hero-actions a')
            self.assertEqual(actions.nth(0).bounding_box()['height'],
                             actions.nth(1).bounding_box()['height'])
        finally:
            page.close()


if __name__ == '__main__':
    unittest.main()
