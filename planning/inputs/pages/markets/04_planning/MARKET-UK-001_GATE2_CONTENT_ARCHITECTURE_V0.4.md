# MARKET-UK-001 Gate 2 Content Architecture and Full Copy V0.4

## 0. Control

| Field | Value |
|---|---|
| Page / URL | `MARKET-UK-001` / `/markets/united-kingdom/` |
| Page type | Market procurement landing page |
| Language | English |
| Version / date | V0.4 / 2026-09-05 |
| Gate 1 | `APPROVED / CLOSED` |
| Content intent | `CONTENT_INTENT_CONFIRMED_WITH_REVISIONS` |
| Gate 2 authority | `USER_AUTHORIZED` |
| Current Gate 2 state | `APPROVED / CLOSED` |
| Full copy checkpoint | `FULL_COPY_AND_MODULE_ORDER_CONFIRMED = USER_APPROVED / CLOSED` |
| Gate 2 user approval | 2026-09-05 |
| Gate 3 | `AUTHORIZED / IN_PROGRESS` |
| Gate 4–10 | `NOT_STARTED / NOT_AUTHORIZED` |
| Buyer Clean language | English |
| Site scope | `tio2-my` |

This document is the user-approved Gate 2 baseline for the complete content skeleton and Buyer Clean English copy. Approval does not make routes live, authorize development or authorize Gate 4–10; Gate 3 alone is authorized for responsive wireframes.

## 1. Approved Gate 1 input

The user approved the following Page Intent on 2026-09-05:

- supplier and product evaluation first;
- an early but secondary Great Britain / Northern Ireland decision;
- six representative grades using only approved global PRODUCT V0.3 relationships;
- zero UK-specific grade recommendations;
- direct use of the approved fact `Malaysia-origin titanium dioxide` on suitable visible and equivalent machine-readable surfaces;
- the exact fact `A Certificate of Origin is available upon request.`;
- Gate 2 content architecture, full copy, SEO, GEO and Schema work only.

The COO approval does not authorize `A Certificate of Origin is provided with every shipment.`, customs acceptance, tariff preference, exclusion, exemption, anti-dumping treatment or any other transaction outcome.

### 1.1 User-approved Gate 2 revision direction

On 2026-09-05, the user approved the bounded external-review direction recorded as `MARKET-UK-001-G2-USER-REV-01`. V0.3 added five reader-facing Application paths, made the GB/NI REACH and CLP split explicit, improved procurement-oriented headings and verbs, removed repeated grade-card support lines, refined the Meta description and corrected the `LocalBusiness` rationale. V0.4 preserves that approved direction and responds only to `MARKET-UK-001-G2-PCR-02` by aligning the reading path and current-content Schema rationale. Neither revision reopens Gate 1, adds a UK-specific grade recommendation, changes the six-grade set, alters the trade paragraph or authorizes Gate 3.

## 2. Buyer decision flow

```text
UK supplier intent
→ Malaysia-origin supply
→ application discovery
→ representative grade discovery
→ Great Britain or Northern Ireland destination decision
→ specification and importer assessment
→ document and COO request
→ current classification and trade check
→ qualified RFQ
```

The page is a commercial procurement landing page. Regulatory and trade content helps the buyer organise an assessment; it does not turn the page into legal advice or a trade-policy article.

## 3. One-page content skeleton

| Order | Module ID | Visible heading | Buyer decision supported | Primary action |
|---:|---|---|---|---|
| 0 | `GLOBAL_HEADER` | Shared Header | Reach primary site destinations and RFQ | `Request a Quote` |
| 1 | `BREADCRUMB` | `Home / Markets / United Kingdom` | Confirm page and parent context | `Markets` |
| 2 | `HERO` | `Malaysia-Origin Titanium Dioxide for United Kingdom Buyers` | Understand the supply proposition and next step | `Request a Quote` + `Explore Applications` |
| 3 | `DIRECT_ANSWER` | `Start Your UK Titanium Dioxide Supply Review` | Understand what to establish first | `Explore Applications` |
| 4 | `APPLICATION_PATHS` | `Titanium Dioxide for UK Industrial Applications` | Choose an application path before comparing grades | Five Application links + `Explore Representative Grades` |
| 5 | `REPRESENTATIVE_GRADES` | `Representative Grades to Explore` | Enter the approved global product/application discovery path | Six grade links + Products Hub |
| 6 | `GB_NI_DECISION` | `Where Will the Goods Be Placed on the Market?` | Separate the GB/UK CLP and NI/EU CLP paths | HSE source links |
| 7 | `PROCUREMENT_CHECKLIST` | `Build a Complete UK Supply Request` | Assemble technical, destination and commercial inputs | `Explore All Applications` |
| 8 | `DOCUMENTS` | `Request Documents for Product and Supplier Qualification` | Identify document needs and request COO | `Request Documents` |
| 9 | `ORIGIN` | `How Malaysia Origin Fits the Assessment` | Place the approved origin fact in supplier and import assessment | `About TiO2 Malaysia` |
| 10 | `TRADE` | `Check Current UK Import Requirements` | Separate origin from classification and current measures | Conditional Trade Update link |
| 11 | `BUYER_QUESTIONS` | `Questions From UK Procurement Teams` | Resolve common sourcing questions | Contextual links |
| 12 | `FINAL_RFQ` | `Prepare Your UK Titanium Dioxide Supply Request` | Send the information needed for evaluation | `Request a Quote` |
| 13 | `GLOBAL_FOOTER` | Shared Footer | Close with site navigation and persistent RFQ | Shared links |

This skeleton is included for project-control review together with the full copy below. It does not create a separate approval checkpoint outside Gate 2.

## 4. Buyer Clean full English copy

Buyer Clean includes only text explicitly labelled as a visible eyebrow, heading, body, intro, label, supporting line, boundary line, question, answer or action. Bullets labelled `contract`, `direction`, `rule`, `condition`, `restriction`, `exclusion` or `prefill` are internal specifications and must not render.

### Module 0 — Global Header

Visible navigation, in the approved order:

`Home | Markets | Products | Applications | Documents | Resources | About | Request a Quote`

Assembly contract:

- consume the current approved Global Chrome without page-level redesign;
- `Markets` receives the current-page visual treatment;
- `Home` remains a visible text link;
- the shared RFQ remains fixed across page states;
- no visible `CURRENT`, route status or governance label.

### Module 1 — Breadcrumb

Visible copy:

`Home / Markets / United Kingdom`

### Module 2 — Hero

Eyebrow:

`UNITED KINGDOM PROCUREMENT`

H1:

`Malaysia-Origin Titanium Dioxide for United Kingdom Buyers`

Body:

`Evaluate a Malaysia-origin titanium dioxide supply option for UK industrial applications. Begin with application and representative grade paths, identify whether the destination is Great Britain or Northern Ireland, then prepare the product, document and commercial details for assessment.`

Actions:

1. Primary button: `Request a Quote`
2. Secondary button: `Explore Applications`
3. Supporting text link: `Request Documents`

Hero secondary anchor target:

`#application-paths`

Hero exclusions:

- no UK establishment, office, warehouse, stock or local-manufacturing claim;
- no delivery, lead-time, MOQ, certification or registration promise;
- no duty, preference, exemption or trade-remedy outcome;
- no image implying a UK facility, port, inventory or transport capability.

### Module 3 — Direct Answer

Heading:

`Start Your UK Titanium Dioxide Supply Review`

Body:

`Start with the intended application, specification or known grade. Explore the representative product paths, then identify whether the goods will be placed on the market in Great Britain or Northern Ireland. That destination determines which regulatory and import checks your team should complete before requesting a quotation.`

