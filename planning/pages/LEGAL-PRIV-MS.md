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

Release: requires explicit user authorization. Local Contact and request notifications are implemented; qualified Malay and legal review remain open. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/LEGAL-PRIV-MS.json` under a structural `/ms/` parent. The changed data-flow passages correspond to the English policy's local Contact receiver and later optional GA4 measurement after consent. Reciprocal hreflang is withheld until a Bahasa Malaysia reviewer confirms final legal equivalence. Production hosting details and legal review remain open before release.

Domain audit 2026-09-23: the privacy contact address is updated to `info@tio2products.com` in the seed and main local WordPress Page; a repeat exact migration changed zero Pages.

GA4 review 2026-09-23: the Malay seed now explains optional GA4 measurement only after consent, the stored choice, and withdrawal. The policy migration updated the three owned main local policy Pages and changed zero on repeat; Contact/Gmail disclosures and other editor text were preserved. Production content is unchanged. Malay legal equivalence and production data-processing review remain open before release.

Local review 2026-09-23: `/ms/privacy-policy/` returns HTTP 200, has `lang="ms-MY"`, the expected SEO and one H1. The 1440/768/390 browser review and screenshots are in `.local/utility-http/`. A qualified Bahasa Malaysia/legal equivalence review is still required before hreflang or publication.

Integrated local review 2026-09-23: main WordPress ownership, SEO, internal links, 1440/768/390 layout, keyboard focus and local storage checks passed. Contact/Gmail and GA4 passages are present in the rendered Page, with no legacy contact email. Reciprocal hreflang remains withheld. Current production-host metadata was reported as Oracle `phx` (Phoenix, US), but the final data-processing map and qualified Malay/legal equivalence approval remain open. Status remains REVIEW.
