# MARKET-UK-001 Gate 5 Full Visual Specification V0.1

## 0. Control

| Field | Value |
|---|---|
| Page / URL | `MARKET-UK-001` / `/markets/united-kingdom/` |
| Page type | Market procurement landing page |
| Version / date | V0.1 / 2026-09-05 |
| Gate 1–4 | `USER_APPROVED / CLOSED` |
| Gate 5 | `APPROVED / CLOSED` |
| Visual direction | `UK Procurement Editorial` |
| Superdesign | Project `8da9d871-2a33-4afe-ae43-0860688e5dbc`; draft `b4c673c4-4169-4fb9-b366-76631e89b632`; version 5 |
| Gate 6 | `AUTHORIZED / IN_PROGRESS` |
| Gate 7–10 | `LOCKED / NOT_AUTHORIZED` |

## 1. Complete visual set

| Proof | Dimensions | SHA-256 | File |
|---|---:|---|---|
| Desktop complete page | 1440 × 8177 | `5197c6b2b997ebaae75c2617fc9d0339a3de4b949a7dbe52745d7107304ed41b` | `market-uk-001/v0.1/MARKET-UK-001_GATE5_FULL_DESKTOP_1440_V0.1.png` |
| Tablet complete page | 768 × 10724 | `37712250a50b56b044fc9c067735c483e530273c0499a759a8b9d92d2243d686` | `market-uk-001/v0.1/MARKET-UK-001_GATE5_FULL_TABLET_768_V0.1.png` |
| Mobile complete page | 390 × 14770 | `122d28a26e63d7034c9c2e330696ee6429f6e28e64bfab0581ce715c16479a56` | `market-uk-001/v0.1/MARKET-UK-001_GATE5_FULL_MOBILE_390_V0.1.png` |
| Mobile Menu open/focus | 390 × 844 | `57ccc0a11bad4bc16e15ff929dbdb599bdef45dff05e5d6b2eeabde32653886b` | `market-uk-001/v0.1/MARKET-UK-001_GATE5_MOBILE_MENU_OPEN_FOCUS_390_V0.1.png` |
| FAQ all expanded | 1440 × 1247 | `68e20e6bc34028c6fd35a2065c041f41c0538048406da30a9e9f256654f080c0` | `market-uk-001/v0.1/MARKET-UK-001_GATE5_FAQ_ALL_EXPANDED_DESKTOP_1440_V0.1.png` |
| FAQ all collapsed | 390 × 808 | `a837e237747e464a4c6d912e0b91fa75c8a6bb590f6369fba48d9bd69d00d13d` | `market-uk-001/v0.1/MARKET-UK-001_GATE5_FAQ_ALL_COLLAPSED_MOBILE_390_V0.1.png` |
| Documents visible focus | 1440 × 772 | `96b3301ef310a49d6cc84641909eb69881b9ef8892a4da804b8e7d25d33740be` | `market-uk-001/v0.1/MARKET-UK-001_GATE5_DOCUMENTS_FOCUS_DESKTOP_1440_V0.1.png` |
| Responsive HTML source | Planning source | `988e0d2cfa3f2883e4c5ff70b90590894f754aff467977ebe0503e0fd9c9acba` | `market-uk-001/v0.1/MARKET-UK-001_GATE5_FULL_VISUAL_V0.1.html` |

The HTML is a planning and visual-validation source. It is not WordPress, Next.js, CMS, production or handoff implementation code.

## 2. Why the complete-page pixels remain unchanged from Gate 4

The user approved `UK Procurement Editorial` at Gate 4. That Gate 4 candidate already included complete, real-copy 1440, 768 and 390 page renders to prove the direction across all primary viewports. Replacing those pixels at Gate 5 would reopen an approved visual decision without a new requirement.

Gate 5 therefore promotes the same complete-page pixels into the formal approval set and adds the evidence that Gate 4 did not yet own as a final package:

1. explicit Gate 5 asset names, dimensions, byte hashes and authority order;
2. FAQ all-expanded and all-collapsed states;
3. visible keyboard-focus proof;
4. Mobile Menu backward/forward focus containment, Escape and focus return;
5. long-content, no-image, unsupported-capability and Trade-state closure evidence;
6. the current unique Gate 1–5 Manifest.

