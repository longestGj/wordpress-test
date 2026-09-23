"""Exercise the real Contact receiver, then delete the uniquely named test record.

Only localhost:8080 is accepted. A local-only WP-CLI guard runs before POST.
"""
from pathlib import Path
from uuid import uuid4
import subprocess
import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright

ROOT = Path(__file__).resolve().parents[1]
BASE = 'http://localhost:8080'
subject = 'Codex utility QA ' + uuid4().hex
OUT = ROOT / '.local' / 'utility-http'
OUT.mkdir(parents=True, exist_ok=True)

def cli(*args):
    return subprocess.run(['docker', 'compose', 'run', '--rm', *args], cwd=ROOT, capture_output=True, text=True, check=True)

cli('cli', 'eval-file', '/workspace/tests/local-only.php')
session = requests.Session()
def capture(label):
    with sync_playwright() as playwright:
        browser = playwright.chromium.launch(headless=True)
        context = browser.new_context(viewport={'width':390,'height':900})
        context.add_cookies([{'name':'tio2_flow','value':session.cookies.get('tio2_flow'),'url':BASE}])
        page = context.new_page()
        page.goto(BASE + '/contact/#general-inquiry', wait_until='networkidle')
        page.locator('#general-inquiry').scroll_into_view_if_needed()
        page.screenshot(path=str(OUT / f'CONTACT-001-{label}-390.png'), full_page=True)
        browser.close()

fields = {
    'action': 'tio2_general_inquiry',
    'full_name': 'Acceptance Test', 'company': 'Local QA',
    'business_email': 'qa@example.com', 'country': 'Malaysia',
    'subject': subject, 'message': 'Local fixture; remove after verification.',
}
successful_post = False
try:
    page = session.get(BASE + '/contact/', timeout=15)
    assert page.status_code == 200 and session.cookies.get('tio2_flow')
    soup = BeautifulSoup(page.text, 'html.parser')
    nonce = soup.select_one('form.contact-form input[name=tio2_nonce]')['value']
    fields['tio2_nonce'] = nonce
    bad = dict(fields, business_email='invalid', message='')
    response = session.post(BASE + '/wp-admin/admin-post.php', data=bad, allow_redirects=False, timeout=15)
    assert response.status_code == 303
    page = session.get(BASE + '/contact/', timeout=15)
    assert 'Please check the form' in page.text and 'Enter a valid email address' in page.text
    assert subject in page.text and 'aria-invalid="true"' in page.text
    capture('validation')
    response = session.post(BASE + '/wp-admin/admin-post.php', data=dict(fields, tio2_nonce='invalid'), allow_redirects=False, timeout=15)
    assert response.status_code == 303
    page = session.get(BASE + '/contact/', timeout=15)
    assert 'Please reload the form and try again.' in page.text
    response = session.post(BASE + '/wp-admin/admin-post.php', data=fields, allow_redirects=False, timeout=15)
    assert response.status_code == 303
    successful_post = True
    page = session.get(BASE + '/contact/', timeout=15)
    soup = BeautifulSoup(page.text, 'html.parser')
    assert soup.select_one('.utility-notice[role=status]')
    assert 'Your inquiry has been received' in soup.select_one('.utility-notice').get_text(' ', strip=True)
    assert 'This confirmation does not mean an email was sent.' in page.text
    capture('received')
finally:
    result = cli('-e', 'TIO2_TEST_SUBJECT=' + subject, 'cli', 'eval-file', '/workspace/tests/utility-form-fixture.php')
    assert 'No matching Contact test record' not in result.stdout if successful_post else True
    assert 'Verified and removed one private local inquiry' in result.stdout if successful_post else True
print('PASS: invalid fields and nonce reject; valid POST creates private local receipt; fixture verified and removed')
