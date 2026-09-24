"""Validate the production logo assets without relying on a browser font."""

from pathlib import Path
import re
import xml.etree.ElementTree as ET


assets = Path(__file__).resolve().parents[1] / 'wp-content/themes/tio2/assets'
required = (
    'logo.svg', 'logo-compact.svg', 'logo-reverse.svg',
    'logo-symbol.svg', 'logo-symbol-reverse.svg',
    'logo-mono.svg', 'logo-mono-reverse.svg',
    'favicon.svg', 'brand-social-square.svg',
)
svg_ns = '{http://www.w3.org/2000/svg}'

for name in required:
    path = assets / name
    assert path.is_file(), name
    source = path.read_text(encoding='utf-8')
    root = ET.fromstring(source)
    assert root.tag == svg_ns + 'svg', name
    assert root.find(svg_ns + 'title') is not None, name
    title = root.find(svg_ns + 'title').text
    assert title and 'TiO2Products' in title, name
    if name == 'logo.svg':
        assert title == 'TiO2Products'
    assert root.find('.//' + svg_ns + 'path') is not None, name
    assert not root.findall('.//' + svg_ns + 'text'), name
    assert not root.findall('.//' + svg_ns + 'image'), name
    assert 'font-family' not in source.lower(), name
    assert 'TiO2 Malaysia' not in source and 'MALAYSIA' not in source, name
    assert 'data:' not in source, name
    assert not re.search(r'(?:href|xlink:href)=["\'](?:https?:)?//', source), name

for name in ('favicon-32x32.png', 'favicon-48x48.png', 'apple-touch-icon.png'):
    assert (assets / name).is_file(), name

print('PASS: approved vector logo system and raster icons')
