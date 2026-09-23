"""Prove the Paper seed changed only at the four authorized copy locations."""
import json
import subprocess
from pathlib import Path

root = Path(__file__).resolve().parents[1]
old = json.loads(subprocess.check_output(
    ['git', 'show', 'HEAD:data/process-applications/paper.json'],
    cwd=root, text=True, encoding='utf-8'))
new = json.loads((root / 'data/process-applications/paper.json').read_text(encoding='utf-8'))
patch = json.loads((root / 'data/paper-refinement-patch.json').read_text(encoding='utf-8'))
content = old['content']
for entry in patch:
    assert content.count(entry['old']) == 1, entry['old'][:80]
    content = content.replace(entry['old'], entry['new'], 1)
assert content == new['content']
assert {key for key in new if new[key] != old[key]} == {'seo_title', 'seo_description', 'content', 'expected_text'}
for start, end in [('topic-module-PAPER-02', 'topic-module-PAPER-10'),
                   ('topic-module-PAPER-11', None)]:
    old_part = old['content'][old['content'].index(start):old['content'].index(end) if end else None]
    new_part = new['content'][new['content'].index(start):new['content'].index(end) if end else None]
    assert old_part == new_part, start
assert old['content'].count('The detailed framework centres on decorative and lightweight paper') == 1
assert new['content'].count('The detailed framework centres on decorative and lightweight paper') == 1
assert old_part.count('https://') == new_part.count('https://') == 7
print('PASS: Paper body modules 02–09/11, grade table, technical sources and scope are unchanged')
