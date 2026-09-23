"""Content contract for the TiO2 Atlas homepage initialization seed."""
import json
import unittest
from pathlib import Path

from bs4 import BeautifulSoup

ROOT = Path(__file__).resolve().parents[1]
HOME = json.loads((ROOT / "data/pages/home.json").read_text(encoding="utf-8"))
ROWS = json.loads((ROOT / "data/product-discovery.json").read_text(encoding="utf-8"))["rows"]
SOUP = BeautifulSoup(HOME["content"], "html.parser")
H1 = "Titanium dioxide products from Malaysia, mapped to your next decision."
SECTIONS = (
    "atlas-hero", "atlas-paths", "atlas-grade-index",
    "atlas-procurement", "atlas-company", "atlas-rfq",
)


class AtlasHomeContentTest(unittest.TestCase):
    def test_page_structure_and_brand(self):
        self.assertEqual([h.get_text(" ", strip=True) for h in SOUP.select("h1")], [H1])
        self.assertEqual([s["id"] for s in SOUP.select("section[id]")], list(SECTIONS))
        self.assertEqual(HOME["main_class"], "hub hub-home atlas-home")
        self.assertEqual(HOME["seo_title"], "Titanium Dioxide Products from Malaysia | TiO2 Atlas")
        self.assertNotIn("TiO2 Malaysia", HOME["content"])
        self.assertNotIn("homepage-hero-tio2-material-v0.6.png", HOME["content"])

    def test_all_approved_grades_have_unique_correct_routes(self):
        links = SOUP.select("#atlas-grade-index a[data-grade]")
        self.assertEqual(len(links), 14)
        actual = {a.get_text(strip=True): a["href"] for a in links}
        self.assertEqual(actual, {row["grade"]: row["url"] for row in ROWS})
        self.assertEqual(len({a["href"] for a in links}), 14)

    def test_buying_paths_and_company_are_available(self):
        hrefs = {a["href"] for a in SOUP.select("a[href]")}
        for path in (
            "/products/", "/applications/", "/markets/", "/documents/",
            "/request-a-quote/", "/about/",
        ):
            self.assertIn(path, hrefs)
        self.assertIn("IKHLAS TITANIUM (MALAYSIA) SDN. BHD.", HOME["content"])


if __name__ == "__main__":
    unittest.main()
