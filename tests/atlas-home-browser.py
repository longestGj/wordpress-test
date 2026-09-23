"""Browser contract for the TiO2 Atlas Home artwork and responsive layout."""
import json
import unittest
from pathlib import Path

from playwright.sync_api import sync_playwright

ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / "wp-content/themes/tio2"
ASSETS = THEME / "assets"
HOME = json.loads((ROOT / "data/pages/home.json").read_text(encoding="utf-8"))
SECTIONS = [
    "atlas-hero", "atlas-paths", "atlas-grade-index",
    "atlas-procurement", "atlas-company", "atlas-rfq",
]


class AtlasHomeBrowserTest(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.playwright = sync_playwright().start()
        cls.browser = cls.playwright.chromium.launch()

    @classmethod
    def tearDownClass(cls):
        cls.browser.close()
        cls.playwright.stop()

    def render(self, width):
        page = self.browser.new_page(viewport={"width": width, "height": 900})
        css = (ASSETS / "site.css").read_text(encoding="utf-8")
        css += "\n" + (ASSETS / "hub-home.css").read_text(encoding="utf-8")
        page.set_content(
            f'<html><head><style>{css}</style></head><body><main id="main" class="{HOME["main_class"]}">'
            f'{HOME["content"]}</main></body></html>'
        )
        return page

    def test_identity_assets_are_separate_from_old_brand(self):
        for name in ("atlas-logo.svg", "atlas-logo-reverse.svg", "atlas-mark.svg"):
            self.assertTrue((ASSETS / name).is_file(), name)
        self.assertIn("is_front_page()", (THEME / "header.php").read_text(encoding="utf-8"))
        self.assertIn("is_front_page()", (THEME / "footer.php").read_text(encoding="utf-8"))

    def test_layout_and_focus_at_target_widths(self):
        for width in (1440, 768, 390):
            with self.subTest(width=width):
                page = self.render(width)
                try:
                    self.assertEqual(
                        [s.get_attribute("id") for s in page.locator("main > section").all()],
                        SECTIONS,
                    )
                    self.assertEqual(page.locator("#atlas-grade-index a[data-grade]").count(), 14)
                    self.assertLessEqual(
                        page.evaluate("document.documentElement.scrollWidth"), width
                    )
                    self.assertEqual(
                        page.locator("main").evaluate("(el) => getComputedStyle(el).backgroundColor"),
                        "rgb(247, 244, 236)",
                    )
                    self.assertEqual(
                        page.locator("#atlas-grade-index h2").evaluate("(el) => getComputedStyle(el).color"),
                        "rgb(247, 244, 236)",
                    )
                    self.assertEqual(
                        page.locator("#atlas-grade-index h3").first.evaluate("(el) => getComputedStyle(el).color"),
                        "rgb(247, 244, 236)",
                    )
                    link = page.locator("#atlas-hero a").first
                    link.focus()
                    self.assertNotEqual(
                        link.evaluate("(el) => getComputedStyle(el).outlineStyle"), "none"
                    )
                finally:
                    page.close()


if __name__ == "__main__":
    unittest.main()
