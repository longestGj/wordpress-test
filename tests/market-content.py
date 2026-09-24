"""Static contract for the eleven committed native market Page seeds."""
import csv
import json
import re
from pathlib import Path
from urllib.parse import urlsplit

from bs4 import BeautifulSoup

root = Path(__file__).resolve().parents[1]
ids = ['MARKET-EU-001','MARKET-EU-DE','MARKET-EU-IT','MARKET-EU-ES','MARKET-EU-PL','MARKET-EU-NL',
       'MARKET-EU-BE','MARKET-UK-001','MARKET-IN-001','MARKET-BR-EN','MARKET-BR-PT']
files = sorted((root / 'data/markets').glob('*.json'))
assert [f.stem for f in files] == sorted(ids)
routes = {'/'} | {row['url'] for row in csv.DictReader((root / 'planning/SITE_MAP.csv').open(encoding='utf-8-sig'))}
pages = {}
for identity in ids:
    seed = json.loads((root / 'data/markets' / (identity+'.json')).read_text(encoding='utf-8'))
    spec = (root / 'planning/pages' / (identity+'.md')).read_text(encoding='utf-8')
    assert seed['identity'] == identity
    for key, label in [('path','URL'),('seo_title','Title'),('seo_description','Meta'),('title','H1')]:
        assert seed[key] == re.search('^'+label+r': (.+)$',spec,re.M).group(1).strip('`'), (identity,key)
    assert seed['source'].startswith('planning/inputs/') and (root/seed['source']).is_file()
    assert seed['language'] == ('pt-BR' if identity == 'MARKET-BR-PT' else 'en')
    soup = BeautifulSoup(seed['content'], 'html.parser')
    assert len(soup.select('h1')) == 1 and soup.h1.get_text(' ',strip=True) == seed['title']
    assert len(soup.select('h2')) >= 3 and len(soup.select('.market-hero')) == 1
    assert not soup.select('script,iframe,form,img[src^="http"]')
    text = soup.get_text(' ',strip=True)
    assert not re.search(r'Gate [0-9]|FULL_COPY|NOT_AUTHORIZED|Document control|PROJECT_CONTROL|source_page',text,re.I),identity
    for a in soup.select('a[href]'):
        url = urlsplit(a['href'])
        if a['href'].startswith('#'):
            assert soup.find(id=a['href'][1:]),(identity,a['href'])
        elif not url.scheme and not url.netloc:
            assert url.path in routes,(identity,url.path)
    pages[identity] = (seed,soup,text)
eu = pages['MARKET-EU-001'][1]
assert len(eu.select('details')) == 8
assert len({a['href'] for a in eu.select('a[href^="/products/"]') if a['href'] != '/products/'}) == 14
assert all(eu.select(f'a[href="/markets/{country}/"]') for country in
           ('germany','italy','spain','poland','netherlands','belgium'))
uk = pages['MARKET-UK-001'][1]
assert len(uk.select('details')) == 6
assert len({a['href'] for a in uk.select('a[href^="/products/"]') if a['href'] != '/products/'}) == 6
assert uk.select('a[href="#application-paths"]') and uk.select('a[href="#representative-grades"]')
assert 'Great Britain' in pages['MARKET-UK-001'][2] and 'Northern Ireland' in pages['MARKET-UK-001'][2]
assert 'Trade context checked 5 September 2026' not in pages['MARKET-UK-001'][2]
assert 'Conditional current-status paragraph' not in pages['MARKET-UK-001'][2]
assert pages['MARKET-BR-EN'][0]['path'] == '/markets/brazil/'
assert pages['MARKET-BR-PT'][0]['path'] == '/pt-br/markets/brazil/'
assert pages['MARKET-BR-EN'][2] != pages['MARKET-BR-PT'][2]
assert 'Os links desta página' in pages['MARKET-BR-PT'][2]
for identity in ('MARKET-EU-DE', 'MARKET-EU-IT'):
    text = pages[identity][2]
    assert 'Not sure / Need help' not in text, identity
    assert 'Product / Grade' not in text, identity
    assert 'checked: 7 September 2026' not in text, identity
    assert 'checked 7 September 2026' not in text, identity
de = pages['MARKET-EU-DE'][2]
assert all(term in de for term in ('VdL', 'GKV', 'Hamburg Port Authority'))
it_seed, it_soup, it_text = pages['MARKET-EU-IT']
assert all(term not in it_text for term in ('Destination Country', 'Destination Port / City',
                                            'Additional Requirements', 'Garzanti Specialties'))
assert all(term in it_text for term in ('Unionplast', 'AMAPLAST',
                                      'A Certificate of Origin is available upon request.'))
compound_card = next(card for card in it_soup.select('.market-card')
                     if card.h3 and card.h3.get_text(' ', strip=True) == 'Compound and Masterbatch')
assert {a['href'] for a in compound_card.select('a[href]')} == {
    '/applications/titanium-dioxide-for-plastics/',
    '/applications/titanium-dioxide-for-masterbatch/',
}
es_seed, es_soup, es_text = pages['MARKET-EU-ES']
assert 'ASEFAPI' in es_text and 'ANAIP' in es_text
assert 'do not establish Grade suitability' in es_text
assert 'Not sure / Need help' not in es_text
assert 'Select the product grade and document types on the request form.' not in es_text
assert 'Document availability and applicable scope are confirmed' in es_text
assert 'A Certificate of Origin is available upon request.' in es_text
assert 'does not promise issuance for every shipment' in es_text
assert all(term not in es_text.lower() for term in ('spain warehouse', 'local stock',
                                                  'spanish manufacturer', 'industry context checked'))
assert {a['href'] for a in es_soup.select('a[href]')} >= {
    '/markets/european-union/', '/resources/eu-titanium-dioxide-anti-dumping-duty/',
}
assert {a['href'] for a in es_soup.select('a[href]')} >= {
    'https://asefapi.es/asociados/',
    'https://anaip.es/divisiones/industria/compuestos-y-masterbatches/grupo-sectorial-de-compuestos-y-masterbatches/',
    '/applications/titanium-dioxide-for-plastics/',
    '/applications/titanium-dioxide-for-masterbatch/',
}
for identity in ids:
    assert '/request-a-quote/' in {a['href'] for a in pages[identity][1].select('a[href]')},identity
print('PASS: 11 market seeds, approved copy boundaries, H1/SEO, links, grade sets, EU/UK FAQ and Brazil languages')
