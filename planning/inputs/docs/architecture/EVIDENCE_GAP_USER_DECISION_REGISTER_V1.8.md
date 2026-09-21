# Evidence Gap User Decision Register V1.8

## 0. Control

| Field | Value |
|---|---|
| Project | TiO2 Malaysia / `site_scope=tio2-my` |
| Date | 2026-09-06 |
| Status | `ACTIVE_PROJECT_GOVERNANCE_REGISTER` |
| Authority | `AGENTS.md` §2.3, §2.5 and §2.6 |
| Purpose | Prevent missing project evidence from being silently converted into a user content decision |

## 1. Status rules

| Status | Meaning |
|---|---|
| `USER_APPROVED / CLOSED` | The user approved the concrete statement and scope for public use. |
| `USER_APPROVED_CURRENT_BASELINE_ONLY / CLOSED` | The user approved the current page wording and chose not to use a stronger unresolved proposition in this baseline. This does not declare the stronger proposition permanently false; future inclusion requires a new scoped decision. |
| `PENDING_USER_FACT_CONFIRMATION` | Project files do not establish the statement; project control must show the actual wording and ask the user. It is not rejected. |
| `EXTERNAL_CURRENT_SOURCE_REQUIRED` | The statement describes a changing government, legal, tax, customs, regulatory or third-party decision. Current authority must be checked and the proposed wording shown to the user. |
| `CONFLICT_REQUIRES_USER_DECISION` | Available records disagree; the conflict and practical choices must be shown to the user. |
| `DO_NOT_RENDER_WITH_REASON` | Use only for verified contradiction, explicit user rejection, explicit legal/project prohibition, or an unverifiable current external conclusion. The reason is mandatory. |

## 2. Current decisions and first review batch

| ID | Concrete statement | Surfaces / pages | Current status | What the user has decided | Next action |
|---|---|---|---|---|---|
| `EG-001` | `Malaysia-origin titanium dioxide` | All suitable visible pages, SEO, GEO, social metadata and Schema | `USER_APPROVED / CLOSED` | Approved for site-wide public use on 2026-09-05 | Current page owners consume `MALAYSIA_ORIGIN_SITE_WIDE_PUBLICATION_AUTHORITY_V1.0.md` |
| `EG-002` | `A Certificate of Origin is available upon request.` | MARKET-UK-001, MARKET-EU-IT, MARKET-EU-ES, MARKET-EU-PL, MARKET-EU-NL, MARKET-EU-BE, MARKET-IN-001, MARKET-BR-EN and MARKET-BR-PT visible copy plus semantically equivalent SEO, GEO, social metadata and Schema; other pages remain separately governed | `MARKET-UK-001 / MARKET-EU-IT / MARKET-EU-ES / MARKET-EU-PL / MARKET-EU-NL / MARKET-EU-BE / MARKET-IN-001 / MARKET-BR-EN / MARKET-BR-PT: USER_APPROVED / CLOSED`; `OTHER PAGES: PENDING_USER_FACT_CONFIRMATION` | UK exact statement approved 2026-09-05; Italy approved 2026-09-06 by the user's reply `1，可以。2，可以提供COO。` to the Italy direction/COO questions. Spain approved 2026-09-06 by the user's reply `确认。` to the Spain direction/CTA and COO questions. Poland approved 2026-09-06 by the user's reply `同意。` to the Poland direction/CTA and COO questions. Netherlands approved 2026-09-06 by the user's reply `同意。下一步` to the Netherlands direction/CTA and COO questions. Belgium confirmed 2026-09-06 by the user's reply `继续` immediately after the controller presented Belgium direction/CTA and the exact COO statement and said the next page would begin after confirmation. India approved 2026-09-06 by the user's reply `继续` immediately after the controller presented India direction/CTA and the exact COO statement and stated that Brazil would begin only after confirmation. Brazil English approved 2026-09-06 by the user's reply `同意。` immediately after the controller presented BR-EN-D01 direction/CTA and BR-EN-D02 exact COO statement. Brazil Portuguese approved 2026-09-06 by the user's reply `批准。` after the controller presented BR-PT-D01 direction/CTA, BR-PT-D02 exact Portuguese COO sentence and BR-PT-D03 planning path. The approved Portuguese sentence is `O Certificado de Origem está disponível mediante solicitação.` This does not add every-shipment provision, customs acceptance or trade outcomes. | UK, Italy, Spain, Poland, Netherlands, Belgium, India, Brazil English and Brazil Portuguese may consume the approved fact without repeating the same confirmation. Content approval does not grant a Gate transition or publication. |
| `EG-003` | `A Certificate of Origin is provided with every shipment.` | Documents, RFQ/order guidance and shipment-related copy | `PENDING_USER_FACT_CONFIRMATION` | Not decided; not rejected | Ask whether this is true for every shipment, only selected transactions, or not a planned promise |
| `EG-004` | `A named customs authority will accept a particular shipment or Grade as Malaysian origin under a specified rule.` | Trade, COO and destination-market content | `EXTERNAL_CURRENT_SOURCE_REQUIRED` | Not decided; not rejected | Identify authority, product/shipment scope and exact proposed statement, then ask the user |
| `EG-005` | `A named destination applies a specific tariff, preference, exclusion, exemption or anti-dumping outcome.` | Trade Updates and destination-market summaries | `EXTERNAL_CURRENT_SOURCE_REQUIRED` | Not decided; not rejected | Verify the current official source and show the dated wording to the user |
| `EG-006` | `Titanium dioxide supplied by IKHLAS TITANIUM (MALAYSIA) SDN. BHD. is covered by an applicable EU REACH registration for the named legal entity and supply arrangement.` | `/documents/reach/` visible answer/FAQ and any semantically equivalent SEO, GEO, social metadata or Schema | `USER_APPROVED_CURRENT_BASELINE_ONLY / CLOSED` | On 2026-09-05 the user decided `保留当前通用答案`; the stronger proposition is not approved for this DOC-REACH baseline. The user did not declare it permanently false. | Gate 7 must carry the approved general answer and exclude the stronger proposition from all public surfaces. Any future inclusion requires a new scoped user decision plus appropriate entity/arrangement evidence. |

