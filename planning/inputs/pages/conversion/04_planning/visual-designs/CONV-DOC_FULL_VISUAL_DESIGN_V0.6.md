# CONV-DOC Gate 5 Directed Repair Full Visual Design V0.6

## 0. Document control

| Field | Value |
|---|---|
| Page / URL | `CONV-DOC` / `/request-documents/` |
| Page type | Utility conversion page |
| Primary keyword | `NO_PRIMARY_KEYWORD` |
| Review ID | `CONV-DOC-G5-DIRECTED-REPAIR-PCR-01` |
| Status | `APPROVED / CLOSED` |
| PCR-01 conclusion | `PROJECT_CONTROL_REVIEW_PASS / CLOSED` |
| Parent | User-approved V0.5 visual redesign |
| Authority | Project-control-directed Gate 5 P0/P1 repair dated 2026-09-03 |
| User approval | 2026-09-03; current user explicit decision |
| Gate ceiling | Gate 5 only |

V0.6 supersedes V0.5 as the current Gate 5 review candidate only. V0.5 user approval remains recorded as historical approval of that exact version; it does not automatically approve this repair version.

Current binding inputs are `CONV-DOC_CONTENT_ARCHITECTURE_V0.5.md` and `wireframes/CONV-DOC_WIREFRAME_SPEC_V0.6.md`. Their predecessors are historical provenance only. This authority correction does not alter or regenerate any V0.6 PNG.

## 1. Directed changes

### 1.1 Country / Region

`Country / Region` is now a required single-line free-text input.

- Label: `Country / Region`
- Placeholder: `Enter your country or region`
- Helper: `Enter the country or region where your company is based.`
- Empty validation: `Enter your country or region.`
- Data key: `country_region`
- Required: yes
- Shared country list: none
- Market/document routing effect: none

The main proof retains `Malaysia` as an editable filled example. Product Grade remains the only 14-value single selector.

### 1.2 Privacy and primary action

The exact privacy sentence is:

> We use the information you provide to review and respond to your document request. Learn more in our Privacy Policy.

`Privacy Policy` links to `/privacy-policy/`. DOM and keyboard order are privacy first, submit second. Desktop/Tablet retain the clear two-column terminal row. At 390px the privacy copy is directly above the full-width `Request Documents` button. No consent checkbox is present.

## 2. Eight-field contract

| # | Key | Label | Control | Requirement |
|---:|---|---|---|---|
| 1 | `full_name` | Full Name | Single-line text | Required |
| 2 | `company` | Company | Single-line text | Required |
| 3 | `business_email` | Business Email | Email | Required; personal-email advice is nonblocking |
| 4 | `country_region` | Country / Region | Single-line free text | Required; no shared country-list dependency |
| 5 | `product_grade` | Product Grade | Single selector | Required; exactly 14 Grades |
| 6 | `document_types` | Document Types | Five-choice multiselect | Required; at least one |
| 7 | `application_industry` | Application / Industry | Single-line text | Optional |
| 8 | `additional_requirements` | Additional Requirements | Textarea | Required only when Other is the sole type; otherwise optional |

No field is added or removed.

## 3. Formal visual assets

### 3.1 Newly affected V0.6 assets

| Proof | File | Dimensions | Bytes | SHA-256 |
|---|---|---:|---:|---|
| Desktop 1440 | `assets/CONV-DOC_G5_DESKTOP_1440_DIRECTED_REPAIR_V0.6.png` | 1440 × 2357 | 308249 | `94AB1D160535C6097F424FE7324FB51A62B131C38635760228BEB1F4C67D7132` |
| Tablet 768 | `assets/CONV-DOC_G5_TABLET_768_DIRECTED_REPAIR_V0.6.png` | 768 × 2658 | 269860 | `2919FC8B630431802BE6A6AAB41750098ABD86B3DA7B683A3FA440A82A46C553` |
| Mobile 390 logical @2× | `assets/CONV-DOC_G5_MOBILE_390_DIRECTED_REPAIR_V0.6.png` | 780 × 6766 | 581551 | `1515014E0BF73ACC8FECF9A0F9AC8F991AA33D6CDD75BC169165D0404B0BA884` |
| Interaction states | `assets/CONV-DOC_G5_INTERACTION_STATES_DIRECTED_REPAIR_V0.6.png` | 1600 × 2820 | 278346 | `4C52C10574B52E504E9142D01C7ACBD09836272814F51849B5CFDE73E3E96183` |

### 3.2 Unchanged required assembly proof

