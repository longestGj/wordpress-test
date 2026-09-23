"""Read-only local contract for the bounded Printing Inks detail refinement."""
import csv
import json
import sys
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright


base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8088').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
path = '/applications/titanium-dioxide-for-printing-inks/'
root = Path(__file__).resolve().parents[1]
seed = json.loads((root / 'data/process-applications/printing-inks.json').read_text(encoding='utf-8'))
assert seed['expected_text'] == BeautifulSoup(seed['content'], 'html.parser').get_text(' ', strip=True)

response = requests.get(base + path, timeout=20)
response.raise_for_status()
page = BeautifulSoup(response.text, 'html.parser')
main = page.select_one('main.topic-printing-inks')
assert main and [h.get_text(' ', strip=True) for h in main.select('h1')] == ['Titanium Dioxide for Printing Inks']
assert page.title.get_text(strip=True) == 'Titanium Dioxide for Printing Inks | Grade Evaluation'
description = page.select_one('meta[name="description"]')['content'].lower()
assert all(term in description for term in ('titanium dioxide', 'printing inks', 'white', 'opacity', 'dispersion', 'rheology', 'grades'))
assert page.select_one('link[rel="canonical"]')['href'] == base + path
assert page.select_one('meta[property="og:url"]')['content'] == base + path
assert page.select_one('meta[property="og:title"]')['content'] == page.title.get_text(strip=True)

hero = main.select_one('.hero')
hero_text = hero.get_text(' ', strip=True).lower()
assert all(term in hero_text for term in (
    'white printing-ink', 'printed white layer', 'new grade', 'incumbent', 'vehicle',
    'printing process', 'substrate', 'deposited layer', 'drying or cure',
    'end use', 'measurement basis', 'acceptance', 'suitability'
))
crumb = hero.select_one('nav.breadcrumb[aria-label="Breadcrumb"]')
assert crumb
assert [(a.get_text(' ', strip=True), a['href']) for a in crumb.select('a')] == [
    ('Home', '/'), ('Applications', '/applications/')
]
assert crumb.select_one('[aria-current="page"]').get_text(' ', strip=True) == 'Titanium Dioxide for Printing Inks'
assert crumb.select_one('[aria-current="page"]').name != 'a'
assert [child.get('class', [''])[0] for child in main.find_all(recursive=False)[:3]] == [
    'wp-block-group', 'inksGlance', 'contentSection'
]
glance = main.select_one('.inksGlance')
assert glance.select_one('h2').get_text(' ', strip=True) == 'Printing Inks Evaluation at a Glance'
assert [h.get_text(' ', strip=True) for h in glance.select('h3')] == [
    'Ink system', 'Printing process', 'Substrate & printed layer', 'Optical result',
    'Dispersion & rheology', 'Grind', 'Grades to Review'
]
assert glance.select_one('a[href="#grades-to-review"]')

grades = main.select('.grades tbody tr')
expected = ['M-350', 'M-510', 'M-52', 'M-2377']
with (root / 'planning/RELATIONSHIPS.csv').open(encoding='utf-8-sig', newline='') as source:
    approved = [row['grade'] for row in csv.DictReader(source) if row['application'] == 'Printing Inks'
                and row['application_status'] == 'VERIFIED_FOR_PUBLIC_MAPPING']
assert approved == expected
assert [row.select('td')[0].get_text(strip=True) for row in grades] == expected
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
    'Home', 'Applications', 'Titanium Dioxide for Printing Inks'
]
items = next(node['itemListElement'] for node in graphs if node.get('@type') == 'ItemList')
assert [(item['@type'], item['position'], item['name'], item['url']) for item in items] == [
    ('ListItem', index, grade, base + '/products/' + grade.lower() + '/')
    for index, grade in enumerate(expected, 1)
]

rfq = next(card for card in main.select('.requestCard') if card.select_one('a[href="/request-a-quote/"]'))
rfq_text = rfq.get_text(' ', strip=True).lower()
assert not rfq.select('code')
assert 'not sure / need help' not in rfq_text and 'additional requirements' not in rfq_text
assert all(term in rfq_text for term in (
    'printing-ink', 'vehicle', 'printing process', 'substrate', 'quantity',
    'destination', 'evaluation requirements', 'candidate grades', 'unknown'
))
assert rfq.select_one('a').get_text(strip=True) == 'Discuss Your Printing Inks Application'
assert main.select_one('.requestCard a[href="/request-documents/"]')
assert main.select_one('.requestCard a[href="/request-sample/"]')
assert len(main.select('.sources a[href^="http"]')) == 6
technical = main.get_text(' ', strip=True)
assert 'excludes inkjet inks' in technical
assert 'does not declare a pigment suitable for a process' in technical

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for width in (1440, 768, 390):
        tab = browser.new_page(viewport={'width': width, 'height': 900})
        errors = []
        tab.on('pageerror', lambda error: errors.append(str(error)))
        tab.goto(base + path, wait_until='networkidle')
        assert tab.locator('body').evaluate('(body) => body.scrollWidth <= innerWidth'), width
        assert tab.locator('.inksGlance').evaluate('(el) => el.scrollWidth <= el.clientWidth'), width
        assert tab.locator('.grades tbody tr').count() == 4
        for row in tab.locator('.grades tbody tr').all():
            assert row.is_visible() and row.evaluate('(el) => el.scrollWidth <= el.clientWidth'), width
        assert not errors, (width, errors)
        tab.close()
    browser.close()

print('PASS: Printing Inks SEO, scope, glance, grade/schema mapping, RFQ, sources and responsive layout')
