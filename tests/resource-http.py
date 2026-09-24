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
        assert len(main.select('details')) == 4, 'four buyer questions'
        assert len(main.select('table tbody tr')) == 6, 'six evidence rows'
        process_links = main.select('a[href*="process-titanium-dioxide"]')
        assert len(process_links) in (0, 2), 'process pair must be atomic'
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
