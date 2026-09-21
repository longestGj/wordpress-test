# CONV-DOC Request Documents — Gate 2 Binding Successor V0.5

## 0. Control

| Field | Value |
|---|---|
| Page / URL | `CONV-DOC` / `/request-documents/` |
| Review ID | `CONV-DOC-G5-DIRECTED-REPAIR-PCR-01` |
| Status | `CURRENT_GATE_5_AUTHORITY / APPROVED_AS_GATE_5_BASELINE` |
| PCR-01 conclusion | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| Parent Gate 5 V0.6 | `APPROVED / CLOSED` |
| User approval | 2026-09-03; current user explicit decision |
| Role | Minimal current Gate 2 binding successor |
| Scope | Country / Region and privacy/submit order only |
| Predecessor | V0.4, retained as historical evidence |
| Gate ceiling | Gate 5 only |

This successor corrects two bindings without reopening the approved page responsibility, module order, five document types, eight-field count, 14-Grade list, Buyer Clean copy, PRODUCT V0.3 relationships, success meaning, Global Chrome or SEO/GEO boundary. The predecessor is not part of the current authority set.

## 1. Country / Region binding

| Property | Current binding |
|---|---|
| Field name | `country_region` |
| Label | `Country / Region` |
| Control | Required single-line free-text input |
| Placeholder | `Enter your country or region` |
| Helper | `Enter the country or region where your company is based.` |
| Empty error | `Enter your country or region.` |
| List/select semantics | None |
| Market or destination meaning | None |
| Routing/evidence effect | None |

The value records buyer-entered company location context only. It does not establish document applicability, approval, availability, route, regulation, certification, market coverage or delivery.

## 2. Privacy and submit binding

Exact privacy sentence:

`We use the information you provide to review and respond to your document request. Learn more in our Privacy Policy.`

`Privacy Policy` links to `/privacy-policy/`. Privacy content precedes submit in DOM, accessible reading and keyboard order at every width and every submittable state.

| Width | Visual order |
|---|---|
| Desktop 1440px | Privacy left; primary CTA right |
| Tablet 768px | Privacy left; primary CTA right unless shared responsive constraints require stacking |
| Mobile 390px | Privacy sentence directly above the full-width primary CTA |

No consent checkbox is part of this form contract.

## 3. Unchanged current contract

- Exactly eight fields: Full Name, Company, Business Email, Country / Region, Product Grade, Document Types, Application / Industry and Additional Requirements.
- Exactly five public Document Types.
- Product Grade remains one required selector with exactly 14 published Grades.
- Valid upstream context remains editable; invalid or empty prefill renders no empty shell.
- Other-only makes Additional Requirements required; mixed selection leaves it optional.
- File availability and applicable scope remain subject to human review.
- Success means receipt only, not approval, access, release or delivery.
- Request Documents remains outside first-level Header navigation; shared RFQ remains permanent and points to `/request-a-quote/`.

## 4. Authority effect

V0.5 is the current effective Gate 2 authority for the bindings in Sections 1–3. V0.4 remains available only for provenance and is marked `HISTORICAL / SUPERSEDED_FOR_COUNTRY_PRIVACY_BINDING`.

PCR-01 remains project-control passed and closed. Parent Gate 5 V0.6 is approved and closed by the user's explicit decision dated 2026-09-03. No Gate 6+, development or `D:/16Wordpress_nextjs` work is authorized.

## 5. Version record

| Version | Date | Change | Status |
|---|---|---|---|
| V0.4 | 2026-09-01 | Historical Buyer Clean content architecture | `HISTORICAL / SUPERSEDED_FOR_COUNTRY_PRIVACY_BINDING` |
| V0.5 | 2026-09-03 | Minimal binding successor for Country free text and privacy-before-submit | `CURRENT_GATE_5_AUTHORITY / APPROVED_AS_GATE_5_BASELINE` |
