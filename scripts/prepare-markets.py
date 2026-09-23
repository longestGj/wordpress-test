"""Build editable native Page seeds from the selected market Buyer Clean copy.

Build-only dependency: python -m pip install --target .local/market-deps markdown
The committed JSON is the import input; planning files are never consulted at runtime.
"""
import json
import re
import sys
from pathlib import Path

from bs4 import BeautifulSoup

ROOT = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(ROOT / '.local/market-deps'))
import markdown

IDS = ['MARKET-EU-001', 'MARKET-EU-DE', 'MARKET-EU-IT', 'MARKET-EU-ES',
       'MARKET-EU-PL', 'MARKET-EU-NL', 'MARKET-EU-BE', 'MARKET-UK-001',
       'MARKET-IN-001', 'MARKET-BR-EN', 'MARKET-BR-PT']

ROUTES = {
    'Request a Quote': '/request-a-quote/', 'Solicitar cotação': '/request-a-quote/',
    'Request Documents': '/request-documents/', 'Solicitar documentos': '/request-documents/',
    'Request Product Information': '/request-documents/',
    'Request a Sample': '/request-sample/',
    'Request Origin & Supplier Information': '/request-documents/',
    'View the Documents Process': '/documents/', 'Explore Documents and Compliance': '/documents/',
    'Explore Titanium Dioxide Grades': '/products/', 'View All Titanium Dioxide Grades': '/products/',
    'Explore All Titanium Dioxide Grades': '/products/', 'Explore Products': '/products/',
    'Explore All Applications': '/applications/', 'About TiO2 Malaysia': '/about/',
    'Explore Applications': '#application-paths',
    'Explore Representative Grades': '#representative-grades',
    'Explore Coatings': '/applications/titanium-dioxide-for-coatings/',
    'Explore Plastics': '/applications/titanium-dioxide-for-plastics/',
    'Explore Masterbatch': '/applications/titanium-dioxide-for-masterbatch/',
    'Explore Printing Inks': '/applications/titanium-dioxide-for-printing-inks/',
    'Explore Paper': '/applications/titanium-dioxide-for-paper/',
    'Explore Paper Applications': '/applications/titanium-dioxide-for-paper/',
    'View the EU Trade Update': '/resources/eu-titanium-dioxide-anti-dumping-duty/',
    'Review the UK Titanium Dioxide Trade Update': '/resources/uk-titanium-dioxide-anti-dumping-investigation/',
    'Check UK REACH roles — HSE': 'https://www.hse.gov.uk/REACH/roles.htm',
    'Check Northern Ireland REACH — HSE': 'https://www.hse.gov.uk/reach/about.htm',
    'Check GB and Northern Ireland chemical classification — HSE': 'https://www.hse.gov.uk/chemical-classification/brexit.htm',
    'Check the UK Trade Tariff — GOV.UK': 'https://www.gov.uk/trade-tariff',
    'Review active TRA investigations — Trade Remedies Authority': 'https://public-file.trade-remedies.service.gov.uk/',
    'Check the ECHA Guidance': 'https://echa.europa.eu/support/getting-started/enquiry-on-reach-and-clp',
}
for grade in ('M-350 M-510 M-896 M-996 M-2196 M-895 M-200 M-108 M-210 M-340 M-886 M-52 M-2377 CR-901').split():
    ROUTES['View ' + grade] = '/products/' + grade.lower() + '/'
    ROUTES['Explore ' + grade] = '/products/' + grade.lower() + '/'
for name in ('Germany', 'Italy', 'Spain', 'Poland', 'Netherlands', 'Belgium'):
    ROUTES[name] = '/markets/' + name.lower() + '/'


def md(text):
    return markdown.markdown(text, extensions=['tables', 'sane_lists'])


def source_for(spec):
    match = re.search(r'\[Current content input\]\(<([^>]+)>\)', spec)
    assert match, 'Missing current content input'
    source = (ROOT / 'planning/pages' / match.group(1)).resolve()
    assert source.is_relative_to(ROOT / 'planning/inputs'), source
    return source


def linked(text):
    return '[' + text + '](' + ROUTES[text] + ')' if text in ROUTES else text


def clean_country(text):
    if '<!-- BUYER_COPY_START -->' in text:
        text = text.split('<!-- BUYER_COPY_START -->', 1)[1].split('<!-- BUYER_COPY_END -->', 1)[0]
    text = re.sub(r'<!--.*?-->', '', text, flags=re.S).strip()
    return text


