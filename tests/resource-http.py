"""Resource acceptance: actual HTTP, approved source copy, links and head output.

Missing routes, omitted paragraphs/table cells, leaked prototype controls and
unavailable CTA targets must fail these checks. Read-only against local WordPress.
"""
import json
import re
from pathlib import Path
import requests
from bs4 import BeautifulSoup

ROOT = Path(__file__).resolve().parents[1]
BASE = 'http://localhost:8080'
SLUGS = [
    'non-china-titanium-dioxide', 'chloride-vs-sulfate-titanium-dioxide',
    'chemours-titanium-dioxide-alternatives', 'ti-pure-r-706-alternative',
    'eu-titanium-dioxide-anti-dumping-duty',
    'uk-titanium-dioxide-anti-dumping-investigation',
    'india-titanium-dioxide-anti-dumping-duty',
    'brazil-titanium-dioxide-anti-dumping-duty',
]

def normalized(text):
    return re.sub(r'\s+([.,;:])', r'\1', ' '.join(text.split()))

def verify(slug):
    path = '/resources/' + slug + '/'
    response = requests.get(BASE + path, timeout=25)
    assert response.status_code == 200, (slug, 'missing resource route', response.status_code)
    seed = json.loads((ROOT / 'data/resources' / (slug + '.json')).read_text(encoding='utf-8'))
    html = BeautifulSoup(response.text, 'html.parser')
    assert len(html.select('main')) == len(html.select('h1')) == 1, slug
    assert html.h1.get_text(strip=True) == seed['h1'], slug
    assert html.title.get_text() == seed['seo_title'], slug
    assert html.select_one('meta[name=description]')['content'] == seed['seo_description'], slug
    assert len(html.select('meta[name=description]')) == 1, slug
    assert html.select_one('link[rel=canonical]')['href'] == BASE + path, slug
    assert 'noindex' in html.select_one('meta[name=robots]')['content'], slug
    main = html.select_one('main')
    assert main.select_one('.resource-breadcrumb [aria-current=page]').get_text(strip=True) == seed['title'], (slug, 'breadcrumb label')
    visible = normalized(main.get_text(' ', strip=True))
    for text in seed['required_text']:
        assert normalized(text) in visible, (slug, 'missing approved text', text)
    for token in ['data-gate', 'planningState', 'processRoutes', 'tailwindcss.com']:
        assert token not in response.text, (slug, 'prototype leakage', token)
    for link in main.select('a[href]'):
        target = link['href']
        if target.startswith('#'):
            assert len(target) > 1 and html.find(id=target[1:]), (slug, 'broken anchor', target)
        elif target.startswith('/') or target.startswith(BASE):
            url = BASE + target if target.startswith('/') else target
            assert requests.get(url, timeout=25).status_code == 200, (slug, 'broken internal link', target)
    schemas = [json.loads(x.string) for x in html.select('script[type="application/ld+json"]')]
    graph = [n for s in schemas for n in s.get('@graph', [s])]
    assert {n['@type'] for n in graph} == {'WebPage', 'BreadcrumbList'}, slug
    crumbs = next(n for n in graph if n['@type'] == 'BreadcrumbList')['itemListElement']
    assert [n['item'] for n in crumbs] == [BASE+'/', BASE+'/resources/', BASE+path], slug
    assert not main.select('a[aria-disabled]'), slug
    if slug == 'chloride-vs-sulfate-titanium-dioxide':
        questions = main.select('#buyer-questions details')
        assert len(questions) == 5, 'five buyer questions'
        environmental = questions[-1]
        assert environmental.summary.get_text(' ', strip=True) == 'Does the production route alone establish lower environmental impact?'
        environmental_text = environmental.get_text(' ', strip=True)
        assert 'not an environmental ranking' in environmental_text
        assert 'system boundary' in environmental_text and 'producer, site and methodology' in environmental_text
        assert len(main.select('table tbody tr')) == 6, 'six evidence rows'
        process_links = main.select('a[href*="process-titanium-dioxide"]')
        assert len(process_links) in (0, 2), 'process pair must be atomic'
        decisions = main.select('#workflow .proc-decision-card')
        assert [a['href'] for card in decisions for a in card.select('a[href]')] == [
            '/applications/', '/request-documents/'
        ]
        assert len(decisions) == 3 and not decisions[2].select('a[href]')
        examples = main.select('#application-overlap .proc-external-example')
        assert [example.get_text(' ', strip=True) for example in examples] == [
            'External industry example — LB Group BLR-886',
            'External industry example — LB Group LR-108',
        ]
        assert 'LR-108 is an LB Group external example, not a TiO2Products grade.' in main.get_text(' ', strip=True)
        assert 'M-108' in main.get_text(' ', strip=True)
        for target in ('/applications/', '/request-documents/'):
            assert requests.get(BASE + target, allow_redirects=False, timeout=25).status_code == 200
    if slug == 'chemours-titanium-dioxide-alternatives':
        branch = main.select_one('section.chemours-r706-branch')
        assert branch and branch.h2.get_text(' ', strip=True) == 'Is Ti-Pure R-706 Your Current Reference?'
        assert branch.select_one('a[href="/resources/ti-pure-r-706-alternative/"]')
        assert 'does not identify an automatic replacement or establish equivalence' in branch.get_text(' ', strip=True)
        application = main.select_one('.chemours-app-path a[href="/applications/"]')
        assert application and application.get_text(' ', strip=True) == 'Explore Application Evaluation'
        assert 'IKHLAS grades' not in main.get_text(' ', strip=True)
        assert not re.search(r'Ti-Pure R-\d+\s*(?:→|->)\s*M-\d+', main.get_text(' ', strip=True))
        assert 'We are not affiliated with, authorized by or endorsed by Chemours.' in main.get_text(' ', strip=True)
        assert 'TiO2 Malaysia' not in response.text
        for target in ('/resources/ti-pure-r-706-alternative/', '/applications/', '/products/', '/request-documents/'):
            assert requests.get(BASE + target, allow_redirects=False, timeout=25).status_code == 200
    if slug == 'ti-pure-r-706-alternative':
        coatings = main.select_one('.r706-coatings-path a[href="/applications/titanium-dioxide-for-coatings/"]')
        assert coatings and coatings.get_text(' ', strip=True) == 'Review the Coatings Evaluation Framework'
        generic = main.select_one('.r706-generic-path a[href="/resources/chemours-titanium-dioxide-alternatives/"]')
        assert generic and 'not R-706' in generic.parent.get_text(' ', strip=True)
        documents = main.select_one('.r706-document-context')
        assert documents and 'one primary product grade' in documents.get_text(' ', strip=True)
        assert 'separate requests' in documents.get_text(' ', strip=True)
        sample = main.select_one('.r706-sample-context')
        assert sample and 'If you have not selected a grade yet' in sample.get_text(' ', strip=True)
        assert 'R-706 reference' in sample.get_text(' ', strip=True)
        assert 'Additional Requirements' not in main.get_text(' ', strip=True)
        assert 'IKHLAS Grade' not in main.get_text(' ', strip=True)
        assert not re.search(r'R-706\s*(?:→|->)\s*M-\d+', main.get_text(' ', strip=True))
        assert 'This independent guide is not affiliated with or endorsed by Chemours.' in main.get_text(' ', strip=True)
        assert 'TiO2 Malaysia' not in response.text
        for target in ('/applications/titanium-dioxide-for-coatings/',
                       '/resources/chemours-titanium-dioxide-alternatives/',
                       '/products/', '/request-documents/', '/request-sample/'):
            assert requests.get(BASE + target, allow_redirects=False, timeout=25).status_code == 200
        sample_form = BeautifulSoup(requests.get(BASE + '/request-sample/', timeout=25).text, 'html.parser')
        assert sample_form.select_one('select[name="product_grade"] option[value="I do not know the grade"]')
    if slug == 'non-china-titanium-dioxide':
        assert 'TiO2 Malaysia' not in response.text, 'legacy website brand'
        assert 'tio2malaysia.com' not in response.text, 'legacy domain reference'
        applications = ['coatings', 'plastics', 'masterbatch', 'printing-inks', 'paper']
        assert [a['href'] for a in main.select('#origin-5 .origin-links a[href]')] == [
            '/applications/titanium-dioxide-for-' + application + '/' for application in applications
        ]
        assert not any('/applications/' + application + '/' in response.text for application in applications)
        cards = main.select('#origin-7 .origin-markets .origin-card')
        assert len(cards) == 4
        expected = [
            ('/markets/european-union/', '/resources/eu-titanium-dioxide-anti-dumping-duty/'),
            ('/markets/united-kingdom/', '/resources/uk-titanium-dioxide-anti-dumping-investigation/'),
            ('/markets/india/', '/resources/india-titanium-dioxide-anti-dumping-duty/'),
            ('/markets/brazil/', '/resources/brazil-titanium-dioxide-anti-dumping-duty/'),
        ]
        assert [tuple(a['href'] for a in card.select('a[href]')) for card in cards] == expected
        entity = main.select_one('section.origin-entity')
        assert entity and entity.select_one('a[href="/about/"]')
        assert 'Company location does not by itself establish the origin of every product, lot or shipment.' in entity.get_text(' ', strip=True)
        decision_cards = main.select('#origin-8 .origin-decisions .origin-card')
        assert [a['href'] for card in decision_cards for a in card.select('a[href]')] == [
            '/products/', '/request-documents/'
        ]
        assert not decision_cards[2].select('a[href]'), 'hold decision must not have a CTA'
        paths = ['/about/', '/products/', '/documents/', '/request-documents/', '/request-a-quote/']
        paths += ['/applications/titanium-dioxide-for-' + application + '/' for application in applications]
        paths += [path for pair in expected for path in pair]
        for target in paths:
            assert main.select_one(f'a[href^="{target}"]'), ('missing route', target)
            routed = requests.get(BASE + target, allow_redirects=False, timeout=25)
            assert routed.status_code == 200, ('redirect or broken route', target, routed.status_code)
    print('PASS:', slug)


