# CONV-SAMPLE Full Visual Design V0.5 — Privacy, Tablet and Reader-first Copy Revision

## 0. Document Control

| Field | Value |
|---|---|
| Page | `CONV-SAMPLE` / `/request-sample/` |
| Page type | Sample conversion page |
| Review ID | `CONV-SAMPLE-G5-RR-05` |
| Date | 2026-09-03 |
| Parent | V0.4 form-first single-column review candidate |
| Status | `APPROVED / CLOSED` |
| Approval boundary | User approved the complete Gate 5 V0.5 result on 2026-09-03; this closes Gate 5 only and does not authorize Gate 6+, development or release |
| Development boundary | No Gate 6+, development, CMS, route implementation, deployment or indexing |

V0.5 retains the approved-direction full-width order `Sample Request → Human Review → FAQ` and applies the project-control-directed privacy, Tablet, destination-field and copy corrections.

## 1. Buyer-visible Final-state Copy

### Hero

`Share the Malaysia-origin titanium dioxide grade you are considering—or tell us if you are not sure—together with your application, destination and test objective. We will use this context to review the request.`

The former enumerated disclaimer box is removed.

### Privacy notice

Immediately above the primary submit action in every submittable Buyer Clean state:

`We use the information you provide to review and respond to your sample request. Learn more in our Privacy Policy.`

- `Privacy Policy` is a visible link to `/privacy-policy/`.
- No consent or acknowledgement checkbox is added.
- The 768px and 390px link target is at least 44px high.

### Submit helper

`Submitting starts a human review. Any sample arrangement will be confirmed separately.`

### Success

Heading:

`Your sample request has been received.`

Body:

`Our team will review the information provided and contact you if clarification is needed. Any sample arrangement will be confirmed separately.`

### Failure

`We could not confirm that your request was received.`

`Your entries are still on this page. Please try again.`

### Detailed approval explanation

The detailed clarification is retained only in the relevant FAQ:

`No. Submitting starts a human review; it does not approve a sample or confirm any sample arrangement. Any sample arrangement will be confirmed separately.`

No detailed enumerated disclaimer remains in Hero, submit helper, Human Review or Success.

## 2. Destination Field

| Property | V0.5 value |
|---|---|
| Control | Required single-line text input |
| Label | `Destination country or market` |
| Placeholder | `Enter your destination country or market` |
| Helper | `Enter the country or market relevant to this evaluation.` |
| Prefill | Visible and editable when supplied by a valid upstream source |
| Excluded inference | No inventory, shipping, availability or regulatory qualification inference |

## 3. Responsive Composition

### 1440px Desktop

- Shared Desktop Header assembly: 84px.
- Full-width single-column form followed by full-width horizontal Human Review band.
- Two-column field pairs remain inside the form where appropriate.
- No right rail or sticky-sidebar whitespace.

### 768px Tablet

- A dedicated 768px full-page raster is provided; it is not an 834px wireframe or scaled Desktop asset.
- Shared responsive Header assembly is 64px with `Logo | RFQ | Menu`.
- Hero, complete form, Privacy Policy link, Human Review, FAQ and Footer are present.
- Form fields use a single-column flow; document choices wrap without clipping.
- Minimum interactive target is 44px and scroll width equals 768px.

### 390px Mobile

- Existing one-column `Sample Request → Human Review → FAQ` order remains.
- Mobile Menu open and all required interaction states remain.
- Minimum interactive target is 44px and scroll width equals 390px logical.

## 4. State Coverage

- unprefilled;
- M-2377 / Coatings / Sulfate / United Kingdom neutral prefill;
- unknown Grade;
- Other Application with buyer-entered context;
- validation, focus and field errors;
- submitting with disabled primary action;
- submission failure with retained values and retry;
- success;
- form service unavailable;
- Mobile Menu open;
- internal Desktop and 390px state boards.

The internal boards record Privacy route, receiver, route, idempotency and fallback as Gate 8 implementation, Gate 9 read-only QA and Gate 10 release controls. They are not Gate 5 visual blockers and never appear in Buyer Clean frames.

## 5. Product and Claim Boundaries

- PRODUCT V0.3 remains the only relationship baseline.
- M-2377 is neutrally prefilled only with approved context; no suitability or sample approval is inferred.
- Rubber remains buyer-entered Other context and creates no taxonomy, URL, keyword or Schema relationship.
- M-996 and M-2196 remain independent options with no difference, ranking, equivalence, substitution or comparison rationale.
- `NO_PUBLIC_MAPPING` is not rendered as not applicable or unsuitable.
- The page does not promise stock, free samples, sample quantity, timing, freight, shipping, dispatch, delivery or regulatory eligibility.

## 6. Shared Global Chrome Consumption

The page consumes, and does not fork:

- `docs/architecture/GLOBAL_HEADER_FOOTER_SPEC_V0.5.md`;
- `docs/architecture/GLOBAL_HEADER_CURRENT_STATE_PCR_01_CLOSURE_V0.1.md`;
- `brand/logo/production/PRODUCTION_SVG_LOGO_MANIFEST_V1.0.md`;
- asset keys `brand_logo_primary_horizontal` and `brand_logo_reverse_monochrome`.

Request a Sample is not added to first-level navigation. RFQ remains permanently visible and points to `/request-a-quote/`. The page current-navigation key remains `NONE`; visible CURRENT and visible `aria-current=page` remain zero.

## 7. Formal Asset Set

The current V0.5 set contains 16 PNG assets:

1. Desktop 1440 unprefilled.
2. Desktop 1440 prefilled.
3. Desktop 1440 success.
4. Tablet 768 unprefilled full page.
5. Mobile 390 unprefilled.
6. Mobile 390 prefilled.
7. Mobile 390 unknown Grade.
8. Mobile 390 Other Application.
9. Mobile 390 validation/focus/error.
10. Mobile 390 submitting.
11. Mobile 390 submission failure.
12. Mobile 390 success.
13. Mobile 390 service unavailable.
14. Mobile Menu open.
15. Desktop internal interaction-state board.
16. Mobile internal interaction-state board.

Exact paths, dimensions, byte counts and SHA-256 values are controlled by `CONV-SAMPLE_CURRENT_GATE_BASELINE_MANIFEST_V0.5.md`.

## 8. Gate Decision

V0.5 passed project-control re-review and was explicitly approved by the user on 2026-09-03. Review ID `CONV-SAMPLE-G5-RR-05` and Gate 5 are `APPROVED / CLOSED`. Gate 8/9/10 dependencies remain recorded for later implementation, read-only QA and release verification. Gate 6+, development and `D:\16Wordpress_nextjs` were not authorized or entered.

## 9. Version Record

| Version | Date | Change | Authority |
|---|---|---|---|
| V0.5 | 2026-09-03 | Added Buyer-clean Privacy Policy notice, dedicated 768px evidence, text destination field, reader-first copy and stage-correct external dependency treatment | Current user explicitly approved Gate 5 on 2026-09-03 after project-control pass; `APPROVED / CLOSED` |
