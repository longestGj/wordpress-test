"""HTTP checks with no test fixtures for an integrated local WordPress instance."""
from urllib.parse import urljoin
import os
import requests
from bs4 import BeautifulSoup

base = os.environ.get('TIO2_BASE_URL', 'http://localhost:8080').rstrip('/')
assert base.startswith(('http://localhost:', 'http://127.0.0.1:')), 'Local site only'
pages = {
    '/contact/': ('Contact TiO2 Malaysia | General Inquiries', 'Contact TiO2 Malaysia'),
    '/privacy-policy/': ('Privacy Policy | TiO2 Malaysia', 'Privacy Policy'),
    '/ms/privacy-policy/': ('Dasar Privasi | TiO2 Malaysia', 'Dasar Privasi'),
    '/cookie-policy/': ('Cookie Policy | TiO2 Malaysia', 'Cookie Policy'),
    '/thank-you/': ('Thank You | TiO2 Malaysia', 'How can we help?'),
}
for path, (title, h1) in pages.items():
    response = requests.get(base + path, timeout=15)
    assert response.status_code == 200, (path, response.status_code)
    soup = BeautifulSoup(response.text, 'html.parser')
    assert soup.title and soup.title.get_text(strip=True) == title, path
    assert len(soup.select('main h1')) == 1 and soup.select_one('main h1').get_text(strip=True) == h1, path
    assert soup.select_one('link[rel=canonical]')['href'] == base + path, path
    assert soup.select_one('meta[name=description]'), path
    if path == '/contact/':
        assert soup.select_one('form[action$="admin-post.php"]'), 'Contact receiver missing'
        assert response.cookies.get('tio2_flow'), 'Contact session Cookie missing'
    if path == '/ms/privacy-policy/': assert soup.html.get('lang') == 'ms-MY'
    if path == '/thank-you/':
        assert 'noindex' in soup.select_one('meta[name=robots]')['content']
        assert 'received your' not in soup.get_text(' ', strip=True).lower()

for forged in ['/thank-you/?receipt=' + 'a' * 48, '/thank-you/?kind=quote&success=1']:
    response = requests.get(base + forged, timeout=15)
    assert response.status_code == 200
    soup = BeautifulSoup(response.text, 'html.parser')
    assert soup.select_one('main h1').get_text(strip=True) == 'How can we help?'

response = requests.get(base + '/this-page-does-not-exist-utility-check/', timeout=15)
assert response.status_code == 404
soup = BeautifulSoup(response.text, 'html.parser')
assert soup.select_one('main h1').get_text(strip=True) == 'Let’s help you find what you need.'
assert 'noindex' in soup.select_one('meta[name=robots]')['content']
for anchor in soup.select('main a[href]'):
    target = urljoin(base, anchor['href'])
    assert requests.get(target, timeout=15).status_code == 200, target
print('PASS: utility routes, SEO, form availability, forged receipts, and real 404 recovery')