## 3. Audit procedure for existing Holds

For each current Hold, `DO_NOT_RENDER`, evidence gap or frozen field:

1. Extract the exact buyer-visible statement that the page would otherwise use.
2. Identify every affected page and machine-readable surface.
3. State what project evidence was found, what was not found and whether the issue concerns an internal business fact or a changing external determination.
4. Replace an evidence-only automatic prohibition with `PENDING_USER_FACT_CONFIRMATION`, `EXTERNAL_CURRENT_SOURCE_REQUIRED` or `CONFLICT_REQUIRES_USER_DECISION` as appropriate.
5. Present the actual wording, impact, risk and project-control recommendation to the user in conversation.
6. Record the user's decision, date, source and precise scope here and in the applicable current Brief, audit or Manifest.
7. Do not infer Gate, development, deployment, publication or indexing authority from a content decision.

Historical files remain unchanged. Their old Holds do not override a newer decision recorded in this register.

## 4. Change record

| Version | Date | Change | Status |
|---|---|---|---|
| V1.0 | 2026-09-05 | Established user-confirmation-first treatment for evidence gaps and recorded the first origin/COO/trade decision batch. | `ACTIVE_PROJECT_GOVERNANCE_REGISTER` |
| V1.0 / EG-002 MARKET-UK-001 decision | 2026-09-05 | Recorded the user's exact approval that a Certificate of Origin is available upon request for MARKET-UK-001 visible and semantically equivalent machine-readable use; EG-003/004/005 and other-page scope remain unchanged. | `MARKET-UK-001: USER_APPROVED / CLOSED` |
| V1.0 / EG-006 DOC-REACH review | 2026-09-05 | Replaced the historical abstract TiO2 Direct Answer publication blocker for current governance with a concrete company/entity/supply-arrangement statement. ECHA's public TiO2 substance entry supports substance-level context only; it does not establish this company-specific proposition. | `PENDING_USER_FACT_CONFIRMATION + EXTERNAL_CURRENT_SOURCE_REQUIRED_FOR_ENTITY_AND_ARRANGEMENT_SCOPE` |
| V1.0 / EG-006 current-baseline decision | 2026-09-05 | Recorded the user's exact decision `保留当前通用答案；授权 Gate 7。`; the current general answer remains authoritative and the stronger proposition is excluded from this baseline without being declared permanently false. Gate 7 authority is recorded separately in the page decision file. | `USER_APPROVED_CURRENT_BASELINE_ONLY / CLOSED` |
| V1.1 / EG-002 MARKET-EU-IT decision | 2026-09-06 | User approved Italy's direction/CTA and confirmed COO availability on request: `1，可以。2，可以提供COO。`. Italy content intent is recorded in its current Manifest; this register adds Italy public and equivalent machine-readable use only. EG-003/004/005 and other-page scope unchanged; V1.0 retained as history. | `MARKET-EU-IT: USER_APPROVED / CLOSED` |
| V1.2 / EG-002 MARKET-EU-ES decision | 2026-09-06 | User replied `确认。` to the two Spain questions: page direction/CTA and COO availability upon request. Spain public and semantically equivalent machine-readable use are approved; Gate 1 approval is recorded separately in Spain Manifest V0.2. Other pages and EG-003/004/005 unchanged. V1.1 preserved as history. | `MARKET-EU-ES: USER_APPROVED / CLOSED` |
| V1.3 / EG-002 MARKET-EU-PL decision | 2026-09-06 | User replied `同意。` to the Poland page direction/CTA and COO availability upon request. Poland public and semantically equivalent machine-readable use are approved; Gate 1 approval is recorded separately in Poland Manifest V0.2. Other pages and EG-003/004/005 unchanged. V1.2 preserved as history. | `MARKET-EU-PL: USER_APPROVED / CLOSED` |
| V1.4 / EG-002 MARKET-EU-NL decision | 2026-09-06 | User replied `同意。下一步` to the Netherlands page direction/CTA and COO availability upon request. Netherlands public and semantically equivalent machine-readable use are approved; Gate 1 approval is recorded separately in Netherlands Manifest V0.2. Other pages and EG-003/004/005 unchanged. V1.3 preserved as history. | `MARKET-EU-NL: USER_APPROVED / CLOSED` |
| V1.5 / EG-002 MARKET-EU-BE decision | 2026-09-06 | User replied `继续` after the two explicit Belgium confirmation items. Under the approved one-page-confirmation serial workflow, this records confirmation of Belgium direction/CTA and COO availability upon request; the exact reply is preserved, not quoted as a different word. Belgium equivalent public use is approved; Gate 1 recorded separately in Manifest V0.2. V1.4, other-page scope and EG-003/004/005 preserved. | `MARKET-EU-BE: USER_APPROVED / CLOSED` |
| V1.6 / EG-002 MARKET-IN-001 decision | 2026-09-06 | User replied `继续` after the controller directly presented India IN-D01 direction/CTA and IN-D02 exact COO statement, and said Brazil would begin after confirmation. Under the approved one-page-confirmation serial workflow, this confirms both explicit India items; the exact reply is preserved. India visible and semantically equivalent machine-readable use is approved; Gate 1 is recorded separately in India Manifest V0.2. V1.5, other-page scope and EG-003/004/005 are preserved. | `MARKET-IN-001: USER_APPROVED / CLOSED` |
| V1.7 / EG-002 MARKET-BR-EN decision | 2026-09-06 | User replied `同意。` after the controller directly presented BR-EN-D01 direction/CTA and BR-EN-D02 exact COO statement and explained that PT-BR would start only after confirmation. This approves Brazil English visible and semantically equivalent machine-readable use. Gate 1 approval is recorded separately in Brazil English Manifest V0.2; PT-BR, Gate 2, every-shipment provision and trade outcomes are not approved by this decision. V1.6 and all other entries are preserved. | `MARKET-BR-EN: USER_APPROVED / CLOSED` |
| V1.8 / EG-002 MARKET-BR-PT decision | 2026-09-06 | User replied `批准。` after the controller directly presented the three PT-BR decisions. This approves the exact Portuguese COO sentence `O Certificado de Origem está disponível mediante solicitação.` for visible and semantically equivalent SEO/GEO/social/Schema use on MARKET-BR-PT. BR-PT-D01 direction/CTA and BR-PT-D03 planning path are recorded in the page Manifest V0.2. It does not assert canonical/hreflang are implemented or approve every-shipment provision or trade outcomes. V1.7 and all other entries are preserved. | `MARKET-BR-PT: USER_APPROVED / CLOSED` |
| V1.8 / EG-001 DOC-COO propagation | 2026-09-13 | Applied the existing site-wide `EG-001` authority to stale claim row `DOC-COO-CL-007`: the exact Malaysia-origin product proposition is public and may be mirrored in SEO/GEO/social metadata/Schema. This is propagation of an existing decision, not a new fact approval. `EG-002/003/004/005` scopes remain unchanged, so no transaction-specific certificate, customs treatment, tariff result or shipment outcome is inferred. | `DOC-COO-CL-007: USER_APPROVED / RENDER_WITH_APPROVED_SCOPE` |






