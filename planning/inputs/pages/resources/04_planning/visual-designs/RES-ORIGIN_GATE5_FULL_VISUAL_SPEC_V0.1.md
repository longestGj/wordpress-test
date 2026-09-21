# RES-ORIGIN Gate 5 Full Visual Specification V0.1

## 0. Control

| Field | Value |
|---|---|
| Page | `RES-ORIGIN` |
| URL | `/resources/non-china-titanium-dioxide/` |
| Date | 2026-09-05 |
| Gate | Gate 5 — Complete Visual Design |
| Direction | `Procurement Evidence Ledger` |
| Status | `DRAFT_FOR_PROJECT_CONTROL_REVIEW` |
| Content authority | `RES-ORIGIN_CONTENT_ARCHITECTURE_V0.2.md` |
| Responsive authority | `RES-ORIGIN_GATE3_RESPONSIVE_WIREFRAME_SPEC_V0.1.md` |
| Visual authority | `RES-ORIGIN_GATE4_VISUAL_DIRECTION_V0.1.md` |

## 1. Complete Page Sequence

The rendered page contains this exact sequence:

1. Global Header with `Resources` current.
2. Breadcrumb.
3. Hero with one H1, approved support copy, two CTAs and six-step qualification path.
4. Direct Answer.
5. Six due-diligence checks.
6. Five-step technical evidence comparison.
7. Five application routes.
8. Eight-item document/evidence request scope.
9. Four destination-market context cards.
10. Three qualification decisions.
11. Nine buyer FAQ disclosures.
12. Final action block with two CTAs.
13. Global Footer.

The visual does not introduce any new content claim beyond the Gate 2 authority.

## 2. Desktop — 1440px

- Full-page export: `RES-ORIGIN_G5_DESKTOP_1440_V0.1.png`.
- Logical page size: `1440 × 7175`.
- Hero presents the editorial proposition at left and the six-check ledger at right.
- Direct Answer uses Deep Navy to separate the definition from the evaluative sections.
- Six checks use a `2 × 3` evidence-led grid with continuous rules and numbered badges.
- Technical comparison, application routes and document scope alternate white and neutral editorial fields.
- Destination cards use four equal columns; decisions use three equal cards.
- FAQ uses a left section title and right disclosure rail.
- Shared Header and Footer use the correct production-logo roles.

## 3. Tablet — 768px

- Full-page export: `RES-ORIGIN_G5_TABLET_768_V0.1.png`.
- Logical page size: `768 × 10023`.
- Hero and qualification path stack without compressing copy.
- Six checks retain a two-column layout at readable measure.
- Destination cards use two columns; decisions stack to one column.
- CTA and Footer content preserve hierarchy and touch sizing.

## 4. Mobile — 390px Logical

- Full-page export: `RES-ORIGIN_G5_MOBILE_390_LOGICAL_AT2X_V0.1.png`.
- Export size: `780 × 27154`; logical width is `390px` at `2x`.
- One-column reading order matches Desktop and Tablet.
- H1 is `39px`; body copy is `16px / 25.6px`.
- CTAs are full width and at least `44px` high.
- Six checks, technical sequence, applications, evidence request, markets and decisions stack without horizontal overflow.
- FAQ questions remain readable with controls aligned to the right edge.

## 5. Required States

### 5.1 Mobile Menu Open

- Asset: `RES-ORIGIN_G5_MOBILE_MENU_OPEN_390_LOGICAL_AT2X_V0.1.png`.
- Export size: `780 × 1440`; logical viewport is `390px`.
- Production primary logo appears on the light surface.
- `Resources` is current.
- RFQ and Close targets meet the `44px` minimum.

### 5.2 FAQ Open and Focus

- Asset: `RES-ORIGIN_G5_FAQ_FOCUS_STATE_1440_V0.1.png`.
- Export size: `1440 × 983`.
- One answer is open by default in the complete page.
- Keyboard focus is visibly represented and does not depend on color alone.
- All nine question and answer pairs remain in the DOM when disclosures are closed.

### 5.3 Conditional Route Absence

When downstream application, document or market routes are not eligible, the approved explanatory copy remains Buyer Clean and useful. The interface must not show disabled controls, empty cards, placeholders, internal status labels or inferred destinations.

## 6. SEO, GEO and Semantic Contract

- One H1 only.
- Canonical: `https://tio2malaysia.com/resources/non-china-titanium-dioxide/`.
- Meta description: `Evaluate non-China titanium dioxide supply using checks for origin evidence, technical documents, application fit and destination-market requirements.`
- All 48 internal anchors are unique and resolve to valid full routes or in-page targets.
- FAQ content supports later FAQ semantics only if the implementation follows the approved SEO/GEO/Schema contract and visible content remains identical.

## 7. Evidence and Claim Controls

- Named grade count: `0`.
- Product-to-application mapping count: `0`.
- M-996/M-2196 claims: `0`.
- Named customs acceptance, tariff treatment or trade outcome claims: `0`.
- Photography, flags, maps, certificates, factories and trust badges: `0`.
- Buyer-visible governance, placeholder or release-blocker phrases: `0`.

## 8. Gate Boundary

This specification is a Gate 5 review candidate. It does not close Gate 5 or authorize Gate 6–10, code, CMS, development, route activation, deployment, publication, DNS or indexing.