def verify_hub():
    path = '/resources/'
    response = requests.get(BASE + path, timeout=25)
    assert response.status_code == 200, ('Resources Hub', response.status_code)
    html = BeautifulSoup(response.text, 'html.parser')
    main = html.select_one('main.hub-resources')
    assert main and len(main.select('h1')) == 1
    assert main.h1.get_text(' ', strip=True) == 'Resources for Titanium Dioxide Procurement Decisions'
    assert html.title.get_text(' ', strip=True) == 'Titanium Dioxide Procurement Resources | TiO2Products'
    description = (
        'Explore titanium dioxide buying guides, technical evaluation principles and dated market or trade updates for sourcing decisions across the EU, UK, India and Brazil.'
    )
    assert html.select_one('meta[name=description]')['content'] == description
    assert html.select_one('link[rel=canonical]')['href'] == BASE + path
    assert html.select_one('meta[property="og:title"]')['content'] == 'Titanium Dioxide Procurement Resources | TiO2Products'
    assert html.select_one('meta[property="og:description"]')['content'] == description
    assert html.select_one('meta[property="og:url"]')['content'] == BASE + path

    cards = main.select('#research-paths .res-grid > article')
    assert [card.select_one('.res-label').get_text(' ', strip=True) for card in cards] == [
        '01 / SOURCING', '02 / TECHNICAL EVALUATION', '03 / TRADE & MARKET'
    ]
    assert [len(card.select('.resource-hub-links a')) for card in cards] == [1, 3, 4]
    visible = [a for card in cards for a in card.select('.resource-hub-links a')]
    expected_urls = [BASE + '/resources/' + slug + '/' for slug in SLUGS]
    assert [a['href'] for a in visible] == expected_urls

    graph = [node for tag in html.select('script[type="application/ld+json"]')
             for node in json.loads(tag.string).get('@graph', [])]
    assert len([node for node in graph if node.get('@type') == 'CollectionPage']) == 1
    breadcrumbs = [node for node in graph if node.get('@type') == 'BreadcrumbList']
    assert len(breadcrumbs) == 1
    assert [item['item'] for item in breadcrumbs[0]['itemListElement']] == [BASE+'/', BASE+path]
    lists = [node for node in graph if node.get('@type') == 'ItemList']
    assert len(lists) == 1
    items = lists[0]['itemListElement']
    assert len(items) == 8
    assert [item['@type'] for item in items] == ['ListItem'] * 8
    assert [item['position'] for item in items] == list(range(1, 9))
    assert [item['url'] for item in items] == [a['href'] for a in visible]
    assert [item['name'] for item in items] == [a.get_text(' ', strip=True) for a in visible]

    faq = main.select_one('.res-faq')
    assert len(faq.select('a[href]')) == 3
    assert [a['href'] for a in faq.select('a[href]')] == ['/products/', '/applications/', '/markets/']
    next_step = main.select_one('section.res-next')
    assert next_step and next_step.h2.get_text(' ', strip=True) == 'Continue Your Procurement Review'
    assert [a['href'] for a in next_step.select('a[href]')] == [
        '/products/', '/applications/', '/documents/', '/markets/'
    ]
    assert not next_step.select('a[href="/request-a-quote/"]')
    assert main.find_all('section').index(next_step) > main.find_all('section').index(faq.find_parent('section'))
    evidence = main.select_one('.res-evidence').get_text(' ', strip=True)
    for phrase in ('automatic product equivalence', 'application and processing requirements',
                   'official source, applicable scope, source date and review date'):
        assert phrase in evidence
    for link in faq.select('a[href]') + next_step.select('a[href]'):
        assert requests.get(BASE + link['href'], timeout=25).status_code == 200, link['href']
    print('PASS: Resources Hub content, eight live links, ItemList and procurement routing')

if __name__ == '__main__':
    import sys
    for slug in sys.argv[1:] or SLUGS:
        verify(slug)
    if not sys.argv[1:]:
        verify_hub()
