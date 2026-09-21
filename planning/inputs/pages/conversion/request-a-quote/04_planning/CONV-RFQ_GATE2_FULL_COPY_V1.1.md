# CONV-RFQ Gate 2 Full Copy V1.1

## 0. Control

| Field | Value |
|---|---|
| Page | `CONV-RFQ` / `/request-a-quote/` |
| Page type | RFQ conversion page |
| Gate | Gate 2 — complete-copy checkpoint |
| Review ID | `CONV-RFQ-G2-FULL-COPY-PCR-01` |
| Date | 2026-09-01 |
| Status | `DRAFT_FOR_PROJECT_CONTROL_REVIEW / TARGETED_REVISION / NOT_APPROVED` |
| Checkpoint | `FULL_COPY_AND_MODULE_ORDER_CONFIRMED_PENDING_PROJECT_CONTROL_REVIEW` |
| Skeleton decision | `USER_CONFIRMED_WITH_HERO_REVERT_TO_ORIGINAL` |
| Hero rebase | `CONV-RFQ-G2-HERO-USER-REBASE-01 = PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| Return authority | `CONV-RFQ-G2-FULL-COPY-01 = TARGETED_REVISION_REQUIRED / NOT_APPROVED` |
| Gate 3 | `NOT_STARTED / NOT_AUTHORIZED` |

This file contains the complete Buyer Clean English copy and interaction-state wording for Gate 2 review. It defines no visual dimensions, wireframe, implementation, route, receiver, deployment or publication behavior.

## 1. Complete page copy in final module order

### 1.1 Shared Header

Consume Global Chrome V0.5 unchanged. The visible order remains:

`Home | Markets | Products | Applications | Documents | Resources | About | Request a Quote`

The Header creates no extra page-body CTA and no visible current-state label for this Conversion route.

### 1.2 Breadcrumb

- Linked item: `Home`
- Current item: `Request a Quote`
- Spoken/linear result: `Home / Request a Quote`

### 1.3 Hero

- Eyebrow: `B2B QUOTATION REQUEST`
- H1: `Request a Titanium Dioxide Quote`
- Paragraph:

> Tell us the product, application, quantity and destination you are evaluating. Our team will review your requirements and prepare the appropriate commercial response.

There is no Hero button. `REQUEST QUOTE` inside the form is the only solid page-body CTA.

### 1.4 RFQ form

#### Form heading and introduction

- H2: `Quotation request details`
- Introduction:

> Required fields are marked. Please use business information and avoid confidential formulations, account credentials, payment details or sensitive personal information.

#### Group A heading

`Your requirement`

##### Product / Grade

- Label: `Product / Grade`
- Required indicator: visible and programmatically associated.
- Empty option: `Select a product or grade`
- Options, in this order:
  1. `M-350`
  2. `M-510`
  3. `M-896`
  4. `M-996`
  5. `M-2196`
  6. `M-895`
  7. `M-200`
  8. `M-108`
  9. `M-210`
  10. `M-340`
  11. `M-886`
  12. `M-52`
  13. `M-2377`
  14. `CR-901`
  15. `Not sure / Need help`
- No helper, recommendation, comparison, equivalence, ranking or availability statement appears beside this field.

##### Application

- Label: `Application`
- Empty option: `Select an application`
- Options, in this order:
  1. `Coatings`
  2. `Plastics`
  3. `Masterbatch`
  4. `Printing Inks`
  5. `Paper`
  6. `Specialty Materials`
  7. `Other / Not sure`
- An Application selection records buyer context only and never selects, recommends or validates a grade.

##### Required Quantity

- Label: `Required Quantity`
- Control: positive numeric input.
- Adjacent fixed suffix: `Metric tonnes (MT)`.
- The suffix is visible, non-editable text. There is no Unit select, radio or second input, and no kg or Other option.
- No MOQ, quotation eligibility or supply conclusion is stated.

##### Destination Country

- Label: `Destination Country`
- Required: Yes.
- Control: single-line text input.
- Placeholder: `Enter the destination country`.
- Maximum length: 100 Unicode characters after trimming.
- The field uses no select, country list, shared country dataset, country code, region taxonomy or automatic country normalization.
- Buyer-entered text is RFQ request context only. It does not prove or promise service scope, shipping, freight, lead time, regulatory applicability, market support, inventory or availability.

##### Destination Port / City

- Label: `Destination Port / City (optional)`
- Maximum length: 120 characters.
- Helper:

> Add this only if it is already known.

#### Group B heading

`Company details`

##### Company Name

- Label: `Company Name`
- Required length: 2–160 Unicode characters after trimming.
- No company-verification or qualification claim is shown.

##### Your Name

- Label: `Your Name`
- Required length: 2–100 Unicode characters after trimming.

##### Business Email

- Label: `Business Email`
- Maximum length: 254 characters.
- Helper:

> Use the business email where we can respond to this request.

Email syntax checking does not prove deliverability, identity or authority.

##### Phone / WhatsApp

- Label: `Phone / WhatsApp (optional)`
- Maximum length: 40 characters.
- No helper text and no consent checkbox.
- International `+` may be entered and must not be discarded during later normalization.

##### Website

- Label: `Website (optional)`
- Maximum length: 2,048 characters.
- A supplied domain does not prove the company’s identity, capability or relationship to a product.

#### Group C heading

`Additional requirements`

##### Additional Requirements

- Label: `Additional Requirements (optional)`
- Maximum length: 2,000 characters.
- Helper:

> Add any non-confidential specification, packaging, schedule, document or other context that may help us review the request.

The helper collects context only. It does not promise packaging, schedule, document availability or acceptance of another workflow.

#### Privacy notice

> We use the information you provide to review and respond to your quotation request. Learn more in our Privacy Policy.

`Privacy Policy` is a visible text link to the approved shared privacy route. There is no receipt, privacy-consent or acknowledgement checkbox.

#### Submit control

- Normal label: `REQUEST QUOTE`
- In-progress label: `SUBMITTING…`
- The form submit is the only solid page-body CTA.

### 1.5 Alternative Requests

- H2: `Other request types`
- Introduction:

> Use the separate request form when you need a sample review or controlled document request instead of a quotation.

- Text link: `Request a Sample` → `/request-sample/`
- Text link: `Request Documents` → `/request-documents/`

These links are low-weight task switches. They do not submit the RFQ and do not state sample or document approval, availability or release.

### 1.6 Shared Footer

Consume the approved shared Footer unchanged.

## 2. Prefill and carried-context copy behavior

Prefill appears only as editable content in an existing field. There is no separate Selected Context panel, pill rail or status message.

| Explicit upstream source | Current visible destination | Buyer-visible behavior |
|---|---|---|
| Registered Product / Grade | Product / Grade | Select that exact supported option; buyer may change it |
| Approved Application | Application | Select that exact supported option; do not infer a grade |
| Explicit Destination Country | Destination Country | Carry only actual country text explicitly provided by the buyer or upstream action; keep it visible and editable |
| Approved public document label | Additional Requirements | Carry neutral editable text only |
| Approved public resource topic | Additional Requirements | Carry neutral editable text only |
| Explicit approved M-2377 process context | Additional Requirements | May carry neutral `Sulfate` text; no suitability statement |

Unsupported, stale or malformed values clear to the neutral empty state without a first-load error. Prefill never states recommendation, compatibility, availability, equivalence, substitution, serviceability, approval or regulatory outcome.

Country prefill does not use a list, country code, taxonomy, lookup or automatic normalization. A broad market or region such as `European Union` must not be written automatically into this field. Empty, whitespace-only, invalid or over-100-character prefill returns to the empty text state without a first-load error.

## 3. Validation, focus and field-error copy

### 3.1 First load

- No validation summary is displayed.
- Empty required fields are not announced as errors before the buyer attempts submission or meaningfully leaves an edited field.
- Discarding unsupported prefill does not create an error message.

### 3.2 Validation summary

- Heading: `Please review the highlighted fields.`
- Body:

> Correct the information below and try again. Your other entries are still here.

After an invalid submission attempt, focus moves to the summary. Each listed error links to its field. Activating a summary item moves focus to the associated control without clearing another entry.

### 3.3 Exact field errors

| Condition | Error copy |
|---|---|
| Product / Grade empty | `Select a product or grade, or choose “Not sure / Need help.”` |
| Application empty | `Select an application.` |
| Required Quantity empty, non-numeric or not greater than zero | `Enter a quantity greater than 0.` |
| Destination Country empty or whitespace-only | `Enter a destination country.` |
| Destination Country over 100 characters after trimming | `Keep the destination country to 100 characters or fewer.` |
| Destination Port / City over 120 characters | `Keep the destination port or city to 120 characters or fewer.` |
| Company Name empty or shorter than 2 characters after trimming | `Enter your company name.` |
| Company Name over 160 characters | `Keep your company name to 160 characters or fewer.` |
| Your Name empty or shorter than 2 characters after trimming | `Enter your name.` |
| Your Name over 100 characters | `Keep your name to 100 characters or fewer.` |
| Business Email empty | `Enter your business email.` |
| Business Email invalid or over 254 characters | `Enter a business email in the format name@company.com.` |
| Phone / WhatsApp over 40 characters | `Keep the phone or WhatsApp number to 40 characters or fewer.` |
| Website invalid or over 2,048 characters | `Enter a complete website address or remove this optional value.` |
| Additional Requirements over 2,000 characters | `Keep additional requirements to 2,000 characters or fewer.` |

Every field error appears beside its field, is programmatically associated with it and remains readable without relying on color alone. Correcting a field removes only that field’s error when its value becomes valid.

### 3.4 Keyboard focus

- Every input, select, link and button retains a visible focus indicator.
- Focus order follows the visible semantic order in §1.
- Opening or closing the Mobile Menu follows the shared Global Chrome focus contract.
- A failed submit does not move focus to Header, Footer, Contact or an unavailable route.

## 4. Submission states

### 4.1 Submitting

- Button label changes to `SUBMITTING…`.
- Duplicate activation of the form submit is prevented until the attempt resolves.
- Entered information remains visible.
- Header, Mobile Menu and Footer Request a Quote links remain visible as shared navigation.

### 4.2 Submission failure

- Heading: `Something went wrong while submitting your request.`
- Body:

> Your information is still here. Please try again.

- Action: `TRY AGAIN`

Focus moves to the failure message. All buyer-entered values remain available. The state includes no Contact link, email, phone, reference number, implied receipt or response-time statement.

### 4.3 Success

- Heading: `Thank you. We’ve received your quotation request.`
- Body:

> Our team will review the details and contact you using the information provided.

The success state appears only after a verified positive receipt response from the eventual form receiver. It means the request has been received for human review. It is not a quotation and does not confirm or approve price, MOQ, stock, lead time, shipping, sample, document, order, regulation result or any commercial outcome.

## 5. Human review, unknown grade and insufficient information

- `Not sure / Need help` is a valid Product / Grade selection and never triggers automatic rejection.
- The form does not calculate suitability, recommend a grade, rank products or expose an automated qualification result.
- When the submitted information is insufficient, the team may request clarification using the supplied contact information.
- No response-time, quotation-time or follow-up SLA is stated.
- Buyer-entered Application, Destination or Additional Requirements text is request context, not an approved product relationship or service commitment.

No extra Human Review module or FAQ is added. These rules are expressed through the form options, success copy and internal interaction contract.

## 6. Form or route unavailable state

Review-only unavailable-state copy:

- Heading: `The quotation request form is temporarily unavailable.`
- Body:

> No request has been submitted. Please return later and try again.

This state does not contain Contact, an email address, phone number, disabled empty slot or hidden RFQ navigation. A route or form that is unavailable remains a `RELEASE_BLOCKER`; it is not an approved production state. All Gate 1–5 Buyer Clean designs continue to show the complete RFQ form and permanent Global Chrome Request a Quote links.

## 7. SEO, GEO and Schema copy parity

| Element | Exact direction |
|---|---|
| SEO Title | `Request a Titanium Dioxide Quote | TiO2 Malaysia` |
| Meta Description | `Request a titanium dioxide quotation from TiO2 Malaysia by providing your grade, application, quantity in metric tonnes and destination for review.` |
| H1 | `Request a Titanium Dioxide Quote` |
| Canonical recommendation | `https://tio2malaysia.com/request-a-quote/` |
| Robots recommendation | `index, follow` only after later route/form/privacy readiness verification |
| Language | `en` |
| Schema | `WebPage` + `BreadcrumbList`; stable approved `WebSite`/`Organization` references only |

