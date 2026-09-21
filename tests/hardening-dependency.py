"""Deactivate only on local site; restore active plugins even when assertions fail."""
import subprocess
import requests

def wp(*args):
    p=subprocess.run(['docker','compose','run','--rm','cli',*args],capture_output=True,text=True)
    if p.returncode: raise RuntimeError(p.stdout+p.stderr)
    return p.stdout

assert 'tio2-products' in wp('plugin','list','--status=active','--field=name')
try:
    wp('plugin','deactivate','tio2-products')
    for path in ['/', '/products/']:
        r=requests.get('http://localhost:8080'+path,timeout=20)
        assert r.status_code==503 and 'temporarily unavailable' in r.text.lower(),(path,r.status_code,'Safe dependency page missing')
        assert 'Fatal error' not in r.text and 'undefined function' not in r.text
    notice=wp('eval',"wp_set_current_user(1); do_action('admin_notices');")
    assert 'TiO2 Products' in notice and 'notice-error' in notice
finally:
    wp('plugin','activate','tio2-products')
assert requests.get('http://localhost:8080/',timeout=20).status_code==200
assert requests.get('http://localhost:8080/products/',timeout=20).status_code==200
print('PASS: plugin deactivation -> safe HTTP 503 + admin notice; plugin reactivated and both routes restored.')
