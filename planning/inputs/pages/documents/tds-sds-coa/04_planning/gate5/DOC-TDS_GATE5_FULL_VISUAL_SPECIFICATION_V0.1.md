# DOC-TDS Gate 5 Full Visual Specification V0.1

## 1. Control record

| Field | Value |
|---|---|
| Page ID | `DOC-TDS` |
| URL | `/documents/tds-sds-coa/` |
| Gate | `GATE 5 / COMPLETE VISUAL DESIGN` |
| Visual direction | `A / TECHNICAL EDITORIAL DECISION DESK` |
| Status | `PROJECT_CONTROL_REVIEW_PASS_PENDING_USER_APPROVAL` |
| Gate 5 authorization | User confirmed the presented bounded execution scope on `2026-09-05` |
| Approved upstream | `DOC-TDS_CURRENT_GATE4_APPROVED_MANIFEST_V0.2.md` |
| Gate 6–10 | `NOT AUTHORIZED` |

## 2. Completion delta from Gate 4

Gate 5 does not redesign the approved visual direction. It promotes the approved A v3 direction into a complete visual baseline by separating normal full-page views from interaction evidence and by proving selected states inside the real page components.

New Gate 5 evidence:

- Desktop default full page with all FAQ items initially closed;
- Tablet default full page;
- 390 logical Mobile default full page at 2× export;
- 390 logical Mobile Menu open at 2× export;
- separate three-state selection board promoted to Gate 5 evidence;
- real-page `TDS + M-2196` selected state;
- real-page `SDS + COA / no Grade` selected state;
- separate FAQ open state.

No Buyer Clean copy, module order, SEO/GEO direction, evidence control, CTA target, Grade contract or document-selection contract changed.

## 3. Complete page visual

The ten approved modules render in the following final order:

1. Hero and three-document decision key;
2. Deep Navy Direct Answer;
3. TDS/SDS/COA document-choice cards plus non-selectable multi-document guidance;
4. Product Grade context;
5. TDS/SDS/COA comparison;
6. request checklist;
7. four-step request process;
8. buyer FAQ;
9. related document paths;
10. final request CTA.

The page consumes Global Chrome V0.5. Header and Footer use the exact production Brand Asset Logo URLs bound to Superdesign project `f0b8ff8d-3fde-4581-b3f7-2ea4a63a4cc1`.

## 4. Visual tokens

| Token | Final use |
|---|---|
| `#062B5B` | Primary Navy headings, document identity and active navigation |
| `#031B3A` | Direct Answer and Footer |
| `#008078` | Accessible filled CTAs, selected borders, interactive text and focus treatment |
| `#00A99D` | Decorative large accent only |
| `#F5F8FB` | Context grouping and quiet editorial surfaces |
| `#D9E2EC` | Default borders and table/accordion rules |
| `#334155` | Body copy |
| Inter | All page and Global Chrome typography |

Cards retain 10–14px radii, CTA controls 6–8px radii and restrained low-contrast shadows. Pills, glass effects, fake PDF imagery and promotional styling remain prohibited.

## 5. Interaction states

### 5.1 Default

- no document type selected;
- no Grade selected;
- summary reads `No request context selected yet.`;
- the request form remains available to start without preselected context.

### 5.2 TDS + M-2196

- TDS selected;
- SDS and COA unselected;
- primary Grade=`M-2196`;
- summary=`Selected: TDS · Product Grade: M-2196`;
- all three primary CTAs carry `document_types[]=technical_product&product_grade=M-2196`.

### 5.3 SDS + COA / no Grade

- SDS and COA selected independently;
- TDS unselected;
- no Grade preselected;
- summary=`Selected: SDS, COA`;
- all three primary CTAs carry both document types and no Grade parameter.

### 5.4 FAQ and Mobile Menu

- FAQ open state remains in document flow and uses the approved teal disclosure treatment.
- Mobile Menu shows eight approved items and marks Documents current exactly once.

## 6. Responsive contract

| Viewport | Final composition |
|---|---|
| Desktop 1440 | two-column Hero; four selection cards; two-column Grade context; wide comparison table; horizontal four-step process |
| Tablet 768 | mobile Header; single-column Hero/Direct Answer/Grade; two-column selection cards/process; compact wide comparison table |
| Mobile 390 | single-column Hero, Direct Answer, selection and Grade; full-width CTAs; comparison cards; vertical process; two-column Footer link groups |

All visible interactive targets remain at least 44px in the shorter dimension. No tested viewport has horizontal overflow.

## 7. Content and evidence boundaries

- Buyer-visible text contains no internal Gate, blocker or evidence-gap labels.
- The separate selection-state board is internal visual evidence and is never part of the default Buyer Clean page.
- Selecting a Grade does not indicate document availability.
- Submission does not confirm availability or delivery.
- No stock/factory/certificate/tourism imagery, availability badge, public-download representation or invented factual claim is present.
- Existing ECHA TiO2 and Malaysia-origin publication holds remain unchanged.

## 8. Superdesign continuity

| Field | Value |
|---|---|
| Project | `f0b8ff8d-3fde-4581-b3f7-2ea4a63a4cc1` |
| Draft | `5688ee2b-6554-4a77-a724-b79677fc1592` |
| Gate 3 baseline | `v1` |
| Gate 4 approved direction | `v3` |
| Gate 5 complete candidate | `v4` |
| Preview | `https://p.superdesign.dev/draft/5688ee2b-6554-4a77-a724-b79677fc1592` |

Gate 5 was implemented through the same draft's direct-import version path because the earlier Superdesign generation endpoint was blocked by account credits. The v4 result is revertible and remains bound to the same approved design context and Brand Assets.

## 9. Gate boundary

This specification is ready for user review. It is not user-approved until an explicit Gate 5 decision is recorded. It does not authorise Gate 6, Gate 7, development, D16 changes, deployment, publication, DNS or indexing.
