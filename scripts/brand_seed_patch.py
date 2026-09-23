"""Apply reviewed brand edits when legacy source adapters regenerate active seeds."""
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
PATCHES = {
    item['seed']: item['changes']
    for item in json.loads((ROOT / 'data/brand-migration-patch.json').read_text(encoding='utf-8'))
}


def apply_brand_patch(seed_path, seed):
    changes = PATCHES.get(seed_path, {})
    target = seed.get('data', seed)
    for field in ('seo_title', 'seo_description'):
        if field not in changes:
            continue
        pair = changes[field]
        if target[field] == pair['old']:
            target[field] = pair['new']
        elif target[field] != pair['new']:
            raise ValueError(f'Unreviewed {field} in generated {seed_path}')

    for patch in changes.get('content', []):
        old, new, expected = patch['old'], patch['new'], patch['count']
        old_count = seed['content'].count(old)
        new_count = seed['content'].count(new)
        if old_count == expected and new_count == 0:
            seed['content'] = seed['content'].replace(old, new)
        elif old_count == 0 and new_count == expected:
            pass
        else:
            raise ValueError(f'Unreviewed brand passage in generated {seed_path}')
        if 'required_text' in seed:
            seed['required_text'] = [value.replace(old, new) for value in seed['required_text']]

    if 'TiO2 Malaysia' in json.dumps(seed, ensure_ascii=False):
        raise ValueError(f'Legacy website brand in generated {seed_path}')
    return seed
