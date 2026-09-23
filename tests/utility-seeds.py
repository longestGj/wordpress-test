from pathlib import Path
from html.parser import HTMLParser
import json
import subprocess
import sys

root = Path(__file__).resolve().parents[1]
seed_files = list((root / 'data/utility').glob('*.json'))
original_seeds = {path: path.read_bytes() for path in seed_files}
try:
    subprocess.run([sys.executable, str(root / 'scripts/prepare-utility-pages.py')], check=True)
    seeds = {p.stem: json.loads(p.read_text(encoding='utf-8')) for p in seed_files}
finally:
    for path, original in original_seeds.items():
        path.write_bytes(original)
assert set(seeds) == {'CONTACT-001', 'LEGAL-PRIV-EN', 'LEGAL-PRIV-MS', 'LEGAL-COOKIE-EN', 'CONV-THANK'}
for page_id, seed in seeds.items():
    assert 'TiO2 Malaysia' not in json.dumps(seed, ensure_ascii=False), page_id
assert all('<h1>' in item['content'] for key, item in seeds.items() if key != 'CONV-THANK')
assert seeds['LEGAL-PRIV-MS']['parent'] == 'ms'
assert '23 September 2026' in seeds['LEGAL-PRIV-EN']['content']
assert '23 September 2026' in seeds['LEGAL-PRIV-MS']['content']
assert 'tio2_flow' in seeds['LEGAL-COOKIE-EN']['content']
assert 'tio2_my_consent_v1' not in seeds['LEGAL-COOKIE-EN']['content']
assert 'Google Analytics 4 (GA4) runs only after you choose Allow analytics' in seeds['LEGAL-COOKIE-EN']['content']
assert 'tio2_analytics_consent_v1' in seeds['LEGAL-COOKIE-EN']['content']
assert '_ga_SY6PZPX0VR' in seeds['LEGAL-COOKIE-EN']['content']
assert 'Google Analytics 4 is used only after you allow analytics' in seeds['LEGAL-PRIV-EN']['content']
assert 'Google Analytics 4 hanya digunakan selepas anda membenarkan analitik' in seeds['LEGAL-PRIV-MS']['content']
assert 'Borang ini tidak menghantar e-mel' in seeds['LEGAL-PRIV-MS']['content']
assert 'No email is sent by this form' in seeds['LEGAL-PRIV-EN']['content']
assert 'tio2_contact_form' in seeds['CONTACT-001']['content']
assert seeds['CONV-THANK']['content'].count('[tio2_thank_state kind=') == 4
assert 'REQUEST RECEIVED' not in seeds['CONV-THANK']['content']
print('PASS: utility page seeds, local data flow, bilingual parity markers, and state copy')
