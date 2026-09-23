"""Read-only local contract for the bounded Plastics detail refinement."""
import json
import sys
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright


base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8086').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
path = '/applications/titanium-dioxide-for-plastics/'
seed = json.loads((Path(__file__).resolve().parents[1] / 'data/process-applications/plastics.json').read_text(encoding='utf-8'))
assert seed['expected_text'] == BeautifulSoup(seed['content'], 'html.parser').get_text(' ', strip=True)

response = requests.get(base + path, timeout=20)
response.raise_for_status()
page = BeautifulSoup(response.text, 'html.parser')
main = page.select_one('main.topic-plastics')
assert main and [h.get_text(' ', strip=True) for h in main.select('h1')] == ['Titanium Dioxide for Plastics']
assert page.title.get_text(strip=True) == 'Titanium Dioxide for Plastics | Grade Evaluation'
assert page.select_one('link[rel="canonical"]')['href'] == base + path
assert page.select_one('meta[property="og:url"]')['content'] == base + path
assert page.select_one('meta[property="og:title"]')['content'] == page.title.get_text(strip=True)

crumb = main.select_one('.hero-section nav.breadcrumb[aria-label="Breadcrumb"]')
assert crumb
assert [(a.get_text(' ', strip=True), a['href']) for a in crumb.select('a')] == [
    ('Home', '/'), ('Applications', '/applications/')
]
assert crumb.select_one('[aria-current="page"]').get_text(' ', strip=True) == 'Plastics'
assert crumb.select_one('[aria-current="page"]').name != 'a'
article = main.select_one('article.buyer-copy')
assert [child.get('class', [''])[0] for child in article.find_all(recursive=False)[:3]] == [
    'wp-block-group', 'plasticsGlance', 'wp-block-group'
]
glance = main.select_one('.plasticsGlance')
assert glance.select_one('h2').get_text(' ', strip=True) == 'Plastics Evaluation at a Glance'
assert [h.get_text(' ', strip=True) for h in glance.select('h3')] == [
    'Material system', 'TiO₂ introduction route', 'Processing', 'Optical',
    'Dispersion & defects', 'Exposure', 'Grades to Review'
]
assert glance.select_one('a[href="#grades-to-review"]')

grades = main.select('.module-plas-10 tbody tr')
expected = ['M-350', 'M-510', 'M-200', 'M-108', 'M-210', 'M-340', 'M-886', 'M-2377']
assert [row.select('td')[0].select_one('.cell-value').get_text(strip=True) for row in grades] == expected
assert [row.select('td')[1].select_one('.cell-value').get_text(strip=True) for row in grades] == [
    'Chloride', 'Chloride', 'Chloride', 'Sulfate', 'Chloride', 'Chloride', 'Chloride', 'Sulfate'
]
for row, grade in zip(grades, expected, strict=True):
    route = '/products/' + grade.lower() + '/'
    assert row.select_one('a')['href'] == route
    requests.get(base + route, timeout=20).raise_for_status()

graphs = []
for tag in page.select('script[type="application/ld+json"]'):
    value = json.loads(tag.string)
    graphs.extend(value.get('@graph', [value]) if isinstance(value, dict) else value)
assert any(node.get('@type') == 'WebPage' and node.get('url') == base + path for node in graphs)
bread_schema = next(node for node in graphs if node.get('@type') == 'BreadcrumbList')
assert [item['name'] for item in bread_schema['itemListElement']] == [
    'Home', 'Applications', 'Titanium Dioxide for Plastics'
]
items = next(node['itemListElement'] for node in graphs if node.get('@type') == 'ItemList')
assert [(item['@type'], item['position'], item['name'], item['url']) for item in items] == [
    ('ListItem', index, grade, base + '/products/' + grade.lower() + '/')
    for index, grade in enumerate(expected, 1)
]

rfq = next(li for li in main.select('.module-plas-11 li') if li.select_one('a[href="/request-a-quote/"]'))
rfq_text = rfq.get_text(' ', strip=True).lower()
assert not rfq.select('code')
assert 'not sure / need help' not in rfq_text and 'additional requirements' not in rfq_text
assert all(term in rfq_text for term in (
    'application', 'resin', 'introduction route', 'conversion process', 'quantity',
    'destination', 'evaluation requirements', 'candidate grades', 'suitability'
))
assert main.select_one('a[href="/applications/titanium-dioxide-for-masterbatch/"]')
requests.get(base + '/applications/titanium-dioxide-for-masterbatch/', timeout=20).raise_for_status()
assert len(main.select('.module-plas-12 a[href^="http"]')) == 13

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for width in (1440, 768, 390):
        tab = browser.new_page(viewport={'width': width, 'height': 900})
        errors = []
        tab.on('pageerror', lambda error: errors.append(str(error)))
        tab.goto(base + path, wait_until='networkidle')
        assert tab.locator('body').evaluate('(body) => body.scrollWidth <= innerWidth'), width
        assert tab.locator('.plasticsGlance').evaluate('(el) => el.scrollWidth <= el.clientWidth'), width
        assert tab.locator('.module-plas-10 tbody tr').count() == 8
        for row in tab.locator('.module-plas-10 tbody tr').all():
            assert row.is_visible() and row.evaluate('(el) => el.scrollWidth <= el.clientWidth'), width
        assert not errors, (width, errors)
        tab.close()
    browser.close()

print('PASS: Plastics breadcrumb, glance, grade/schema mapping, RFQ, sources and responsive layout')
