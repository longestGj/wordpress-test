"""Exercise the prepared legacy Nginx 301 map in a disposable local container."""
import csv
import socket
import subprocess
import tempfile
from datetime import datetime, timedelta, timezone
from pathlib import Path
from uuid import uuid4

import requests
from cryptography import x509
from cryptography.hazmat.primitives import hashes, serialization
from cryptography.hazmat.primitives.asymmetric import rsa
from cryptography.x509.oid import NameOID

ROOT = Path(__file__).resolve().parents[1]
CONFIG = ROOT / 'deploy-legacy/tio2-production-adoption.conf'
session = requests.Session()
session.trust_env = False  # Keep Host-header tests on the local Docker port.
with (ROOT / 'planning/SITE_MAP.csv').open(encoding='utf-8-sig', newline='') as source:
    paths = [row['url'] for row in csv.DictReader(source)
             if row['url'].startswith('/') and row['url'] != '/thank-you/']
assert len(paths) == 57


def docker(*args):
    result = subprocess.run(['docker', *args], text=True, capture_output=True)
    if result.returncode:
        raise RuntimeError(result.stdout + result.stderr)
    return result.stdout.strip()


with tempfile.TemporaryDirectory(dir=ROOT / '.local') as directory:
    fixture = Path(directory)
    cert_dir = fixture / 'cert'
    upstream_dir = fixture / 'upstream'
    cert_dir.mkdir()
    upstream_dir.mkdir()
    key = rsa.generate_private_key(public_exponent=65537, key_size=2048)
    (cert_dir / 'privkey.pem').write_bytes(key.private_bytes(
        serialization.Encoding.PEM, serialization.PrivateFormat.TraditionalOpenSSL, serialization.NoEncryption()
    ))
    name = x509.Name([x509.NameAttribute(NameOID.COMMON_NAME, 'tio2malaysia.com')])
    cert = (x509.CertificateBuilder().subject_name(name).issuer_name(name).public_key(key.public_key())
            .serial_number(x509.random_serial_number()).not_valid_before(datetime.now(timezone.utc) - timedelta(days=1))
            .not_valid_after(datetime.now(timezone.utc) + timedelta(days=1)).sign(key, hashes.SHA256()))
    (cert_dir / 'fullchain.pem').write_bytes(cert.public_bytes(serialization.Encoding.PEM))
    (upstream_dir / 'web-upstream.conf').write_text('proxy_pass http://127.0.0.1:3000;\n', encoding='utf-8')
    mounts = [
        '--mount', f'type=bind,source={CONFIG},target=/etc/nginx/conf.d/tio2-production-adoption.conf,readonly',
        '--mount', f'type=bind,source={cert_dir},target=/etc/letsencrypt/live/tio2malaysia.com,readonly',
        '--mount', f'type=bind,source={upstream_dir},target=/etc/tio2-production,readonly',
    ]
    docker('run', '--rm', *mounts, 'nginx:stable-alpine', 'nginx', '-t')
    with socket.socket() as sock:
        sock.bind(('127.0.0.1', 0))
        port = sock.getsockname()[1]
    name = 'tio2-legacy-redirect-test-' + uuid4().hex[:8]
    docker('run', '-d', '--rm', '--name', name, '-p', f'127.0.0.1:{port}:80', *mounts, 'nginx:stable-alpine')
    try:
        base = f'http://127.0.0.1:{port}'
        for path in paths:
            response = session.get(base + path, headers={'Host': 'tio2malaysia.com'}, allow_redirects=False, timeout=10)
            assert response.status_code == 301, (path, response.status_code)
            assert response.headers['Location'] == 'https://tio2products.com' + path, (path, response.headers['Location'])
        response = session.get(base + '/resources/non-china-titanium-dioxide/?ref=old',
                                headers={'Host': 'www.tio2malaysia.com'}, allow_redirects=False, timeout=10)
        assert response.status_code == 301
        assert response.headers['Location'] == 'https://tio2products.com/resources/non-china-titanium-dioxide/?ref=old'
        unknown = session.get(base + '/unknown-legacy-path/', headers={'Host': 'tio2malaysia.com'},
                               allow_redirects=False, timeout=10)
        assert unknown.status_code == 301
        assert unknown.headers['Location'] == 'https://tio2malaysia.com/unknown-legacy-path/'
        sitemap = session.get(base + '/sitemap.xml', headers={'Host': 'tio2malaysia.com'},
                               allow_redirects=False, timeout=10)
        assert sitemap.status_code == 301
        assert sitemap.headers['Location'] == 'https://tio2products.com/wp-sitemap.xml'
        robots = session.get(base + '/robots.txt', headers={'Host': 'tio2malaysia.com'}, timeout=10)
        assert robots.status_code == 200
        assert 'Sitemap: https://tio2products.com/wp-sitemap.xml' in robots.text
        assert 'tio2malaysia.com' not in robots.text
    finally:
        docker('stop', name)

print('PASS: candidate Nginx config, direct path-preserving 301, query, unmatched path, sitemap and robots')
