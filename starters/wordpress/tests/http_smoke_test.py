"""Verify the HTTP checker rejects broken rendering, independently of WordPress."""
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer
from pathlib import Path
import subprocess
import sys
import threading
import unittest


SHELL = '<html><head><title>Site</title><meta name="robots" content="noindex"></head><body><a href="#main-content">Skip</a><main id="main-content">{}</main></body></html>'


class Handler(BaseHTTPRequestHandler):
    page = ''

    def do_GET(self):
        if self.path == '/?p=999999999':
            self.send_error(404)
            return
        self.send_response(200)
        self.end_headers()
        self.wfile.write(SHELL.format(self.page if self.path == '/known/' else '').encode())

    def log_message(self, *args):
        pass


class RenderingTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.server = ThreadingHTTPServer(('127.0.0.1', 0), Handler)
        cls.thread = threading.Thread(target=cls.server.serve_forever, daemon=True)
        cls.thread.start()

    @classmethod
    def tearDownClass(cls):
        cls.server.shutdown()
        cls.server.server_close()
        cls.thread.join()

    def check_output(self, content):
        Handler.page = content
        return subprocess.run([
            sys.executable, str(Path(__file__).with_name('http-smoke.py')),
            '--base-url', f'http://127.0.0.1:{self.server.server_port}',
            '--page-path', '/known/', '--expected-h1', 'Sample & test',
            '--expected-text', 'Approved body.'
        ], capture_output=True, text=True)

    def test_real_heading_and_body_pass(self):
        result = self.check_output('<h1>Sample &amp; <em>test</em></h1><p>Approved <strong>body.</strong></p>')
        self.assertEqual(result.returncode, 0, result.stderr)

    def test_missing_body_fails(self):
        result = self.check_output('<h1>Sample &amp; test</h1>')
        self.assertNotEqual(result.returncode, 0)
        self.assertIn('Page body mismatch', result.stderr)

    def test_missing_heading_fails(self):
        result = self.check_output('<p>Approved body.</p>')
        self.assertNotEqual(result.returncode, 0)
        self.assertIn('Page H1 mismatch', result.stderr)

    def test_script_text_cannot_substitute_for_body(self):
        result = self.check_output('<h1>Sample &amp; test</h1><script>Approved body.</script>')
        self.assertNotEqual(result.returncode, 0)
        self.assertIn('Page body mismatch', result.stderr)


if __name__ == '__main__':
    unittest.main()
