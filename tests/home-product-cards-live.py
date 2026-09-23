"""Read-only browser and route check against a loopback WordPress preview."""
import os
import re
from html import unescape
from urllib.parse import urlparse

import requests
from playwright.sync_api import expect, sync_playwright


BASE = os.environ.get('HOME_CARDS_BASE', 'http://localhost:8080').rstrip('/')
assert urlparse(BASE).hostname in ('localhost', '127.0.0.1')
EXPECTED = {
    'M-350': '/products/m-350/', 'M-510': '/products/m-510/',
    'M-896': '/products/m-896/', 'M-996': '/products/m-996/',
    'M-2196': '/products/m-2196/', 'M-895': '/products/m-895/',
    'M-200': '/products/m-200/', 'M-108': '/products/m-108/',
    'M-210': '/products/m-210/', 'M-340': '/products/m-340/',
    'M-886': '/products/m-886/', 'M-52': '/products/m-52/',
    'M-2377': '/products/m-2377/', 'CR-901': '/products/cr-901/',
}

with sync_playwright() as playwright:
    browser = playwright.chromium.launch()
    desktop = browser.new_page(viewport={'width': 1440, 'height': 900})
    response = desktop.goto(BASE + '/', wait_until='networkidle')
    assert response and response.status == 200
    group = desktop.locator('.product-group').first
    assert group.locator('.product-body').is_visible()
    group.locator('summary').click()
    assert group.locator('.product-body').is_visible(), 'Desktop card collapsed when its title was clicked'
    links = desktop.locator('.product-grid .grades a')
    assert links.count() == 14, 'Every Home grade must be a link'
    actual = {link.inner_text(): link.get_attribute('href') for link in links.all()}
    assert actual == EXPECTED, actual
    for grade, path in EXPECTED.items():
        detail = requests.get(BASE + path, timeout=10)
        assert detail.status_code == 200 and urlparse(detail.url).path == path, path
        heading = re.search(r'<h1\b[^>]*>(.*?)</h1>', detail.text, re.I | re.S)
        assert heading, path
        title = unescape(re.sub(r'<[^>]+>', '', heading.group(1)))
        assert re.search(r'(?<![A-Za-z0-9-])' + re.escape(grade) + r'(?![A-Za-z0-9-])', title), (path, title)
    mobile = browser.new_page(viewport={'width': 390, 'height': 844})
    mobile.goto(BASE + '/', wait_until='networkidle')
    group = mobile.locator('.product-group').first
    assert not group.locator('.product-body').is_visible()
    expect(group.locator('summary span')).to_have_text('+')
    group.locator('summary').click()
    assert group.locator('.product-body').is_visible()
    expect(group.locator('summary span')).to_have_text('−')
    group.locator('summary').click()
    assert not group.locator('.product-body').is_visible()
    expect(group.locator('summary span')).to_have_text('+')
    assert not mobile.evaluate('document.documentElement.scrollWidth > innerWidth + 1')
    browser.close()

print('PASS: desktop cards remain visible, mobile accordion works, 14 grade links resolve')
