# PRODUCT-000 — Titanium Dioxide Products

Status: REVIEW

URL: `/products/`
Family: Product hub · EN

## 页面职责

Own generic pigment, rutile, grade-list, and grade-selection intent; route to process and grade pages.

## 内容与事实

- [Current content input](<../inputs/pages/products/04_planning/d32-gate4-v0.1/product-visual.html>)
- [WordPress initialization seed](../../data/pages/products.json) — initialization only; preserve live editor changes.
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide pigment
Title: Titanium Dioxide Pigment Grades | TiO2 Malaysia
Meta: Explore 14 titanium dioxide pigment grades by application, production process and portfolio group, then continue to grade pages for technical evaluation.
H1: Titanium Dioxide Pigment Grades for Industrial Applications

Keyword boundary: Product hub owns generic product-family intent; process pages own process terms; grade pages own model terms; Applications owns use-case terms.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/products/04_planning/d32-gate4-v0.1/product-visual.html>)

## 实现与验收

当前本地实现已完成并在对应批次验收；ACCEPTED 不代表正式上线或所有外部目标已连接。
Native WordPress Page; Products Hub renders through the Product archive.

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

## Products refinement — 2026-09-23

Scope: retain product/grade-selection intent, title/H1, section order, application-first selector, five evaluation steps, design tokens and responsive breakpoints. Primary CTA remains Start Grade Selection. This is a local refinement, not production publication.

Hero: **Explore 14 rutile titanium dioxide pigment grades by application, production process and portfolio group, then continue to individual product pages for grade-specific technical evaluation.** The existing Hero and selector suitability boundary remains. CR-901 is introduced as “CR-901 — specialty grade; process descriptor: vapor-phase oxidation”, not a third industry route alongside chloride/sulfate.

Directory keeps four portfolio groups and the existing summary for each grade. It now shows Grade, Listed applications, Production process (or Process descriptor for CR-901), Key characteristics and View Grade. Applications and process membership are read from the actual product's WordPress taxonomy. CR-901's descriptor comes from its existing `_tio2_product.process_label`. Only legacy lowercase labels are formatted for readability; no terms or relationships are changed. Grade links come from the published product permalink.

Runtime summary source for all 14 grades remains the hub's existing `_tio2_discovery.rows[].summary` (legacy option fallback; initialization seed `data/product-discovery.json`). It is intentionally not overwritten by the more cautious individual-page copy. This preserves approved technical phrasing and editor changes without adding another performance dataset. Technical debt: discovery summaries and individual product copy remain separately editable and require grade-by-grade source review; no automatic taxonomy/copy/discovery synchronization was introduced.

Not Sure: the JSON seed lacked `not_sure`, although import scripts injected approved guidance into the live metadata. Existing populated sites therefore worked, but missing/empty legacy guidance produced no explanatory paragraphs. The seed now includes the approved two paragraphs; a common runtime fallback covers missing/empty guidance while preserving non-empty editor text; the editor rejects empty guidance. Selector mappings are unchanged.

FAQ: replaced “How do I choose a titanium dioxide pigment grade?” with “Can one titanium dioxide grade be used in more than one application?”; removed the duplicate “Does a listed grade guarantee suitability?” question. FAQ count is four. The technical evaluation module and all five steps remain unchanged.

### Grade source verification

The table audits each existing directory summary; it is not runtime configuration. Product records are `data/products/<slug>.json` except M-350, whose current seed is `data/m350.json`. These initialize `_tio2_product`; approved detail contracts live in `planning/inputs/pages/products/detail-template/06_handoff/`. Strong source-backed performance language is retained without cross-grade generalization. TDS files below are under [planning/products/tds](../products/tds/).

