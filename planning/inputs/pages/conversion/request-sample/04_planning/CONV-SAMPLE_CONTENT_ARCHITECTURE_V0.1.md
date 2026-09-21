# CONV-SAMPLE Content Architecture V0.1

## 0. Document Control

| Field | Value |
|---|---|
| Page ID | `CONV-SAMPLE` |
| Page name | Request a Sample |
| URL | `/request-sample/` |
| Page type | Sample conversion page |
| Gate | Gate 2 — Content Architecture |
| Date | 2026-09-01 |
| Status | `SUBMITTED_FOR_GATE_4_USER_REVIEW / NOT_APPROVED` |
| Page lifecycle | `DESIGN_IN_REVIEW` |
| Mapping / Verification | `PLANNED_CONVERSION` / `QUALITATIVE_KEYWORD_EVIDENCE` |
| Shared authority | `docs/page-playbooks/CONVERSION_PLAYBOOK_V0.1.md` V0.1, `USER_AUTHORIZED_WORKING_BASELINE / SUBMITTED_FOR_PROJECT_CONTROL_REVIEW` |
| Chrome authority | `docs/architecture/GLOBAL_HEADER_FOOTER_SPEC_V0.5.md`, `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| Product authority | PRODUCT V0.3 matrix and three companion audits: 30 verified / 0 conflict / 54 no-public; Process 8 Chloride / 5 Sulfate / 1 Vapor-phase oxidation |
| Authorization | User authorized CONV-SAMPLE progression through Gate 4 and required a stop before Gate 5 |
| Excluded | RFQ and Request Documents page work, Gate 5+, development, CMS, code, testing, deployment, release, DNS, indexing operations, and `D:\16Wordpress_nextjs` |

This document defines a technical-evaluation sample request. It is not a sample policy, inventory statement, approval mechanism, shipment commitment or regulatory qualification service. Gate 2 is submitted with the Gate 4 package for review; submission is not approval.

> **Continuation notice — 2026-09-01:** The user subsequently authorized Gate 5 execution. Gate 5 assets are recorded in `04_planning/visual-designs/CONV-SAMPLE_FULL_VISUAL_DESIGN_V0.1.md`; their status is submitted/not approved/privacy-blocked. The Gate 2 content contract below remains unchanged.

## 1. Chosen Architecture

The selected architecture is a **single-page technical review intake**. It keeps the form short enough for qualified B2B buyers while providing the minimum context a human reviewer needs: grade or unknown-grade state, application, destination, company identity and a buyer-written test objective.

| Approach | Benefit | Risk | Decision |
|---|---|---|---|
| Short contact form | Low effort | Too little technical context; creates avoidable follow-up | Rejected |
| Multi-step sample configurator | Structured | Implies automatic qualification, fit or sample availability | Rejected |
| Single-page review intake | Minimal, editable and transparent; unknown grade remains valid | Requires disciplined optional fields and clear receipt boundary | Selected |

The page does not recommend a product. It preserves a buyer’s approved upstream context and sends that context for human review.

## 2. Buyer-clean Page State Model

| State | Trigger | Visible behavior |
|---|---|---|
| `FORM_READY_UNPREFILLED` | Direct visit | Empty, neutral form; no first-load validation errors |
| `FORM_READY_PREFILLED` | Valid upstream context | Meaningful prefill is visible, editable and removable; no suitability language |
| `GRADE_UNKNOWN` | Buyer chooses `I do not know the grade` | Grade requirement is satisfied; Application and Test objective remain required |
| `PREFILL_UNRECOGNIZED` | Stale or invalid public value | Unsafe value is cleared; field returns to neutral choice; source context may remain system-only for diagnostics |
| `VALIDATING` | Buyer interacts or submits | Interacted field validates inline; full submit adds focusable error summary and field links |
| `SUBMITTING` | Valid request is sent | One submit control shows progress and prevents duplicate activation without clearing entries |
| `RECEIPT_CONFIRMED` | Explicit positive receiver acknowledgement | Exact success heading states receipt for human review; no approval or fulfilment implication |
| `SUBMISSION_UNCONFIRMED` | Timeout, ambiguous response or receiver uncertainty | Values remain; safe failure message and `Try again` action appear |
| `FORM_SERVICE_UNAVAILABLE` | Form receiver is known unavailable | Restricted state explains the request cannot be confirmed; no invented fallback contact |
| `ROUTE_UNAVAILABLE` | `/request-sample/` or required form route is not ready | Release blocker; not a Buyer Clean published state |

Internal tokens in this table never appear in Buyer Clean copy, hidden SEO text or Schema.

## 3. Module Order and Buyer Decision

| Order | Module ID | Visible heading or label | Buyer decision supported | State |
|---:|---|---|---|---|
| 0 | `GLOBAL_HEADER` | Inherited site navigation | Continue elsewhere without losing Global RFQ access | Always; no current-page marker for this conversion route |
| 1 | `BREADCRUMB` | `Home / Request a Sample` | Confirm task and location | Always; route-live gated |
| 2 | `HERO` | `Request a Titanium Dioxide Sample for Technical Evaluation` | Understand purpose and receipt boundary before disclosing data | Always |
| 3 | `PREFILL_CONTEXT` | `Context brought from your previous page` | Verify or remove upstream Grade, Application, Process or Market | Only when meaningful valid prefill exists |
| 4 | `SAMPLE_FORM` | `Tell us what you need to evaluate` | Provide the minimum information for human review | Always when form service is ready |
| 5 | `REVIEW_SEQUENCE` | `What happens after you submit` | Understand receipt, review, possible clarification and separate outcome | Always |
| 6 | `ANSWER_BLOCKS` | `Sample request questions` | Resolve unknown-grade, minimum-information and approval misunderstandings | Always |
| 7 | `RESTRICTED_STATE` | `We cannot confirm sample requests right now` | Avoid believing an unavailable receiver accepted the request | Conditional replacement for form |
| 8 | `GLOBAL_FOOTER` | Inherited Deep Navy footer | Continue to registered site sections and permanent RFQ | Always |

The form is the only page-body conversion action. No sticky duplicate submit control is used.

## 4. Exact Buyer-clean Copy Deck

### 4.1 SEO metadata candidates

| Field | Proposed value |
|---|---|
| Primary keyword | `titanium dioxide sample supplier` |
| Title | `Request a Titanium Dioxide Sample | TiO2 Malaysia` |
| Meta description | `Submit your grade, application, destination and test objective for human review. A request does not confirm sample approval, availability, quantity or dispatch.` |
| H1 | `Request a Titanium Dioxide Sample for Technical Evaluation` |
| Canonical candidate | `https://tio2malaysia.com/request-sample/` only if indexing is separately approved |
| Robots | `DECISION_REQUIRED`; this design package does not authorize indexing |
| Hreflang | `NOT_APPLICABLE` under the current EN-only registration |

