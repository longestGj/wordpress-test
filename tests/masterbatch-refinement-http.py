"""Read-only local contract for the bounded Masterbatch detail refinement."""
import csv
import json
import sys
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright


base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8087').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
path = '/applications/titanium-dioxide-for-masterbatch/'
seed = json.loads((Path(__file__).resolve().parents[1] / 'data/process-applications/masterbatch.json').read_text(encoding='utf-8'))
assert seed['expected_text'] == BeautifulSoup(seed['content'], 'html.parser').get_text(' ', strip=True)

response = requests.get(base + path, timeout=20)
response.raise_for_status()
page = BeautifulSoup(response.text, 'html.parser')
main = page.select_one('main.topic-masterbatch')
assert main and [h.get_text(' ', strip=True) for h in main.select('h1')] == ['Titanium Dioxide for Masterbatch']
assert page.title.get_text(strip=True) == 'Titanium Dioxide for Masterbatch | TiO₂ Grade Evaluation'
description = page.select_one('meta[name="description"]')['content'].lower()
assert all(term in description for term in ('masterbatch', 'concentrate', 'carrier', 'receiving resin', 'let-down', 'final-article', 'grade'))
assert page.select_one('link[rel="canonical"]')['href'] == base + path
assert page.select_one('meta[property="og:url"]')['content'] == base + path
assert page.select_one('meta[property="og:title"]')['content'] == page.title.get_text(strip=True)

hero = main.select_one('.hero')
assert 'two connected stages' in hero.get_text(' ', strip=True)
assert 'concentrate result is not mistaken for final-use evidence' in hero.get_text(' ', strip=True)
crumb = hero.select_one('nav.breadcrumb[aria-label="Breadcrumb"]')
assert crumb
assert [(a.get_text(' ', strip=True), a['href']) for a in crumb.select('a')] == [
    ('Home', '/'), ('Applications', '/applications/')
]
assert crumb.select_one('[aria-current="page"]').get_text(' ', strip=True) == 'Masterbatch'
assert crumb.select_one('[aria-current="page"]').name != 'a'
assert [child.get('class', [''])[0] for child in main.find_all(recursive=False)[:3]] == [
    'wp-block-group', 'masterbatchGlance', 'wp-block-group'
]
glance = main.select_one('.masterbatchGlance')
assert glance.select_one('h2').get_text(' ', strip=True) == 'Masterbatch Evaluation at a Glance'
assert [h.get_text(' ', strip=True) for h in glance.select('h3')] == [
    'Carrier system', 'Pigment loading', 'Concentrate processing', 'Dispersion evidence',
    'Let-down & conversion', 'Final article', 'Grades to Review'
]
assert glance.select_one('a[href="#grades-to-review"]')

grades = main.select('.topic-module-grades-to-review tbody tr')
expected = ['M-510', 'M-200', 'M-108', 'M-210', 'M-340', 'M-886', 'M-2377']
with (Path(__file__).resolve().parents[1] / 'planning/RELATIONSHIPS.csv').open(encoding='utf-8-sig', newline='') as source:
    approved = [row['grade'] for row in csv.DictReader(source) if row['application'] == 'Masterbatch'
                and row['application_status'] == 'VERIFIED_FOR_PUBLIC_MAPPING']
assert approved == expected
assert [row.select('td')[0].get_text(strip=True) for row in grades] == expected
assert [row.select('td')[1].get_text(strip=True) for row in grades] == [
    'Chloride', 'Chloride', 'Sulfate', 'Chloride', 'Chloride', 'Chloride', 'Sulfate'
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
    'Home', 'Applications', 'Titanium Dioxide for Masterbatch'
]
items = next(node['itemListElement'] for node in graphs if node.get('@type') == 'ItemList')
assert [(item['@type'], item['position'], item['name'], item['url']) for item in items] == [
    ('ListItem', index, grade, base + '/products/' + grade.lower() + '/')
    for index, grade in enumerate(expected, 1)
]

rfq = next(li for li in main.select('.topic-module-prepare-your-request li') if li.select_one('a[href="/request-a-quote/"]'))
rfq_text = rfq.get_text(' ', strip=True).lower()
assert not rfq.select('code')
assert 'not sure / need help' not in rfq_text and 'additional requirements' not in rfq_text
assert all(term in rfq_text for term in (
    'masterbatch', 'carrier', 'receiving resin', 'concentrate', 'preparation', 'conversion',
    'quantity', 'destination', 'evaluation requirements', 'candidate grades', 'unknown', 'suitability'
))
assert rfq.select_one('a[href="/request-a-quote/"]').get_text(strip=True) == 'Discuss Your Masterbatch Application'
assert main.select_one('.topic-module-prepare-your-request a[href="/request-documents/"]')
assert main.select_one('.topic-module-prepare-your-request a[href="/request-sample/"]')
assert main.select_one('a[href="/applications/titanium-dioxide-for-plastics/"]')
requests.get(base + '/applications/titanium-dioxide-for-plastics/', timeout=20).raise_for_status()
assert len(main.select('.sourceSection a[href^="http"]')) == 4
body = main.select_one('.topic-module-ask-each-document-the-right-question').get_text(' ', strip=True)
assert 'Chemours Ti-Pure R-350' not in body and 'KRONOS 2220' not in body
assert 'product positioning, TDS, SDS and typical-value statements' in body

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for width in (1440, 768, 390):
        tab = browser.new_page(viewport={'width': width, 'height': 900})
        errors = []
        tab.on('pageerror', lambda error: errors.append(str(error)))
        tab.goto(base + path, wait_until='networkidle')
        assert tab.locator('body').evaluate('(body) => body.scrollWidth <= innerWidth'), width
        assert tab.locator('.masterbatchGlance').evaluate('(el) => el.scrollWidth <= el.clientWidth'), width
        assert tab.locator('.topic-module-grades-to-review tbody tr').count() == 7
        for row in tab.locator('.topic-module-grades-to-review tbody tr').all():
            assert row.is_visible() and row.evaluate('(el) => el.scrollWidth <= el.clientWidth'), width
        assert not errors, (width, errors)
        tab.close()
    browser.close()

print('PASS: Masterbatch SEO, breadcrumb, glance, grade/schema mapping, RFQ, sources and responsive layout')