Supporting line:

`TiO2 Malaysia is operated by IKHLAS TITANIUM (MALAYSIA) SDN. BHD. and presents Malaysia-origin titanium dioxide for evaluation by UK industrial buyers.`

Action:

`Explore Applications`

Action anchor target:

`#application-paths`

### Module 4 — Application Paths

Heading:

`Titanium Dioxide for UK Industrial Applications`

Intro:

`Select the application that best matches your intended use, then use the application guidance to prepare the formulation, processing and product questions for your enquiry.`

| Application | Visible copy | Action |
|---|---|---|
| `Coatings` | `Explore titanium dioxide considerations for coating and paint formulations.` | `Explore Coatings` |
| `Plastics` | `Identify the processing and end-use questions that shape titanium dioxide evaluation for plastics.` | `Explore Plastics` |
| `Masterbatch` | `Prepare the pigment-selection and processing requirements for a masterbatch enquiry.` | `Explore Masterbatch` |
| `Printing Inks` | `Explore titanium dioxide considerations for printing-ink formulations and intended use.` | `Explore Printing Inks` |
| `Paper` | `Identify the product and performance questions relevant to paper applications.` | `Explore Paper Applications` |

Closing action:

`Explore All Applications`

Next-step action:

`Explore Representative Grades`

Next-step anchor target:

`#representative-grades`

Application contract:

- the five labels and routes are general application discovery paths, not UK-specific recommendations;
- this module introduces no Grade, process, performance, availability, registration or market-fit relationship;
- Application pages own generic use-case detail and any verified Grade relationships;
- routes remain final-reader targets subject to later implementation and release checks.

### Module 5 — Representative Grades

Heading:

`Representative Grades to Explore`

Intro:

`Use these six representative grades to begin your evaluation by application, then compare the grade information with your formulation, specification and document requirements.`

#### Coatings paths

Intro:

`Explore M-350, M-510 and M-896 as representative Coatings paths, then compare the grade information with your formulation, specification and document requirements.`

| Grade | Visible application label | Action |
|---|---|---|
| `M-350` | `Coatings` | `Explore M-350` |
| `M-510` | `Coatings` | `Explore M-510` |
| `M-896` | `Coatings` | `Explore M-896` |

#### Plastics and masterbatch paths

Intro:

`Explore M-200, M-108 and M-210 as representative Plastics and Masterbatch paths, then compare the grade information with your processing, specification and document requirements.`

| Grade | Visible application labels | Action |
|---|---|---|
| `M-200` | `Plastics` · `Masterbatch` | `Explore M-200` |
| `M-108` | `Plastics` · `Masterbatch` | `Explore M-108` |
| `M-210` | `Plastics` · `Masterbatch` | `Explore M-210` |

Closing copy:

`Need a different grade or application path? Continue to the complete product range or describe the required specification in your enquiry.`

Actions:

- `View All Titanium Dioxide Grades`
- `Explore All Applications`

Relationship contract:

- sole row-level authority: `PRODUCT_GRADE_APPLICATION_PROCESS_MATRIX_V0.3.csv`;
- only the application labels stated above may appear on these six cards;
- no process labels are needed in this Market page module;
- no best, preferred, equivalent, alternative, replacement, UK-suitable, registered, stocked or available claim;
- `NO_PUBLIC_MAPPING` rows produce no visible or machine-readable negative inference;
- M-2377 is not globally hidden, but it is outside this user-approved six-grade first content version;
- M-996/M-2196 comparison remains outside the page.

### Module 6 — Great Britain or Northern Ireland

Heading:

`Where Will the Goods Be Placed on the Market?`

Intro:

`Select the destination so your team can follow the relevant regulatory and import assessment path.`

#### Great Britain

Label:

`England, Scotland and Wales`

Body:

`UK REACH and GB CLP apply in Great Britain. A GB importer can have registration responsibilities, and a qualifying GB-based Only Representative can take on applicable importer obligations when appointed by a non-GB manufacturer, formulator or article producer. Confirm the legal entity, substance, volume and role for the transaction.`

Source link:

`Check UK REACH roles — HSE`

Classification source link:

`Check GB and Northern Ireland chemical classification — HSE`

#### Northern Ireland

Label:

`Northern Ireland`

Body:

`EU REACH and EU CLP continue to apply in Northern Ireland. Confirm the importing entity, applicable EU requirements and the product information needed for the intended use and transaction.`

Source link:

`Check Northern Ireland REACH — HSE`

Classification source link:

`Check GB and Northern Ireland chemical classification — HSE`

### Module 7 — Procurement Checklist

Heading:

`Build a Complete UK Supply Request`

Intro:

`A useful supplier evaluation connects the product requirement, destination and commercial request. Prepare the following information before your team asks for a quotation.`

#### 1. Define the intended application

`State the formulation or end-use context, such as coatings, plastics or masterbatch, and identify the performance questions your technical team needs to assess.`

#### 2. Identify the grade or specification

`Name the grade when known. When starting from a specification or reference product, share the evaluation criteria so the technical team can identify the next step.`

#### 3. Choose Great Britain or Northern Ireland

`Confirm where the goods will be placed on the market so the relevant importer, regulatory and tariff checks can be organised.`

#### 4. List the required information

`Identify the technical, safety, quality, Certificate of Analysis, origin and supplier-qualification information your team wants to assess.`

#### 5. Add the commercial requirement

`Provide the destination, estimated quantity, required timing and packaging needs so the commercial request can be assessed in context.`

#### 6. Confirm the current import position

`Use the UK Trade Tariff and current official trade-remedy sources to check classification and measures for the specific product and transaction.`

Actions:

- `Explore All Applications`
- `Explore Titanium Dioxide Grades`

### Module 8 — Documents

Heading:

`Request Documents for Product and Supplier Qualification`

Intro:

`Document requirements depend on the grade, intended use, importing entity and qualification purpose. Tell us what your team needs so the applicable scope can be confirmed.`

#### Technical Data and Product Documentation

`Request the product data and specification information needed to evaluate the selected grade.`

#### Safety Documentation

`Identify the safety, handling and storage information required for workplace and product assessment.`

#### Quality and COA Documentation

`State the quality and Certificate of Analysis information your purchasing or QA team needs to assess.`

#### Origin and Supplier Qualification Documentation

`A Certificate of Origin is available upon request. Include any additional origin or company information required by your supplier-qualification process.`

Actions:

1. Primary: `Request Documents`
2. Secondary: `Explore Documents and Compliance`

Supporting line:

`We will assess the requested documents against the selected grade, intended use, importing entity and shipment context.`

### Module 9 — Malaysia Origin

Heading:

`How Malaysia Origin Fits the Assessment`

Body:

`Malaysia-origin titanium dioxide gives your team a clear origin fact to carry into supplier qualification and import assessment. Use the About page to understand the company and the role of Malaysia origin in the wider supply context.`

Supporting body:

`Treat origin as one input to the import assessment, then complete classification, tariff and current trade checks for the specific transaction using official UK sources.`

Action:

`About TiO2 Malaysia`

### Module 10 — UK Import and Trade Check

Heading:

`Check Current UK Import Requirements`

Evergreen body:

`UK customs treatment depends on the product's classification, substantiated origin, destination and the measures in force for the transaction. Use the appropriate UK tariff service and current official notices before relying on a commodity code, duty rate, preference, exclusion, exemption or trade-remedy conclusion.`

GB/NI helper:

`The tariff lookup path differs for goods entering Great Britain and Northern Ireland. Select the destination before completing the current classification and measures check.`

