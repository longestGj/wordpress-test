# CONV-RFQ Gate 5 Full Visual Specification V1.0

## 0. Control

| Field | Value |
|---|---|
| Page | `CONV-RFQ` / `/request-a-quote/` |
| Page type | RFQ conversion page |
| Primary keyword | `titanium dioxide quote supplier` |
| Gate | Gate 5 — complete visual design |
| Review ID | `CONV-RFQ-G5-VIS-01` |
| Date | 2026-09-01 |
| Status | `DRAFT_FOR_PROJECT_CONTROL_REVIEW / NOT_APPROVED` |
| Gate 4 | `USER_APPROVED / CLOSED` |
| Gate 6 | `LOCKED / NOT_AUTHORIZED` |
| Development / release | `NO / OUT_OF_SCOPE` |

This specification turns the approved Procurement Form Editorial direction into complete Buyer Clean Desktop, Tablet and Mobile pages plus the required interaction and restricted-state evidence. It does not authorize Gate 6, development, deployment, release or indexing.

## 1. Complete-page composition

The complete page uses a single vertical page-body composition at every viewport:

1. inherited Global Header;
2. breadcrumb and `B2B QUOTATION REQUEST` eyebrow;
3. H1 `Request a Titanium Dioxide Quote`;
4. the user-confirmed original Hero body;
5. one dominant quotation-request form surface;
6. low-weight `Other request types` module;
7. inherited complete Global Footer.

Desktop has no page-level left/right split. Two columns are used only for related fields inside the single centred form surface. Tablet and Mobile use one field column.

## 2. Frozen buyer-visible copy

### Hero

> Tell us the product, application, quantity and destination you are evaluating. Our team will review your requirements and prepare the appropriate commercial response.

This is the user-confirmed original Gate 2 Hero body. Gate 5 does not use the rejected replacement beginning `Tell us the grade you need...`.

### Form introduction

> Required fields are marked. Please use business information and avoid confidential formulations, account credentials, payment details or sensitive personal information.

### Privacy

> We use the information you provide to review and respond to your quotation request. Learn more in our Privacy Policy.

`Privacy Policy` is a visible final-reader link. No internal privacy placeholder appears.

### Alternative request routes

> Use the separate request form when you need a sample review or controlled document request instead of a quotation.

The only links are `Request a Sample` and `Request Documents`. They do not replace or compete with the quotation submit action.

## 3. Form hierarchy and minimum-data boundary

### Your requirement

- `Product / Grade *` — one editable select; accepts approved upstream product prefill.
- `Application *` — one editable select; accepts approved upstream application prefill.
- `Required Quantity *` — numeric input with adjacent fixed `Metric tonnes (MT)` suffix.
- `Destination Country *` — editable text input; accepts approved upstream market/destination context.
- `Destination Port / City (optional)`.

### Company details

- `Company Name *`.
- `Your Name *`.
- `Business Email *`.
- `Phone / WhatsApp (optional)`.
- `Website (optional)`.

### Additional requirements

- `Additional Requirements (optional)` — accepts non-confidential specification, packaging, schedule, document or other context from Products, Grade, Applications, Markets, Documents and Resources.

The primary submit label is `REQUEST QUOTE`. There is no duplicate page-body RFQ button above or beside the form.

## 4. PRODUCT V0.3 boundary

- `M-2377` may be neutrally prefilled with `Coatings`, `Plastics`, `Masterbatch`, `Printing Inks`, `Paper` and `Sulfate` when supplied by an approved upstream relationship.
- A prefill remains visible and editable; it is context, not a recommendation or applicability claim.
- `Specialty Materials` is `DO_NOT_RENDER`.
- Rubber evidence does not create a public option, category, page, URL or keyword.
- M-996 and M-2196 receive identical visual treatment. No difference, ranking, advantage, equivalence, substitution or comparative selection reason appears.
- `NO_PUBLIC_MAPPING` is never rendered as `not applicable`.

## 5. Responsive assets