| Grade | Existing product fields / per-grade TDS | Source finding |
|---|---|---|
| M-350 | `data/m350.json` hero/positioning/facts; `TDS_M-350_V3_2023.pdf` | Excellent hue, high gloss and strong hiding power supported. |
| M-510 | `data/products/m-510.json` excerpt/facts; `TDS_M-510_TME TMP FREE.pdf` | TMP/TME-free, multi-application, brightness and durability supported. |
| M-896 | `data/products/m-896.json` facts; `TDS_M896_IKHLAS_2023V3.pdf` | Superior weather resistance, high gloss, excellent opacity and exterior context supported. |
| M-996 | `data/products/m-996.json`; approved `2-LBR996-TITAN.pdf` | **SOURCE VERIFICATION REQUIRED — “good gloss”**. Current-approved replacement says good brightness; older branded TDS has gloss. Durability and opacity supported. |
| M-2196 | `data/products/m-2196.json`; `GRADE-M2196_PRODUCT_DETAIL_CONTENT_CONTRACT_V0.1.json` evidenceLedger | **SOURCE VERIFICATION REQUIRED — “Highly durable”, “high opacity”, “easy dispersion”**. Present in older `TDS_M2196_IKHLAS_2024V3.pdf`; the approved replacement image is not located in this repository, and the contract does not establish these exact claims. |
| M-895 | `data/products/m-895.json`; `TDS_M-895_IKHLAS_V1_2026.pdf` | Opacity, gloss and weather resistance supported. |
| M-200 | `data/products/m-200.json`; `TDS_CR-200_2024 V3.pdf` | Exterior plastics, durability and strong anti-chalking supported. |
| M-108 | `data/products/m-108.json`; `TDS_M108_IKHLAS_2023V3.pdf` | Heat stability, low oil absorption and rapid dispersion supported. |
| M-210 | `data/products/m-210.json`; `TDS_M-210_ FDA GRADE V3_2023.pdf` | Hiding power and easy dispersion for polyolefin masterbatch supported. |
| M-340 | `data/products/m-340.json`; `TDS_M340_IKHLAS_2023V.pdf` | **SOURCE VERIFICATION REQUIRED — intensity “strong”** for high-temperature anti-yellowing. Whiteness and anti-yellowing characteristic are supported; the intensity is not established. |
| M-886 | `data/products/m-886.json`; `TDS_M-886_IKHLAS_V1_2026.pdf` | Bright white, excellent dispersion and processability supported. |
| M-52 | `data/products/m-52.json`; `TDS_M-52_V3_2023.pdf` | Very high gloss, high opacity and low abrasivity supported. |
| M-2377 | `data/products/m-2377.json`; approved `TDS-SR2377.pdf` | **SOURCE VERIFICATION REQUIRED — “brightness”, “good opacity”, “easy dispersion”**. Older branded TDS contains them; current-approved replacement has excellent gloss and good dispersion, not those exact qualitative claims. Whiteness/L numeric values alone are not treated as the qualitative brightness claim. |
| CR-901 | `data/products/cr-901.json` excerpt/summary/process_label/rows; `TDS_CR-901_.pdf` | High purity, low impurities, stable batch-to-batch quality and vapor-phase oxidation descriptor supported. |

M-2196 approved replacement evidence hash: `B8B5CF39D54BB1DA66825B8F3B8A68EF36D97D02166667DF4547C872E08A969D`. The missing image has not been visually verified. Source precedence follows [approved relationship/source audit](../inputs/pages/products/01_research/PRODUCT_GRADE_APPLICATION_PROCESS_UNIFIED_AUDIT_V0.3.md). M-996/M-2196 comparative differentiation remains frozen. Existing summaries above were preserved for human verification, not silently weakened or declared newly verified.

Application consistency: all 14 selector grade sets match the individual product taxonomy seeds after label/slug normalization (including M-350's capitalized arrays). Counts remain Coatings 8, Plastics 8, Masterbatch 7, Printing Inks 4, Paper 2, Specialty Materials 1. CR-901 has no chloride/sulfate assignment; its own descriptor is displayed without inventing an industry hierarchy.

### Verification

Isolated WordPress preview: localhost:8083. `tests/products-directory.php` first failed for missing Not Sure fallback, then passed 14 comparison entries, unchanged summary claims, product relationships, descriptor handling and edited-guidance preservation. `tests/products-refinement-migration.php` passed repeat-run preservation and edited-target refusal, restoring fixtures. Existing `tests/discovery-editor.php` and `tests/batch-products.php` passed.

`tests/products-refinement-http.py` passed one H1, frozen SEO title/canonical, 14 correct crawlable product links, all original application mappings, populated Not Sure, working process/CR-901 links, unchanged ItemList, four FAQs and no JS errors/horizontal overflow at 1440/768/390. Desktop and mobile directory screenshots were visually reviewed; evidence is in ignored `.local/products-refinement/`. Independent read-only source and code review found no Critical/Important implementation issues. The four source-verification items above remain open.

Apply local copy changes with `scripts/refine-products-hub.php`: requires owned Products hub, preflights every exact target passage, preserves unrelated editor content/SEO/discovery and refuses intervening target edits. Repeat run makes no changes. No full content import or taxonomy synchronization is required. No production deployment is authorized by this refinement request.
