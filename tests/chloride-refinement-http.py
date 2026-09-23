"""Read-only local contract for the focused Chloride page refinement."""
import json
import sys
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup

base = (sys.argv[1] if len(sys.argv) > 1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost', '127.0.0.1'}
session = requests.Session()
chloride = '/products/chloride-process-titanium-dioxide/'
sulfate = '/products/sulfate-process-titanium-dioxide/'
chloride_grades = ['M-350', 'M-510', 'M-896', 'M-895', 'M-200', 'M-210', 'M-340', 'M-886']
sulfate_grades = ['M-996', 'M-2196', 'M-108', 'M-52', 'M-2377']


def load(path):
    response = session.get(base + path, timeout=25)
    assert response.status_code == 200, (path, response.status_code)
    page = BeautifulSoup(response.text, 'html.parser')
    assert page.select_one('link[rel=canonical]')['href'] == base + path
    graphs = []
    for tag in page.select('script[type="application/ld+json"]'):
        value = json.loads(tag.string)
        graphs.extend(value.get('@graph', [value]) if isinstance(value, dict) else value)
    return page, graphs


page, graph = load(chloride)
main = page.select_one('main.topic-chloride')
assert main
assert len(main.select('h1')) == 1
assert main.h1.get_text(' ', strip=True) == 'Chloride Process Titanium Dioxide'
assert page.title.get_text(' ', strip=True) == 'Chloride Process Titanium Dioxide | TiO2Products'
assert page.select_one('meta[name=description]')['content'] == (
    'Explore eight Malaysia-origin chloride-process titanium dioxide grades, understand what the process label means, and review product information or request a quote.'
)
assert [a.get_text(' ', strip=True) for a in main.select('nav[aria-label=Breadcrumb] a')] == ['Home', 'Products']
sections = main.select('section.cl-module')
assert [section.get('id') for section in sections[:3]] == ['cl-01', 'cl-glance', 'cl-02']
glance = sections[1]
assert glance.h2.get_text(' ', strip=True) == 'Chloride Process at a Glance'
glance_text = glance.get_text(' ', strip=True)
for phrase in ('Process', 'Chloride', 'Production-route classification', '8 chloride-process grades', 'Process alone does not determine application performance'):
    assert phrase in glance_text, phrase
assert 'The process label helps identify how the base pigment was formed. It does not, by itself, determine how a grade will perform in your formulation or process.' in main.get_text(' ', strip=True)
assert 'Not sure / Need help' not in main.get_text(' ', strip=True)
assert main.select_one('#cl-05 a[href="/request-a-quote/"]').get_text(' ', strip=True) == 'Request a Quote'
grade_links = main.select('#cl-03 .cl-grade a[href^="/products/"]')
assert [a.get_text(' ', strip=True).removeprefix('View ') for a in grade_links] == chloride_grades
for link in grade_links:
    assert session.get(base + link['href'], timeout=20).status_code == 200
for path in ('/request-a-quote/', '/request-documents/', '/applications/', '/resources/chloride-vs-sulfate-titanium-dioxide/'):
    assert main.select_one(f'a[href="{path}"]')
    assert session.get(base + path, timeout=20).status_code == 200
sources = main.select_one('section.cl-sources')
assert sources and sources.h2.get_text(' ', strip=True) == 'Technical Sources'
assert sources.select_one('a[href^="https://www.epa.gov/"]')
assert sources.select_one('a[href^="https://bureau-industrial-transformation.jrc.ec.europa.eu/"]')


def check_process(path, expected):
    process_page, nodes = load(path)
    assert len([node for node in nodes if node.get('@type') == 'CollectionPage']) == 1, path
    assert len([node for node in nodes if node.get('@type') == 'BreadcrumbList']) == 1, path
    lists = [node for node in nodes if node.get('@type') == 'ItemList']
    assert len(lists) == 1, path
    assert [item['name'] for item in lists[0]['itemListElement']] == expected, path
    assert not any(node.get('@type') in {'Product', 'Offer', 'FAQPage'} for node in nodes), path
    assert 'CR-901' not in json.dumps(nodes), path
    return process_page


check_process(chloride, chloride_grades)
check_process(sulfate, sulfate_grades)
for application in ('coatings', 'plastics', 'masterbatch', 'printing-inks', 'paper'):
    application_page, nodes = load('/applications/titanium-dioxide-for-' + application + '/')
    assert len([node for node in nodes if node.get('@type') == 'WebPage']) == 1, application
    assert not any(node.get('@type') == 'CollectionPage' for node in nodes), application
    assert application_page.select_one('main h1'), application
    assert not application_page.select_one('section.cl-glance'), application

print('PASS: Chloride copy, links, 8 verified grades, both process schemas, and 5 application regressions')
