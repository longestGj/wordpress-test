# Legal / Privacy Gate 5 Full Visual Specification V0.1

## 1. Control

| Field | Value |
|---|---|
| Review ID | `LEGAL-PRIVACY-G5-PCR-01` |
| Date | 2026-09-02 |
| Status | `PROJECT_CONTROL_REVIEW_PASS_PENDING_USER_APPROVAL` |
| Gate 4 | `USER_APPROVED / CLOSED` |
| Gate 5 | `USER_AUTHORIZED / PROJECT_CONTROL_REVIEW_PASS_PENDING_USER_APPROVAL / NOT_CLOSED` |
| Gate 6–10 | `NOT_AUTHORIZED` |

This specification applies the approved Gate 2 copy, Gate 3 responsive structure and Gate 4 visual direction to complete buyer-visible pages. It does not change any URL, page responsibility, copy, consent signal or release-state rule.

## 2. Complete page set

| Page | Desktop | Tablet | Mobile |
|---|---|---|---|
| Privacy Policy EN | 1440×7335 | 768×7886 | 390×10984 logical; 780×21968 physical |
| Dasar Privasi BM | 1440×7684 | 768×8234 | 390×11955 logical; 780×23910 physical |
| Cookie Policy EN | 1440×4022 | 768×4489 | 390×5929 logical; 780×11858 physical |

Each asset contains the complete current Buyer Clean copy and closes with the shared Footer. The BM page remains subject to human-equivalence review before release, but no untranslated placeholder or internal instruction appears in the visual.

## 3. Shared visual system

- Header and Footer assemble Global Chrome V0.5 and the approved production SVG Logo assets.
- Legal pages have no inferred current top-navigation item.
- Deep Navy `#031B3A` is used for the Hero and Footer; Primary Navy `#062B5B` controls headings and outlined actions.
- Accessible Functional Teal `#008078` controls filled CTA, normal links, focus indicators and functional borders on light surfaces.
- Malaysia Teal `#00A99D` remains decorative, including section rules and dark-surface accents.
- Pages use a white long-form reading surface, restrained dividers and no decorative photography.
- Buyer body text uses a 15–16px visual target with approximately 1.55–1.65 line height; exact production typography remains governed by the visual standard and implementation QA.

## 4. Responsive behaviour

### Desktop 1440

- Header is 84px.
- Privacy EN/BM use a left table-of-contents rail and right reading column.
- The left rail is in normal document flow and scrolls away with the page; it is never sticky or fixed.
- Cookie inventory renders as a structured table.

### Tablet 768

- Header is 64px in `Logo | RFQ | Menu` order.
- Table of contents becomes a compact disclosure summary while all page sections remain present below it.
- Content is a single reading column; the Cookie inventory remains a compact table with protected technical-key wrapping.

### Mobile 390 logical

- Assets are exported at 780px physical width and explicitly represent `390px logical @2x`.
- Header remains `Logo | RFQ | Menu` and all interactive targets are at least 44px high.
- Table of contents is a compact disclosure summary.
- Cookie inventory reflows from columns into labelled field cards; no horizontal table scroll is required.
- Footer stacks brand, navigation, RFQ and the four legal utilities.

## 5. Shared Mobile Menu open

- Uses the approved eight-item navigation order.
- RFQ remains visible in the header and Request a Quote remains the terminal menu action.
- No false current-page marker is applied to a Legal route.
- The lower utility area contains Privacy Policy, Cookie Policy and Cookie Settings; no internal navigation-state explanation renders.

## 6. Cookie Settings / Consent states

The state proofs contain review annotations outside the public component. Those annotations are not implementation copy.

### Current no-Analytics state

- Title: `Cookie settings`.
- Actions: `Close` and `Read Cookie Policy` with comparable outlined treatment.
- No first-visit Analytics request is shown because no optional Analytics service is active.

### Conditional verified-Analytics state

- Title: `Analytics preferences`.
- `Accept analytics` and `Necessary only` have identical dimensions, white fill and Navy outline.
- Neither choice is preselected.
- Cookie Policy is a tertiary `#008078` link, including on mobile.

### Reopened settings

- Necessary is always active; Analytics is off by default.
- `Save preferences` may use the filled Accessible Functional Teal treatment.
- Closing without saving preserves the existing choice.
- Focus returns to the Footer Cookie Settings control when the interface closes.

### Signal and failure contract

- Before choice, Necessary only and withdrawal: all four Google consent signals are denied.
- Accept analytics: only `analytics_storage` is granted; all three advertising-related signals remain denied.
- If storage is blocked, the interface must not claim persistence.
- On runtime error, optional tags remain denied and the page/RFQ remain usable.
- Only the release state verified at Gate 8/9 may render.

## 7. Authority order

1. Current user decisions and `LEGAL_PRIVACY_GATE4_USER_APPROVAL_AND_GATE5_AUTHORIZATION_V0.1.md`.
2. Gate 2 full-copy authorities for the relevant page.
3. `LEGAL_PRIVACY_GATE3_RESPONSIVE_WIREFRAME_SPEC_V0.2.md`.
4. `LEGAL_PRIVACY_GATE4_VISUAL_DIRECTION_SPEC_V0.2.md`.
5. This Gate 5 specification and the per-page current Gate 5 Manifest.
6. Global Chrome V0.5, Production SVG Logo Manifest V1.0 and the CTA Accessibility Addendum V1.0.

## 8. Boundaries

- The current Cookie Policy page uses only the no-Analytics buyer-visible version.
- The conditional Analytics disclosure is not combined with the current page and cannot render before Gate 8/9 production verification.
- No Terms page is introduced.
- Gate 5 approval would not authorize Gate 6, Gate 7, development, deployment, publication, DNS or indexing.

