"""Read-only contract for the bounded Paper application refinement."""
import csv
import json
import sys
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright


base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
path = '/applications/titanium-dioxide-for-paper/'
root = Path(__file__).resolve().parents[1]
seed = json.loads((root / 'data/process-applications/paper.json').read_text(encoding='utf-8'))
assert seed['expected_text'] == BeautifulSoup(seed['content'], 'html.parser').get_text(' ', strip=True)

response = requests.get(base + path, timeout=20)
response.raise_for_status()
page = BeautifulSoup(response.text, 'html.parser')
main = page.select_one('main.topic-paper')
assert main and [h.get_text(' ', strip=True) for h in main.select('h1')] == ['Titanium Dioxide for Paper']
assert page.title.get_text(strip=True) == 'Titanium Dioxide for Paper | Grade Evaluation'
description = page.select_one('meta[name="description"]')['content'].lower()
assert all(term in description for term in ('titanium dioxide for paper', 'paper system', 'brightness', 'whiteness', 'colour', 'opacity', 'grade evaluation'))
assert page.select_one('link[rel="canonical"]')['href'] == base + path
assert page.select_one('meta[property="og:url"]')['content'] == base + path
assert page.select_one('meta[property="og:title"]')['content'] == page.title.get_text(strip=True)

hero = main.select_one('.hero')
assert 'whether screening a new Grade or comparing it with an existing baseline' in hero.get_text(' ', strip=True)
assert 'A similar technical data sheet field can support screening; it does not establish a replacement result.' in hero.get_text(' ', strip=True)
assert 'This page supports general paper evaluation.' in hero.get_text(' ', strip=True)
assert 'The detailed framework centres on decorative and lightweight paper' in hero.get_text(' ', strip=True)
crumb = hero.select_one('nav.breadcrumb[aria-label="Breadcrumb"]')
assert crumb
assert [(a.get_text(' ', strip=True), a['href']) for a in crumb.select('a')] == [('Home', '/'), ('Applications', '/applications/')]
assert crumb.select_one('[aria-current="page"]').get_text(' ', strip=True) == 'Paper'
assert crumb.select_one('[aria-current="page"]').name != 'a'

glance = main.select_one('.paperGlance')
assert glance and glance.select_one('h2').get_text(' ', strip=True) == 'Paper Evaluation at a Glance'
assert [h.get_text(' ', strip=True) for h in glance.select('h3')] == [
    'Paper system', 'Addition route', 'Optical properties', 'System observations',
    'Evidence identity', 'Validation', 'Grades to Review'
]
assert main.find_all(recursive=False)[:2] == [hero, glance]
assert glance.select_one('a[href="#grades-to-review"]')

grades = main.select('.grades tbody tr')
expected = ['M-350', 'M-2377']
with (root / 'planning/RELATIONSHIPS.csv').open(encoding='utf-8-sig', newline='') as source:
    approved = [row['grade'] for row in csv.DictReader(source) if row['application'] == 'Paper'
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
assert [item['name'] for item in bread_schema['itemListElement']] == ['Home', 'Applications', 'Titanium Dioxide for Paper']
items = next(node['itemListElement'] for node in graphs if node.get('@type') == 'ItemList')
assert [(item['@type'], item['position'], item['name'], item['url']) for item in items] == [
    ('ListItem', index, grade, base + '/products/' + grade.lower() + '/')
    for index, grade in enumerate(expected, 1)
]

rfq = next(item for item in main.select('.request-paths li') if item.select_one('a[href="/request-a-quote/"]'))
rfq_text = rfq.get_text(' ', strip=True).lower()
assert not rfq.select('code')
assert 'not sure / need help' not in rfq_text and 'additional requirements' not in rfq_text
assert all(term in rfq_text for term in ('paper type', 'furnish', 'addition route', 'end use', 'quantity', 'destination', 'requirements', 'candidate grades', 'unknown'))
assert main.select_one('.request-paths a[href="/request-documents/"]')
assert main.select_one('.request-paths a[href="/request-sample/"]')
assert len(main.select('.sources a[href^="http"]')) == 7
technical = main.get_text(' ', strip=True)
for claim in ('L* is not another name for brightness', 'Do not convert a brightness value into whiteness, colour or opacity',
              'does not establish mill performance', 'does not establish within-lot variability',
              'retention-related observation'):
    assert claim.lower() in technical.lower(), claim

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for width in (1440, 768, 390):
        tab = browser.new_page(viewport={'width': width, 'height': 900})
        errors = []
        tab.on('pageerror', lambda error: errors.append(str(error)))
        tab.goto(base + path, wait_until='networkidle')
        assert tab.locator('body').evaluate('(body) => body.scrollWidth <= innerWidth'), width
        assert tab.locator('.paperGlance').evaluate('(el) => el.scrollWidth <= el.clientWidth'), width
        for row in tab.locator('.grades tbody tr').all():
            assert row.is_visible() and row.evaluate('(el) => el.scrollWidth <= el.clientWidth'), width
        assert not errors, (width, errors)
        tab.close()
    browser.close()

print('PASS: Paper SEO, scope, glance, grades/schema, RFQ, sources and responsive layout')