The Desktop, Tablet, Mobile and Mobile Menu PNG files are byte-identical to Gate 4. The Gate 5 HTML differs only by unique anchor IDs required by the review canvas and a keyboard focus loop. Neither change alters visible content or pixels.

## 3. Locked page composition

The complete page keeps the approved reader sequence:

1. Global Header;
2. Breadcrumb;
3. supplier-led Hero;
4. Direct Answer;
5. five Application paths;
6. six representative Grade paths;
7. equal Great Britain and Northern Ireland decision panels;
8. UK supply-request checklist;
9. Documents and qualification request;
10. Malaysia Origin context;
11. evergreen UK Trade check;
12. six Buyer Questions;
13. Final RFQ section;
14. Global Footer.

No approved Gate 2 copy, heading, module order, CTA label, final-reader destination, UK fact, Grade/Application relationship, SEO/GEO/Schema direction or Trade-freshness rule changes in Gate 5.

## 4. Visual system

- Inter is the sole interface typeface.
- Navy `#062B5B` carries headings and high-trust structure.
- Teal `#007F77` carries primary actions, rules and active treatments.
- Soft Background `#F5F8FB` alternates with white to maintain long-page rhythm.
- Deep Navy `#031B3A` carries the final RFQ and Footer.
- Blue Gray `#D9E2EC` defines restrained borders and separators.
- Cards use clean borders and limited radius; no decorative elevation or consumer-retail styling is introduced.
- Great Britain and Northern Ireland remain equal decision paths, distinguished by Navy and Teal top rules rather than flags or maps.

## 5. Responsive behavior

### Desktop — 1440px

- Maximum content width is 1200px with deliberate 12-column relationships.
- The Hero uses an 8/4 information-to-action split.
- Applications use a balanced 3+2 editorial card arrangement.
- Representative Grades use two equal category panels.
- GB and NI remain side-by-side and equal in weight.
- Questions use a 4/8 label-to-disclosure split.

### Tablet — 768px

- Shared Mobile Header replaces the Desktop navigation.
- Outer margins remain 32px.
- Multi-column cards reduce or stack before copy becomes narrow.
- All modules and actions remain present; nothing is removed for density.

### Mobile — 390px

- Outer margins remain 16px.
- Header order is `Logo | RFQ | Menu`.
- All sections become a deliberate single reading column.
- Coatings precedes Plastics and Masterbatch in the Grade sequence.
- GB precedes NI without suggesting priority.
- FAQ questions wrap naturally; controls remain at least 44px high.
- Client, document and body scroll widths remain 390px.

## 6. Interaction and accessibility

- One H1 and ten approved main-section H2 headings preserve the semantic hierarchy.
- Sixty-four anchors have unique IDs in the Gate 5 planning source; duplicate IDs are zero.
- All visible anchors, buttons and FAQ summaries meet the 44px minimum target height.
- The Documents focus proof uses a 3px solid Teal outline with offset.
- FAQ uses native `details` and `summary`; default, all-expanded and all-collapsed states retain the same content and order.
- Mobile Menu is a modal dialog, focuses Close, traps forward and backward Tab movement, closes on Escape, restores document scrolling and returns focus to Menu.
- Cookie Settings is a semantic button, has no `href` and dispatches the shared Consent Manager event.

## 7. Long-content, media and restricted states

- Complete approved Buyer Clean copy is the long-content fixture; no placeholder or shortened sentence is used.
- Main-content image count is zero. The image-free editorial page is the approved normal state, with no blank media frame.
- No map, flag, landmark, port, route, warehouse, stock, factory, certificate or generic business image appears.
- Unsupported UK office, warehouse, inventory, registration, certification, MOQ, lead-time, packaging and delivery claims remain absent without leaving empty cards.
- The COO sentence appears exactly once in Documents: `A Certificate of Origin is available upon request.`
- The dated Trade paragraph and internal Trade Update action remain absent because the current freshness and route conditions have not been supplied; the evergreen Trade section closes naturally.
- Required final-reader actions remain visible as the approved complete-site target. Their presence is not a live-route assertion.

