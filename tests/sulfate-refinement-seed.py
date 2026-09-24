"""Focused seed contract for the accepted Sulfate process page."""
import json
from pathlib import Path

from bs4 import BeautifulSoup

root = Path(__file__).resolve().parents[1]
seed = json.loads((root / 'data/process-applications/sulfate.json').read_text(encoding='utf-8'))
main = BeautifulSoup(seed['content'], 'html.parser')
text = main.get_text(' ', strip=True)

assert seed['page_id'] == 'PRODUCT-PROC-SU'
assert seed['seo_title'] == 'Sulfate Process Titanium Dioxide Grades | TiO2Products'
assert seed['seo_description'] == 'Explore five Malaysia-origin sulfate process titanium dioxide Grades by application, then review product details, request documents or request a quote.'
assert [h.get_text(' ', strip=True) for h in main.select('h1')] == ['Sulfate Process Titanium Dioxide']
assert seed['expected_text'] == text

sections = main.select('section')
assert [section.get('class', [])[-1] for section in sections[:3]] == ['m1', 'm-glance', 'm2']
glance = sections[1]
assert glance.h2.get_text(' ', strip=True) == 'Sulfate Process at a Glance'
assert [(dt.get_text(' ', strip=True), dt.find_next_sibling('dd').get_text(' ', strip=True)) for dt in glance.select('dt')] == [
    ('Process', 'Sulfate'),
    ('Also spelled', 'Sulphate'),
    ('Portfolio', '5 sulfate-process Grades'),
    ('Evaluation principle', 'Process alone does not determine application performance'),
]
assert "Review each Grade's documented properties and application evidence before evaluation." in glance.get_text(' ', strip=True)

assert 'The process label identifies a production route. It does not by itself establish the best Grade, application fit, performance, cost, environmental result or equivalence.' in text
grade_cards = main.select('.grades article')
assert [(card.h3.get_text(' ', strip=True), card.p.get_text(' ', strip=True)) for card in grade_cards] == [
    ('M-996', 'Documented for evaluation in industrial coatings, powder coatings, and exterior or interior architectural coatings.'),
    ('M-2196', 'Documented for evaluation in solvent-based furniture and industrial paints.'),
    ('M-108', 'Documented for masterbatch and compounds, polyolefin and PVC film, and plastics requiring high thermal stability.'),
    ('M-52', 'Documented for printing inks, can coatings and high-gloss interior architectural coatings.'),
    ('M-2377', 'Documented for evaluation across coatings, plastics, masterbatch, printing inks and paper.'),
]
assert [card.select_one('a')['href'] for card in grade_cards] == [f'/products/{grade}/' for grade in ('m-996', 'm-2196', 'm-108', 'm-52', 'm-2377')]
assert not any(term in text for term in ('Not sure / Need help', 'Additional Requirements', 'Product Grade'))
assert 'Document requests are reviewed for a selected Grade.' in text
assert 'If you already have a Grade in mind, include it with your quotation request.' in text
source = main.select_one('.m2 a[href]')
assert source and source['href'] == 'https://www.epa.gov/sites/default/files/2018-05/documents/2006_eg-plan-tsd_final_dec-2006.pdf'
assert 'Federal Trade Commission' not in text
assert 'Source last reviewed: 24 September 2026.' in text
print('PASS: Sulfate seed scope, glance, five frozen grades, durable forms copy, EPA source')
