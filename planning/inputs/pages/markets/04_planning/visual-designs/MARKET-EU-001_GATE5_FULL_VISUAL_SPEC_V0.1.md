# MARKET-EU-001 Gate 5 Full Visual Specification V0.1

## 0. Control

| Field | Value |
|---|---|
| Page / URL | `MARKET-EU-001` / `/markets/european-union/` |
| Version / date | V0.1 / 2026-09-04 |
| Gate 1–4 | `USER_APPROVED / CLOSED` |
| Gate 5 | `AUTHORIZED / EXECUTED / PROJECT_CONTROL_REVIEW_PASS_PENDING_USER_APPROVAL` |
| Visual direction | `A — Procurement Editorial` |
| Superdesign | Draft `17fff1e7-222d-49da-a016-511b72599344`, version 8 |
| Gate 6–10 | `NOT_AUTHORIZED` |

## 1. Complete visual set

| Proof | Dimensions | File |
|---|---:|---|
| Desktop full page | 1440 × 9746 | `market-eu-001/v0.1/MARKET-EU-001_GATE5_FULL_DESKTOP_1440_V0.1.png` |
| Tablet full page | 768 × 13227 | `market-eu-001/v0.1/MARKET-EU-001_GATE5_FULL_TABLET_768_V0.1.png` |
| Mobile full page | 390 × 18025 | `market-eu-001/v0.1/MARKET-EU-001_GATE5_FULL_MOBILE_390_V0.1.png` |
| Mobile Menu open/focus | 390 × 844 | `market-eu-001/v0.1/MARKET-EU-001_GATE5_MOBILE_MENU_OPEN_FOCUS_390_V0.1.png` |
| FAQ all expanded | 1440 × 1565 | `market-eu-001/v0.1/MARKET-EU-001_GATE5_FAQ_ALL_EXPANDED_DESKTOP_1440_V0.1.png` |
| FAQ all collapsed | 390 × 1078 | `market-eu-001/v0.1/MARKET-EU-001_GATE5_FAQ_ALL_COLLAPSED_MOBILE_390_V0.1.png` |
| Documents keyboard focus | 1440 × 818 | `market-eu-001/v0.1/MARKET-EU-001_GATE5_DOCUMENTS_FOCUS_DESKTOP_1440_V0.1.png` |

Responsive HTML:

`market-eu-001/v0.1/MARKET-EU-001_GATE5_FULL_VISUAL_V0.1.html`

## 2. Approved visual composition

- Shared white Header with full-colour production Logo, Desktop navigation and one Markets current state.
- Supplier-led Hero using a 7/5 Desktop split and the six-node procurement rail.
- White and Soft Background editorial rhythm with Navy headings, Teal actions and Blue Gray dividers.
- Six procurement cards, five Application cards and six representative Grade cards arranged in two equal category panels.
- Separate Documents and EU importing-responsibility sections.
- Deep Navy origin band, controlled customs/trade editorial blocks, six destination links and eight FAQ rows.
- Deep Navy final conversion and shared Footer with reverse production Logo.
- No content photography, map, flag, shipping route, warehouse visual, coverage graphic or certification badge.

## 3. Responsive contract

### Desktop — 1440px

- 1200px maximum content width and editorial 12-column relationships.
- Hero and responsibility modules retain readable asymmetric splits.
- Applications, procurement steps, Grades and Documents use deliberate multi-column arrangements.
- Full long-form copy remains visible; no content truncation or horizontal carousel.

### Tablet — 768px

- 32px outer margins and Mobile Header surface.
- Side-by-side structures stack or reduce columns before readability is compromised.
- Grade category panels, destination links and long source text wrap without clipping.

### Mobile — 390px

- 16px outer margins and `Logo | RFQ | Menu` Header order.
- All content becomes a deliberate single reading column.
- Coatings precedes Plastics & Masterbatch; all six Grade cards remain visible.
- FAQ summaries wrap naturally, and all primary controls retain 44px-or-greater touch height.
- No horizontal overflow.

## 4. Interaction and accessibility states

### Mobile Menu dialog

- Menu opens as a full-height dialog and focuses Close.
- `aria-expanded` changes to `true`; background scrolling is locked.
- Shift+Tab from Close wraps to the final Request a Quote action.
- Tab from the final Request a Quote action wraps to Close.
- Escape closes the dialog, restores scrolling, sets `aria-expanded=false` and returns focus to the Menu trigger.
- Eight navigation links remain present; Markets is the sole current item.

### FAQ disclosure

- Eight native `details/summary` controls are present.
- Desktop all-expanded proof validates full answer length and vertical rhythm.
- Mobile all-collapsed proof validates scanability, wrapping and touch spacing.
- The default complete-page proof retains the approved mixed state with the first two supplier-identity answers expanded.

### Visible focus

- Teal/offset focus rings remain visible on light surfaces.
- The Documents CTA focus proof demonstrates that focus is not represented by colour alone.
- Deep Navy surfaces retain white focus treatment.

## 5. Long-content, missing-media and restricted-state handling

- The approved Buyer Clean copy is the long-content test; no short placeholder text is used.
- Grade positioning, official source lines, company name, FAQ questions and regulatory/trade explanations wrap without clipping.
- The approved design is intentionally image-free. `contentImageCount=0`; therefore the no-content-image state is the normal approved state rather than an error or placeholder.
- Unavailable or unapproved documents remain represented through controlled request language, not empty download cards or public placeholders.
- No internal governance label, release blocker or evidence-gap text appears in the Buyer Clean page.

## 6. Gate 5 interaction correction

The approved Gate 4 prototype opened and closed the Mobile Menu correctly but did not contain backward keyboard focus. A regression test was written and failed with Shift+Tab moving from Close to the background Menu trigger. The minimum correction added a focus loop inside the dialog.

Regression evidence:

| Step | Before correction | After correction |
|---|---|---|
| Initial focus | Close | Close |
| Shift+Tab from Close | Background Menu trigger — FAIL | Final menu RFQ action — PASS |
| Tab from final menu action | Not contracted | Close — PASS |
| Escape | Closes and returns focus | Closes and returns focus — PASS |

This correction changes interaction only. Full-page and Mobile Menu screenshots remain pixel-identical to Gate 4 because no visible styling or content changed.

## 7. Automated validation

| Viewport | Client width | Scroll width | H1 | Grades | Grade groups | Destinations | FAQ | Result |
|---|---:|---:|---:|---:|---:|---:|---:|---|
| 1440 | 1440 | 1440 | 1 | 6 | 2 | 6 | 8 | PASS |
| 768 | 768 | 768 | 1 | 6 | 2 | 6 | 8 | PASS |
| 390 | 390 | 390 | 1 | 6 | 2 | 6 | 8 | PASS |

Additional results:

- exact approved H2 set: PASS;
- selected representative Grades only: PASS;
- FAQ summary count: 8/8;
- content images: 0;
- approved primary Logo references: 2 in DOM; reverse Footer Logo: 1;
- Title and Meta: unchanged;
- visible current navigation: exactly one on the active navigation surface;
- keyboard focus containment and return: PASS.

## 8. Gate boundary

This is a Gate 5 approval candidate. It does not constitute Gate 5 user approval, Gate 6 project-control authorization, development handoff, implementation, CMS change, deployment, production publication, DNS or indexing authorization.