def visible_modules(text, kind):
    """Extract only explicitly labelled reader-facing copy from mixed control documents."""
    if kind == 'eu':
        text = text.split('## 2. Buyer Clean page copy', 1)[1].split('## 3. CTA and route mapping', 1)[0]
        parts = re.split(r'^### 2\.(\d+) ([^\n]+)$', text, flags=re.M)
        allowed = set(range(2, 14))
        skip_labels = {'Dated context', 'Official references'}
    else:
        text = text.split('## 4. Buyer Clean full English copy', 1)[1].split('## 5. CTA and internal-link contract', 1)[0]
        parts = re.split(r'^### Module (\d+) — ([^\n]+)$', text, flags=re.M)
        allowed = set(range(2, 13))
        skip_labels = {'Conditional current-status paragraph', 'Conditional action'}
    modules = []
    for i in range(1, len(parts), 3):
        number, module_name, body = int(parts[i]), parts[i + 1], parts[i + 2]
        if number not in allowed:
            continue
        lines, pending, skip = [], '', False
        for raw in body.splitlines():
            line = raw.strip()
            if not line:
                continue
            if line.startswith('#### '):
                lines.append('### ' + line[5:]); pending = ''; skip = False; continue
            inline = re.match(r'^([A-Za-z][A-Za-z0-9 /&-]*):\s+(.+)$', line)
            if inline and '`' in inline.group(2):
                pending = inline.group(1);line = inline.group(2)
                skip = pending in skip_labels or any(word in pending.lower() for word in
                    ('contract', 'direction', 'exclusion', 'condition', 'prefill', 'state', 'rule', 'anchor target'))
            label = re.match(r'^([A-Za-z][A-Za-z0-9 /&-]*):$', line)
            if label:
                pending = label.group(1)
                skip = pending in skip_labels or any(word in pending.lower() for word in
                    ('contract', 'direction', 'exclusion', 'condition', 'prefill', 'state', 'rule', 'anchor target'))
                continue
            if line.startswith('|'):
                if skip or line.startswith('|---') or line.startswith('| Grade |') or line.startswith('| Application |'):
                    continue
                cells = [re.sub(r'`', '', c.strip()) for c in line.strip('|').split('|')]
                if len(cells) == 3 and cells[0] and cells[0] not in ('Grade', 'Application'):
                    action = linked(cells[2])
                    lines += ['### ' + cells[0], cells[1], action]
                elif len(cells) == 2 and cells[0] not in ('Label', 'Field', '') and pending == 'Fact row':
                    lines.append('**' + cells[0] + ':** ' + cells[1])
                continue
            if skip:
                continue
            # All accepted source sentences are explicitly marked as visible with backticks.
            values = re.findall(r'`([^`]+)`', line)
            if not values:
                continue
            if line.startswith('- ') and pending not in ('Checklist', 'Destination links', 'Visible official-source links', 'Actions'):
                continue
            for value in values:
                if re.match(r'^(Home / Markets|Home /)', value):
                    continue
                if pending == 'H1':
                    lines.append('# ' + value)
                elif pending in ('Heading', 'Subheading'):
                    lines.append('## ' + value)
                elif line.startswith('- ') and pending in ('Checklist', 'Destination links', 'Visible official-source links'):
                    lines.append('- ' + linked(value))
                elif pending == 'Eyebrow':
                    lines.append('> ' + value)
                elif pending in ('Application tags', 'Application tag', 'Service note', 'Qualification line', 'Supporting line', 'Boundary note', 'Helper line'):
                    lines.append(value)
                elif pending.endswith('CTA') or 'link' in pending.lower() or pending in ('Action', 'Actions', 'Destination links', 'Closing action', 'Next-step action'):
                    lines.append(linked(value))
                elif line.startswith('- ') and pending == 'Actions':
                    lines.append(linked(value))
                elif re.match(r'^\d+\. ', line) and 'button' in line or 'text link:' in line:
                    lines.append(linked(value))
                elif pending in ('Fact row', 'Official references'):
                    continue
                else:
                    lines.append(value)
            if not line.startswith('- '):
                pending = ''
        if number == (5 if kind == 'uk' else 6):
            # Grade links appear in labelled card rows, not a generic country recommendation.
            for index, line in enumerate(lines):
                if re.fullmatch(r'(M-\d+|CR-901)', line):
                    lines[index] = '### ' + line
        html = md('\n\n'.join(lines))
        if kind == 'uk' and number == 4:
            html = html.replace('<h2>', '<h2 id="application-paths" tabindex="-1">', 1)
        if kind == 'uk' and number == 5:
            html = html.replace('<h2>', '<h2 id="representative-grades" tabindex="-1">', 1)
        modules.append((number, module_name, html))
    return modules


def make_sections(identity, text):
    kind = 'eu' if identity == 'MARKET-EU-001' else 'uk' if identity == 'MARKET-UK-001' else 'country'
    if kind != 'country':
        return visible_modules(text, kind)
    text = clean_country(text)
    match = re.search(r'^# (.+)$', text, re.M)
    assert match, identity + ' needs a Buyer Clean H1'
    breadcrumb = text[:match.start()].strip()
    text = text[match.start():]
    chunks = re.split(r'^## (.+)$', text, flags=re.M)
    modules = [(1, 'Hero', md(chunks[0]))]
    for i in range(1, len(chunks), 2):
        modules.append((len(modules)+1, chunks[i], md('## ' + chunks[i] + '\n\n' + chunks[i+1])))
    return modules, md(breadcrumb)


