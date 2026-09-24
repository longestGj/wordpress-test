"""Read-only local HTTP and responsive review for the eleven imported market Pages.

Run after integration/import: python tests/market-http.py
Screenshots are written under ignored .local/market-http for visual inspection.
"""
import json
import os
import re
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright

ROOT = Path(__file__).resolve().parents[1]
BASE = 'http://localhost:8080'
SCHEMA_BASE = os.environ.get('MARKET_SCHEMA_BASE', BASE)
parsed = urlparse(BASE)
assert parsed.hostname in ('localhost', '127.0.0.1') and parsed.scheme in ('http', 'https')
assert urlparse(SCHEMA_BASE).hostname in ('localhost', '127.0.0.1')
OUT = ROOT / '.local/market-http'
OUT.mkdir(parents=True, exist_ok=True)
seeds = [json.loads(path.read_text(encoding='utf-8')) for path in sorted((ROOT/'data/markets').glob('*.json'))]
assert len(seeds) == 11
schema_session = requests.Session()
schema_session.trust_env = False

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for seed in seeds:
        schema_response = schema_session.get(SCHEMA_BASE + seed['path'],
                                             headers={'Host':urlparse(BASE).netloc}, timeout=15)
        assert schema_response.status_code == 200, (seed['identity'], schema_response.status_code)
        schema_soup = BeautifulSoup(schema_response.text, 'html.parser')
        graphs = [graph for node in schema_soup.select('script[type="application/ld+json"]')
                  for graph in json.loads(node.string).get('@graph', [])]
        assert {graph['@type'] for graph in graphs} == {'WebPage', 'BreadcrumbList'}, seed['identity']
        crumbs = next(graph['itemListElement'] for graph in graphs if graph['@type'] == 'BreadcrumbList')
        visible = re.split(r'\s*[›/]\s*', schema_soup.select_one('nav.market-breadcrumb').get_text(' ', strip=True))
        assert [crumb['name'] for crumb in crumbs] == visible, (seed['identity'], crumbs, visible)
        assert [crumb['position'] for crumb in crumbs] == list(range(1, len(visible)+1))
        is_eu_country = seed['identity'] in {'MARKET-EU-DE', 'MARKET-EU-IT',
                                              'MARKET-EU-ES', 'MARKET-EU-PL',
                                              'MARKET-EU-NL', 'MARKET-EU-BE'}
        assert len(crumbs) == (4 if is_eu_country else 3), seed['identity']
        assert [crumb['item'] for crumb in crumbs[:2]] == [BASE+'/', BASE+'/markets/']
        if is_eu_country:
            assert crumbs[2]['item'] == BASE + '/markets/european-union/'
        assert crumbs[-1]['item'] == BASE + seed['path']
        if seed['identity'] in {'MARKET-EU-DE', 'MARKET-EU-IT'}:
            main = schema_soup.select_one('main.market-page')
            text = main.get_text(' ', strip=True)
            assert all(term not in text for term in ('Not sure / Need help', 'Product / Grade',
                                                     'checked 7 September 2026',
                                                     'checked: 7 September 2026'))
            assert 'The request does not select a Grade or confirm price, stock, supply, transport or delivery timing.' in text
            assert 'submission does not confirm sample approval, quantity or delivery.' in text
            if seed['identity'] == 'MARKET-EU-DE':
                assert all(term in text for term in ('VdL', 'GKV', 'Hamburg Port Authority'))
                assert 'does not establish a route offered by IKHLAS TITANIUM, transport mode, cost or lead time.' in text
            else:
                assert all(term not in text for term in ('Garzanti Specialties', 'Destination Country',
                                                         'Destination Port / City', 'Additional Requirements'))
                assert all(term in text for term in ('Federchimica AVISA', 'Unionplast', 'AMAPLAST',
                                                     'A Certificate of Origin is available upon request.'))
                assert 'does not promise that a certificate is issued for every shipment or determine customs acceptance or treatment.' in text
                card = next(card for card in main.select('.market-card')
                            if card.h3 and card.h3.get_text(' ', strip=True) == 'Compound and Masterbatch')
                assert {a['href'] for a in card.select('a[href]')} == {
                    '/applications/titanium-dioxide-for-plastics/',
                    '/applications/titanium-dioxide-for-masterbatch/',
                }
        for width in (1440, 768, 390):
            page = browser.new_page(viewport={'width':width,'height':900},device_scale_factor=1)
            response = page.goto(BASE + seed['path'], wait_until='networkidle')
            assert response and response.status == 200, (seed['identity'], width, response.status if response else None)
            assert page.locator('main.market-page').count() == 1, seed['identity']
            assert page.locator('main h1').all_text_contents() == [seed['title']], seed['identity']
            assert page.title() == seed['seo_title'], seed['identity']
            assert page.locator('meta[name="description"]').get_attribute('content') == seed['seo_description']
            assert page.locator('link[rel="canonical"]').get_attribute('href') == BASE + seed['path']
            assert 'noindex' in page.locator('meta[name="robots"]').get_attribute('content')
            assert page.locator('html').get_attribute('lang') == seed['language']
            assert page.locator('link[href*="markets.css"]').count() == 1
            assert not page.evaluate('document.documentElement.scrollWidth > window.innerWidth'), (seed['identity'], width)
            assert all(not action.is_visible() for action in page.locator('main a.market-action[aria-disabled="true"]').all())
            if width == 1440:
                local_links = {a.get_attribute('href').split('#',1)[0] for a in page.locator('main a[href^="/"]').all()}
                for path in local_links:
                    if not path or path.startswith('//'): continue
                    target = page.request.get(BASE + path)
                    assert target.status == 200, (seed['identity'], path, target.status)
            if seed['identity'] in ('MARKET-BR-EN','MARKET-BR-PT'):
                alternates = {a.get_attribute('hreflang'):a.get_attribute('href') for a in page.locator('link[rel="alternate"][hreflang]').all()}
                assert alternates == {'en':BASE+'/markets/brazil/', 'pt-BR':BASE+'/pt-br/markets/brazil/'}, seed['identity']
                assert page.locator('nav.market-language a').count() == 1
            else:
                assert page.locator('link[rel="alternate"][hreflang]').count() == 0
            if width == 390:
                button = page.locator('.menuButton')
                assert button.is_visible()
                button.click(); assert page.locator('#site-menu').is_visible()
                page.keyboard.press('Escape'); assert not page.locator('#site-menu').is_visible()
                page.wait_for_function('document.activeElement === document.querySelector(".menuButton")')
                if page.locator('main details').count():
                    summary = page.locator('main details summary').first
                    summary.focus(); assert summary.evaluate('(element) => document.activeElement === element')
                    page.keyboard.press('Enter')
                    assert page.locator('main details').first.get_attribute('open') is not None, seed['identity']
                    assert summary.evaluate('(element) => parseFloat(getComputedStyle(element).outlineWidth) >= 3')
                    page.keyboard.press('Enter')
                    assert page.locator('main details').first.get_attribute('open') is None
            page.screenshot(path=str(OUT/(seed['identity']+'-'+str(width)+'.png')),full_page=True)
            page.close()
    hub = browser.new_page()
    response = hub.goto(BASE + '/markets/', wait_until='networkidle')
    assert response and response.status == 200
    hub_links = {a.get_attribute('href') for a in hub.locator('main a[href^="/"]').all()}
    assert {seed['path'] for seed in seeds}.issubset(hub_links), 'Markets Hub is missing a live destination link'
    hub.close()
    browser.close()
print('PASS: 11 local market Pages × 3 widths; HTTP, SEO, visible/JSON-LD breadcrumbs, copy, links, menu and overflow')
print('Screenshots:', OUT)
