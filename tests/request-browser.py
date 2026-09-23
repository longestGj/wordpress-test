"""Browser acceptance for the three local request Pages; no POST or database writes."""
from playwright.sync_api import sync_playwright
from pathlib import Path
BASE='http://localhost:8081'
OUT=Path('.local/request-browser');OUT.mkdir(parents=True,exist_ok=True)
with sync_playwright() as p:
 browser=p.chromium.launch();page=browser.new_page();errors=[]
 page.on('pageerror',lambda error:errors.append(str(error)))
 for route in ['request-a-quote','request-documents','request-sample']:
  for width in [1440,768,390]:
   page.set_viewport_size({'width':width,'height':900});page.goto(BASE+'/'+route+'/',wait_until='networkidle')
   assert page.locator('form.request-form').count()==1,route
   assert page.evaluate('document.documentElement.scrollWidth<=innerWidth+1'),(route,width,'overflow')
   page.screenshot(path=str(OUT/f'{route}-{width}.png'),full_page=True)
   print('PASS browser',route,width)
  if route=='request-sample':
   page.select_option('#request-application','Other')
   assert page.locator('#request-application_other').is_visible()
   page.select_option('#request-application','Coatings')
   assert page.locator('#request-application_other').is_hidden()
  if route=='request-documents':
   assert page.locator('#request-product_grade option').count()==15
   assert page.locator('#request-country_region').get_attribute('placeholder')=='Enter your country or region'
   page.locator('input[value="technical_product"]').check()
   page.locator('input[value="quality_coa"]').check()
   assert page.locator('input[type=checkbox]:checked').count()==2
 page.goto(BASE+'/request-a-quote/?destination_country=European%20Union&grade=BAD',wait_until='networkidle')
 assert page.locator('#request-destination_country').input_value()==''
 assert page.locator('#request-product_grade').input_value()==''
 assert not errors,errors
 browser.close()
