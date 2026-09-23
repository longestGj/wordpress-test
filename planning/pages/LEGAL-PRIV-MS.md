# LEGAL-PRIV-MS — Dasar Privasi / Notis Perlindungan Data Peribadi

Status: REVIEW

URL: `/ms/privacy-policy/`
Family: Legal / privacy page · MS-MY

## 页面职责

Provide the approved Bahasa Malaysia counterpart to the verified English privacy notice.

## 内容与事实

- [Current content input](<../inputs/pages/legal-privacy/04_planning/LEGAL-PRIV-MS_GATE2_FULL_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: NO_PRIMARY_KEYWORD
Title: Dasar Privasi | TiO2 Malaysia
Meta: Ketahui cara TiO2 Malaysia mengendalikan data pertanyaan perniagaan umum, tempoh penyimpanan, Kuki yang diperlukan dan permintaan privasi.
H1: Dasar Privasi

Keyword boundary: Bahasa Malaysia Privacy owns language-equivalent legal transparency only and must not target commercial queries.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/legal-privacy/04_planning/LEGAL_PRIVACY_GATE5_FULL_VISUAL_SPEC_V0.1.md>)

## 实现与验收

本页已在本地 WordPress 实现，运行态验收结果与待解决依赖记录于下方；不继承旧项目的开发或上线状态。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Sending form notifications by email remains a later task. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/LEGAL-PRIV-MS.json` under a structural `/ms/` parent. The changed data-flow passages correspond to the English policy's local Contact receiver and inactive Analytics state. Reciprocal hreflang is withheld until a Bahasa Malaysia reviewer confirms final legal equivalence. Production hosting details and legal review remain open before release.

Domain audit 2026-09-23: the privacy contact address is updated to `info@tio2products.com` in the seed and the isolated 18080 preview. GA4 is not configured; the present no-Analytics disclosure remains accurate until an opt-in implementation and Malay legal review are completed.

Local review 2026-09-23: `/ms/privacy-policy/` returns HTTP 200, has `lang="ms-MY"`, the expected SEO and one H1. The 1440/768/390 browser review and screenshots are in `.local/utility-http/`. A qualified Bahasa Malaysia/legal equivalence review is still required before hreflang or publication.
