"""Read-only checks for the current catalog and its explicit local entry links."""
import csv
import json
import re
from pathlib import Path
from urllib.parse import unquote, urlsplit

ROOT = Path(__file__).resolve().parents[1]
PLAN = ROOT / 'planning'
catalog = list(csv.DictReader((PLAN / 'SITE_MAP.csv').open(encoding='utf-8-sig')))
seo = list(csv.DictReader((PLAN / 'SEO_MAP.csv').open(encoding='utf-8-sig')))
ids = {row['page_id'] for row in catalog}
assert len(catalog) == len(ids) == 59
assert {row['page_id'] for row in seo} == ids
assert len({row['url'] for row in catalog}) == 59
assert 'status' not in catalog[0], 'Status belongs only in each Page Spec'
accepted = 0
for row in catalog:
    spec = PLAN / row['spec']
    text = spec.read_text(encoding='utf-8')
    states = re.findall(r'^Status: (\w+)$', text, re.M)
    assert len(states) == 1 and states[0] in {'PLANNED','READY','BUILDING','REVIEW','ACCEPTED','RELEASED'}, row['page_id']
    accepted += states[0] == 'ACCEPTED'
    assert f'URL: `{row["url"]}`' in text
    for target in re.findall(r'\]\((?:<([^>]+)>|([^\s)]+))\)', text):
        href = next(x for x in target if x)
        if urlsplit(href).scheme or href.startswith('#'):
            continue
        assert (spec.parent / unquote(href.split('#')[0])).exists(), (spec, href)
for file in (ROOT/'data/process-applications').glob('*.json'):
    d=json.loads(file.read_text(encoding='utf-8'))
    assert (PLAN/'inputs'/d['source']).is_file(), file
for file in (ROOT/'data/products').glob('*.json'):
    d=json.loads(file.read_text(encoding='utf-8'))
    assert (PLAN/'inputs'/d['data']['source_note']).is_file(), file
for file in (ROOT/'data/pages').glob('*.json'):
    d=json.loads(file.read_text(encoding='utf-8'))
    assert (PLAN/'inputs'/d['source']).is_file(), file
for file in (ROOT/'scripts').glob('prepare-*.py'):
    code=file.read_text(encoding='utf-8')
    assert '.local/batch-source' not in code and '.local/m350-source' not in code, file
    assert 'raw.githubusercontent.com/longestGj/tio2mydesign' not in code, file
assert not any((PLAN/x).exists() for x in ['agents','skills','workflow-packages','workflow-tooling'])
print(f'PASS: {len(catalog)} unique page specs, {accepted} locally accepted; local references and adapter inputs resolve.')
