"""Generate the editable Home initialization seed from the Atlas source HTML."""
import hashlib
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
seed_path = ROOT / "data/pages/home.json"
html_path = ROOT / "data/pages/home-atlas.html"

seed = json.loads(seed_path.read_text(encoding="utf-8"))
old_content = seed["content"]
was_atlas = seed.get("main_class") == "hub hub-home atlas-home"

seed["content"] = "<!-- wp:html -->\n" + html_path.read_text(encoding="utf-8").strip() + "\n<!-- /wp:html -->"
seed["main_class"] = "hub hub-home atlas-home"
seed["seo_title"] = "Titanium Dioxide Products from Malaysia | TiO2 Atlas"
seed["seo_description"] = (
    "Explore titanium dioxide grades from Malaysia by application, "
    "product group and destination market with TiO2 Atlas."
)
seed["h1"] = "Titanium dioxide products from Malaysia, mapped to your next decision."
seed["source"] = "pages/home/04_planning/visual-designs/home-root-page-hero-v1.4/homepage-root-page-hero-preview-v1.4.html"
if not was_atlas:
    seed["previous_content_hash"] = hashlib.sha256(old_content.encode("utf-8")).hexdigest()

seed_path.write_text(json.dumps(seed, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")