Detailed state mapping is in `MARKET-UK-001_GATE5_RESPONSIVE_AND_CONDITIONAL_STATES_V0.1.md`.

## 8. Global Chrome

- Header, Mobile Header, Mobile Menu and Footer consume the shared Global Chrome baseline without page-level redesign.
- Markets is the sole current item on the visible navigation surface; buyer-visible `CURRENT` is zero.
- The production primary SVG Logo appears twice in the DOM and the reverse SVG Logo once.
- Fixed RFQ appears on all applicable shared surfaces.
- Footer legal order and semantics are exactly `P, A, A, A, BUTTON`.
- Dasar Privasi (BM) uses `https://tio2malaysia.com/ms/privacy-policy/`.

## 9. Automated and visual validation

| Viewport | Client width | Scroll width | Body width | Page height | H1 | H2 contract | Applications | Grades | FAQ | Result |
|---|---:|---:|---:|---:|---:|---|---:|---:|---:|---|
| 1440 | 1440 | 1440 | 1440 | 8177 | 1 | PASS | 5 | 6 | 6 | PASS |
| 768 | 768 | 768 | 768 | 10724 | 1 | PASS | 5 | 6 | 6 | PASS |
| 390 | 390 | 390 | 390 | 14770 | 1 | PASS | 5 | 6 | 6 | PASS |

Additional verified results:

- Title and Meta match the approved Gate 2 contract;
- GB and NI counts are one each;
- default FAQ state is two open and four collapsed;
- dated Trade statement and internal Trade Update action are zero;
- visible internal governance labels are zero;
- visible `CURRENT` is zero;
- content images are zero;
- short interactive targets are zero;
- Footer order, BM route and Cookie Settings behavior pass;
- Mobile Menu focus containment and return pass;
- all-expanded FAQ, all-collapsed FAQ and Documents focus proofs were visually reviewed at original detail.

## 10. Superdesign trace

| Field | Value |
|---|---|
| Project | `TiO2 Malaysia — United Kingdom Market Gate 4` |
| Project ID | `8da9d871-2a33-4afe-ae43-0860688e5dbc` |
| Draft ID | `b4c673c4-4169-4fb9-b366-76631e89b632` |
| Current version | 5 |
| Version 5 delta | Unique anchor IDs retained; Mobile Menu focus containment added; visible pixels unchanged |
| Canvas | `https://superdesign.dev/teams/748bead0-f9b5-4101-ae48-150238cc276b/projects/8da9d871-2a33-4afe-ae43-0860688e5dbc?node=draft-variant-b4c673c4-4169-4fb9-b366-76631e89b632` |
| Preview | `https://p.superdesign.dev/draft/b4c673c4-4169-4fb9-b366-76631e89b632` |

Gate 4 recorded that two model-generation attempts failed with `insufficient_credits`; the approved local source was imported through the documented fallback. Gate 5 uses a deterministic direct update and does not claim model-generated visual changes.

## 11. Gate boundary

This is the user-approved Gate 5 visual baseline. Gate 6 review is authorized; Gate 7–10, another Market child page, development handoff, WordPress, Next.js, CMS, deployment, publication, DNS and indexing remain unauthorized. `D:\16Wordpress_nextjs` was not accessed.

## 12. Change record

| Version | Date | Change | Status |
|---|---|---|---|
| V0.1 | 2026-09-05 | Promoted the user-approved UK Procurement Editorial direction into the formal 1440/768/390 complete visual set; added FAQ, focus, Mobile Menu containment, restricted/no-image state evidence and current Manifest references without changing approved content or visible page pixels. | `DRAFT_FOR_PROJECT_CONTROL_REVIEW` |
| V0.1 project-control review | 2026-09-05 | Project control passed all complete-page, responsive, interaction, accessibility, Global Chrome and conditional-state checks; retained every visual asset unchanged pending user approval. | `PROJECT_CONTROL_REVIEW_PASS_PENDING_USER_APPROVAL` |
| V0.1 user approval | 2026-09-05 | User approved Gate 5 and authorized Gate 6 review; all visual assets, copy and conditional-state evidence remain unchanged. | `APPROVED_GATE_5_BASELINE / CLOSED` |