Visible official-source links:

- `Check the UK Trade Tariff — GOV.UK`
- `Review active TRA investigations — Trade Remedies Authority`

#### Conditional current-status paragraph

Render only when a same-day freshness check confirms the public-file status and the exact text remains approved:

`Trade context checked 5 September 2026: the Trade Remedies Authority public file lists AD0086, Rutile Titanium Dioxide from China, as an active dumping investigation. The investigation and import-registration notice concern defined goods originating from China; check the current product scope and official position for the transaction.`

Conditional action:

`Review the UK Titanium Dioxide Trade Update`

Trade state contract:

- if the dated paragraph cannot be freshly verified, omit it and keep the evergreen body plus official-source links;
- show the internal Trade Update action only when `RES-TRADE-UK` content is approved, current, Canonical-consistent and route-ready;
- do not state or imply a rate, exemption, preference, customs acceptance or outcome for Malaysia-origin goods;
- do not frame Malaysia origin as a way to avoid or circumvent a measure.

### Module 11 — Buyer Questions

Heading:

`Questions From UK Procurement Teams`

#### How does TiO2 Malaysia support UK procurement?

`TiO2 Malaysia provides Malaysia-origin titanium dioxide product information, document-request paths and a structured quotation process for UK buyers. Begin with the intended application and destination, then share the specification and commercial details required for evaluation.`

#### Which grades can a UK buyer explore first?

`The page presents M-350, M-510 and M-896 for the global Coatings path, and M-200, M-108 and M-210 for the global Plastics and Masterbatch paths. Use them to begin product evaluation, then compare the grade information with your own formulation and specification requirements.`

#### Why must we distinguish Great Britain from Northern Ireland?

`UK REACH and GB CLP apply in Great Britain, while EU REACH and EU CLP continue to apply in Northern Ireland. The destination therefore changes the regulatory and tariff assessment path. Confirm the applicable role and requirements for the transaction.`

#### What documents can our team request?

`Your request can identify technical and product information, safety documentation, quality and COA information, and origin or supplier-qualification information. Include the selected grade, intended use and shipment context so the requested document scope can be assessed.`

#### How should Malaysia origin be used in the UK import assessment?

`Treat Malaysia origin as one product fact in the import assessment, then verify classification, duty, preference, exclusions, exemptions and current trade-remedy treatment for the specific transaction using official UK sources.`

#### What should we include in a quotation request?

`Include the application, grade or specification when known, Great Britain or Northern Ireland destination, estimated quantity, required timing, packaging needs and the documents your team wants to assess.`

Interaction direction:

- all question and answer text remains present in the initial semantic page content;
- Gate 3 may choose progressive disclosure if labels, focus order and expanded state remain accessible;
- no `FAQPage` or `QAPage` Schema in the current Gate 2 direction.

### Module 12 — Final RFQ

Heading:

`Prepare Your UK Titanium Dioxide Supply Request`

Body:

`Share the application, grade or specification, Great Britain or Northern Ireland destination, estimated requirement, timing, packaging needs and document questions. The request will be assessed in the relevant product and market context.`

Actions:

1. Primary: `Request a Quote`
2. Secondary: `Request Documents`
3. Supporting text link: `Explore All Titanium Dioxide Grades`

RFQ prefill direction:

- `market=United Kingdom`;
- `source_page=MARKET-UK-001`;
- prefilled market remains visible and editable;
- destination territory is not prefilled unless the buyer explicitly selects Great Britain or Northern Ireland;
- grade is not prefilled unless the buyer explicitly selects one;
- acknowledgement means the request was received for human review, not that price, stock, documents, delivery or suitability are approved.

### Module 13 — Global Footer

Consume the current approved shared Footer without page-level changes. The shared RFQ remains available. Legal links and controls follow the Global Chrome owner baseline; this page does not create or rename them.

## 5. CTA and internal-link contract

| Priority | Visible action | Target | Final-reader purpose | Gate 2 condition |
|---:|---|---|---|---|
| 1 | `Request a Quote` | `/request-a-quote/` / `CONV-RFQ` | Begin a UK-context commercial request | Always present in final target; runtime verified at Gates 8–10 |
| 1 | `Request Documents` | `/request-documents/` / `CONV-DOC` | Begin a controlled information request | Present; exact document scope reviewed after submission |
| 2 | `Explore Applications` | `#application-paths` | Move from Hero or Direct Answer to the five Application paths | Page anchor; target heading receives focus without obscuring content |
| 2 | `Explore Representative Grades` | `#representative-grades` | Continue from Application Paths to the six-grade set | Page anchor; target heading receives focus without obscuring content |
| 2 | `View All Titanium Dioxide Grades` | `/products/` / `PRODUCT-000` | Continue to the complete range | Required final-site path |
| 2 | `Explore All Applications` | `/applications/` / `APP-000` | Continue to the complete application range | Required final-site path |
| 2 | `Explore Documents and Compliance` | `/documents/` / `DOC-000` | Understand document categories and request path | Required final-site path |
| 2 | `Explore Coatings` | `/applications/titanium-dioxide-for-coatings/` / `APP-COAT` | Continue to Coatings application guidance | Required final-site path; runtime verified at Gates 8–10 |
| 2 | `Explore Plastics` | `/applications/titanium-dioxide-for-plastics/` / `APP-PLAS` | Continue to Plastics application guidance | Required final-site path; runtime verified at Gates 8–10 |
| 2 | `Explore Masterbatch` | `/applications/titanium-dioxide-for-masterbatch/` / `APP-MB` | Continue to Masterbatch application guidance | Required final-site path; runtime verified at Gates 8–10 |
| 2 | `Explore Printing Inks` | `/applications/titanium-dioxide-for-printing-inks/` / `APP-INK` | Continue to Printing Inks application guidance | Required final-site path; runtime verified at Gates 8–10 |
| 2 | `Explore Paper Applications` | `/applications/titanium-dioxide-for-paper/` / `APP-PAPER` | Continue to Paper application guidance | Required final-site path; runtime verified at Gates 8–10 |
| 3 | `About TiO2 Malaysia` | `/about/` / `ABOUT-001` | Explore entity and origin context | Required final-site path |
| 3 | `Explore M-350` | `/products/m-350/` / `GRADE-M350` | Grade discovery | Required final-site path |
| 3 | `Explore M-510` | `/products/m-510/` / `GRADE-M510` | Grade discovery | Required final-site path |
| 3 | `Explore M-896` | `/products/m-896/` / `GRADE-M896` | Grade discovery | Required final-site path |
| 3 | `Explore M-200` | `/products/m-200/` / `GRADE-M200` | Grade discovery | Required final-site path |
| 3 | `Explore M-108` | `/products/m-108/` / `GRADE-M108` | Grade discovery | Required final-site path |
| 3 | `Explore M-210` | `/products/m-210/` / `GRADE-M210` | Grade discovery | Required final-site path |
| 3 | `Review the UK Titanium Dioxide Trade Update` | `/resources/uk-titanium-dioxide-anti-dumping-investigation/` / `RES-TRADE-UK` | Read time-sensitive detail | Conditional on approved current content and route readiness |
| 3 | `Check UK REACH roles — HSE` | `https://www.hse.gov.uk/REACH/roles.htm` | Review current official GB dutyholder guidance | External official source; recheck before release |
| 3 | `Check Northern Ireland REACH — HSE` | `https://www.hse.gov.uk/reach/about.htm` | Review the current GB/NI REACH split | External official source; recheck before release |
| 3 | `Check GB and Northern Ireland chemical classification — HSE` | `https://www.hse.gov.uk/chemical-classification/brexit.htm` | Confirm the GB CLP and EU CLP territorial split | External official source; recheck before release |
| 3 | `Check the UK Trade Tariff — GOV.UK` | `https://www.gov.uk/trade-tariff` | Check current classification, duty and VAT information | External official source; transaction-specific |
| 3 | `Review active TRA investigations — Trade Remedies Authority` | `https://public-file.trade-remedies.service.gov.uk/` | Check current investigation status | External official source; time-sensitive |