The primary keyword appears in metadata context without turning the page into a general supplier landing page. Home and Market pages retain supplier search intent outside sample-action intent; Grade pages retain exact model intent.

### 4.2 Breadcrumb and Hero

- Breadcrumb: `Home / Request a Sample`
- Eyebrow: `TECHNICAL EVALUATION REQUEST`
- H1: `Request a Titanium Dioxide Sample for Technical Evaluation`
- Intro: `Share the grade you are considering—or tell us that you are not sure—together with your application, destination and test objective. We will use this context to review the request.`
- Receipt boundary: `Submitting this form sends a request for human review. It does not confirm sample approval, availability, quantity, free supply, freight, dispatch, delivery or regulatory eligibility.`

The Hero contains no page-body CTA. The primary submit action remains next to the form acknowledgement, after the buyer has reviewed the entered information.

### 4.3 Prefill context

Heading: `Context brought from your previous page`

Intro: `Review any details carried into this form. You can change or remove them before submitting.`

Visible chips/rows may show only a meaningful approved public value:

- `Grade: M-2377`;
- `Application: Coatings`;
- `Process context: Sulfate`;
- `Destination: United Kingdom`.

Each row has a labelled `Change` or `Remove` action. No row uses `recommended`, `best match`, `eligible`, `available`, `suitable`, `equivalent` or a confidence score. The entire module collapses to 0px when no meaningful prefill exists.

