# TiO2 Malaysia Global Header / Footer Specification V0.5

## 1. Document control

| Field | Value |
|---|---|
| Document ID | `GLOBAL-CHROME-005` |
| Version | `V0.5` |
| Date | 2026-08-31 |
| Review ID | `GHC-CURRENT-TEXT-REMOVAL-PCR-01` |
| Parent review | `GHC-CURRENT-TEXT-REMOVAL-01 = CONDITIONAL_RETURN / TARGETED_REVISION_REQUIRED / NOT_APPROVED` |
| Current status | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| Project-control approval date | `2026-08-31` |
| Approval source | `GHC-CURRENT-TEXT-REMOVAL-PCR-01` directed project-control re-review |
| Revision scope | P0-01 navigation-surface `aria-current` contract; P1-01 external-development status wording |
| Inherited baseline | `GLOBAL_HEADER_FOOTER_SPEC_V0.4.md`; all other V0.4 contracts remain unchanged |
| Production Logo authority | `GLOBAL_HEADER_FOOTER_LOGO_ASSET_ADDENDUM_V1.0.md` and Production SVG Logo Manifest V1.0 |

V0.5 is a targeted governance correction. It does not change the approved visible current-state direction, navigation, Header geometry, Logo, fixed RFQ, Footer or page body.

## 2. Locked visible current-page treatment

### Desktop Header

- Current link: Bold + 3px Malaysia Teal underline.
- Buyer-visible `CURRENT` text, badge, suffix or equivalent status word: 0.

### Mobile Menu

- Current link: Bold + 4px Malaysia Teal left marker.
- Buyer-visible `CURRENT` text, badge, suffix or equivalent status word: 0.

### Footer

- No current-page marker.
- No change from the approved Footer contract.

## 3. Navigation-surface semantic contract

### 3.1 Surface definition

A navigation surface is one complete primary-navigation instance with its own link set. The shared component may retain both a Desktop navigation surface and a Mobile Menu navigation surface in the rendered DOM for responsive behaviour and testing.

### 3.2 Surface-scoped cardinality

- Each navigation surface may contain **at most one** `aria-current="page"`.
- When the current route has an approved navigation mapping, the Desktop navigation surface contains one current link and the Mobile Menu navigation surface contains one current link.
- When the current route has no approved navigation mapping, each surface contains zero current links. The implementation must not infer a false parent.
- A single surface must never contain two or more `aria-current="page"` links.

### 3.3 Accessible-tree cardinality at the active viewport

- At the current viewport, exactly one accessible primary-navigation surface is exposed when the route has an approved current mapping.
- That active accessible surface contains exactly one `aria-current="page"`.
- A responsive navigation surface hidden at the current viewport must not enter the accessibility tree and must not expose focusable descendants.
- The implementation may retain the hidden surface in the DOM, but its hidden state must be programmatically effective rather than visual-only.
- Therefore a full shared-component DOM/test fixture may contain two semantic current nodes in total—one scoped to each surface—while the active viewport accessibility tree exposes exactly one.

### 3.4 Implementation-neutral hidden-surface requirement

The implementation method is owned by the external development project. Whichever mechanism is used must ensure that the inactive surface:

- is excluded from the accessibility tree;
- cannot receive keyboard focus;
- cannot be announced as a duplicate navigation surface; and
- does not change the visible 84px / 64px Header geometry.

This specification does not prescribe a framework component, CSS technique or DOM architecture.

## 4. Approved navigation mapping

| Page / family | Current navigation item per surface |
|---|---|
| Home | Home |
| Markets Hub and Market pages | Markets |
| Products Hub, Process pages and Grade pages | Products |
| Applications Hub and Application pages | Applications |
| Documents Hub and document-request information pages | Documents |
| Resources Hub and Resource pages | Resources |
| About / Contact | About |
| RFQ / Conversion pages | Zero unless a later approved mapping explicitly defines a parent |

## 5. Frozen Global Chrome contracts

| Contract | Frozen value |
|---|---|
| Desktop navigation | `Home | Markets | Products | Applications | Documents | Resources | About | Request a Quote` |
| Desktop Header geometry | 84px |
| Mobile Header geometry | 64px |
| Mobile Header order | `Logo | RFQ | Menu` |
| Mobile Menu order | Same eight-item order as Desktop; RFQ remains the terminal action |
| Logo | Approved Production SVG Manifest V1.0 bindings |
| RFQ | Permanently visible across approved Global Chrome surfaces |
| Footer | No change |
| Page body | No module, copy, SEO/GEO, Schema, fact, route, CTA or responsive-content change |
| Historical PNGs | Retained without overwrite; visible-label pixels are historical static evidence only |

## 6. External-development status boundary

- This `D:\23MySec` specification task did not access or modify `D:\16Wordpress_nextjs`.
- An independent external development task has a candidate implementation identified by commit `024f171`, as reported in the project-control return.
- The existence of that candidate implementation does not approve this specification, prove conformance, authorise release, or change the status of this review.
- Runtime accessibility-tree behaviour, current-state rendering and regression acceptance remain subject to later authorised review of the external result.

No global statement that development is “not started” is made by V0.5.

## 7. Revised acceptance contract

### 7.1 Buyer-visible scan

- Exact buyer-visible status word `CURRENT` in Desktop Header: 0.
- Exact buyer-visible status word `CURRENT` in Mobile Menu: 0.

Internal governance documents may mention the removed word when describing the decision; that is not a buyer-facing failure.

### 7.2 Surface-scoped semantic scan

For each Desktop or Mobile navigation surface:

- approved mapped route: `aria-current="page"` count = 1;
- unmapped route: count = 0;
- count greater than 1: blocking failure.

### 7.3 Active-viewport accessibility scan

For a mapped route at each tested viewport:

- accessible primary-navigation surface count = 1;
- `aria-current="page"` count within the accessible surface = 1;
- focusable links from the inactive surface = 0;
- inactive surface exposure in the accessibility tree = 0.

The full DOM may contain two surface-scoped current nodes when both responsive surfaces are retained, but this is not the active accessibility-tree expectation.

### 7.4 Visual and frozen-contract scan

- Desktop current item: Bold + 3px Teal underline.
- Mobile Menu current item: Bold + 4px Teal left marker.
- Navigation order unchanged.
- Header heights remain 84px / 64px.
- Logo, fixed RFQ, Footer and page body unchanged.
- 390px and 200% zoom introduce no horizontal overflow or hidden-surface focus leak.

## 8. Stage gate

`GHC-CURRENT-TEXT-REMOVAL-PCR-01 = PROJECT_CONTROL_REVIEW_PASS / CLOSED`

V0.5 is the formal Global Chrome authority for buyer-visible current-state treatment. This closure approves the specification only; it does not mean the independent candidate implementation is released, deployed or published.
