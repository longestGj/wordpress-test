"""Read-only checks against an installed starter, no database changes."""
import argparse
import re
from urllib.request import urlopen
from urllib.error import HTTPError

parser = argparse.ArgumentParser()
parser.add_argument("--base-url", required=True)
args = parser.parse_args()
base = args.base_url.rstrip("/")
with urlopen(base + "/", timeout=20) as response:
    html = response.read().decode()
assert len(re.findall(r"<title[ >]", html)) == 1, "Expected a single title"
assert 'id="main-content"' in html and 'href="#main-content"' in html, "Accessible main and skip link"
assert re.search(r'<meta[^>]+name=[\x27"]robots[\x27"][^>]+noindex', html), "Local noindex required"
for forbidden in ("Titanium Dioxide", "M-350", "tio2", "D:/23MySec", "D:/33wordpress"):
    assert forbidden.lower() not in html.lower(), forbidden
try:
    urlopen(base + "/?p=999999999", timeout=20)
    raise AssertionError("Missing object must return 404")
except HTTPError as exc:
    assert exc.code == 404, exc.code
print("PASS: Core output, noindex, no business binding, and real 404.")
