"""Build-time adapter for the three native WordPress request Pages."""
import json
from pathlib import Path
ROOT=Path(__file__).resolve().parents[1]
DATA={
 'quote':('CONV-RFQ','request-a-quote','Request a Quote','Request a Titanium Dioxide Quote','B2B QUOTATION REQUEST','Tell us the product, application, quantity and destination you are evaluating. Our team will review your requirements and prepare the appropriate commercial response.','Request a Titanium Dioxide Quote | TiO2 Malaysia','Request a titanium dioxide quotation from TiO2 Malaysia by providing your grade, application, quantity in metric tonnes and destination for review.'),
 'documents':('CONV-DOC','request-documents','Request Documents','Request Documents','DOCUMENT REQUEST','Choose the Product Grade and document types your team needs. Add the company and product context so a person can review your request.','Request Documents | TiO2 Malaysia','Submit a controlled request for titanium dioxide product, safety, quality, COA, origin or supplier-qualification documentation for human review.'),
 'sample':('CONV-SAMPLE','request-sample','Request a Sample','Request a Titanium Dioxide Sample for Technical Evaluation','TECHNICAL EVALUATION REQUEST','Share the Malaysia-origin titanium dioxide grade you are considering—or tell us if you are not sure—together with your application, destination and test objective. We will use this context to review the request.','Request a Titanium Dioxide Sample | TiO2 Malaysia','Request a Malaysia-origin titanium dioxide sample for technical evaluation by sharing the grade, application, destination and test objective for human review.'),
}
for kind,(identity,slug,title,h1,eyebrow,intro,seo_title,seo_description) in DATA.items():
 hero=f'<nav class="wrap request-crumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><span aria-current="page">{title}</span></nav><section class="request-hero"><div class="wrap"><p class="eyebrow">{eyebrow}</p><h1>{h1}</h1><p class="lead">{intro}</p></div></section>'
 sections=[]
 if kind=='quote':
  sections=['<h2>Other request types</h2><p>Use the separate request form when you need a sample review or controlled document request instead of a quotation.</p><p><a href="/request-sample/">Request a Sample</a> · <a href="/request-documents/">Request Documents</a></p>']
 elif kind=='documents':
  sections=['<h2>How requests work</h2><p>Choose the document types and one Product Grade relevant to your review. File availability and applicable scope are confirmed only during human review. A submission does not approve or release any document.</p>','<h2>Continue your review</h2><p><a href="/documents/">View the Document Hub</a> or <a href="/products/">explore Products</a>.</p>']
 else:
  sections=['<h2>What happens after you submit</h2><ol><li>Your request and the context you provided are received.</li><li>A person reviews the grade, application, destination and test objective.</li><li>We may ask for clarification if more information is needed.</li><li>Any outcome is communicated separately after review.</li></ol>',
  '<h2>Questions buyers ask</h2><details><summary>Can I submit if I do not know the grade?</summary><p>Yes. Choose “I do not know the grade,” then describe the application and test objective. This keeps the request open for human review.</p></details><details><summary>Does submission mean a sample is approved?</summary><p>No. Submitting starts a human review; it does not approve a sample or confirm any sample arrangement. Any sample arrangement will be confirmed separately.</p></details>']
 content='<!-- wp:html -->\n'+hero+'\n<!-- /wp:html -->\n<!-- wp:shortcode -->\n[tio2_request_form kind="'+kind+'"]\n<!-- /wp:shortcode -->\n'
 for section in sections:content+='<!-- wp:html -->\n<section class="wrap request-information">'+section+'</section>\n<!-- /wp:html -->\n'
 seed={'page_id':identity,'kind':kind,'slug':slug,'path':'/'+slug+'/','title':title,'seo_title':seo_title,'seo_description':seo_description,'content':content}
 out=ROOT/'data/requests';out.mkdir(exist_ok=True)
 (out/(identity+'.json')).write_text(json.dumps(seed,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
 print('Prepared',identity)
