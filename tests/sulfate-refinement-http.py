"""Read-only local contract for the focused Sulfate page refinement."""
import json
import sys
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup

base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
path = '/products/sulfate-process-titanium-dioxide/'
response = requests.get(base + path, timeout=25)
assert response.status_code == 200, response.status_code
page = BeautifulSoup(response.text, 'html.parser')
main = page.select_one('main.topic-sulfate')
assert main
assert page.select_one('link[rel=canonical]')['href'] == base + path
assert page.title.get_text(' ', strip=True) == 'Sulfate Process Titanium Dioxide Grades | TiO2Products'
assert page.select_one('meta[name=description]')['content'] == 'Explore five Malaysia-origin sulfate process titanium dioxide Grades by application, then review product details, request documents or request a quote.'
assert [h.get_text(' ', strip=True) for h in main.select('h1')] == ['Sulfate Process Titanium Dioxide']
assert [section.get('class', [])[-1] for section in main.select('section')[:3]] == ['m1', 'm-glance', 'm2']
glance = main.select_one('section.m-glance')
assert glance.h2.get_text(' ', strip=True) == 'Sulfate Process at a Glance'
for phrase in ('Process', 'Sulfate', 'Also spelled', 'Sulphate', '5 sulfate-process Grades', 'Process alone does not determine application performance'):
    assert phrase in glance.get_text(' ', strip=True), phrase
assert 'Review each Grade\'s documented properties and application evidence before evaluation.' in glance.get_text(' ', strip=True)
text = main.get_text(' ', strip=True)
assert 'The process label identifies a production route. It does not by itself establish the best Grade, application fit, performance, cost, environmental result or equivalence.' in text
assert 'Document requests are reviewed for a selected Grade.' in text
assert 'If you already have a Grade in mind, include it with your quotation request.' in text
assert not any(term in text for term in ('Not sure / Need help', 'Additional Requirements', 'Product Grade', 'Federal Trade Commission'))
source = main.select_one('.m2 a[href^="https://www.epa.gov/"]')
assert source and source['href'] == 'https://www.epa.gov/sites/default/files/2018-05/documents/2006_eg-plan-tsd_final_dec-2006.pdf'
assert 'Source last reviewed: 24 September 2026.' in text
grades = main.select('.grades article')
expected = ['M-996', 'M-2196', 'M-108', 'M-52', 'M-2377']
assert [card.h3.get_text(' ', strip=True) for card in grades] == expected
for card in grades:
    assert requests.get(base + card.select_one('a[href^="/products/"]')['href'], timeout=20).status_code == 200
for target in ('/request-documents/', '/request-a-quote/', '/applications/', '/resources/chloride-vs-sulfate-titanium-dioxide/'):
    assert main.select_one(f'a[href="{target}"]')
    assert requests.get(base + target, timeout=20).status_code == 200

nodes = []
for tag in page.select('script[type="application/ld+json"]'):
    graph = json.loads(tag.string)
    nodes.extend(graph.get('@graph', [graph]) if isinstance(graph, dict) else graph)
assert len([node for node in nodes if node.get('@type') == 'CollectionPage']) == 1
assert len([node for node in nodes if node.get('@type') == 'BreadcrumbList']) == 1
lists = [node for node in nodes if node.get('@type') == 'ItemList']
assert len(lists) == 1
assert [item['name'] for item in lists[0]['itemListElement']] == expected
assert not any(node.get('@type') in ('Product', 'Offer', 'FAQPage') for node in nodes)
assert 'CR-901' not in json.dumps(nodes)
print('PASS: Sulfate copy, links, EPA source, five verified grades and process schema')