### 4.4 Form introduction

Heading: `Tell us what you need to evaluate`

Intro: `Fields marked Required provide the minimum context for review. Please share non-confidential information only. Do not include a complete formulation, payment details or other unnecessary sensitive information.`

### 4.5 Form sections and exact labels

#### A. Evaluation context

| Field | Label | Helper or option copy |
|---|---|---|
| Grade | `Product grade` | Required options include registered Grade values and `I do not know the grade` |
| Application | `Application` | Required options use the six approved public Application categories plus `Other` and `Not sure` |
| Other application detail | `Describe the application` | Appears and becomes required only when `Other` is selected; accepts buyer-entered context without creating taxonomy |
| Test objective | `What do you need to evaluate?` | Required. Helper: `Describe the result, processing question or trial objective you need to assess. Do not include a confidential formulation.` |
| Current grade or target requirement | `Current grade or target requirement` | Optional. Helper: `Share a non-confidential reference point if it will help the review.` |

#### B. Business and destination context

| Field | Label | Helper or option copy |
|---|---|---|
| Contact name | `Contact name` | Required |
| Company | `Company or organisation` | Required |
| Business email | `Business email` | Required. Helper: `Use an address where we can contact you about this request.` |
| Destination | `Destination country or market` | Required; editable if prefilled |

#### C. Optional trial context

| Field | Label | Helper or option copy |
|---|---|---|
| Expected use | `Expected project or annual use` | Optional. Helper: `This is project context, not a requested sample quantity.` |
| Documents | `Documents needed for the trial` | Optional multi-select: `TDS`, `SDS`, `COA`, `COO`, `Other / Not sure`. Selection does not imply availability or release. |
| Notes | `Additional non-confidential context` | Optional. Helper: `Add only information needed to understand the evaluation.` |

Not collected on the initial form: phone, detailed delivery address, port, Incoterm, packaging, sample quantity, payment data, complete formulation or other unnecessary confidential details.

### 4.6 Acknowledgement and submit

The acknowledgement slot is required but remains **internally annotated, not Buyer Clean**, until controller, purpose, recipients, transfer, retention, rights, contact and legal-basis wording are approved. Gate 2–4 reserves:

`[APPROVED DATA-HANDLING AND HUMAN-REVIEW ACKNOWLEDGEMENT REQUIRED BEFORE GATE 5]`

No invented `/privacy/` route, generic “secure”, “never shared” or fixed-retention claim may replace it.

Submit label: `Submit Sample Request for Review`

Submitting copy beside the button: `Sending your request…`

### 4.7 Review sequence

Heading: `What happens after you submit`

1. `Your request and the context you provided are received.`
2. `A person reviews the grade, application, destination and test objective.`
3. `We may ask for clarification if the available information is not enough to review the request.`
4. `Any outcome is communicated separately after review.`

Boundary note: `Receipt is not sample approval and does not confirm availability, quantity, free supply, freight, dispatch, delivery, timing or regulatory eligibility.`

No SLA, guaranteed response or automatic qualification appears.

### 4.8 Answer-ready buyer questions

| ID | Question | Exact answer |
|---|---|---|
| BQ-01 | `What information is needed for a sample request?` | `Provide your contact name, company, business email, destination, application, product grade or an unknown-grade choice, and your test objective. Optional non-confidential project context can help a person review the request.` |
| BQ-02 | `Can I submit if I do not know the grade?` | `Yes. Choose “I do not know the grade,” then describe the application and test objective. This keeps the request open for human review without creating an automatic product recommendation.` |
| BQ-03 | `Does submission mean a sample is approved?` | `No. A confirmed submission means only that the request was received for human review. It does not confirm approval, availability, quantity, free supply, freight, dispatch, delivery or regulatory eligibility.` |
| BQ-04 | `What happens if more information is needed?` | `A reviewer may ask for clarification. Any outcome is communicated separately after the request has been reviewed.` |