The original Hero paragraph may be reused only within the same approved meaning. Metadata and Schema must not extend `prepare the appropriate commercial response` into a quotation, price, availability, MOQ, lead-time, shipping or order promise.

No `Product`, `Offer`, `AggregateOffer`, `FAQPage`, `QAPage`, `HowTo` or `ContactPage` node is created. Form values, buyer data, success state and internal release status never enter metadata or JSON-LD.

## 8. PRODUCT V0.3 and relationship restrictions

- M-2377 may be carried neutrally with Coatings, Plastics, Masterbatch, Printing Inks, Paper or Sulfate only from explicit approved upstream context.
- M-2377 + Specialty Materials must not create a public relationship statement.
- Rubber remains buyer-entered context only; it creates no taxonomy, page, URL or keyword.
- M-996 and M-2196 remain independent options with no difference, ranking, superiority, equivalence, substitution or comparison rationale.
- `NO_PUBLIC_MAPPING` means no public relationship and is never rewritten as not applicable.
- A buyer’s independent field selections do not create a verified Product–Application, Product–Market or Product–Document relationship.

## 9. Responsive content-order contract for later Gate 3

- Desktop/PC: one vertical page flow. No left/right page composition, side rail, independent context rail or Dashboard shell.
- Desktop form only: a two-column field Grid may align related fields; long labels, errors or values may span full width.
- Tablet and 390px Mobile: all form fields follow the §1 semantic order in one column.
- `Metric tonnes (MT)` stays adjacent to Required Quantity at every viewport.
- Shared Desktop Header is 84px; Mobile Header is 64px; Mobile targets remain at least 44px.
- No sticky submit bar, second form or second solid page-body CTA is introduced.

This section is a content-order constraint, not Gate 3 wireframe authorization.

## 10. Gate 2 full-copy decision

This targeted complete-copy revision is submitted as `DRAFT_FOR_PROJECT_CONTROL_REVIEW / TARGETED_REVISION / NOT_APPROVED` under `CONV-RFQ-G2-FULL-COPY-PCR-01`. Requested checkpoint: verify only the user-confirmed Destination Country text-input contract, then decide whether `FULL_COPY_AND_MODULE_ORDER_CONFIRMED` may be recorded. All other confirmed copy remains frozen. Shared Footer remains wholly owned by Home / Global Chrome and is not a page-level Gate 2 revision item.

Gate 3, wireframes, visual direction, complete visuals, code, development, `D:\16Wordpress_nextjs`, deployment, publication and indexing remain outside this submission.
