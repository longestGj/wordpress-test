"""Read-only check that every migrated active seed matches local WordPress SEO output."""
import json
import re
import sys
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup

root = Path(__file__).resolve().parents[1]
base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
manifest = json.loads((root / 'data/brand-migration-patch.json').read_text(encoding='utf-8'))
assert len(manifest) == 51
normalize = lambda value: re.sub(r'\s+', ' ', value).strip()
session = requests.Session()

for item in manifest:
    route = '/products/' if item['seed'] == 'pages/products.json' else item['path']
    seed = json.loads((root / 'data' / item['seed']).read_text(encoding='utf-8'))
    fields = seed.get('data', seed)
    response = session.get(base + route, timeout=25)
    response.raise_for_status()
    page = BeautifulSoup(response.text, 'html.parser')
    assert page.title.get_text(strip=True) == fields['seo_title'], route
    if 'seo_description' in item['changes']:
        assert page.select_one('meta[name="description"]')['content'] == fields['seo_description'], route
    if item['post_type'] == 'page' and 'content' in item['changes']:
        visible = normalize(page.select_one('main').get_text(' ', strip=True))
        for patch in item['changes']['content']:
            summary = normalize(BeautifulSoup(patch['new'], 'html.parser').get_text(' ', strip=True))
            if summary:
                assert summary in visible, (route, summary[:110])
        if 'expected_text' in seed:
            assert seed['expected_text'] == BeautifulSoup(seed['content'], 'html.parser').get_text(' ', strip=True), route

print(f'PASS: SEO and reviewed brand copy for {len(manifest)} migrated seed records')