These answers are visible GEO-ready content. They do not authorize `FAQPage` or `QAPage` Schema.

## 5. Field and Validation Contract

| Field | Requirement | Validation behavior | Failure-safe treatment |
|---|---|---|---|
| Product grade | Required choice; unknown valid | No error on first load; registered value or unknown state | Invalid prefill clears to neutral choice |
| Application | Required; `Other` and `Not sure` valid | `Other` reveals and focuses description field | Buyer-entered unclassified context remains text only |
| Other application detail | Required only with `Other` | Persistent label; whitespace-only is invalid | Preserve text when another field fails |
| Test objective | Required buyer-entered text | Whitespace-only invalid; no inferred objective | Preserve up to the approved storage limit; 2,000-character layout tested |
| Contact name | Required | Persistent label; whitespace-only invalid | Preserve |
| Company | Required | Persistent label; whitespace-only invalid | Preserve long legal names |
| Business email | Required | Standard email syntax and maximum 254-character handling; do not promise deliverability | Preserve and link error summary to field |
| Destination | Required | Approved country/market value or buyer-entered supported control | Invalid prefill clears; buyer may reselect |
| Current grade/target | Optional | No technical interpretation | Preserve |
| Expected use | Optional | No sample-quantity parsing | Preserve as project context only |
| Documents | Optional | Approved visible document labels only | No availability implication |
| Notes | Optional | Non-confidential reminder; 2,000-character layout tested | Preserve |
| Acknowledgement | Required once approved copy exists | Must be actively selected; hidden metadata cannot satisfy it | Focus/link from summary |

Validation rules:

- first load shows no errors;
- blur/interacted validation may show one field-level error;
- failed submit validates the complete form;
- error summary receives programmatic focus and links to every invalid field;
- labels remain visible; placeholders are supplemental only;
- error, focus, success and failure use text, icon/structure and color, never color alone;
- unknown grade and `Not sure` Application are valid choices, not error states.

Representative messages:

- Error summary heading: `Check the information you entered.`
- Grade: `Choose a product grade or select “I do not know the grade”.`
- Application: `Choose an application, Other or Not sure.`
- Other detail: `Describe the application or choose a different option.`
- Test objective: `Describe what you need to evaluate.`
- Business email: `Enter a business email address in a valid format.`
- Destination: `Choose a destination country or market.`
- Acknowledgement: final wording pending approval; error must name the approved acknowledgement.

## 6. Submission, Failure and Success Copy

### Confirmed receipt

Heading: `Your sample request has been received for human review.`

Body: `The information you provided will be reviewed. We may ask for clarification, and any outcome will be communicated separately.`

Boundary: `This receipt does not confirm sample approval, availability, quantity, free supply, freight, dispatch, delivery or regulatory eligibility.`

No tracking number, fulfilment status, approved badge, dispatch icon or promised response time appears.

### Unconfirmed submission

Heading: `We could not confirm that your request was received.`

Body: `Your entries are still on this page. Please try again.`

Action: `Try again`

No contact email, phone number, Contact fallback or ticket is displayed until a manual channel is separately verified and approved.

### Service unavailable

Heading: `We cannot confirm sample requests right now.`

Body: `The sample request form is not available. No request has been confirmed.`

The form route must not be released in this state unless a separately approved and verified recovery path exists.

## 7. Prefill Contract

| Key | Visible destination | Rule |
|---|---|---|
| `source_page_id` | No direct Buyer Clean field | System context only; allowlisted route identity; no required-field satisfaction |
| `grade_id` | Product grade | Registered Grade only; visible, editable and removable |
| `application_id` | Application | Approved public Application only; visible, editable and removable |
| `process_context` | Prefill context summary | Neutral approved process only; does not select eligibility or fulfil a required buyer choice |
| `market_id` / `destination` | Destination | Approved visible value; editable and removable |
| `document_needs[]` | Documents needed | Approved labels only; does not imply document availability |
| `resource_context` | No direct field unless approved visible source label | System context only; cannot infer a grade, application or test objective |

