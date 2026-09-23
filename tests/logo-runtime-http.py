"""Read-only local contract for logo placement and icon metadata."""
import sys
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup

base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
response = requests.get(base + '/', timeout=20)
response.raise_for_status()
page = BeautifulSoup(response.text, 'html.parser')
header = page.select_one('header.header a.logoLink')
assert header and header['aria-label'] == 'TiO2Products — Home'
assert header.select_one('img.logo')['src'].endswith('/assets/logo-compact.svg')
assert header.select_one('img.logo')['alt'] == 'TiO2Products'
assert page.select_one('footer.footer img.logo')['src'].endswith('/assets/logo-reverse.svg')
assert page.select_one('link[rel="icon"][type="image/svg+xml"]')['href'].endswith('/assets/favicon.svg')
assert page.select_one('link[rel="apple-touch-icon"]')['href'].endswith('/assets/apple-touch-icon.png')
print('PASS: compact header, reverse footer, and symbol icons')