| Proof | File | Dimensions | Bytes | SHA-256 | Status |
|---|---|---:|---:|---|---|
| Mobile Menu open | `assets/CONV-DOC_G5_MOBILE_390_MENU_OPEN_V0.5.png` | 780 × 1440 (`390 logical @2×`) | 45816 | `BCB361A515FB3E03FE918E27BAD3B66C2A9DB67A624F5BA3788448831FB0DC4E` | Byte-identical; unaffected shared Global Chrome proof |

## 4. Interaction-state coverage

The V0.6 state board covers editable prefill, no/invalid prefill, all 14 Grades, keyboard focus, Country/Email field errors, focusable error summary, Other-only and mixed selection, personal-email advice, submitting, failure/retry, success and long-content stress.

Country / Region error coverage includes the exact placeholder, helper and error. The error summary includes `Enter your country or region.` Submission values remain preserved on failure.

## 5. Responsive verification

| Proof | Viewport / output | Scroll width | Minimum tested target | Visual review |
|---|---:|---:|---:|---|
| Desktop | 1440 / 1440×2357 | 1440 | 40px | No crop, horizontal overflow or abnormal blank region |
| Tablet | 768 / 768×2658 | 768 | 40px | No crop, horizontal overflow or abnormal blank region |
| Mobile | 390 @2× / 780×6766 | 390 | 44px | Privacy above CTA; no crop, horizontal overflow or abnormal blank region |
| State board | 1600 / 1600×2820 | 1600 | 40px | Country validation and existing states readable without overlap |
| Mobile Menu | 390 @2× / 780×1440 | 390 | 44px | Unchanged V0.5 shared proof |

Long-content verification retains a 254-character email and 500-character Additional Requirements value.

## 6. Shared Global Chrome consumption

CONV-DOC does not redesign, fork, own or maintain Header, Mobile Header, Mobile Menu or Footer. It consumes the current shared Global Chrome authority:

- `docs/architecture/GLOBAL_HEADER_FOOTER_SPEC_V0.5.md`;
- `pages/home/04_planning/16_global_header_footer_current_state_component_states_v0.5.md`.

The Footer visible in full-page PNGs is assembly evidence only. Its historical text is not a CONV-DOC Gate 7 development contract. Final Footer content, routes and behavior belong to the Global Chrome owner and external integration.

Request Documents remains outside first-level navigation. Shared Request a Quote remains permanently visible in approved surfaces and points to `/request-a-quote/`. No false parent highlight is added for this Conversion route.

## 7. Unchanged evidence and Buyer Clean boundary

- Exactly five public types remain.
- File availability and applicable scope are confirmed only during human review.
- Submission success means the request was received, not approved or delivered.
- No public download, availability badge, approval badge or turnaround promise appears.
- PRODUCT V0.3 remains the relationship baseline.
- M-2377 neutral five-Application and Sulfate context remains allowed; Specialty Materials and Rubber taxonomy remain unrendered; M-996/M-2196 comparisons remain frozen.
- `NO_PUBLIC_MAPPING` is not rewritten as not applicable.
- `NO_PRIMARY_KEYWORD`, current Title/Meta/H1/Canonical input and `WebPage` + `BreadcrumbList` Schema boundary remain unchanged.

## 8. Open Gate 8/9 verification items

1. Render `country_region` as required free text with no country-list binding.
2. Preserve it as contact/company location only, with no market, applicability, route or document-version effect.
3. Verify privacy link route and privacy-before-submit keyboard order across breakpoints.
4. Verify no privacy consent checkbox is introduced.
5. Consume the then-current shared Footer from Global Chrome rather than this page proof's static text.
6. Verify all shared RFQ placements and `/request-a-quote/`.
7. Verify form receiver, preserved-input retry and receipt-only success.

## 9. Review status and stop

`CONV-DOC-G5-DIRECTED-REPAIR-PCR-01 = PROJECT_CONTROL_REVIEW_PASS / CLOSED`.

Parent/current Gate 5 V0.6: `APPROVED / CLOSED`. User approval was recorded on 2026-09-03 from the current explicit decision.

This document has received Gate 5 user approval and Gate 5 is closed. That approval does not authorize Gate 6/7, handoff, development, code, CMS, testing, deployment, release, DNS or indexing.

## 10. Version record

| Version | Date | Change | Status |
|---|---|---|---|
| V0.5 | 2026-09-01 | User-approved compact visual redesign | `SUPERSEDED_AS_ACTIVE_GATE5_CANDIDATE / USER_APPROVAL_PRESERVED_FOR_V0.5` |
| V0.6 | 2026-09-03 | Country free-text control, 390px privacy-before-CTA order and shared Footer ownership correction | `APPROVED / CLOSED` |