### 5.1 Page anchor inventory

| Anchor | Target module / semantic heading | Incoming visible actions | Accessibility contract |
|---|---|---|---|
| `#application-paths` | Module 4 / `Titanium Dioxide for UK Industrial Applications` | Hero secondary `Explore Applications`; Direct Answer `Explore Applications` | The destination heading is programmatically focusable when focus transfer is used; fixed Chrome must not obscure it; browser history and keyboard activation remain functional |
| `#representative-grades` | Module 5 / `Representative Grades to Explore` | Application Paths next-step `Explore Representative Grades` | The destination heading is programmatically focusable when focus transfer is used; fixed Chrome must not obscure it; no empty anchor or duplicate ID |

Upstream relationship:

- parent breadcrumb and contextual return: `/markets/` / `MARKET-000`.

Route governance:

- all external page routes remain `NOT_VERIFIED_LIVE` in this planning project;
- that runtime state does not remove required final-reader actions from Gate 2–5;
- Gate 7 specifies contracts, Gate 8 implements, and Gate 9/10 verify or block release;
- no fake URL, silent dead link, cross-`site_scope` fallback or Buyer Clean governance label;
- when the conditional Trade Update action is absent, its container collapses completely without an empty card or separator.

## 6. SEO package

| Field | Proposed Gate 2 value / rule |
|---|---|
| Primary keyword | `titanium dioxide supplier uk` |
| Search intent | Commercial supplier search / procurement evaluation |
| SEO Title | `Malaysia Titanium Dioxide Supplier for UK Buyers | TiO2 Malaysia` |
| Meta description | `Explore Malaysia-origin titanium dioxide for UK industrial applications, representative grades, GB or NI review paths, documents and RFQ steps.` |
| H1 | `Malaysia-Origin Titanium Dioxide for United Kingdom Buyers` |
| Canonical | `https://tio2malaysia.com/markets/united-kingdom/` |
| Language | `en` |
| Hreflang | `NOT_APPLICABLE` in the current English-only page contract |
| Robots | `index,follow` only after Gate 10 release authorization; non-production environments remain non-indexable |
| OG title | `Malaysia Titanium Dioxide Supplier for UK Buyers` |
| OG description | Same meaning as the approved visible Hero and Meta; no additional capability or trade claim |
| Current navigation key | `Markets` |

Cannibalization boundaries:

- supplier intent remains on MARKET-UK-001;
- generic application intent remains on Application pages;
- exact grade intent remains on the six Grade pages;
- UK trade-investigation intent remains on `RES-TRADE-UK`;
- origin proof and corporate identity depth remain on About and Documents;
- quotation intent remains on `CONV-RFQ`.

The Gate 2 Meta uses the user-approved exact wording and does not introduce an `import paths` claim or change the approved fact scope.

Excluded acquisition targets:

- generic application terms;
- exact grade keywords;
- detailed trade-update keywords;
- `UK-based titanium dioxide supplier`;
- claims of local UK stock, office, warehouse or manufacturing.

## 7. GEO answer-block contract

| Answer ID | Buyer question | Exact concise answer | Visible module | Source / status |
|---|---|---|---|---|
| `UK-GEO-A01` | What does this page help a UK buyer do? | `It helps a UK buyer evaluate Malaysia-origin titanium dioxide, explore industrial Application paths, continue to representative global grade paths, choose the Great Britain or Northern Ireland assessment path, identify document needs and prepare a quotation request.` | Direct Answer | Gate 1 approved intent + site-wide origin authority |
| `UK-GEO-A02` | Which representative grades appear? | `M-350, M-510 and M-896 appear under Coatings; M-200, M-108 and M-210 appear under Plastics and Masterbatch. Buyers can use these global discovery paths to begin evaluation against their own formulation and specification requirements.` | Representative Grades / Buyer Questions | PRODUCT V0.3 verified rows; zero UK-specific recommendation remains an internal contract |
| `UK-GEO-A03` | Why distinguish GB and NI? | `UK REACH and GB CLP apply in Great Britain, while EU REACH and EU CLP continue to apply in Northern Ireland, so the destination changes the regulatory assessment path.` | GB/NI / Buyer Questions | HSE, checked 2026-09-05 |
| `UK-GEO-A04` | Who may carry UK REACH duties in GB? | `A GB importer can have UK REACH responsibilities, while a qualifying GB-based Only Representative can take on applicable importer obligations when properly appointed.` | GB path | HSE, checked 2026-09-05; no company appointment claim |
| `UK-GEO-A05` | Is a Certificate of Origin available? | `A Certificate of Origin is available upon request.` | Documents | `EG-002 / UK-G1-07 = USER_APPROVED / CLOSED`, 2026-09-05; one Buyer Clean occurrence |
| `UK-GEO-A06` | How should Malaysia origin be used in the UK import assessment? | `Treat Malaysia origin as one product fact, then verify classification, duty, preference, exclusions, exemptions and current trade-remedy treatment for the specific transaction using official UK sources.` | Origin / Trade / Buyer Questions | `EG-004`–`EG-005 = EXTERNAL_CURRENT_SOURCE_REQUIRED` |
| `UK-GEO-A07` | What belongs in the RFQ? | `Include the application, grade or specification, Great Britain or Northern Ireland destination, estimated quantity, timing, packaging needs and document questions.` | Final RFQ | Approved Page Intent |

GEO rules:

- every answer is visible in substantially identical language;
- answers do not imply UK establishment, product-market suitability, stock, registration, delivery or customs outcome;
- the COO fact may be expressed in an equivalent machine-readable form only when visible on the same page;
- time-sensitive trade wording carries an explicit check date and is omitted if freshness cannot be established;
- no hidden relationship is added for an AI system or crawler.

## 8. Schema direction

| Type | Direction | Allowed fields and source | Release condition |
|---|---|---|---|
| `WebPage` | Use | URL, name, description, `inLanguage=en`, publisher reference and visible Malaysia-origin/UK procurement meaning | Canonical and visible copy must match |
| `BreadcrumbList` | Use | Home → Markets → United Kingdom | All three URLs live and Canonical-consistent |
| `ItemList` | Conditional use | The six visible grade discovery items in the exact displayed order, with name and URL only | Six visible links approved and live; no UK recommendation or availability semantics |
| `FAQPage` | Do not use | Buyer Questions remain visible content without FAQ rich-result targeting | Current baseline rule |
| `QAPage` | Do not use | The page is not a community question-and-answer page | Current baseline rule |
| `LocalBusiness` | Do not use | `NOT_APPLICABLE_TO_MARKET_LANDING_PAGE / NOT_PROPOSED` | If a future exact UK establishment statement is proposed, route it through `AGENTS.md` §2.6 and user confirmation before reconsidering any Schema |
| `Offer` / inventory / delivery markup | Do not use | `NOT_APPLICABLE_TO_CURRENT_VISIBLE_CONTENT / NOT_PROPOSED`; the current page has no visible price, stock, availability or delivery offer content, so Schema must not create a hidden fact | If future exact offer, stock or delivery content is proposed, present its wording, scope and risk under `AGENTS.md` §2.6 for user confirmation, then re-review visible content and Schema parity before use |
| Product-level process/comparison markup | Do not use | Market page does not own process or comparison intent | Omit |