Rules:

- hidden source context never satisfies a required buyer field;
- a generic Application page never auto-selects a Grade;
- a Grade never auto-claims Application fit, Market support, document eligibility or sample eligibility;
- stale/invalid public values are cleared safely without a negative suitability message;
- all valid prefilled values are visible and buyer-editable;
- `NO_PUBLIC_MAPPING`, `DO_NOT_RENDER`, comparison holds and internal workflow terms never reach visible or machine-readable output.

## 8. PRODUCT V0.3 Relationship Lock

- M-2377 may be neutrally selected or prefilled with Coatings, Plastics, Masterbatch, Printing Inks or Paper when the value came from an approved explicit upstream choice. Sulfate may appear as neutral process context.
- These values do not state sample approval, availability, suitability, performance, quantity, timing, shipment or regulatory eligibility.
- M-2377 → Specialty Materials remains `DO_NOT_RENDER`; the pair is never auto-prefilled or emitted as a public relationship. If a buyer independently describes specialty context, it remains unclassified buyer input for review.
- Rubber is not an Application option, filter, category, page, URL, keyword, navigation item, Schema relation or auto-prefill. Buyer-entered Rubber text may remain under `Other` without being promoted to taxonomy.
- M-996 and M-2196 may each be independently selected. No difference, ranking, superiority, equivalence, substitute, replacement or comparison-based choice reason appears.
- The absence of a public mapping is never rewritten as `not applicable`, `not suitable`, `not available` or an exclusion.

## 9. SEO, GEO and Schema Contract

### SEO ownership

- The page owns sample-action intent for `titanium dioxide sample supplier`.
- It does not own generic supplier, country supplier, quote, document-request, exact Grade, process or generic Application terms.
- The H1 is human-readable and uses sample-action intent once.
- Indexing, canonical activation and sitemap inclusion remain release decisions; this package records the candidate only.

### GEO entity and answer model

| Entity/relationship | Allowed expression |
|---|---|
| TiO2 Malaysia → sample request workflow | Page purpose and receipt/human-review process |
| Buyer → Grade | Buyer-selected or approved upstream context only |
| Buyer → Application | Buyer-selected or approved upstream context only |
| Buyer → Market | Destination provided or approved upstream context only |
| Request → human review | Explicit visible sequence |
| Request receipt → outcome | Separate events; receipt never equals approval |

Answer-ready blocks cover minimum information, unknown grade, receipt meaning and human review. They are concise, visible and consistent with the form.

### Schema

Allowed candidate types:

- `WebPage` with registered name and URL;
- `BreadcrumbList` matching the visible `Home / Request a Sample` path.

Prohibited types/properties:

- `Product`, `Offer`, price, availability, shipping, certification, rating or order properties;
- `FAQPage`, `QAPage`, `HowTo` or automatic-approval process markup;
- any Grade–Application–Process relation not visible and approved under PRODUCT V0.3;
- any sample policy, free-sample, stock, quantity, lead-time, delivery or regulatory statement.

## 10. CTA and Internal-link Contract

| Surface | Action | Route/behavior | State |
|---|---|---|---|
| Page body | `Submit Sample Request for Review` | One form submission control | Only when receiver and approved acknowledgement are operational |
| Failure | `Try again` | Retry without clearing entries | Submission unconfirmed only |
| Unknown grade | `I do not know the grade` | Field option, not navigation or CTA | Always valid |
| Breadcrumb | `Home` | Registered Home route | Route-live gated |
| Context help | Products / Applications links only if separately route-live | Optional text links; no recommendation | Route-live gated |
| Global RFQ | `Request a Quote` | `/request-a-quote/` | Permanent in Desktop Header, Mobile Header, Mobile Menu and Desktop/Mobile Footer |

Inbound paths: Products Hub, Grade pages, Application pages and Market pages may pass only the prefill keys in §7. No Header first-level item is added for Request a Sample.

## 11. Global Chrome V0.5 Contract

