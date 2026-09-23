"""Responsive chrome check on representative local WordPress page families."""
import sys
from urllib.parse import urlparse

from playwright.sync_api import sync_playwright

base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
paths = (
    '/', '/products/', '/about/', '/applications/',
    '/applications/titanium-dioxide-for-paper/', '/markets/european-union/',
    '/documents/', '/contact/',
)

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for path in paths:
        for width in (1440, 768, 390):
            page = browser.new_page(viewport={'width': width, 'height': 900})
            errors = []
            page.on('pageerror', lambda error: errors.append(str(error)))
            response = page.goto(base + path, wait_until='networkidle')
            assert response and response.status == 200, (path, width)
            assert not page.evaluate('document.documentElement.scrollWidth > innerWidth'), (path, width)
            for selector in ('header.header img.logo', 'footer.footer img.logo'):
                logo = page.locator(selector)
                assert logo.is_visible(), (path, width, selector)
                assert logo.get_attribute('alt') == 'TiO2Products'
                assert logo.evaluate('(image) => image.complete && image.naturalWidth > 0'), (path, width, selector)
                assert logo.evaluate('(image) => image.scrollWidth <= innerWidth'), (path, width, selector)
            assert not errors, (path, width, errors)
            page.close()
    browser.close()

print('PASS: 8 page families, 1440/768/390 brand chrome, loaded logos, no overflow or JS errors')
