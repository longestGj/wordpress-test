"""One-time adapter from pinned approved visuals to editable WordPress blocks.

Not a runtime dependency. Reads audited inputs listed in data/resources/source.
Run with Python requests/beautifulsoup4/tinycss2; Tailwind CLI is build-only for
the approved RES-PROC utility styles. No prototype script is imported.
"""
import csv
import copy
import hashlib
import json
import re
import subprocess
import sys
from pathlib import Path
from urllib.parse import urlsplit
from bs4 import BeautifulSoup, Comment, NavigableString, Tag

ROOT = Path(__file__).resolve().parents[1]
WORK = ROOT / '.local/resource-work'
sys.path.insert(0, str(WORK / 'deps'))
import tinycss2

CACHE = ROOT / 'planning/inputs'
SEEDS = ROOT / 'data/resources'
ASSETS = ROOT / 'wp-content/themes/tio2/assets'
PIN = '765c66ed2b2d9d42cacab9009c7b17830cfdedf5'

def block(name, html, attrs=None):
    data = ' ' + json.dumps(attrs, ensure_ascii=False, separators=(',', ':')) if attrs else ''
    return f'<!-- wp:{name}{data} -->\n{html}\n<!-- /wp:{name} -->\n'

def native(node):
    if isinstance(node, Comment):
        return ''
    if isinstance(node, NavigableString):
        return block('paragraph', '<p>' + str(node) + '</p>') if str(node).strip() else ''
    node = copy.deepcopy(node)
    classes = ' '.join(node.get('class', []))
    attrs = {'className': classes} if classes else {}
    if node.get('id'):
        attrs['anchor'] = node['id']
    if node.name in ('p', 'h1', 'h2', 'h3', 'h4'):
        if node.name != 'p':
            level = int(node.name[1])
            if level != 2:
                attrs['level'] = level
            node['class'] = ['wp-block-heading'] + node.get('class', [])
            return block('heading', str(node), attrs)
        return block('paragraph', str(node), attrs)
    if node.name in ('ul', 'ol'):
        if node.name == 'ol':
            attrs['ordered'] = True
        node['class'] = ['wp-block-list'] + node.get('class', [])
        items = []
        for li in node.find_all('li', recursive=False):
            li_attrs = {'className': ' '.join(li.get('class', []))} if li.get('class') else {}
            items.append(block('list-item', str(li), li_attrs))
        node.clear()
        return block('list', str(node).replace(f'</{node.name}>', '\n' + ''.join(items) + f'</{node.name}>'), attrs)
    if node.name == 'details':
        summary = node.find('summary', recursive=False).extract()
        for span in summary.select('span'):
            if span.get_text(strip=True) in ('+', '−', '-'):
                span.decompose()
        summary.attrs = {}
        if node.has_attr('open'):
            attrs['showContent'] = True
            node['open'] = ''
        node['class'] = ['wp-block-details'] + node.get('class', [])
        children = ''.join(native(child) for child in list(node.children))
        node.clear()
        return block('details', str(node).replace('</details>', str(summary) + children + '</details>'), attrs)
    if node.name in ('table', 'svg', 'nav'):
        # Technical tables preserve explicit header relationships and mobile labels.
        return block('html', str(node))
    if node.name in ('a', 'span', 'strong', 'code'):
        return block('paragraph', '<p class="resource-inline">' + str(node) + '</p>', {'className': 'resource-inline'})
    if node.name in ('div', 'section', 'article', 'aside'):
        if node.name != 'div':
            attrs['tagName'] = node.name
        # Only group-supported attributes survive into editable group markup.
        for attr in list(node.attrs):
            if attr not in ('id', 'class'):
                del node[attr]
        node['class'] = ['wp-block-group'] + node.get('class', [])
        children = ''.join(native(child) for child in list(node.children))
        node.clear()
        return block('group', str(node).replace(f'</{node.name}>', '\n' + children + f'</{node.name}>'), attrs)
    return block('html', str(node))

