"""Browser contract for basic GA4 consent: no Google request before opt-in."""
import unittest
from pathlib import Path

from playwright.sync_api import expect, sync_playwright


ROOT = Path(__file__).resolve().parents[1]
SCRIPT = (ROOT / 'wp-content/themes/tio2/assets/utility.js').read_text(encoding='utf-8')
MEASUREMENT_ID = 'G-SY6PZPX0VR'
HTML = f'''<!doctype html><html><body>
<button class="cookie-settings-open">Cookie Settings</button>
<aside id="analytics-consent" data-ga4-id="{MEASUREMENT_ID}" hidden>
  <button class="analytics-allow">Allow analytics</button>
  <button class="analytics-reject">Reject analytics</button>
</aside>
<dialog id="cookie-settings">
  <p id="analytics-status"></p>
  <button class="analytics-enable">Enable analytics</button>
  <button class="analytics-disable">Disable analytics</button>
  <button class="cookie-settings-close">Close</button>
</dialog>
<script>{SCRIPT}</script></body></html>'''


class AnalyticsConsentBrowser(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.playwright = sync_playwright().start()
        cls.browser = cls.playwright.chromium.launch()

    @classmethod
    def tearDownClass(cls):
        cls.browser.close()
        cls.playwright.stop()

    def page(self, html=HTML):
        context = self.browser.new_context()
        page = context.new_page()
        requests = []
        page.route('https://tio2products.com/**', lambda route: route.fulfill(status=200, content_type='text/html', body=html))

        def google_request(route):
            requests.append(route.request.url)
            route.fulfill(status=200, content_type='application/javascript', body='')

        page.route('https://www.googletagmanager.com/**', google_request)
        page.goto('https://tio2products.com/thank-you/?receipt=private-token', wait_until='domcontentloaded')
        return context, page, requests

    def test_rejection_never_contacts_google_and_survives_reload(self):
        context, page, requests = self.page()
        try:
            expect(page.locator('#analytics-consent')).to_be_visible()
            self.assertEqual(requests, [])
            self.assertIsNone(page.evaluate('window.dataLayer'))
            page.locator('.analytics-reject').click()
            expect(page.locator('#analytics-consent')).to_be_hidden()
            self.assertEqual(page.evaluate("localStorage.getItem('tio2_analytics_consent_v1')"), 'denied')
            page.evaluate("document.cookie = '_ga=leftover; path=/'")
            page.reload(wait_until='domcontentloaded')
            self.assertEqual(requests, [])
            self.assertNotIn('_ga=', page.evaluate('document.cookie'))
            expect(page.locator('#analytics-consent')).to_be_hidden()
        finally:
            context.close()

    def test_acceptance_loads_once_and_excludes_query_parameters(self):
        context, page, requests = self.page()
        try:
            page.locator('.analytics-allow').click()
            page.wait_for_function('window.dataLayer && window.dataLayer.length >= 4')
            self.assertEqual(requests, [f'https://www.googletagmanager.com/gtag/js?id={MEASUREMENT_ID}'])
            self.assertEqual(page.evaluate("localStorage.getItem('tio2_analytics_consent_v1')"), 'granted')
            queued = page.evaluate('window.dataLayer.map(item => Array.from(item))')
            self.assertEqual(queued[0][:2], ['consent', 'default'])
            self.assertEqual(queued[0][2]['analytics_storage'], 'denied')
            self.assertEqual(queued[1][:2], ['consent', 'update'])
            self.assertEqual(queued[1][2]['analytics_storage'], 'granted')
            self.assertEqual(queued[1][2]['ad_storage'], 'denied')
            config = next(item for item in queued if item[0] == 'config')
            self.assertEqual(config[1], MEASUREMENT_ID)
            self.assertEqual(config[2]['page_location'], 'https://tio2products.com/thank-you/')
            self.assertNotIn('private-token', str(queued))
        finally:
            context.close()

    def test_withdrawal_deletes_analytics_cookies_and_blocks_next_page(self):
        context, page, requests = self.page()
        try:
            page.locator('.analytics-allow').click()
            page.wait_for_function('window.dataLayer && window.dataLayer.length >= 4')
            page.evaluate("document.cookie = '_ga=one; path=/'; document.cookie = '_ga_SY6PZPX0VR=two; path=/'")
            page.locator('.cookie-settings-open').click()
            page.locator('.analytics-disable').click()
            page.wait_for_load_state('domcontentloaded')
            self.assertEqual(page.evaluate("localStorage.getItem('tio2_analytics_consent_v1')"), 'denied')
            self.assertNotIn('_ga=', page.evaluate('document.cookie'))
            self.assertNotIn('_ga_SY6PZPX0VR=', page.evaluate('document.cookie'))
            self.assertEqual(len(requests), 1)
        finally:
            context.close()

    def test_malay_policy_reports_rejected_choice_in_malay(self):
        context, page, requests = self.page(HTML.replace('<html><body>', '<html lang="ms-MY"><body>'))
        try:
            page.locator('.analytics-reject').click()
            page.locator('.cookie-settings-open').click()
            expect(page.locator('#analytics-status')).to_have_text('Analitik dimatikan.')
            self.assertEqual(requests, [])
        finally:
            context.close()


if __name__ == '__main__':
    unittest.main()
