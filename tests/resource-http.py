"""Resource acceptance: actual HTTP, approved source copy, links and head output.

Missing routes, omitted paragraphs/table cells, leaked prototype controls and
unavailable CTA targets must fail these checks. Read-only against local WordPress.
"""
import json
import re
from pathlib import Path
from urllib.parse import urlsplit
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

if __name__ == '__main__':
    import sys
    for slug in sys.argv[1:] or SLUGS:
        verify(slug)
    if not sys.argv[1:]:
        hub = BeautifulSoup(requests.get(BASE+'/resources/',timeout=25).text,'html.parser')
        links = [urlsplit(a['href']).path for a in hub.select('main a[href]')]
        for slug in SLUGS:
            assert '/resources/'+slug+'/' in links, ('Resources Hub missing link',slug)
        print('PASS: Resources Hub connects all eight resource pages')
