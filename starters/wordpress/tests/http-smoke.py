"""Read-only checks against an installed starter, no database changes."""
import argparse
import re
from html.parser import HTMLParser
from urllib.request import urlopen
from urllib.error import HTTPError

parser = argparse.ArgumentParser()
parser.add_argument("--base-url", required=True)
parser.add_argument("--page-path", required=True, help="Path of a saved Core Page, e.g. /sample-page/")
parser.add_argument("--expected-h1", required=True)
parser.add_argument("--expected-text", required=True, help="Known excerpt of the saved body, not its heading")
args = parser.parse_args()
if not args.page_path.startswith('/') or args.page_path.startswith('//'):
    parser.error('--page-path must be a path on the tested site')
if not args.expected_h1.strip() or not args.expected_text.strip():
    parser.error('Expected heading and body excerpt must be nonempty')
base = args.base_url.rstrip("/")
with urlopen(base + "/", timeout=20) as response:
    html = response.read().decode()
assert len(re.findall(r"<title[ >]", html)) == 1, "Expected a single title"
assert 'id="main-content"' in html and 'href="#main-content"' in html, "Accessible main and skip link"
assert re.search(r'<meta[^>]+name=[\x27"]robots[\x27"][^>]+noindex', html), "Local noindex required"
for forbidden in ("Titanium Dioxide", "M-350", "tio2", "D:/23MySec", "D:/33wordpress"):
    assert forbidden.lower() not in html.lower(), forbidden


class PageText(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.main = self.heading = False
        self.ignore = 0
        self.headings = []
        self.body = []

    def handle_starttag(self, tag, attrs):
        if tag == 'main':
            self.main = True
        if tag in ('script', 'style'):
            self.ignore += 1
        if self.main and tag == 'h1':
            self.heading = True
            self.headings.append([])

    def handle_endtag(self, tag):
        if tag == 'h1':
            self.heading = False
        if tag == 'main':
            self.main = False
        if tag in ('script', 'style'):
            self.ignore = max(0, self.ignore - 1)

    def handle_data(self, data):
        if self.main and not self.ignore:
            (self.headings[-1] if self.heading else self.body).append(data)


def normalize(parts):
    return ' '.join(''.join(parts).split())


with urlopen(base + args.page_path, timeout=20) as response:
    page = PageText()
    page.feed(response.read().decode())
assert [normalize(h) for h in page.headings] == [normalize([args.expected_h1])], 'Page H1 mismatch'
assert normalize([args.expected_text]) in normalize(page.body), 'Page body mismatch'
try:
    urlopen(base + "/?p=999999999", timeout=20)
    raise AssertionError("Missing object must return 404")
except HTTPError as exc:
    assert exc.code == 404, exc.code
print("PASS: saved Page H1/body, Core shell, noindex, no business binding, and real 404.")
