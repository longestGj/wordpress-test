# DOC-REACH Gate 5 Full Visual Specification V0.1

## 1. Control record

| Field | Value |
|---|---|
| Page ID | `DOC-REACH` |
| URL | `/documents/reach/` |
| Gate | `GATE 5 / COMPLETE VISUAL DESIGN` |
| Visual direction | `A / REGULATORY EVIDENCE LEDGER` |
| Status | `PROJECT_CONTROL_REVIEW_PASS_PENDING_USER_APPROVAL` |
| Gate 5 authority | User statement `批准 Gate 4，并授权启动 Gate 5` on `2026-09-05` |
| Approved upstream | `DOC-REACH_CURRENT_GATE4_APPROVED_MANIFEST_V0.9.md` |
| Gate 6–10 | `NOT_AUTHORIZED` |

## 2. Completion delta from Gate 4

Gate 5 does not redesign the approved direction. It promotes Direction A into the complete visual baseline by separating the default Buyer Clean page from real interaction/fail-closed evidence.

New Gate 5 evidence:

- Desktop 1440 default full page with all FAQ items initially closed;
- Tablet 768 default full page;
- 390 logical Mobile default full page at 2× export;
- 390 logical Mobile Menu open at 2× export;
- Gate 5 key-state board;
- real-page request-route-unavailable state;
- real-page legal-actor FAQ open with keyboard focus.

No Buyer Clean copy, module order, SEO/GEO direction, evidence boundary, source set, request target or approved visual direction changed.

## 3. Complete page visual

The eleven approved modules remain in final order:

1. Hero and EU/EEA–GB–NI scope orientation;
2. general REACH Direct Answer;
3. substance information vs supplier coverage;
4. legal actors and supply-chain role;
5. regulatory scope by jurisdiction;
6. seven-item buyer verification checklist;
7. official source/scope/date ledger;
8. four-step documentation request process;
9. buyer FAQ;
10. related procurement paths;
11. final documentation CTA.

The page consumes Global Chrome V0.5 and the exact production primary/reverse Logo bindings.

## 4. Final visual tokens

| Token / treatment | Final use |
|---|---|
| `#062B5B` | Primary headings, legal/jurisdiction identity and active navigation |
| `#031B3A` | Direct Answer and Footer |
| `#008078` | request action, evidence rails, interactive text and focus accents |
| Cool soft surfaces | regulatory grouping and evidence orientation |
| Cool grey-blue rules | card/source boundaries and date separation |
| 8–12px radius | restrained panel structure |
| Low-elevation shadow | evidence depth without promotional styling |
| Inter | page and shared Chrome typography |

No certificate seals, compliance badges, public-download thumbnails, flags, factory imagery, stock photography, glass effects or official-authority branding are permitted.

## 5. Interaction and fail-closed states

### 5.1 Default

- all five FAQs are initially closed;
- three request-action groups are present in Hero, Request Process and Final CTA;
- source rows show `Reviewed` for all four sources;
- `Source updated` appears only for the two HSE sources that provide a reliable date.

### 5.2 Legal-actor FAQ open

- exactly one FAQ opens;
- the open question is `Which legal entity and supply-chain role should be checked?`;
- `aria-expanded=true`, answer content remains in document flow and keyboard focus is visible.

### 5.3 Request route unavailable

- all `/request-documents/` links are absent;
- the Request Process submission panel is absent;
- the submission note in the Final CTA is absent;
- the Hero and Final `View Document Hub` routes remain available as ordinary navigation;
- no disabled request button and no automatic Contact fallback are shown;
- the corresponding structured-data relationship must also be absent in implementation.

## 6. Responsive contract

| Viewport | Final composition |
|---|---|
| Desktop 1440 | two-column Hero; three-column role/jurisdiction groups; source/scope/date ledger; horizontal four-step process |
| Tablet 768 | Mobile Header; single-column Hero; two-column decision/source/process groups; complete copy retained |
| Mobile 390 | single-column evidence flow; stacked source rows; vertical process; full-width actions; two-column Footer links where space permits |
| Mobile Menu | eight approved items; Documents current once; terminal Request a Quote; viewport-height native 2× evidence without blank full-page tail |

All tested viewports have zero horizontal overflow. Every visible compact interactive target is at least 44px in its shorter dimension at 1440, 768 and 390 logical px. Mobile full-page and menu evidence is rendered natively at `deviceScaleFactor=2`, not enlarged after capture.

## 7. Content, SEO/GEO and evidence boundaries

- Gate 2 Full Buyer Clean Copy V0.1 remains authoritative.
- The TiO2-specific registration question and answer remain absent while `ECHA_TIO2_DIRECT_ANSWER_PUBLICATION_BLOCKER=OPEN`.
- No company/Grade coverage, registration number, named importer/Only Representative relationship, tonnage band or unqualified compliance claim is introduced.
- Schema and Meta direction remain unchanged; machine-readable relations must match visible route eligibility.
- Source links and time-sensitive dates remain Gate 7/8/9 revalidation items.

## 8. Superdesign continuity

| Field | Value |
|---|---|
| Project | `ab00e0d4-ad19-44d1-8825-84dc020f3cb3` |
| Draft | `02c20623-7f87-4edf-b87e-24ef2137d028` |
| Gate 4 approved direction | `v1` |
| Gate 5 complete candidate | `v3` |
| Preview | `https://p.superdesign.dev/draft/02c20623-7f87-4edf-b87e-24ef2137d028` |

The Gate 5 candidate was imported through the approved Direction A draft's version history, preserving v1 as the revertible approved Gate 4 baseline. Version v3 includes the independently reviewed Desktop 44px target correction. Server-fetched HTML is byte-identical to the validated local source.

## 9. Gate boundary

This specification is ready for user review. It is not user-approved until an explicit Gate 5 decision is recorded. It does not authorise Gate 6–10, development, D16 changes, deployment, publication, DNS or indexing.
