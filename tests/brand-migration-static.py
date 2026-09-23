"""Source audit: only reviewed seed fields changed; geography remains explicit."""
import copy
import csv
import json
import subprocess
from pathlib import Path

from bs4 import BeautifulSoup

root = Path(__file__).resolve().parents[1]
base_commit = 'b6786229076cc42e7e8f806f3136c9f5d5ec1805'
manifest = json.loads((root / 'data/brand-migration-patch.json').read_text(encoding='utf-8'))
assert len(manifest) == 51

for item in manifest:
    file = 'data/' + item['seed']
    old = json.loads(subprocess.check_output(['git', 'show', f'{base_commit}:{file}'], cwd=root, text=True, encoding='utf-8'))
    new = json.loads((root / file).read_text(encoding='utf-8'))
    rebuilt = copy.deepcopy(old)
    target = rebuilt.get('data', rebuilt)
    for field in ('seo_title', 'seo_description'):
        if field in item['changes']:
            pair = item['changes'][field]
            assert target[field] == pair['old'], (file, field)
            target[field] = pair['new']
    for patch in item['changes'].get('content', []):
        assert rebuilt['content'].count(patch['old']) == patch['count'], file
        rebuilt['content'] = rebuilt['content'].replace(patch['old'], patch['new'])
        if 'required_text' in rebuilt:
            rebuilt['required_text'] = [value.replace(patch['old'], patch['new']) for value in rebuilt['required_text']]
    if 'content' in item['changes'] and 'expected_text' in rebuilt:
        rebuilt['expected_text'] = BeautifulSoup(rebuilt['content'], 'html.parser').get_text(' ', strip=True)
    assert rebuilt == new, file
    for place in ('Taiping, Perak, Malaysia', 'Malaysia-origin', 'Port Klang'):
        assert old.get('content', '').count(place) == new.get('content', '').count(place), (file, place)
    assert 'TiO2 Malaysia' not in new.get('content', '')
    assert 'TiO2 Malaysia' not in target.get('seo_title', '')
    assert 'TiO2 Malaysia' not in target.get('seo_description', '')

active = [root / 'data/m350.json']
for folder in ('pages', 'process-applications', 'markets', 'documents', 'resources', 'requests', 'utility', 'products'):
    active.extend((root / 'data' / folder).glob('*.json'))
for file in active:
    if file.name == 'brand-migration-patch.json':
        continue
    seed = json.loads(file.read_text(encoding='utf-8'))
    assert 'TiO2 Malaysia' not in json.dumps(seed, ensure_ascii=False), file

with (root / 'planning/SEO_MAP.csv').open(encoding='utf-8-sig', newline='') as source:
    for row in csv.DictReader(source):
        for field in ('page_name', 'secondary_keywords', 'seo_title', 'meta_description', 'h1'):
            assert 'TiO2 Malaysia' not in row.get(field, ''), (row['page_id'], field)
for file in (root / 'planning/pages').glob('*.md'):
    for line in file.read_text(encoding='utf-8').splitlines():
        if line.startswith(('Title:', 'Meta:', 'H1:')):
            assert 'TiO2 Malaysia' not in line, (file.name, line)

print(f'PASS: {len(manifest)} seed records have only reviewed brand edits; location/origin/Port Klang references retained')
