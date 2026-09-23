"""Real local WordPress responsive review for Contact, Legal, Thank You and 404.

Screenshots are kept under ignored .local/utility-http.
"""
from pathlib import Path
from urllib.parse import urlparse
from playwright.sync_api import sync_playwright

ROOT = Path(__file__).resolve().parents[1]
BASE = 'http://localhost:8080'
assert urlparse(BASE).hostname in ('localhost', '127.0.0.1')
OUT = ROOT / '.local' / 'utility-http'
OUT.mkdir(parents=True, exist_ok=True)
PAGES = {
    'CONTACT-001': ('/contact/', 'Contact TiO2Products', 200),
    'LEGAL-PRIV-EN': ('/privacy-policy/', 'Privacy Policy', 200),
    'LEGAL-PRIV-MS': ('/ms/privacy-policy/', 'Dasar Privasi', 200),
    'LEGAL-COOKIE-EN': ('/cookie-policy/', 'Cookie Policy', 200),
    'CONV-THANK': ('/thank-you/', 'How can we help?', 200),
    'SYS-404': ('/missing-page-utility-browser-check/', 'Let’s help you find what you need.', 404),
}

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    errors = []
    for identity, (path, heading, status) in PAGES.items():
        for width in (1440, 768, 390):
            page = browser.new_page(viewport={'width': width, 'height': 900}, device_scale_factor=1)
            page.on('pageerror', lambda error: errors.append(str(error)))
            response = page.goto(BASE + path, wait_until='networkidle')
            assert response and response.status == status, (identity, width, response.status if response else None)
            assert page.locator('main h1').all_text_contents() == [heading], (identity, width, page.locator('main h1').all_text_contents())
            assert not page.evaluate('document.documentElement.scrollWidth > innerWidth + 1'), (identity, width, 'horizontal overflow')
            if identity == 'LEGAL-PRIV-MS': assert page.locator('html').get_attribute('lang') == 'ms-MY'
            if identity in ('CONV-THANK', 'SYS-404'):
                assert 'noindex' in page.locator('meta[name="robots"]').get_attribute('content')
            if identity == 'CONTACT-001':
                assert page.locator('form.contact-form').count() == 1
                assert page.locator('form.contact-form input[required]').count() == 5
                assert page.locator('form.contact-form textarea[required]').count() == 1
            if identity == 'LEGAL-COOKIE-EN' and width == 390:
                assert page.locator('.utility-legal table tbody tr').first.evaluate('(e) => getComputedStyle(e).display') == 'block'
            if identity in ('LEGAL-PRIV-EN','LEGAL-PRIV-MS','LEGAL-COOKIE-EN') and width == 768:
                assert page.locator('.utility-toc details summary').is_visible()
            page.screenshot(path=str(OUT / f'{identity}-{width}.png'), full_page=True)
            if width == 390:
                button = page.locator('.menuButton')
                assert button.is_visible()
                button.click(); assert page.locator('#site-menu').is_visible()
                page.keyboard.press('Escape'); assert not page.locator('#site-menu').is_visible()
                page.wait_for_function('document.activeElement === document.querySelector(".menuButton")')
            if width == 1440:
                opener = page.locator('.footerUtilities .cookie-settings-open')
                opener.click(); assert page.locator('#cookie-settings').is_visible()
                page.keyboard.press('Escape'); assert not page.locator('#cookie-settings').is_visible()
                assert opener.evaluate('(element) => document.activeElement === element')
            page.close()
    context = browser.new_context()
    page = context.new_page()
    hosts = set()
    page.on('request', lambda request: hosts.add(urlparse(request.url).hostname))
    page.goto(BASE + '/privacy-policy/', wait_until='networkidle')
    assert not context.cookies() and page.evaluate('localStorage.length') == 0
    page.goto(BASE + '/contact/', wait_until='networkidle')
    cookies = context.cookies()
    assert [cookie['name'] for cookie in cookies] == ['tio2_flow']
    assert cookies[0]['httpOnly'] and cookies[0]['expires'] == -1
    assert page.evaluate('localStorage.length') == 0
    assert {host for host in hosts if host} == {'localhost'}
    context.close()
    browser.close()
assert not errors, errors
print('PASS: six real WordPress views × three widths; layout, navigation, forms and Cookie Settings')
print('Screenshots:', OUT)
