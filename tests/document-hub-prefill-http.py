"""Read-only regression for Documents hub grade-to-request navigation."""
import sys
from urllib.parse import urlparse

from playwright.sync_api import sync_playwright


base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1', 'tio2products.com'}

with sync_playwright() as playwright:
    browser = playwright.chromium.launch()
    for width in (1440, 390):
        page = browser.new_page(viewport={'width': width, 'height': 900})
        errors = []
        page.on('pageerror', lambda error: errors.append(str(error)))
        response = page.goto(base + '/documents/', wait_until='networkidle', timeout=30000)
        assert response and response.status == 200
        page.locator('#continue-request').click()
        assert page.locator('#grade-error').inner_text() == 'Select a product grade to continue.'
        page.locator('#product-grade').select_option('M-350')
        with page.expect_navigation(wait_until='domcontentloaded'):
            page.locator('#continue-request').click()
        assert page.url.startswith(base + '/request-documents/?'), page.url
        assert page.locator('main h1').inner_text() == 'Request Documents', page.url
        assert page.locator('select[name="product_grade"]').input_value() == 'M-350', page.url
        assert not page.evaluate('document.documentElement.scrollWidth > innerWidth'), width
        assert not errors, (width, errors)
        page.close()
    browser.close()
print('PASS: Documents selected grade opens the request form with M-350 prefilled at desktop and mobile widths')
