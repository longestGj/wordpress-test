"""Read-only M-350 schema, summary and frozen-content contract on local WordPress."""
import json
import sys
from pathlib import Path
from urllib.parse import parse_qs, urlparse

import requests
from bs4 import BeautifulSoup

root = Path(__file__).resolve().parents[1]
base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
seed = json.loads((root / 'data/m350.json').read_text(encoding='utf-8'))


def load(path):
    response = requests.get(base + path, timeout=25)
    assert response.status_code == 200, (path, response.status_code)
    page = BeautifulSoup(response.text, 'html.parser')
    nodes = []
    for tag in page.select('script[type="application/ld+json"]'):
        value = json.loads(tag.string)
        nodes.extend(value if isinstance(value, list) else value.get('@graph', [value]))
    return page, nodes


path = '/products/m-350/'
page, nodes = load(path)
home, home_nodes = load('/')
main = page.select_one('main')
assert main and len(main.select('h1')) == 1
assert main.h1.get_text(' ', strip=True) == seed['data']['h1']
assert page.title.get_text(' ', strip=True) == seed['data']['seo_title']
assert page.select_one('meta[name=description]')['content'] == seed['data']['seo_description']
assert page.select_one('link[rel=canonical]')['href'] == base + path
assert main.select_one('.lead').get_text(' ', strip=True) == seed['excerpt']

summary = main.select_one('aside.dataVisual')
assert summary and summary.h2.get_text(' ', strip=True) == 'M-350 product data and evaluation'
assert [item.get_text(' ', strip=True) for item in summary.select('li')] == seed['data']['summary']
assert len(summary.select('li')) == 5
assert 'Paper' in summary.get_text(' ', strip=True)
assert 'no paper-specific performance detail' in summary.get_text(' ', strip=True)
assert not main.select_one('h2:-soup-contains("M-350 at a Glance")')

def single(items, kind):
    matches = [node for node in items if node.get('@type') == kind]
    assert len(matches) == 1, (kind, len(matches))
    return matches[0]


product = single(nodes, 'Product')
brand = single(nodes, 'Brand')
organization = single(nodes, 'Organization')
website = single(nodes, 'WebSite')
home_brand = single(home_nodes, 'Brand')
home_organization = single(home_nodes, 'Organization')
home_website = single(home_nodes, 'WebSite')
assert brand == home_brand and organization == home_organization and website == home_website
assert product['@id'] == base + path + '#product'
assert product['url'] == base + path
assert product['name'] == 'M-350 Titanium Dioxide'
assert product['sku'] == 'M-350'
assert product['description'] == seed['excerpt']
assert product['brand'] == {'@id': base + '/#brand'}
assert product['manufacturer'] == {'@id': base + '/#organization'}
assert brand['name'] == website['name'] == 'TiO2Products'
assert organization['name'] == organization['legalName'] == 'IKHLAS TITANIUM (MALAYSIA) SDN. BHD.'
assert website['@id'] == base + '/#website'
assert organization['@id'] == base + '/#organization'
assert brand['@id'] == base + '/#brand'
assert organization['brand'] == product['brand']
assert website['publisher'] == product['manufacturer']
assert not any(key in product for key in ('offers', 'price', 'priceCurrency', 'availability', 'inventoryLevel', 'aggregateRating', 'review', 'rating', 'gtin'))
assert not any(node.get('@type') in ('Offer', 'AggregateRating', 'Review', 'FAQPage', 'QAPage') for node in nodes)

visible_rows = []
for tr in main.select('.techTable tbody tr'):
    for label in tr.select('.mobileLabel'):
        label.decompose()
    visible_rows.append([cell.get_text(' ', strip=True) for cell in tr.select('th,td')])
assert visible_rows == [[row['property'], row['standard'], row['typical_value']] for row in seed['data']['rows']]
assert len(product['additionalProperty']) == len(visible_rows) == 15
for entry, row in zip(product['additionalProperty'], visible_rows, strict=True):
    assert entry == {'@type': 'PropertyValue', 'name': row[0], 'value': f'Standard: {row[1]}; Typical Value: {row[2]}'}

crumb = single(nodes, 'BreadcrumbList')['itemListElement']
assert [(item['position'], item['name'], item['item']) for item in crumb] == [
    (1, 'Home', base + '/'), (2, 'Products', base + '/products/'), (3, 'M-350', base + path)
]
assert [item.get_text(' ', strip=True) for item in main.select('nav.crumb > a, nav.crumb > span[aria-current]')] == ['Home', 'Products', 'M-350']

cards = main.select('.appGrid article')
assert [card.h3.get_text(' ', strip=True) for card in cards] == [app['title'] for app in seed['data']['applications']]
assert 'paper' in cards[-1].get('class', [])
assert 'Paper is an additional evaluation path for M-350.' in cards[-1].get_text(' ', strip=True)
assert 'The M-350 TDS does not provide paper-specific performance detail' in cards[-1].get_text(' ', strip=True)
assert not any(term in main.get_text(' ', strip=True) for term in ('Masterbatch', 'Specialty Materials', 'Related Grades'))
for link in main.select('a[href*="request-"]'):
    parsed = urlparse(link['href'])
    query = parse_qs(parsed.query)
    assert query.get('grade') == ['M-350'] and query.get('source_page') == ['GRADE-M350']
    if 'TDS' in link.get_text(' ', strip=True):
        assert query.get('requested_type') == ['TDS']
assert 'Submitting a request does not confirm that every requested document is applicable or available.' in main.get_text(' ', strip=True)
assert 'Submitting a request does not mean that a sample, quantity, freight arrangement or dispatch timing has been approved.' in main.get_text(' ', strip=True)
print('PASS: M-350 five-line summary, complete shared entities, 15 visible/schema rows, applications, boundaries and CTAs')
