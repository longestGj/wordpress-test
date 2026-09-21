from pathlib import Path
import requests
from bs4 import BeautifulSoup
root=Path(__file__).resolve().parents[1]
html=requests.get('http://localhost:8080/about/').text
soup=BeautifulSoup(html,'html.parser')
for img in soup.select('main img[src$=".svg"]'):
    svg=requests.get('http://localhost:8080'+img['src']).text
    assert '<image ' not in svg,'External raster inside SVG cannot render in an HTML image.'
code=(root/'wp-content/plugins/tio2-products/pages.php').read_text(encoding='utf-8')
assert 'tio2_discovery_nonce' in code,'Product discovery needs a real editing and saving surface.'
assert "add_shortcode('tio2_grade_directory'" in code,'Directory and schema must share editable records.'
print('PASS: review findings covered')
