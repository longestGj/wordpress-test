"""One-time vector reconstruction from the approved raster design board."""

from pathlib import Path
import cv2
import numpy as np

root = Path(__file__).resolve().parents[1]
board = root / 'planning/inputs/brand/logo/approved-2026-09-24/design-board.png'
assets = root / 'wp-content/themes/tio2/assets'
rgb = cv2.cvtColor(cv2.imread(str(board)), cv2.COLOR_BGR2RGB)


def trace_wordmark(left, right):
    x0, y0, y1 = 320, 110, 210
    crop = rgb[y0:y1, left:right]
    r, g, b = crop[:, :, 0], crop[:, :, 1], crop[:, :, 2]
    mask = ((r < 150) & (g < 205) & (b < 220)).astype(np.uint8) * 255
    count, labels, stats, _ = cv2.connectedComponentsWithStats(mask)
    for index in range(1, count):
        if stats[index, cv2.CC_STAT_AREA] < 9:
            mask[labels == index] = 0
    contours, _ = cv2.findContours(mask, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)
    commands = []
    for contour in contours:
        if cv2.contourArea(contour) < 4:
            continue
        vertices = cv2.approxPolyDP(contour, 0.45, True)[:, 0, :]
        points = [(int(x + left - 132), int(y + y0 - 75)) for x, y in vertices]
        commands.append('M' + 'L'.join(f'{x} {y}' for x, y in points) + 'Z')
    return ''.join(commands)


# The wordmark is outlined from the largest occurrence on the approved board.
# The color boundary is the gap between the final 2 and initial P.
navy_wordmark = trace_wordmark(320, 568)
teal_wordmark = trace_wordmark(568, 1020)

def trace_symbol():
    crop = rgb[70:272, 128:300]
    r, g, b = crop[:, :, 0], crop[:, :, 1], crop[:, :, 2]
    mask = ((r < 170) & (g < 215) & (b < 225)).astype(np.uint8) * 255
    contours, _ = cv2.findContours(mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    contours = sorted(contours, key=lambda item: cv2.boundingRect(item)[1])
    paths = []
    for contour in contours:
        vertices = cv2.approxPolyDP(contour, 1.0, True)[:, 0, :]
        points = [(int(x - 4), int(y - 5)) for x, y in vertices]
        paths.append('M' + 'L'.join(f'{x} {y}' for x, y in points) + 'Z')
    assert len(paths) == 2
    return paths


symbol_top, symbol_bottom = trace_symbol()
teal_overlay = 'M80 0 L165 42 L165 190 L105 190 L105 73 L80 50 L115 24 Z'
teal_facet = 'M117 110 L165 78 L165 190 L105 190 Z'


def symbol(reverse=False, mono=None, simplified=False):
    if mono:
        fill = '#0B2D5B' if mono == 'dark' else '#FFFFFF'
        return (f'<path fill="{fill}" d="{symbol_top}"/>'
                f'<path fill="{fill}" d="{symbol_bottom}"/>')
    navy = '#FFFFFF' if reverse else '#0B2D5B'
    blue = '#DDF7FA' if reverse else '#08799D'
    teal = '#0EA5A0'
    color = (
        f'<defs><clipPath id="symbol-geometry">'
        f'<path d="{symbol_top}"/><path d="{symbol_bottom}"/>'
        f'</clipPath></defs>'
        f'<path fill="{navy}" d="{symbol_top}"/>'
        f'<path fill="{blue}" d="{symbol_bottom}"/>'
        f'<g clip-path="url(#symbol-geometry)">'
        f'<path fill="{teal}" d="{teal_overlay}"/>'
    )
    if not simplified:
        color += f'<path fill="#45C4C4" opacity=".28" d="{teal_facet}"/>'
    return color + '</g>'


def svg(name, viewbox, content, description):
    title = 'TiO2Products' if name == 'primary logo' else f'TiO2Products {name}'
    return (
        '<?xml version="1.0" encoding="UTF-8"?>\n'
        f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="{viewbox}" role="img" aria-labelledby="title desc">\n'
        f'  <title id="title">{title}</title>\n'
        f'  <desc id="desc">{description}</desc>\n'
        f'  {content}\n'
        '</svg>\n'
    )


def write(name, source):
    (assets / name).write_text(source, encoding='utf-8')


def horizontal(reverse=False, mono=None):
    mark = symbol(reverse, mono)
    navy = '#FFFFFF' if reverse or mono == 'light' else '#0B2D5B'
    teal = '#FFFFFF' if mono else '#0EA5A0'
    return (
        f'<g>{mark}</g>'
        f'<path fill="{navy}" fill-rule="evenodd" d="{navy_wordmark}"/>'
        f'<path fill="{teal}" fill-rule="evenodd" d="{teal_wordmark}"/>'
    )


write('logo.svg', svg('primary logo', '0 0 880 190', horizontal(), 'Geometric faceted symbol and outlined TiO2Products wordmark.'))
write('logo-compact.svg', svg('compact logo', '0 0 880 190', horizontal(), 'Compact geometric symbol and outlined TiO2Products wordmark.'))
write('logo-reverse.svg', svg('reverse logo', '0 0 880 190', horizontal(reverse=True), 'Geometric symbol and outlined wordmark for dark backgrounds.'))
write('logo-symbol.svg', svg('symbol', '0 0 160 190', symbol(), 'Geometric faceted TiO2Products symbol.'))
write('logo-symbol-reverse.svg', svg('reverse symbol', '0 0 160 190', symbol(reverse=True), 'Geometric TiO2Products symbol for dark backgrounds.'))
write('logo-mono.svg', svg('monochrome dark logo', '0 0 880 190', horizontal(mono='dark'), 'Single-color TiO2Products symbol and outlined wordmark.'))
write('logo-mono-reverse.svg', svg('monochrome light logo', '0 0 880 190', horizontal(mono='light'), 'Single-color white TiO2Products symbol and outlined wordmark.'))
write('favicon.svg', svg('favicon', '0 0 160 190', symbol(simplified=True), 'Simplified faceted TiO2Products symbol.'))
write('brand-social-square.svg', svg('social square', '0 0 512 512', '<path fill="#0B2D5B" d="M0 36Q0 0 36 0H476Q512 0 512 36V476Q512 512 476 512H36Q0 512 0 476Z"/><g transform="translate(96 66) scale(2)">' + symbol(reverse=True) + '</g>', 'TiO2Products symbol centered on Deep Navy.'))

from playwright.sync_api import sync_playwright

with sync_playwright() as playwright:
    browser = playwright.chromium.launch(headless=True)
    for size, filename in ((32, 'favicon-32x32.png'), (48, 'favicon-48x48.png'), (180, 'apple-touch-icon.png')):
        page = browser.new_page(viewport={'width': size, 'height': size}, device_scale_factor=1)
        artwork = (assets / ('logo-symbol-reverse.svg' if size == 180 else 'favicon.svg')).read_text(encoding='utf-8').split('?>', 1)[-1]
        background = '#0B2D5B' if size == 180 else 'transparent'
        width = round(size * (0.69 if size == 180 else 0.78))
        page.set_content(
            f'<html><body style="margin:0;width:{size}px;height:{size}px;display:grid;place-items:center;background:{background}">'
            f'<div style="width:{width}px;line-height:0">{artwork}</div></body></html>'
        )
        page.locator('svg').evaluate('(svg) => { svg.style.display = "block"; svg.style.width = "100%"; svg.style.height = "auto"; }')
        page.screenshot(path=str(assets / filename), omit_background=size != 180)
        page.close()
    browser.close()

print('Generated 9 SVG and 3 PNG assets.')
