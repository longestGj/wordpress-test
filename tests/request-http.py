"""Real HTTP request forms on an isolated local WordPress instance. Synthetic data only."""
import json,sys
from urllib.parse import urlparse
from bs4 import BeautifulSoup
import requests
BASE=sys.argv[1] if len(sys.argv)>1 else 'http://localhost:8081'
CASES={
 'quote':('/request-a-quote/',{'product_grade':'M-350','application':'Coatings','quantity_mt':'2.5','destination_country':'Malaysia','company':'Codex request test','contact_name':'Test Buyer','business_email':'request-test@invalid.example'}),
 'documents':('/request-documents/',{'full_name':'Test Buyer','company':'Codex request test','business_email':'request-test@invalid.example','country_region':'Malaysia','product_grade':'M-350','document_types':['technical_product','quality_coa']}),
 'sample':('/request-sample/',{'product_grade':'I do not know the grade','application':'Other','application_other':'Trial coating','test_objective':'Evaluate opacity','contact_name':'Test Buyer','company_organisation':'Codex request test','business_email':'request-test@invalid.example','destination_country_market':'Malaysia'}),
}
for kind,(path,values) in CASES.items():
    session=requests.Session()
    response=session.get(BASE+path,timeout=12);response.raise_for_status()
    soup=BeautifulSoup(response.text,'html.parser')
    assert len(soup.select('form.request-form'))==1 and len(soup.select('h1'))==1,kind
    form=soup.select_one('form.request-form')
    data={field.get('name'):field.get('value','') for field in form.select('input[type=hidden]') if field.get('name')}
    data.update(values)
    if kind=='documents': data['document_types[]']=data.pop('document_types')
    response=session.post(BASE+'/wp-admin/admin-post.php',data=data,allow_redirects=False,timeout=12)
    assert response.status_code==303,(kind,response.status_code)
    receipt=response.headers['Location']
    assert urlparse(receipt).path=='/thank-you/' and 'receipt=' in receipt,(kind,receipt)
    success=BeautifulSoup(session.get(receipt,timeout=12).text,'html.parser')
    assert 'received' in success.h1.get_text().lower(),kind
    duplicate=session.post(BASE+'/wp-admin/admin-post.php',data=data,allow_redirects=False,timeout=12)
    assert duplicate.status_code==303 and duplicate.headers['Location']==receipt,(kind,'duplicate returned a new receipt')
    invalid=data.copy();invalid['business_email']='invalid'
    rejected=session.post(BASE+'/wp-admin/admin-post.php',data=invalid,allow_redirects=True,timeout=12)
    assert urlparse(rejected.url).path==path,kind
    form=BeautifulSoup(rejected.text,'html.parser')
    assert form.select_one('#request-errors') and form.select_one('input[name=business_email]').get('value')=='invalid',kind
    if kind=='sample':
        overlong=data.copy();overlong['test_objective']='x'*2001
        rejected=session.post(BASE+'/wp-admin/admin-post.php',data=overlong,allow_redirects=True,timeout=12)
        form=BeautifulSoup(rejected.text,'html.parser')
        assert form.select_one('#request-errors a[href="#request-test_objective"]'),kind
        assert form.select_one('#request-test_objective').get_text()=='x'*2001,kind
    print('PASS HTTP:',kind,'stored receipt, duplicate receipt, preserved invalid input')
