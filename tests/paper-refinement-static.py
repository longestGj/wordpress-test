"""Prove the Paper seed changed only at the four authorized copy locations."""
import json
import subprocess
from pathlib import Path
from bs4 import BeautifulSoup

root = Path(__file__).resolve().parents[1]
# Fixed pre-refinement seed, independent of the current branch HEAD.
base_commit = '3c8e63ab1041a112f1be6efe3786fd34d5d9eb2d'
old = json.loads(subprocess.check_output(
    ['git', 'show', f'{base_commit}:data/process-applications/paper.json'],
    cwd=root, text=True, encoding='utf-8'))
new = json.loads((root / 'data/process-applications/paper.json').read_text(encoding='utf-8'))
brand_manifest = json.loads((root / 'data/brand-migration-patch.json').read_text(encoding='utf-8'))
brand = next(item for item in brand_manifest if item['seed'] == 'process-applications/paper.json')
pre_brand = dict(new)
pre_brand['content'] = new['content']
for change in reversed(brand['changes']['content']):
    assert pre_brand['content'].count(change['new']) == change['count']
    pre_brand['content'] = pre_brand['content'].replace(change['new'], change['old'])
pre_brand['expected_text'] = BeautifulSoup(pre_brand['content'], 'html.parser').get_text(' ', strip=True)
patch = json.loads((root / 'data/paper-refinement-patch.json').read_text(encoding='utf-8'))
content = old['content']
for entry in patch:
    assert content.count(entry['old']) == 1, entry['old'][:80]
    content = content.replace(entry['old'], entry['new'], 1)
assert content == pre_brand['content']
assert {key for key in pre_brand if pre_brand[key] != old[key]} == {'seo_title', 'seo_description', 'content', 'expected_text'}
for start, end in [('topic-module-PAPER-02', 'topic-module-PAPER-10'),
                   ('topic-module-PAPER-11', None)]:
    old_part = old['content'][old['content'].index(start):old['content'].index(end) if end else None]
    new_part = pre_brand['content'][pre_brand['content'].index(start):pre_brand['content'].index(end) if end else None]
    assert old_part == new_part, start
assert old['content'].count('The detailed framework centres on decorative and lightweight paper') == 1
assert pre_brand['content'].count('The detailed framework centres on decorative and lightweight paper') == 1
assert old_part.count('https://') == new_part.count('https://') == 7
print('PASS: Paper body modules 02–09/11, grade table, technical sources and scope are unchanged')
