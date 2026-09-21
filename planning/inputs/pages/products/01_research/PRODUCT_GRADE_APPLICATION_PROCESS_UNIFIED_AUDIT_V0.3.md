# PRODUCT Grade–Application–Process Unified Audit V0.3

## Document control

| Field | Value |
|---|---|
| Page | `PRODUCT-000` Products Hub |
| Date | 2026-08-30 |
| Status | `APPROVED_RELATION_BASELINE / USER_APPROVED` |
| Matrix | `PRODUCT_GRADE_APPLICATION_PROCESS_MATRIX_V0.3.csv` |
| Supersedes | V0.2.1 for current relationship decisions; V0.2.1 remains immutable history |
| Approval source | User: the three attachments are current valid technical materials and PRODUCT V0.3 is approved |

## 1. Approved relationship baseline

- Matrix shape: 14 grades × 6 approved Application taxonomy groups = 84 unique relations.
- Application status: 30 `VERIFIED_FOR_PUBLIC_MAPPING`, 0 `CONFLICT_HOLD`, 54 `NO_PUBLIC_MAPPING`.
- Verified Application distribution: Coatings 8, Plastics 8, Masterbatch 7, Printing Inks 4, Paper 2, Specialty Materials 1.
- Grade-level Process distribution: Chloride 8, Sulfate 5, Vapor-phase oxidation 1; unresolved Process count 0.
- `NO_PUBLIC_MAPPING` means no approved positive public relationship. It must never be rendered as “not suitable” or “not applicable”.

## 2. M-2377 decision

| Relation | V0.3 status | Public behavior | Evidence / decision |
|---|---|---|---|
| Coatings | `VERIFIED_FOR_PUBLIC_MAPPING` | Neutral mapping | Current-valid `TDS-SR2377.pdf` |
| Plastics | `VERIFIED_FOR_PUBLIC_MAPPING` | Neutral mapping | Current-valid `TDS-SR2377.pdf` |
| Masterbatch | `VERIFIED_FOR_PUBLIC_MAPPING` | Neutral mapping | Current-valid `TDS-SR2377.pdf` |
| Printing Inks | `VERIFIED_FOR_PUBLIC_MAPPING` | Neutral mapping | Current-valid `TDS-SR2377.pdf` |
| Paper | `VERIFIED_FOR_PUBLIC_MAPPING` | Neutral mapping | Current-valid `TDS-SR2377.pdf` |
| Specialty Materials | `NO_PUBLIC_MAPPING` | `DO_NOT_RENDER` | No approved positive mapping; absence is not unsuitability |
| Process | Sulfate / `VERIFIED_FOR_PUBLIC_MAPPING` | Neutral classification | User technical decision dated 2026-08-30 |

The TDS also lists Rubber. Rubber is recorded as evidence only. It does not alter the approved six-group taxonomy and does not create a page, URL, primary keyword or navigation item.

## 3. M-996 and M-2196 decision

- `2-LBR996-TITAN.pdf` is the current valid technical material mapped to M-996.
- `微信图片_20260830100826_2095_3.png` is the current valid technical material mapped to M-2196.
- Their individual Coatings and Sulfate facts remain verified and now cite the user-approved attachments.
- `M996_VS_M2196_DIFFERENTIATION_FROZEN` remains on all 12 matrix rows.
- Do not publish ranking, superiority, equivalence, substitution, relative positioning or an unsupported direct selection rationale between the two grades.
- Individual source-bound facts may be used without turning them into comparative claims.

## 4. Gate disposition

| Gate | V0.3 status | Meaning |
|---|---|---|
| `R-M2377-PROCESS` | `RESOLVED` | Sulfate confirmed by user technical decision |
| `R-M2377-APPLICATION` | `RESOLVED` | Five positive Application mappings approved from current-valid TDS |
| `R-M2377-DOCUMENT-FRESHNESS` | `RESOLVED` | User confirmed the submitted TDS is current and valid |
| legacy `R-M2377-TDS` | `RESOLVED / SUPERSEDED_BY_SPLIT_GATES` | Must not remain an active hard gate |
| `M996_VS_M2196_DIFFERENTIATION_FROZEN` | `OPEN` | Comparison restrictions remain |

## 5. Public rendering rules

1. Render only rows marked `VERIFIED_FOR_PUBLIC_MAPPING` and only as neutral relationships.
2. M-2377 may appear under Sulfate and the five approved Application groups.
3. Keep M-2377 Specialty Materials hidden.
4. Do not create Rubber taxonomy or a Rubber page from this audit.
5. SEO, GEO and Schema may expose only visible approved relationships.
6. No Product, Process or Application child page is started by this baseline.

## 6. Validation record

- 84 relation IDs and 84 grade+application pairs are unique.
- Counts: 30 verified / 0 conflict / 54 no-public.
- M-2377: five verified Applications, one no-public Application and Sulfate Process; zero conflict rows.
- M-996/M-2196: 12/12 comparison-hold rows preserved.
- V0.2.1 SHA-256 remains `AFF7785098FBDA31A560D028CB8E49D04A222BA8EAE88294C1285A8CD814FDC9`.

