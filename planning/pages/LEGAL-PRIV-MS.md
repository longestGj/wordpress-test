# LEGAL-PRIV-MS — Dasar Privasi / Notis Perlindungan Data Peribadi

Status: ACCEPTED

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

Release: requires explicit user authorization. The user confirmed review of the three policy Pages on 2026-09-23, including Malay equivalence; production deployment and release checks remain separate. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/LEGAL-PRIV-MS.json` under a structural `/ms/` parent. The changed data-flow passages correspond to the English policy's local Contact receiver and optional GA4 measurement after consent. The policy now names Oracle Cloud Infrastructure in US West (Phoenix) and the possible United States processing of submitted data. Reciprocal hreflang is active after the user's Malay equivalence signoff.

Domain audit 2026-09-23: the privacy contact address is updated to `info@tio2products.com` in the seed and main local WordPress Page; a repeat exact migration changed zero Pages.

GA4 review 2026-09-23: the Malay seed explains optional GA4 measurement only after consent, the stored choice, and withdrawal. The policy migration updated the three owned main local policy Pages and changed zero on repeat; Contact/Gmail disclosures and other editor text were preserved. Production content is unchanged.

Local review 2026-09-23: `/ms/privacy-policy/` returns HTTP 200, has `lang="ms-MY"`, the expected SEO and one H1. The 1440/768/390 browser review and screenshots are in `.local/utility-http/`. The user confirmed Malay/legal equivalence review passed; publication remains a separate release action.

Integrated local review 2026-09-23: main WordPress ownership, SEO, internal links, 1440/768/390 layout, keyboard focus and local storage checks passed. Contact/Gmail and GA4 passages are present in the rendered Page, with no legacy contact email. The user confirmed that the English, Malay and Cookie policy reviews passed. Production-host metadata supplied by the primary task identified Oracle `phx`; [Oracle's region list](https://docs.oracle.com/en-us/iaas/Content/General/Concepts/regions.htm) maps that key to US West (Phoenix). `scripts/update-hosting-policy.php` updated the two owned local privacy Pages, preserved unrelated editor content, and changed zero Pages on repeat. The rendered Malay text describes possible United States processing without inventing a log or email retention period. Reciprocal `en`/`ms-MY` hreflang and canonical URLs passed HTTP checks. Local Page accepted; production release remains separately gated.