def scope_css(css, scope):
    def walk(rules):
        output = []
        for rule in rules:
            if rule.type == 'qualified-rule':
                selectors = []
                for selector in tinycss2.serialize(rule.prelude).split(','):
                    selector = selector.strip()
                    if not selector:
                        continue
                    selector = re.sub(r'^\.res-proc-g4\s+main\b', scope, selector)
                    selector = re.sub(r'^\.res-proc-g4\b', scope, selector)
                    if re.match(r'^(?:html|body|:root|main)(?=[\s.#:\[]|$)', selector):
                        selector = re.sub(r'^(?:html|body|:root|main)', scope, selector)
                        selector = selector.replace(scope + ' main', scope)
                    elif not selector.startswith(scope):
                        selector = scope + ' ' + selector
                    selectors.append(selector)
                output.append(','.join(selectors) + '{' + tinycss2.serialize(rule.content) + '}')
            elif rule.type == 'at-rule' and rule.lower_at_keyword in ('media', 'supports'):
                output.append('@' + rule.lower_at_keyword + ' ' + tinycss2.serialize(rule.prelude) + '{' + walk(tinycss2.parse_rule_list(rule.content)) + '}')
        return '\n'.join(output)
    return walk(tinycss2.parse_stylesheet(css, skip_comments=True, skip_whitespace=True))

def adapt_proc(main, soup):
    # Replace the prototype's duplicated desktop/mobile div-table with one table.
    desktop = main.select_one('#grade-evidence .hidden')
    rows = desktop.find_all('div', recursive=False)
    table = soup.new_tag('table', attrs={'class': 'resource-evidence-table'})
    head, body = soup.new_tag('thead'), soup.new_tag('tbody')
    labels = [cell.get_text(' ', strip=True) for cell in rows[0].find_all('div', recursive=False)]
    for index, row in enumerate(rows):
        tr = soup.new_tag('tr')
        for column, cell in enumerate(row.find_all('div', recursive=False)):
            tag = soup.new_tag('th' if index == 0 or column == 0 else 'td')
            if tag.name == 'th':
                tag['scope'] = 'col' if index == 0 else 'row'
            tag['data-label'] = labels[column]
            tag.string = cell.get_text(' ', strip=True)
            tr.append(tag)
        (head if index == 0 else body).append(tr)
    table.extend([head, body])
    mobile = main.select_one('#grade-evidence .lg\\:hidden')
    mobile.decompose()
    desktop.replace_with(table)
    # Remove historical design-specific ID prefixes from public anchors.
    for node in main.select('[id]'):
        if node['id'].startswith('g4-'):
            node['id'] = node['id'][3:]
    pair = soup.new_tag('div', attrs={'class': 'resource-process-pair'})
    chloride, sulfate = main.select_one('#final-chloride'), main.select_one('#final-sulfate')
    chloride.insert_before(pair)
    pair.extend([chloride.extract(), sulfate.extract()])

