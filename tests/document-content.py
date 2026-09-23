"""Static content contract for document guide seeds; no database writes."""
import json, re
from pathlib import Path
from bs4 import BeautifulSoup
root = Path(__file__).resolve().parents[1]
expected = {'DOC-REACH': ('reach', 11), 'DOC-TDS': ('tds-sds-coa', 10), 'DOC-COO': ('certificate-of-origin', 6)}
for identity, (slug, sections) in expected.items():
    seed = json.loads((root / 'data/documents' / (identity + '.json')).read_text(encoding='utf-8'))
    assert seed['identity'] == identity and seed['path'] == '/documents/' + slug + '/'
    soup = BeautifulSoup(seed['content'], 'html.parser')
    assert len(soup.select('h1')) == 1, identity
    assert len(soup.select('section.document-section')) == sections, identity
    assert not soup.select('script, iframe, [download]'), identity
    assert not re.search(r'Gate |allowlist|fail-closed|FULL_COPY|Source attribution', soup.get_text()), identity
    assert soup.select('a[href="/documents/"]'), identity
    assert seed['seo_title'] and seed['seo_description']
    if identity != 'DOC-COO':
        assert len(soup.select('details')) == 5 and not soup.select('details[open]')
    if identity == 'DOC-REACH':
        assert '5 September 2026' in soup.get_text()
        assert '2 September 2025' in soup.get_text()
        assert 'Northern Ireland' in soup.get_text()
    if identity == 'DOC-TDS':
        assert len(soup.select('input[type="checkbox"]')) == 3
        assert '[tio2_document_grades]' in seed['content']
        assert len(soup.select('table th')) >= 4
    if identity == 'DOC-COO':
        assert '7 September 2026' in soup.get_text()
print('PASS: three document seeds, headings, modules, FAQ, sources, selection and claim boundaries')
