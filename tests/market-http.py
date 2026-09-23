"""Read-only local HTTP and responsive review for the eleven imported market Pages.

Run after integration/import: python tests/market-http.py
Screenshots are written under ignored .local/market-http for visual inspection.
"""
import json
from pathlib import Path
from urllib.parse import urlparse

from playwright.sync_api import sync_playwright

ROOT = Path(__file__).resolve().parents[1]
BASE = 'http://localhost:8080'
parsed = urlparse(BASE)
assert parsed.hostname in ('localhost', '127.0.0.1') and parsed.scheme in ('http', 'https')
OUT = ROOT / '.local/market-http'
OUT.mkdir(parents=True, exist_ok=True)
seeds = [json.loads(path.read_text(encoding='utf-8')) for path in sorted((ROOT/'data/markets').glob('*.json'))]
assert len(seeds) == 11

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for seed in seeds:
        for width in (1440, 768, 390):
            page = browser.new_page(viewport={'width':width,'height':900},device_scale_factor=1)
            response = page.goto(BASE + seed['path'], wait_until='networkidle')
            assert response and response.status == 200, (seed['identity'], width, response.status if response else None)
            assert page.locator('main.market-page').count() == 1, seed['identity']
            assert page.locator('main h1').all_text_contents() == [seed['title']], seed['identity']
            assert page.title() == seed['seo_title'], seed['identity']
            assert page.locator('meta[name="description"]').get_attribute('content') == seed['seo_description']
            assert page.locator('link[rel="canonical"]').get_attribute('href') == BASE + seed['path']
            assert page.locator('html').get_attribute('lang') == seed['language']
            assert page.locator('link[href*="markets.css"]').count() == 1
            assert not page.evaluate('document.documentElement.scrollWidth > window.innerWidth'), (seed['identity'], width)
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
            page.screenshot(path=str(OUT/(seed['identity']+'-'+str(width)+'.png')),full_page=True)
            page.close()
    browser.close()
print('PASS: 11 local market Pages × 3 widths; HTTP, SEO, hreflang, language, menu and overflow')
print('Screenshots:', OUT)
