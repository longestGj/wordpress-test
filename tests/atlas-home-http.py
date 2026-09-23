"""Real HTTP and browser acceptance for the isolated TiO2 Atlas Home preview."""
import json
import os
import unittest
from pathlib import Path
from urllib.parse import urljoin, urlparse

import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright

ROOT = Path(__file__).resolve().parents[1]
BASE = os.environ.get("ATLAS_BASE", "http://localhost:18081").rstrip("/")
ROWS = json.loads((ROOT / "data/product-discovery.json").read_text(encoding="utf-8"))["rows"]
SHOT_DIR = ROOT / ".local/atlas-http"


class AtlasHomeHttpTest(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.response = requests.get(BASE + "/", timeout=20)
        cls.soup = BeautifulSoup(cls.response.text, "html.parser")
        cls.playwright = sync_playwright().start()
        cls.browser = cls.playwright.chromium.launch()

    @classmethod
    def tearDownClass(cls):
        cls.browser.close()
        cls.playwright.stop()

    def test_home_identity_and_seo(self):
        self.assertEqual(self.response.status_code, 200)
        self.assertEqual(
            self.soup.title.get_text(strip=True),
            "Titanium Dioxide Products from Malaysia | TiO2 Atlas",
        )
        self.assertEqual(
            self.soup.select_one("h1").get_text(" ", strip=True),
            "Titanium dioxide products from Malaysia, mapped to your next decision.",
        )
        self.assertIn(
            "TiO2 Atlas",
            self.soup.select_one('meta[name="description"]')["content"],
        )
        self.assertEqual(
            self.soup.select_one('link[rel="canonical"]')["href"],
            BASE + "/",
        )
        self.assertIn("noindex", self.soup.select_one('meta[name="robots"]')["content"])
        self.assertEqual(
            self.soup.select_one("header img.logo")["alt"], "TiO2 Atlas"
        )
        self.assertIn("atlas-logo.svg", self.soup.select_one("header img.logo")["src"])
        self.assertIn("atlas-logo-reverse.svg", self.soup.select_one("footer img.logo")["src"])
        self.assertIn("atlas-mark.svg", self.soup.select_one('link[rel="icon"]')["href"])
        self.assertNotIn("homepage-hero-tio2-material-v0.6.png", self.response.text)
        self.assertNotIn("TiO2 Malaysia", self.soup.select_one("main").get_text(" ", strip=True))

    def test_all_home_links_are_live_and_grades_match(self):
        links = self.soup.select("#atlas-grade-index a[data-grade]")
        self.assertEqual(len(links), 14)
        self.assertEqual(
            {a.get_text(strip=True): a["href"] for a in links},
            {row["grade"]: row["url"] for row in ROWS},
        )
        paths = set()
        for a in self.soup.select("main a[href]"):
            target = urljoin(BASE + "/", a["href"])
            parsed = urlparse(target)
            if parsed.netloc == urlparse(BASE).netloc:
                paths.add(parsed.path)
        for path in sorted(paths):
            with self.subTest(path=path):
                response = requests.get(BASE + path, timeout=20)
                self.assertEqual(response.status_code, 200, path)

    def test_other_page_keeps_existing_identity(self):
        response = requests.get(BASE + "/about/", timeout=20)
        self.assertEqual(response.status_code, 200)
        soup = BeautifulSoup(response.text, "html.parser")
        self.assertEqual(soup.select_one("header img.logo")["alt"], "TiO2 Malaysia")
        self.assertIn("/assets/logo.svg", soup.select_one("header img.logo")["src"])
        self.assertNotIn("atlas-logo", soup.select_one("footer img.logo")["src"])

    def test_responsive_keyboard_and_screenshots(self):
        SHOT_DIR.mkdir(parents=True, exist_ok=True)
        for width in (1440, 768, 390):
            with self.subTest(width=width):
                page = self.browser.new_page(viewport={"width": width, "height": 900})
                try:
                    page.goto(BASE + "/", wait_until="networkidle")
                    self.assertLessEqual(
                        page.evaluate("document.documentElement.scrollWidth"), width
                    )
                    self.assertEqual(page.locator("#atlas-grade-index a[data-grade]").count(), 14)
                    if width == 1440:
                        current = page.locator('.desktopNav a[aria-current="page"]')
                        self.assertEqual(
                            current.evaluate(
                                "(el) => getComputedStyle(el).borderBottomColor"
                            ),
                            "rgb(156, 61, 34)",
                        )
                    hero_link = page.locator("#atlas-hero a").first
                    hero_link.focus()
                    self.assertNotEqual(
                        hero_link.evaluate("(el) => getComputedStyle(el).outlineStyle"),
                        "none",
                    )
                    if width == 390:
                        trigger = page.locator(".menuButton")
                        self.assertTrue(trigger.is_visible())
                        trigger.focus()
                        page.keyboard.press("Enter")
                        self.assertTrue(page.locator("#site-menu").is_visible())
                        page.keyboard.press("Escape")
                        self.assertFalse(page.locator("#site-menu").is_visible())
                        self.assertTrue(trigger.evaluate("(el) => document.activeElement === el"))
                    page.screenshot(path=str(SHOT_DIR / f"home-{width}.png"), full_page=True)
                finally:
                    page.close()


if __name__ == "__main__":
    unittest.main()