| Asset | Role | Canvas |
|---|---|---|
| `gate5_v1.0/CONV-RFQ_GATE5_DESKTOP_1440_BUYER_CLEAN_V1.0.png` | Complete Desktop Buyer Clean | 1440 × 2615 |
| `gate5_v1.0/CONV-RFQ_GATE5_TABLET_768_BUYER_CLEAN_V1.0.png` | Complete Tablet Buyer Clean | 768 × 3026 |
| `gate5_v1.0/CONV-RFQ_GATE5_MOBILE_390_LOGICAL_AT2X_BUYER_CLEAN_V1.0.png` | Complete Mobile Buyer Clean | 390 × 3257 logical, exported 780 × 6514 |
| `gate5_v1.0/CONV-RFQ_GATE5_MOBILE_MENU_OPEN_390_LOGICAL_AT2X_V1.0.png` | Mobile Menu open | 390 × 844 logical, exported 780 × 1688 |
| `gate5_v1.0/CONV-RFQ_GATE5_STATE_BOARD_1440_V1.0.png` | Desktop interaction/restricted-state proof | 1440 × 2600 |
| `gate5_v1.0/CONV-RFQ_GATE5_MOBILE_STATE_BOARD_390_LOGICAL_AT2X_V1.0.png` | 390px interaction/long-value proof | 390 × 2100 logical, exported 780 × 4200 |

Matching SVG source artifacts are stored beside every PNG. They are design evidence, not development implementation.

## 6. Global Chrome assembly

- Consume `GLOBAL_HEADER_FOOTER_SPEC_V0.5.md` unchanged.
- Desktop Header is 84px; Mobile Header is 64px.
- Header order remains `Home | Markets | Products | Applications | Documents | Resources | About | Request a Quote`.
- Desktop Header, Mobile Header, Mobile Menu, Desktop Footer and Mobile Footer always expose Request a Quote and point to `/request-a-quote/`.
- The conversion route has no inferred current parent and does not add a buyer-visible current-status word.
- Header uses the approved primary horizontal SVG Logo; Footer uses the approved reverse monochrome SVG Logo.
- Footer remains Deep Navy and retains Brand, Explore, Information and Procurement groups.
- No Contact fallback, RFQ-off, hidden, disabled or empty RFQ slot is present.

## 7. Interaction and restricted states

| State | Required visual/behavior contract |
|---|---|
| Initial | Complete responsive Buyer Clean page; no errors before interaction. |
| Editable prefill | Prefilled product/application/market/document/resource context remains visible and editable. |
| Focus | 2px Accessible Teal border and visible separation; focus is not communicated by colour alone. |
| Validation/error | Error summary plus field-level linked text; other entered values remain. |
| Submitting | Submit becomes `SUBMITTING…`; duplicate activation is prevented and the shared RFQ navigation remains visible. |
| Submission failure | `Something went wrong while submitting your request.` / `Your information is still here. Please try again.` / `TRY AGAIN`. |
| Success | `Thank you. We’ve received your quotation request.` / `Our team will review the details and contact you using the information provided.` |
| Form unavailable | `The quotation request form is temporarily unavailable.` / `No request has been submitted. Please return later and try again.` |
| Long value/copy | Text remains inside its container, wraps or produces a clear field error; no page-level horizontal overflow. |

Success means receipt for human review only. It does not mean a quotation, price, inventory, lead time, sample, document, order, transport or regulatory outcome has been approved.

Unknown grade or insufficient information is handled through an editable `Not sure / Need help` product selection and the optional additional-requirements field; the interface does not infer a substitute or promise that the available information is sufficient.

If the route or form is not operational at release time, Buyer Clean remains visible and the release dependency is recorded internally. The RFQ surfaces are not hidden or disabled.

## 8. Visual system

- Inter typography; Navy hierarchy; Accessible Deep Teal action/focus system.
- White main canvas with restrained Soft Background framing.
- One white form surface with Border Gray, 12px radius, light Navy shadow and thin Teal leading rule.
- No page-specific photography, certification badge, availability badge or implied factory/inventory imagery.
- Buttons and links remain at least 44px high on 390px Mobile.
- Long Mobile headings and errors wrap within the 390px logical canvas.

## 9. Review decision requested

Project control should verify the six visual assets, frozen copy, full field set, Global Chrome continuity, responsive/no-overflow evidence and state semantics. A PASS may move the package to pending user approval only. It must not be recorded as user approval or Gate 5 closure.

