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
        for width in (1440, 1024, 768, 390, 320):
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
            logo_box = page.locator('header.header img.logo').bounding_box()
            rfq_box = page.locator('header.header .headerRfq').bounding_box()
            assert logo_box['x'] + logo_box['width'] + 8 <= rfq_box['x'], (path, width, 'logo overlaps RFQ')
            if width > 1100:
                nav_box = page.locator('header.header .desktopNav').bounding_box()
                assert logo_box['x'] + logo_box['width'] + 8 <= nav_box['x'], (path, width, 'logo overlaps navigation')
                assert nav_box['x'] + nav_box['width'] + 8 <= rfq_box['x'], (path, width, 'navigation overlaps RFQ')
            else:
                menu_box = page.locator('header.header .menuButton').bounding_box()
                assert rfq_box['x'] + rfq_box['width'] + 8 <= menu_box['x'], (path, width, 'RFQ overlaps menu')
            assert not errors, (path, width, errors)
            page.close()
    browser.close()

print('PASS: 8 page families, 1440/1024/768/390/320 brand chrome, clear navigation, loaded logos, no overflow or JS errors')