out = ROOT / 'data/markets'
out.mkdir(exist_ok=True)
for identity in IDS:
    spec = (ROOT / 'planning/pages' / (identity + '.md')).read_text(encoding='utf-8')
    fields = {key: re.search('^' + label + r': (.+)$', spec, re.M).group(1)
              for key, label in [('path', 'URL'), ('seo_title', 'Title'), ('seo_description', 'Meta'), ('h1', 'H1')]}
    fields['path'] = fields['path'].strip('`')
    source = source_for(spec)
    text = source.read_text(encoding='utf-8-sig')
    result = make_sections(identity, text)
    if identity in ('MARKET-EU-001', 'MARKET-UK-001'):
        sections = result
        crumb = '<a href="/">Home</a> / <a href="/markets/">Markets</a> / ' + ('European Union' if identity == 'MARKET-EU-001' else 'United Kingdom')
    else:
        sections, crumb = result
    content = '<!-- wp:html -->\n<nav class="market-breadcrumb" aria-label="Breadcrumb">' + crumb + '</nav>\n<!-- /wp:html -->\n'
    for number, name, html in sections:
        soup = BeautifulSoup(html, 'html.parser')
        questions = (identity == 'MARKET-EU-001' and number == 12) or (identity == 'MARKET-UK-001' and number == 11)
        if questions:
            for heading in list(soup.find_all('h3', recursive=False)):
                detail = soup.new_tag('details')
                summary = soup.new_tag('summary');summary.string = heading.get_text(' ', strip=True)
                detail.append(summary)
                sibling = heading.next_sibling
                while sibling and getattr(sibling, 'name', None) not in ('h3', 'h2'):
                    nxt = sibling.next_sibling;detail.append(sibling.extract());sibling = nxt
                heading.replace_with(detail)
        elif len(soup.find_all('h3', recursive=False)) >= 3 or (identity == 'MARKET-UK-001' and number == 6):
            heads = list(soup.find_all('h3', recursive=False))
            if heads:
                grid = soup.new_tag('div', attrs={'class':'market-card-grid'})
                heads[0].insert_before(grid)
                limit = (1 if (identity == 'MARKET-EU-001' and number in (4,7))
                         or (identity == 'MARKET-UK-001' and number in (7,8)) else
                         4 if identity == 'MARKET-UK-001' and number == 6 else 2)
                for heading in heads:
                    card = soup.new_tag('article', attrs={'class':'market-card'})
                    sibling = heading.next_sibling
                    paragraphs = 0
                    while sibling and getattr(sibling, 'name', None) not in ('h3', 'h2'):
                        nxt = sibling.next_sibling
                        if getattr(sibling, 'name', None) == 'p':
                            if paragraphs >= limit: break
                            paragraphs += 1
                        card.append(sibling.extract());sibling = nxt
                    card.insert(0, heading.extract());grid.append(card)
        if number == 1 or (identity in ('MARKET-EU-001','MARKET-UK-001') and number == 2):
            section_class = 'market-hero'
        else:
            section_class = 'market-section'
        for a in soup.select('a[href]'):
            if a['href'].startswith(('/', '#')):
                a['class'] = ['market-action'] if a.parent.name == 'p' and len(a.parent.get_text(' ',strip=True)) == len(a.get_text(' ',strip=True)) else []
        if soup.find('h1'):
            assert soup.h1.get_text(' ', strip=True) == fields['h1'], identity + ': H1 differs from Page Spec: ' + soup.h1.get_text(' ',strip=True)
        if identity == 'MARKET-BR-PT':
            for a in soup.select('a[href="/markets/brazil/"]'):
                a['lang'] = 'en'
        content += f'<!-- wp:html -->\n<section class="{section_class} market-module-{number}" id="market-module-{number}"><div class="market-inner">{soup}</div></section>\n<!-- /wp:html -->\n'
    assert content.count('<h1') == 1, identity
    seed = {'identity': identity, 'path': fields['path'], 'slug': fields['path'].strip('/').split('/')[-1],
            'title': fields['h1'], 'seo_title': fields['seo_title'], 'seo_description': fields['seo_description'],
            'language': 'pt-BR' if identity == 'MARKET-BR-PT' else 'en', 'main_class': 'market-page market-' + identity.lower(),
            'source': str(source.relative_to(ROOT)).replace('\\', '/'), 'content': content}
    (out / (identity + '.json')).write_text(json.dumps(seed, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    print(identity, len(sections), 'modules', len(content), 'chars')