def prepare(item):
    identity = item['page_id']
    slug = item['url'].strip('/').split('/')[-1]
    key = identity.lower()
    scope = '.resource-' + key
    visual = Path(item.get('visual_path') or item['adapter_html_path'])
    if not visual.is_absolute():
        visual = (ROOT if item.get('adapter_html_path') and not item.get('visual_path') else CACHE) / visual
    source_text = visual.read_text(encoding='utf-8-sig')
    assert '\ufffd' not in source_text, ('source encoding', visual)
    soup = BeautifulSoup(source_text, 'html.parser')
    main = soup.find('main')
    assert main and main.h1, visual
    breadcrumb = soup.select_one('[aria-label="Breadcrumb"],.breadcrumb')
    crumb_title = breadcrumb.get_text(' ', strip=True).split('/')[-1].strip() if breadcrumb else main.h1.get_text(' ', strip=True)
    if identity == 'RES-PROC':
        crumb_title = 'Chloride vs Sulfate Titanium Dioxide'
    elif identity == 'RES-ORIGIN':
        crumb_title = 'Non-China Titanium Dioxide Supply Guide'
    for node in list(main.select('script,style,.breadcrumb,[aria-label="Breadcrumb"],.prototype-controls')):
        node.decompose()
    for node in main.find_all(string=lambda text: isinstance(text, Comment)):
        node.extract()
    if identity == 'RES-PROC':
        adapt_proc(main, soup)
    for node in main.find_all(True):
        if node.attrs is None:
            continue
        for attr in list(node.attrs):
            if attr.startswith('on') or (attr.startswith('data-') and attr != 'data-label') or attr == 'tabindex':
                del node[attr]
        if node.name == 'a':
            href = node.get('href', '')
            parsed = urlsplit(href)
            if parsed.hostname in ('tio2malaysia.com', 'www.tio2malaysia.com', 'tio2products.com'):
                node['href'] = parsed.path + ('?' + parsed.query if parsed.query else '') + ('#' + parsed.fragment if parsed.fragment else '')
        if node.name == 'summary':
            node.attrs = {}
            for icon in list(node.select('span')):
                if icon.get_text(strip=True) in ('+', '−', '-'):
                    icon.decompose()
    required_text = []
    for node in main.select('h1,h2,h3,p,li,th,td,summary'):
        if node.find_parent(['li', 'p', 'th', 'td', 'summary']):
            continue
        # Standalone CTAs may disappear when the destination is unavailable.
        if node.name == 'p' and node.find('a') and node.get_text(' ', strip=True) == node.find('a').get_text(' ', strip=True):
            continue
        text = node.get_text(' ', strip=True)
        if text:
            required_text.append(text)
    content = ''.join(native(node) for node in list(main.children))
    source = {'commit': PIN, 'copy': item['copy_path'], 'visual': str(visual)}
    for field in ('copy', 'visual'):
        if not Path(source[field]).is_absolute():
            continue
        try:
            source[field] = str(Path(source[field]).relative_to(CACHE)).replace('\\', '/')
        except ValueError:
            # RES-ORIGIN is recreated from the locked copy/spec; not an upstream HTML.
            source[field] = 'native adaptation from approved RES-ORIGIN copy and visual specification'
    seed = {
        'identity': identity, 'slug': slug, 'path': item['url'],
        'title': item.get('breadcrumb_title') or crumb_title,
        'h1': main.h1.get_text(' ', strip=True), 'seo_title': item['title'],
        'seo_description': item['meta_description'],
        'source': json.dumps(source, ensure_ascii=False, sort_keys=True),
        'main_class': 'resource-page resource-' + key + ' ' + ' '.join(main.get('class', [])),
        'content': content, 'required_text': required_text,
    }
    from brand_seed_patch import apply_brand_patch
    seed = apply_brand_patch('resources/' + slug + '.json', seed)
    SEEDS.mkdir(parents=True, exist_ok=True)
    (SEEDS / (slug + '.json')).write_text(json.dumps(seed, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    css = '\n'.join(style.get_text() for style in soup.find_all('style'))
    if identity == 'RES-PROC':
        css = css.replace('#g4-', '#')
        WORK.mkdir(parents=True, exist_ok=True)
        config = WORK / 'tailwind.config.cjs'
        config.write_text('module.exports=' + json.dumps({
            'content': [str(visual).replace('\\', '/')], 'important': scope,
            'corePlugins': {'preflight': False},
            'theme': {'extend': {'colors': {'navy':'#062B5B','deep':'#031B3A','teal':'#008078','soft':'#F5F8FB','line':'#D9E2EC','ink':'#334155','muted':'#5D6F83'}, 'fontFamily': {'sans':['Inter','Arial','sans-serif']}, 'boxShadow': {'soft':'0 10px 28px rgba(6,43,91,.07)'}}}
        }), encoding='utf-8')
        (WORK / 'tailwind.css').write_text('@tailwind utilities;', encoding='utf-8')
        subprocess.run(['npm.cmd','exec','--yes','--package=tailwindcss@3.4.17','--','tailwindcss','-c',str(config),'-i',str(WORK/'tailwind.css'),'-o',str(WORK/'proc-utilities.css'),'--minify'], check=True)
        utilities = (WORK / 'proc-utilities.css').read_text(encoding='utf-8')
    else:
        utilities = ''
    (ASSETS / ('resource-' + key + '.css')).write_text(utilities + '\n' + scope_css(css, scope), encoding='utf-8')
    print('Prepared', identity, len(required_text), 'approved text blocks')

if __name__ == '__main__':
    items = []
    path = SEEDS / 'source/source-map.json'
    items.extend(json.loads(path.read_text(encoding='utf-8')))
    inventory = list(csv.DictReader((ROOT / 'planning/SEO_MAP.csv').open(encoding='utf-8-sig')))
    proc = next(item for item in inventory if item['page_id'] == 'RES-PROC')
    proc.update(visual_path=str(CACHE/'pages/resources/04_planning/visual-designs/RES-PROC_GATE5_FULL_VISUAL_V0.1.html'), copy_path=str(CACHE/'pages/resources/04_planning/RES-PROC_GATE2_CONTENT_ARCHITECTURE_V0.3.md'))
    items.append(proc)
    for item in items:
        if not sys.argv[1:] or item['page_id'] in sys.argv[1:]:
            prepare(item)
