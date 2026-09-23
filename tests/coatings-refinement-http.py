"""Read-only, local end-to-end contract for the Coatings evaluation page."""
import json
import sys
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright


base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8085').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
seed = json.loads((Path(__file__).resolve().parents[1] / 'data/process-applications/coatings.json').read_text(encoding='utf-8'))
assert seed['expected_text'] == BeautifulSoup(seed['content'], 'html.parser').get_text(' ', strip=True)
path = '/applications/titanium-dioxide-for-coatings/'
response = requests.get(base + path, timeout=20)
response.raise_for_status()
page = BeautifulSoup(response.text, 'html.parser')
main = page.select_one('main.topic-coatings')
assert main and len(main.select('h1')) == 1
assert main.h1.get_text(' ', strip=True) == 'Titanium Dioxide for Coatings'
assert page.title.get_text(strip=True) == 'Titanium Dioxide for Paints & Coatings | Grade Evaluation'
description = page.select_one('meta[name="description"]')['content'].lower()
assert all(term in description for term in ('paints', 'coatings', 'formulation', 'dispersion', 'film', 'optical', 'grade'))
assert page.select_one('link[rel="canonical"]')['href'] == base + path

hero = main.select_one('.hero')
hero_copy = hero.get_text(' ', strip=True).lower()
assert 'new formulation' in hero_copy and 'incumbent' in hero_copy
assert 'equivalence' in hero_copy and 'prepared film' in hero_copy
sections = [element for element in main.children if getattr(element, 'name', None)]
assert [element.get('class', [''])[0] for element in sections[:3]] == ['wp-block-group', 'coatingsGlance', 'contentSection']
glance = main.select_one('.coatingsGlance')
assert glance.select_one('h2').get_text(' ', strip=True) == 'Coatings Evaluation at a Glance'
assert [heading.get_text(' ', strip=True) for heading in glance.select('h3')] == [
    'Formulation', 'Dispersion', 'Optical', 'Film', 'Durability', 'Grades to Review'
]
assert glance.select_one('a[href="#grades-to-review"]')

grades = main.select('#grades-to-review tbody tr')
expected = ['M-350', 'M-510', 'M-896', 'M-996', 'M-2196', 'M-895', 'M-52', 'M-2377']
assert [row.select_one('td').get_text(strip=True) for row in grades] == expected
assert [row.select('td')[1].get_text(' ', strip=True) for row in grades] == [
    'Chloride process', 'Chloride process', 'Chloride process', 'Sulfate process',
    'Sulfate process', 'Chloride process', 'Sulfate process', 'Sulfate process'
]
for row, grade in zip(grades, expected, strict=True):
    assert row.select_one('a')['href'] == '/products/' + grade.lower() + '/'

graphs = []
for tag in page.select('script[type="application/ld+json"]'):
    value = json.loads(tag.string)
    graphs.extend(value.get('@graph', [value]) if isinstance(value, dict) else value)
assert any(node.get('@type') == 'WebPage' and node.get('url') == base + path for node in graphs)
crumb = next(node for node in graphs if node.get('@type') == 'BreadcrumbList')
assert [item['name'] for item in crumb['itemListElement']] == ['Home', 'Applications', 'Titanium Dioxide for Coatings']
items = next(node['itemListElement'] for node in graphs if node.get('@type') == 'ItemList')
assert [(item['name'], item['url']) for item in items] == [
    (grade, base + '/products/' + grade.lower() + '/') for grade in expected
]

rfq = next(card for card in main.select('.requestCard') if card.select_one('h3').get_text(' ', strip=True) == 'Send an RFQ')
rfq_text = rfq.get_text(' ', strip=True).lower()
assert not rfq.select('code')
assert all(term in rfq_text for term in ('application', 'formulation', 'quantity', 'destination', 'candidate grades', 'technical qualification'))
assert rfq.select_one('a[href="/request-a-quote/"]')
assert len(main.select('#technical-sources li a[href]')) == 6
assert len(main.select('.endpoint-table tbody tr')) == 5

# The shared application-detail Schema must follow each page's visible grade routes.
for topic in ('plastics', 'masterbatch', 'printing-inks', 'paper'):
    detail = requests.get(base + '/applications/titanium-dioxide-for-' + topic + '/', timeout=20)
    detail.raise_for_status()
    detail_page = BeautifulSoup(detail.text, 'html.parser')
    visible = [a['href'] for a in detail_page.select('main a[href^="/products/"]')
               if a.get_text(' ', strip=True).startswith('View ')]
    detail_graph = []
    for tag in detail_page.select('script[type="application/ld+json"]'):
        value = json.loads(tag.string)
        detail_graph.extend(value.get('@graph', [value]) if isinstance(value, dict) else value)
    item_list = next(node['itemListElement'] for node in detail_graph if node.get('@type') == 'ItemList')
    assert [item['url'] for item in item_list] == [base + route for route in visible], topic

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for width in (1440, 768, 390):
        tab = browser.new_page(viewport={'width': width, 'height': 900})
        errors = []
        tab.on('pageerror', lambda error: errors.append(str(error)))
        tab.goto(base + path, wait_until='networkidle')
        assert tab.locator('body').evaluate('(body) => body.scrollWidth <= innerWidth'), width
        assert tab.locator('.coatingsGlance').evaluate('(element) => element.scrollWidth <= element.clientWidth'), width
        assert tab.locator('.endpoint-table tbody tr').count() == 5
        for row in tab.locator('.endpoint-table tbody tr').all():
            assert row.is_visible(), width
            assert row.evaluate('(element) => element.scrollWidth <= element.clientWidth'), width
        assert not errors, (width, errors)
        tab.close()
    browser.close()

print('PASS: Coatings entry, glance, unchanged grade routes, public schema, RFQ, sources and responsive layout')
