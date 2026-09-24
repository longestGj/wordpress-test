"""Read-only sitewide brand/entity contract against a local WordPress instance."""
import csv
import json
import sys
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup

root = Path(__file__).resolve().parents[1]
base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
with (root / 'planning/SITE_MAP.csv').open(encoding='utf-8-sig', newline='') as source:
    routes = sorted({row['url'] for row in csv.DictReader(source) if row['url'].startswith('/')})
assert len(routes) >= 58
session = requests.Session()

for path in routes:
    response = session.get(base + path, timeout=25)
    assert response.status_code == 200, (path, response.status_code)
    page = BeautifulSoup(response.text, 'html.parser')
    canonical = page.select_one('link[rel="canonical"]')
    assert canonical and canonical['href'] == base + path, path
    header = page.select_one('header.header')
    footer = page.select_one('footer.footer')
    assert header and footer, path
    header_logo = header.select_one('a.logoLink img.logo')
    footer_logo = footer.select_one('img.logo')
    assert header_logo and header_logo['alt'] == 'TiO2Products', path
    assert header_logo.parent['aria-label'] == 'TiO2Products — Home', path
    assert footer_logo and footer_logo['alt'] == 'TiO2Products', path
    assert 'Operated by IKHLAS TITANIUM (MALAYSIA) SDN. BHD.' in footer.get_text(' ', strip=True), path
    assert 'TiO2 Malaysia' not in page.title.get_text(' ', strip=True), path
    for tag in page.select('meta[content]'):
        assert 'TiO2 Malaysia' not in tag['content'], (path, tag.get('name') or tag.get('property'))
    main = page.select_one('main')
    assert main and 'TiO2 Malaysia' not in main.get_text(' ', strip=True), path
    for tag in page.select('[alt], [aria-label]'):
        assert 'TiO2 Malaysia' not in (tag.get('alt') or '') + (tag.get('aria-label') or ''), path
    assert 'Manufactured by TiO2Products' not in response.text, path
    if path == '/':
        graphs = []
        for tag in page.select('script[type="application/ld+json"]'):
            value = json.loads(tag.string)
            graphs.extend(value.get('@graph', [value]) if isinstance(value, dict) else value)
        website = next(node for node in graphs if node.get('@type') == 'WebSite')
        organization = next(node for node in graphs if node.get('@type') == 'Organization')
        brand = next(node for node in graphs if node.get('@type') == 'Brand')
        assert website['name'] == brand['name'] == 'TiO2Products'
        assert website['publisher']['@id'] == organization['@id'] == base + '/#organization'
        assert organization['name'] == organization['legalName'] == 'IKHLAS TITANIUM (MALAYSIA) SDN. BHD.'
        assert organization['brand']['@id'] == brand['@id'] == base + '/#brand'
    if path == '/about/':
        text = main.get_text(' ', strip=True)
        assert 'TiO2Products is the commercial website' in text
        assert 'operated by IKHLAS TITANIUM (MALAYSIA) SDN. BHD.' in text
        assert 'Taiping, Perak' in text
        about_graph = []
        for tag in page.select('script[type="application/ld+json"]'):
            value = json.loads(tag.string)
            about_graph.extend(value.get('@graph', [value]) if isinstance(value, dict) else value)
        about_page = next(node for node in about_graph if node.get('@type') == 'WebPage')
        assert about_page['about']['@id'] == base + '/#organization'
        assert about_page['isPartOf']['@id'] == base + '/#website'
    if path == '/products/m-350/':
        products = []
        for tag in page.select('script[type="application/ld+json"]'):
            value = json.loads(tag.string)
            products.extend(value if isinstance(value, list) else value.get('@graph', [value]))
        product = next(node for node in products if node.get('@type') == 'Product')
        assert product['manufacturer'] == {'@id': base + '/#organization'}
        assert product.get('brand', {}).get('name') != 'TiO2Products'

for asset in ('logo.svg', 'logo-compact.svg', 'logo-reverse.svg', 'logo-symbol.svg', 'favicon.svg'):
    logo = session.get(base + '/wp-content/themes/tio2/assets/' + asset, timeout=15)
    logo.raise_for_status()
    assert '<path' in logo.text and '<text' not in logo.text, asset
    assert 'font-family' not in logo.text and '<image' not in logo.text, asset
    assert 'TiO2 Malaysia' not in logo.text and 'MALAYSIA' not in logo.text, asset

missing = session.get(base + '/brand-audit-missing-route/', timeout=15)
assert missing.status_code == 404
assert BeautifulSoup(missing.text, 'html.parser').title.get_text(strip=True) == 'Page Not Found | TiO2Products'

print(f'PASS: TiO2Products brand and legal entity across {len(routes)} active routes and vector logos')