Schema parity rule:

`Malaysia-origin titanium dioxide` and `A Certificate of Origin is available upon request.` may be expressed only with the same meaning and scope as visible copy. Schema must not transform either fact into manufacturing-site location, shipment-by-shipment provision, customs acceptance, preference, exemption or trade-remedy treatment.

The COO fact may appear only in an applicable standard descriptive field whose meaning matches visible copy; do not invent a custom Schema property or encode it as an `Offer`, fulfilment or shipment attribute.

## 9. Source and freshness register

| Source ID | Visible or supporting source | Fact scope used by Gate 2 | Checked | Refresh rule |
|---|---|---|---|---|
| `UK-E01` | [HSE — UK REACH roles](https://www.hse.gov.uk/REACH/roles.htm) | GB importer and qualifying GB-based Only Representative roles | 2026-09-05 | Recheck before Gate 5 and Gate 10 if visible wording remains |
| `UK-E02` | [HSE — UK REACH explained](https://www.hse.gov.uk/reach/about.htm) | UK REACH applies in GB; EU REACH continues in NI | 2026-09-05 | Recheck before Gate 5 and Gate 10 |
| `UK-E03` | [HSE — GB and Northern Ireland chemical classification](https://www.hse.gov.uk/chemical-classification/brexit.htm) | GB CLP applies in Great Britain; EU CLP continues in Northern Ireland | 2026-09-05 | Recheck before Gate 5 and Gate 10 |
| `UK-E05` | [GOV.UK — UK Trade Tariff](https://www.gov.uk/trade-tariff) | Product details are needed for commodity-code and duty/VAT checks | 2026-09-05 | Recheck before Gate 5 and Gate 10 |
| `UK-E07` | [GOV.UK — TRA investigation announcement](https://www.gov.uk/government/news/tra-opens-investigation-into-chinese-imports-of-titanium-dioxide) | Investigation initiation date and China/rutile scope | 2026-09-05 | Same-day check required for dated public status |
| `UK-E08` | [GOV.UK — Trade Remedies Notice 2026/14](https://www.gov.uk/government/publications/trade-remedies-notice-registration-of-imports-of-rutile-titanium-dioxide-originating-from-china/trade-remedies-notice-202614-registration-of-imports-of-rutile-titanium-dioxide-originating-from-china) | Defined goods and import-registration notice; no outcome claim | 2026-09-05 | Same-day check required for dated public status |
| `UK-E09` | [TRA public file](https://public-file.trade-remedies.service.gov.uk/) | AD0086 listed as active and updated 2026-09-02 when checked | 2026-09-05 | Same-day check required for dated public status |
| `UK-I01` | `TIO2MY-MALAYSIA-ORIGIN-SITEWIDE-01` | `Malaysia-origin titanium dioxide` | User approved 2026-09-05 | Stable until user changes authority |
| `UK-I02` | `EG-002 / UK-G1-07` | `A Certificate of Origin is available upon request.` | User approved 2026-09-05 | Stable until user changes fact scope |
| `UK-I03` | `PRODUCT_GRADE_APPLICATION_PROCESS_MATRIX_V0.3.csv` | Six exact global Grade→Application paths | Approved baseline | Recheck if product authority changes |

## 10. Conditional-state matrix

| State | Buyer Clean rendering | Hidden / omitted | Internal condition |
|---|---|---|---|
| `UK-S0-FINAL-TARGET` | Full modules, six grades, GB/NI, document request, COO fact, final RFQ and required shared paths | Dated trade paragraph and internal Trade Update CTA may be absent | Default Gate 2 final-reader target |
| `UK-S1-TRADE-CURRENT` | S0 plus dated AD0086 paragraph and UK Trade Update CTA | None within Trade module | Official status freshly verified; Resources content approved/current; route ready at release |
| `UK-S2-TRADE-NOT-CURRENT` | Evergreen Trade guidance plus GOV.UK/HSE/TRA official links | Dated paragraph and internal Trade Update CTA | Freshness or Resources route condition not met |
| `UK-S3-ROUTE-IMPLEMENTATION` | Gate 2–5 still show all required final-reader core actions | No Buyer Clean route-status label | Gate 7 contracts; Gate 8 implementation; Gate 9/10 verification/release blocking |
| `UK-S4-UNSUPPORTED-CAPABILITY` | Adjacent approved copy closes naturally | UK office, warehouse, stock, registration, certification, MOQ, lead time, packaging or delivery claim | No user-approved exact statement |
| `UK-S5-COO-APPROVED` | Exact sentence `A Certificate of Origin is available upon request.` once in the Documents module; Origin and Buyer Questions use non-repeating contextual guidance | Every-shipment, acceptance, tariff or remedy expansion | `EG-002 / UK-G1-07 = USER_APPROVED / CLOSED` |
| `UK-S6-RFQ-FAILURE` | Page-body and shared RFQ links remain ordinary navigation to the RFQ owner | No inline form, inline success or inline failure message | Form validation/error/success belongs to `CONV-RFQ`; its failed production behavior blocks release |
| `UK-S7-NO-IMAGE` | Full text, hierarchy, product paths, GB/NI decision, documents, trade guidance and CTA remain complete | Decorative or unverified geographic/industrial image | Default valid fallback; no blank media frame |

Condition closure rules:

- omitted content leaves no empty card, heading, punctuation, divider, anchor or Schema item;
- Buyer Clean never exposes status names, Evidence IDs, Gate names or review notes;
- required final-site route failure is a Gate 9/10 release blocker, not permission to publish a broken link;
- all states preserve the fixed Global Chrome RFQ.

## 11. Accessibility and later-layout requirements

These are content requirements for later Gates; Gate 3 is not authorized:

- one H1 and continuous heading order;
- descriptive action labels such as `Explore M-350`, not repeated ambiguous `Learn more` links;
- `Explore Applications` anchors from Hero and Direct Answer target the visible Application heading; `Explore Representative Grades` continues from that module to the visible Grades heading;
- anchor activation must preserve keyboard history, transfer focus only to a programmatically focusable target, and keep the target heading clear of fixed Chrome;
- GB and NI meaning conveyed in text, not by colour or flag alone;
- question controls, if used later, retain programmatic names, focus order and expanded state;
- external official links identify the source in their visible label;
- six grades remain usable as a list at 1440px, 768px and 390px without horizontal scrolling;
- long copy, dates and `Northern Ireland` must wrap naturally;
- no decorative image is required; an evidence-neutral no-image state is valid;
- visible text, focus treatment and touch targets must follow the approved visual and accessibility standards.

## 12. Buyer Clean / Internal Review separation

Buyer Clean includes only the explicitly labelled visible English copy and action labels in Section 4 plus approved shared Global Chrome. Section 4 contracts, directions, rules, conditions, restrictions, exclusions and prefill notes remain internal. The following also must not render:

- Gate and review statuses;
- Evidence IDs and claim statuses;
- route-live, freshness and `site_scope` labels;
- unsupported-capability explanations;
- the conditional-state names in Section 10;
- content ownership and cannibalization notes.

## 13. Gate 2 review checklist

- [x] H1, Hero, all 14 module headings and complete English copy are present.
- [x] Module order begins with supplier/product evaluation and keeps GB/NI early but secondary.
- [x] Five reader-facing Application paths use the exact registry destinations and do not add Grade, process or UK-fit relationships.
- [x] Hero and Direct Answer lead to `#application-paths`; Application Paths then leads to `#representative-grades`, preserving Applications → Grades reading order.
- [x] Six representative grades use only approved PRODUCT V0.3 application labels.
- [x] No UK-specific Grade recommendation, ranking, fit, registration, stock or availability claim appears.
- [x] The approved Malaysia-origin fact is visible and machine-readable scope is equivalent.
- [x] The approved COO sentence appears exactly and is not expanded to every shipment or customs outcome.
- [x] GB and NI regulatory paths state UK REACH + GB CLP and EU REACH + EU CLP using current official HSE sources.
- [x] Trade uses evergreen guidance plus a separately conditional dated paragraph and Resource link.
- [x] CTA labels, destinations, RFQ context and route phasing are explicit.
- [x] SEO Title, Meta, Canonical, language, robots and cannibalization boundaries are explicit.
- [x] Meta uses the user-approved exact wording and contains no `import paths` claim.
- [x] GEO answers, sources, dates and Schema parity rules are explicit.
- [x] `LocalBusiness` is not proposed because it is not applicable to this market landing page; future establishment facts remain subject to §2.6 confirmation.
- [x] `Offer` / inventory / delivery markup is not proposed because the current visible page has no matching content; future exact content requires §2.6 confirmation and a fresh parity review.
- [x] Route, unsupported-fact, trade-freshness and omitted-module states close cleanly.
- [x] Buyer Clean leads with actions and next steps; defensive fact boundaries remain in internal contracts except where Trade accuracy requires visible qualification.
- [x] The exact COO availability sentence appears once in Buyer Clean, in Documents; Origin explains why origin matters and links to About without repeating the sentence.
- [x] `MARKET-UK-001-G2-PCR-02` received project-control review PASS and is closed.
- [x] User approved Gate 2 and `FULL_COPY_AND_MODULE_ORDER_CONFIRMED` on 2026-09-05.
- [x] Gate 3 is authorized for responsive wireframes only; Gate 4–10, other Market child pages and development remain unauthorized.

## 14. Decisions submitted to project control

Project control reviewed and passed the following visible package, and the user approved Gate 2 on 2026-09-05:

1. H1: `Malaysia-Origin Titanium Dioxide for United Kingdom Buyers`.
2. Hero: Malaysia-origin UK supply evaluation, primary RFQ, secondary `Explore Applications` anchor and supporting `Request Documents`.
3. Fourteen-module order and CTA sequence: Application discovery precedes representative-grade discovery, then the early secondary GB/NI decision.
4. Five exact Application destinations plus six exact representative Grade→Application paths, with zero UK-specific recommendations.
5. Exact territorial wording: UK REACH + GB CLP for Great Britain; EU REACH + EU CLP for Northern Ireland.
6. Exact COO statement: `A Certificate of Origin is available upon request.`
7. Neutral UK importer/regulatory copy and unchanged conditional trade-status paragraph.
8. Six Buyer Questions, final RFQ copy, exact Meta and complete SEO/GEO/Schema direction, including current-content rationales for both `LocalBusiness` and `Offer` / inventory / delivery markup.

No additional user fact decision is required for this approved Gate 2 baseline. `FULL_COPY_AND_MODULE_ORDER_CONFIRMED = USER_APPROVED / CLOSED`; the separate user decision authorizes Gate 3 wireframes only.

## 15. Review response

| Review ID | Item | V0.2 resolution | Status |
|---|---|---|---|
| `MARKET-UK-001-G2-PCR-01 / P1-01` | Buyer Clean used repeated defensive or negative framing | Rewrote Grades, GB/NI, commercial-input, Documents and FAQ copy around buyer actions and next steps; removed the visible UK-establishment/OR boundary | `RESOLVED_IN_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |
| `MARKET-UK-001-G2-PCR-01 / P1-02` | Documents and Origin repeated the exact COO fact | Retained the exact sentence and Request Documents CTA in Documents; Origin now explains the role of origin in supplier/import review and links only to About without repeating it | `RESOLVED_IN_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |
| `MARKET-UK-001-G2-PCR-01 / P1-03` | Disclaimers were distributed across too many Buyer Clean modules | Consolidated fact limits in internal contracts and retained visible qualification only where current customs/trade accuracy requires it | `RESOLVED_IN_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |

### 15.1 Historical V0.1 → V0.2 Buyer Clean sentence revision register

| Location | V0.1 sentence | V0.2 sentence / disposition |
|---|---|---|
| Representative Grades intro | `The six grades below are starting points for product discovery. Their application labels organise the review by intended use; they are not UK-specific recommendations, rankings or availability statements.` | `Use these six representative grades to begin your review by application, then compare the grade information with your formulation, specification and document requirements.` |
| GB/NI intro | `Use the destination to organise the next review. The choice does not by itself confirm registration, compliance, classification or customs treatment.` | `Select the destination so your team can follow the relevant regulatory and import review path.` |
| GB/NI boundary | `TiO2 Malaysia does not claim a UK establishment or an existing GB Only Representative appointment on this page.` | Removed from Buyer Clean; the company-specific claim boundary remains in the internal claim contract. |
| Procurement Step 2 | `Name the grade when known. If it is not known, provide the current specification, evaluation criteria or reference product so the request can be reviewed without assuming a match.` | `Name the grade when known. When starting from a specification or reference product, share the evaluation criteria so the technical review can identify the next step.` |
| Procurement Step 5 | `Provide the destination, estimated quantity, required timing and packaging needs. These are request inputs, not a promise of stock, price, packing format or delivery.` | `Provide the destination, estimated quantity, required timing and packaging needs so the commercial request can be reviewed in context.` |
| Documents closing line | `A document request starts a scope review. It does not confirm that every document applies to every grade, use, importer or shipment.` | `We will review the requested documents against the selected grade, intended use, importing entity and shipment context.` |
| Origin heading | `Malaysia Origin and Certificate of Origin` | `How Malaysia Origin Fits the Review` |
| Origin primary body | `TiO2 Malaysia presents Malaysia-origin titanium dioxide for UK procurement review. A Certificate of Origin is available upon request, allowing your team to include origin information in its product, supplier and import checks.` | `Malaysia-origin titanium dioxide gives your team a clear origin fact to carry into supplier qualification and import review. Use the About page to understand the company and the role of Malaysia origin in the wider supply context.` |
| Origin supporting body | `Origin is one part of an import assessment. It does not by itself determine commodity classification, customs acceptance, duty, preference, exemption or the outcome of a trade-remedy review.` | `Treat origin as one input to the import assessment, then complete classification, tariff and current trade checks for the specific transaction using official UK sources.` |
| Buyer Question 1 | `Is TiO2 Malaysia a UK-based supplier?` | `How does TiO2 Malaysia support UK procurement?` |
| Buyer Answer 1 | `No UK establishment is claimed. This page helps UK buyers evaluate Malaysia-origin titanium dioxide supplied by TiO2 Malaysia and organise the product, destination, document and import questions for review.` | `TiO2 Malaysia provides Malaysia-origin titanium dioxide product information, document-request paths and a structured quotation process for UK buyers. Begin with the intended application and destination, then share the specification and commercial details required for review.` |
| Buyer Answer 2 | `The page presents M-350, M-510 and M-896 for the global Coatings path, and M-200, M-108 and M-210 for the global Plastics and Masterbatch paths. These are representative discovery links, not UK-specific recommendations or availability statements.` | `The page presents M-350, M-510 and M-896 for the global Coatings path, and M-200, M-108 and M-210 for the global Plastics and Masterbatch paths. Use them to begin product review, then compare the grade information with your own formulation and specification requirements.` |
| Buyer Answer 4 | `Your request can identify technical and product information, safety documentation, quality and COA information, and origin or supplier-qualification information. A Certificate of Origin is available upon request. Applicability and scope are reviewed against the selected product and request.` | `Your request can identify technical and product information, safety documentation, quality and COA information, and origin or supplier-qualification information. Include the selected grade, intended use and shipment context so the requested document scope can be reviewed.` |
| Buyer Question 5 | `Does Malaysia origin determine the UK duty or trade-remedy result?` | `How should Malaysia origin be used in the UK import review?` |
| Buyer Answer 5 | `No. Malaysia origin is a product-origin fact, but customs classification, duty, preference, exclusion, exemption and trade-remedy treatment depend on the current official rules and the specific transaction.` | `Treat Malaysia origin as one product fact in the import review, then verify classification, duty, preference, exclusions, exemptions and current trade-remedy treatment for the specific transaction using official UK sources.` |
| GEO answer A02 | `M-350, M-510 and M-896 appear under Coatings; M-200, M-108 and M-210 appear under Plastics and Masterbatch. The relationships are global discovery paths, not UK-specific recommendations.` | `M-350, M-510 and M-896 appear under Coatings; M-200, M-108 and M-210 appear under Plastics and Masterbatch. Buyers can use these global discovery paths to begin review against their own formulation and specification requirements.` |
| GEO answer A05 placement | Exact COO answer was associated with Documents, Origin and Buyer Questions. | Exact COO answer is owned by Documents only; other modules link to that request path without repeating it. |
| GEO answer A06 | `No. Classification, customs acceptance, duty, preference, exclusion, exemption and trade-remedy treatment depend on current official rules and the specific transaction.` | `Treat Malaysia origin as one product fact, then verify classification, duty, preference, exclusions, exemptions and current trade-remedy treatment for the specific transaction using official UK sources.` |

### 15.2 User-approved V0.3 revision response

| Review ID | Approved direction | V0.3 resolution | Status |
|---|---|---|---|
| `MARKET-UK-001-G2-USER-REV-01 / U1` | Add a lightweight five-link UK industrial Applications module | Added Coatings, Plastics, Masterbatch, Printing Inks and Paper with one reader-facing sentence and the exact registered Application path for each; no Grade, process or UK-fit relationship was added | `USER_APPROVED_DIRECTION / IMPLEMENTED_IN_V0.3_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |
| `MARKET-UK-001-G2-USER-REV-01 / U2` | Make the GB/NI REACH and CLP split explicit | Great Britain now states `UK REACH and GB CLP`; Northern Ireland states `EU REACH and EU CLP`; HSE chemical-classification guidance is cited as `UK-E03` | `USER_APPROVED_DIRECTION / IMPLEMENTED_IN_V0.3_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |
| `MARKET-UK-001-G2-USER-REV-01 / U3` | Use the exact approved Documents, Direct Answer and Final RFQ headings | All three headings are replaced exactly; the Origin heading is aligned to `Assessment` | `USER_APPROVED_DIRECTION / IMPLEMENTED_IN_V0.3_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |
| `MARKET-UK-001-G2-USER-REV-01 / U4` | Remove repeated grade-card support copy and reduce visible `review` repetition | The six cards now contain grade, approved application label and action only; comparison guidance appears once in each group intro; action-led alternatives are used across Buyer Clean | `USER_APPROVED_DIRECTION / IMPLEMENTED_IN_V0.3_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |
| `MARKET-UK-001-G2-USER-REV-01 / U5` | Preserve positive UK support FAQ wording and the one-occurrence COO rule | The positive procurement answer remains; the exact COO sentence appears once in Documents and is not repeated in Origin or Buyer Questions | `USER_APPROVED_DIRECTION / IMPLEMENTED_IN_V0.3_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |
| `MARKET-UK-001-G2-USER-REV-01 / U6` | Use the exact approved Meta and correct the `LocalBusiness` rationale | Exact Meta adopted; `import paths` absent; `LocalBusiness` is `NOT_APPLICABLE_TO_MARKET_LANDING_PAGE / NOT_PROPOSED`, with any future UK-establishment fact routed to §2.6 user confirmation | `USER_APPROVED_DIRECTION / IMPLEMENTED_IN_V0.3_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |
| `MARKET-UK-001-G2-USER-REV-01 / U7` | Preserve Gate 1, Trade, route and evidence boundaries | The dated AD0086 paragraph, evergreen trade copy, GB/NI tariff helper, official Trade sources and all fact/route conditions remain unchanged; Gate 3 remains unauthorized | `USER_APPROVED_DIRECTION / IMPLEMENTED_IN_V0.3_DRAFT_PENDING_PROJECT_CONTROL_REVIEW` |

Complete V0.2 → V0.3 Buyer Clean sentence changes:

| Location | V0.3 Buyer Clean wording / disposition |
|---|---|
| Hero body | `Evaluate a Malaysia-origin titanium dioxide supply option for UK industrial applications. Begin with application and representative grade paths, identify whether the destination is Great Britain or Northern Ireland, then prepare the product, document and commercial details for assessment.` |
| Direct Answer H2 | `Start Your UK Titanium Dioxide Supply Review` |
| Direct Answer body | `Start with the intended application, specification or known grade. Explore the representative product paths, then identify whether the goods will be placed on the market in Great Britain or Northern Ireland. That destination determines which regulatory and import checks your team should complete before requesting a quotation.` |
| Application intro | `Select the application that best matches your intended use, then use the application guidance to prepare the formulation, processing and product questions for your enquiry.` |
| Coatings | `Explore titanium dioxide considerations for coating and paint formulations.` / `Explore Coatings` |
| Plastics | `Identify the processing and end-use questions that shape titanium dioxide evaluation for plastics.` / `Explore Plastics` |
| Masterbatch | `Prepare the pigment-selection and processing requirements for a masterbatch enquiry.` / `Explore Masterbatch` |
| Printing Inks | `Explore titanium dioxide considerations for printing-ink formulations and intended use.` / `Explore Printing Inks` |
| Paper | `Identify the product and performance questions relevant to paper applications.` / `Explore Paper Applications` |
| Application closing action | `Explore All Applications` |
| Representative Grades heading / intro | `Representative Grades to Explore` / `Use these six representative grades to begin your evaluation by application, then compare the grade information with your formulation, specification and document requirements.` |
| Coatings group intro | `Explore M-350, M-510 and M-896 as representative Coatings paths, then compare the grade information with your formulation, specification and document requirements.` |
| Plastics/Masterbatch group intro | `Explore M-200, M-108 and M-210 as representative Plastics and Masterbatch paths, then compare the grade information with your processing, specification and document requirements.` |
| Six grade cards | Removed the six repeated generic support sentences; retained grade, approved application label and `Explore M-*` action only |
| GB/NI intro | `Select the destination so your team can follow the relevant regulatory and import assessment path.` |
| Great Britain body | `UK REACH and GB CLP apply in Great Britain. A GB importer can have registration responsibilities, and a qualifying GB-based Only Representative can take on applicable importer obligations when appointed by a non-GB manufacturer, formulator or article producer. Confirm the legal entity, substance, volume and role for the transaction.` |
| Northern Ireland body | `EU REACH and EU CLP continue to apply in Northern Ireland. Confirm the importing entity, applicable EU requirements and the product information needed for the intended use and transaction.` |
| Classification link | `Check GB and Northern Ireland chemical classification — HSE` |
| Procurement intro | `A useful supplier evaluation connects the product requirement, destination and commercial request. Prepare the following information before your team asks for a quotation.` |
| Procurement Steps 1, 2, 4 and 5 | Replaced repetitive `review` forms with `assess`, `technical team` and `assessed in context`; meaning and required inputs are unchanged |
| Procurement action | `Explore Applications` |
| Documents H2 | `Request Documents for Product and Supplier Qualification` |
| Documents intro | `Document requirements depend on the grade, intended use, importing entity and qualification purpose. Tell us what your team needs so the applicable scope can be confirmed.` |
| Safety copy | `Identify the safety, handling and storage information required for workplace and product assessment.` |
| Documents secondary action / supporting line | `Explore Documents and Compliance` / `We will assess the requested documents against the selected grade, intended use, importing entity and shipment context.` |
| Origin H2 / body | `How Malaysia Origin Fits the Assessment` / `Malaysia-origin titanium dioxide gives your team a clear origin fact to carry into supplier qualification and import assessment. Use the About page to understand the company and the role of Malaysia origin in the wider supply context.` |
| UK-support FAQ answer | `TiO2 Malaysia provides Malaysia-origin titanium dioxide product information, document-request paths and a structured quotation process for UK buyers. Begin with the intended application and destination, then share the specification and commercial details required for evaluation.` |
| Grade FAQ | `Which grades can a UK buyer explore first?` / grade answer uses `begin product evaluation` |
| GB/NI FAQ answer | States `UK REACH and GB CLP` for Great Britain and `EU REACH and EU CLP` for Northern Ireland, followed by the regulatory and tariff assessment path |
| Documents FAQ answer | Uses `requested document scope can be assessed` |
| Origin FAQ | `How should Malaysia origin be used in the UK import assessment?` and corresponding `import assessment` answer |
| RFQ FAQ answer | Uses `documents your team wants to assess` |
| Final RFQ H2 / body | `Prepare Your UK Titanium Dioxide Supply Request` / the request `will be assessed in the relevant product and market context` |
| Meta | `Explore Malaysia-origin titanium dioxide for UK industrial applications, representative grades, GB or NI review paths, documents and RFQ steps.` |

### 15.3 Buyer Clean negative-sentence and terminology scan

- Scope: all visible eyebrows, headings, bodies, intros, labels, supporting lines, questions, answers and action labels in Section 4.
- Result after V0.4 revision: fresh counts are recorded in the submission validation; Buyer Clean retains `0` sentences beginning with or built around `No`, `not`, `does not`, `cannot`, `never`, `without` or `unless`.
- Case-insensitive `review` word-family count across the complete Section 4 contract remains `4` in V0.4, unchanged from V0.3 and down from `45` in V0.2; three visible uses remain in the user-approved Direct Answer H2 and the two unchanged Trade action labels, while one internal RFQ acknowledgement contract retains `human review`. The approved Meta is outside Section 4.
- Trade accuracy remains positive and action-led: origin is treated as one input, followed by a current official classification, tariff and trade-remedy check.
- Internal contracts retain every prohibition and evidence boundary from V0.1.

### 15.4 `MARKET-UK-001-G2-PCR-02` targeted response

| Review item | V0.3 | V0.4 exact change | Status |
|---|---|---|---|
| `P1-01` Buyer decision flow | Malaysia-origin supply and representative product discovery preceded the Application step | The flow is now `UK supplier intent → Malaysia-origin supply → application discovery → representative grade discovery → GB/NI destination decision → specification and importer assessment → document and COO request → current classification and trade check → qualified RFQ` | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| `P1-01` Hero secondary | `Explore Representative Grades` → `#representative-grades` | `Explore Applications` → `#application-paths`; primary `Request a Quote` and supporting `Request Documents` remain unchanged | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| `P1-01` Direct Answer action | `Explore Representative Grades` → `#representative-grades` | `Explore Applications` → `#application-paths` | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| `P1-01` Application next step | Five Application page actions plus `Explore All Applications` | Preserved those actions and added `Explore Representative Grades` → `#representative-grades` as the natural next step | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| `P1-01` Hub action ambiguity | `Explore Applications` linked to `/applications/` while the new anchor needed the same label | The Hub action is now `Explore All Applications` → `/applications/`; `Explore Applications` is reserved for the local `#application-paths` anchor | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| `P1-01` GEO and accessibility | `UK-GEO-A01` named representative grades before Applications; no explicit anchor inventory | `UK-GEO-A01` now states Application paths before representative grades; Section 5.1 registers both anchors, semantic targets, focus behavior, fixed-Chrome clearance, history and duplicate-ID rules | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| `P1-02` Offer/inventory/delivery Schema | `Prohibited in current baseline` because no approved facts existed | `Do not use / NOT_APPLICABLE_TO_CURRENT_VISIBLE_CONTENT / NOT_PROPOSED`; future exact content requires §2.6 user confirmation followed by visible-content and Schema-parity re-review | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |

The V0.3 Buyer Clean body, five Application sentences, six-grade set, GB/NI REACH + CLP copy, Documents/Origin split, positive FAQ, exact COO sentence, Meta, Trade text and all evidence boundaries otherwise remain unchanged.

## 16. Change record

| Version | Date | Change | Status |
|---|---|---|---|
| V0.1 | 2026-09-05 | Created the complete UK Gate 2 content skeleton and full Buyer Clean English copy after user Gate 1 approval and Gate 2 authorization; included six representative grades, GB/NI, approved Malaysia-origin and COO facts, trade freshness, CTA/links, SEO/GEO/Schema and conditional states. | `DRAFT_FOR_PROJECT_CONTROL_REVIEW` |
| V0.2 | 2026-09-05 | Targeted response to `MARKET-UK-001-G2-PCR-01`: changed Buyer Clean to positive next-step language, removed repeated defensive disclaimers, retained the exact COO statement once in Documents, and separated Origin into context/About ownership without changing facts, routes, SEO/GEO/Schema or Gate scope. | `DRAFT_FOR_PROJECT_CONTROL_REVIEW` |
| V0.3 | 2026-09-05 | Implemented user-approved `MARKET-UK-001-G2-USER-REV-01`: added five Application paths, stated the GB/NI REACH + CLP split, adopted exact procurement headings and Meta, simplified the grade cards, reduced repetitive terminology, and corrected the `LocalBusiness` rationale while preserving Gate 1, Trade, route and evidence boundaries. | `DRAFT_FOR_PROJECT_CONTROL_REVIEW` |
| V0.4 | 2026-09-05 | Targeted response to `MARKET-UK-001-G2-PCR-02`: aligned Buyer flow, Hero, Direct Answer, Application next step, CTA/anchor inventory, accessibility and GEO A01 to Applications → Grades; changed Offer/inventory/delivery Schema to current-content `NOT_APPLICABLE / NOT_PROPOSED` semantics without adding facts. | `DRAFT_FOR_PROJECT_CONTROL_REVIEW` |
| V0.4 PCR pass sync | 2026-09-05 | Recorded `MARKET-UK-001-G2-PCR-02 = PROJECT_CONTROL_REVIEW_PASS / CLOSED`; promoted V0.4 to the current Gate 2 final candidate pending user approval. | `PROJECT_CONTROL_REVIEW_PASS_PENDING_USER_APPROVAL` |
| V0.4 user Gate 2 / Gate 3 decision | 2026-09-05 | User approved Gate 2, closed `FULL_COPY_AND_MODULE_ORDER_CONFIRMED`, and authorized Gate 3 responsive wireframes only. | `APPROVED_GATE_2_BASELINE / GATE_3_IN_PROGRESS` |