- Desktop Header height is 84px with exact order `Home | Markets | Products | Applications | Documents | Resources | About | Request a Quote`.
- Mobile Header height is 64px with `Logo | RFQ | Menu`.
- Mobile Menu uses the same exact order and includes permanent RFQ.
- Global RFQ always points to `/request-a-quote/`; `RFQ_ROUTE_READY=false` is a release blocker only and never hides, disables, empties or reroutes it.
- CONV-SAMPLE has zero buyer-visible `CURRENT` text/badges/suffixes and zero `aria-current=page` mappings in Desktop Header and Mobile Menu unless a later shared-Chrome change is separately approved.
- Production Header logo: `brand/logo/candidates/v0.1/tio2-malaysia-primary-horizontal-v0.1.svg`.
- Production Footer logo: `brand/logo/candidates/v0.1/tio2-malaysia-reverse-monochrome-v0.1.svg`.
- Deep Navy Footer structure and registered links are inherited unchanged. No Privacy, Legal or Contact fallback is invented.

## 12. Gate 2 Validation

- [x] Page ID, URL, type, keyword and intent match the registry/master.
- [x] Shared Conversion Playbook V0.1 is used as the only page-type authority.
- [x] Minimum required and optional fields are separated.
- [x] Prefill is visible/editable/removable and cannot infer suitability.
- [x] Unknown Grade and Not sure Application are valid.
- [x] PRODUCT V0.3, M-2377, Rubber and M-996/M-2196 controls are explicit.
- [x] Validation, focus, failure, success, privacy and restricted states are specified.
- [x] Success means receipt for human review only.
- [x] SEO/GEO/Schema and anti-cannibalization boundaries are defined.
- [x] Global Chrome V0.5 and production SVG bindings are unchanged.
- [x] Long content is preserved for Gate 3 testing.
- [x] Gate 5+, development and release are excluded.

Self-validation is not Gate 2 approval.

## 13. Open Items and Release Blocks

| ID | Level | Item | Current treatment |
|---|---|---|---|
| CS-G2-01 | REVIEW | Gate 2 content and copy require project-control/user review | `OPEN / SUBMITTED_WITH_GATE_4_PACKAGE` |
| CS-PRIV-01 | BLOCKING_GATE_5_RELEASE | Approved data-handling and acknowledgement copy is absent | Annotated internal slot only; no Buyer Clean final copy |
| CS-RCV-01 | BLOCKING_RELEASE | Form receiver, owner/inbox and positive acknowledgement contract are not verified | Gate 2–4 design only; no operational claim |
| CS-MAN-01 | BLOCKING_RELEASE | Manual fallback channel is not verified | No public fallback email/phone/Contact route |
| CS-ROUTE-01 | BLOCKING_RELEASE | Route/form operation not tested in this project | No Gate 5 or release claim |
| CS-SEO-01 | REVIEW | Robots, canonical activation and sitemap inclusion are not approved | Candidate recorded; no indexing action |
| CS-REL-01 | CONTROLLED | PRODUCT V0.3 non-public/comparison boundaries | Controlled in copy, prefill and Schema contracts |

## 14. Approval Record

| Item | Status | Source |
|---|---|---|
| Progression to Gate 4 | `USER_AUTHORIZED` | Delegated user instruction received 2026-09-01 |
| Gate 2 content architecture | `SUBMITTED_FOR_REVIEW / NOT_APPROVED` | This document |
| Gate 3 wireframe | `SUBMITTED_FOR_REVIEW / NOT_APPROVED` | Companion wireframe specification |
| Gate 4 visual direction | `SUBMITTED_FOR_USER_REVIEW / NOT_APPROVED` | Companion visual-direction specification |
| Gate 5 | `NOT_STARTED / NOT_AUTHORIZED` | Explicit stop boundary |

## 15. Version Record

| Version | Date | Change | Approval source |
|---|---|---|---|
| V0.1 | 2026-09-01 | First Gate 2 architecture using Conversion Playbook V0.1, Global Chrome V0.5 and PRODUCT V0.3 | User authorized work through Gate 4; content remains submitted, not approved |
