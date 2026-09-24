"""Read-only sitemap preflight and post-install checks for the old domain."""
import argparse
import csv
import json
import xml.etree.ElementTree as ET
from pathlib import Path
from urllib.parse import urlsplit

import requests
from bs4 import BeautifulSoup

ROOT = Path(__file__).resolve().parents[1]
OLD = 'https://tio2malaysia.com'
NEW = 'https://tio2products.com'
session = requests.Session()


def locations(url):
    response = session.get(url, timeout=20)
    response.raise_for_status()
    return {urlsplit(node.text).path for node in ET.fromstring(response.content).iter()
            if node.tag.endswith('loc') and node.text}


with (ROOT / 'planning/SITE_MAP.csv').open(encoding='utf-8-sig', newline='') as source:
    paths = {row['url'] for row in csv.DictReader(source)
             if row['url'].startswith('/') and row['url'] != '/thank-you/'}
assert len(paths) == 57
parser = argparse.ArgumentParser()
parser.add_argument('--postdeploy', action='store_true')
args = parser.parse_args()
new_sitemaps = locations(NEW + '/wp-sitemap.xml')
new_paths = set().union(*(locations(NEW + path) for path in new_sitemaps))
assert new_paths == paths, (sorted(paths - new_paths), sorted(new_paths - paths))
if not args.postdeploy:
    old_paths = locations(OLD + '/sitemap.xml')
    assert old_paths == paths, (sorted(paths - old_paths), sorted(old_paths - paths))
    print('PASS: both live sitemaps contain the same 57 reviewed public paths; /thank-you/ is excluded')
else:
    for path in sorted(paths):
        response = session.get(OLD + path, allow_redirects=False, timeout=20)
        assert response.status_code == 301 and response.headers.get('Location') == NEW + path, (path, response.status_code, response.headers.get('Location'))
    for host in ('http://tio2malaysia.com', 'https://www.tio2malaysia.com'):
        response = session.get(host + '/resources/non-china-titanium-dioxide/', allow_redirects=False, timeout=20)
        assert response.status_code == 301 and response.headers.get('Location') == NEW + '/resources/non-china-titanium-dioxide/'
    target = '/resources/non-china-titanium-dioxide/'
    page = BeautifulSoup(session.get(NEW + target, timeout=20).text, 'html.parser')
    assert page.select_one('link[rel=canonical]')['href'] == NEW + target
    assert page.select_one('meta[property="og:url"]')['content'] == NEW + target
    graphs = [json.loads(node.string) for node in page.select('script[type="application/ld+json"]')]
    assert 'tio2malaysia.com' not in json.dumps(graphs)
    for path in ['/wp-sitemap.xml', *new_sitemaps]:
        assert 'tio2malaysia.com' not in session.get(NEW + path, timeout=20).text
    sitemap = session.get(OLD + '/sitemap.xml', allow_redirects=False, timeout=20)
    assert sitemap.status_code == 301 and sitemap.headers.get('Location') == NEW + '/wp-sitemap.xml'
    robots = session.get(OLD + '/robots.txt', timeout=20)
    assert robots.status_code == 200 and 'Sitemap: ' + NEW + '/wp-sitemap.xml' in robots.text
    print('PASS: 57 live path-preserving 301s, HTTP/www target, new canonical/OG/schema and sitemap/robots')
