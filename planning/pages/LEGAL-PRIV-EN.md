# LEGAL-PRIV-EN — Privacy Policy / Privacy Notice

Status: REVIEW

URL: `/privacy-policy/`
Family: Legal / privacy page · EN

## 页面职责

Explain verified personal-data processing, recipients, retention, rights, transfers and privacy contact routes.

## 内容与事实

- [Current content input](<../inputs/pages/legal-privacy/04_planning/LEGAL-PRIV-EN_GATE2_FULL_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: NO_PRIMARY_KEYWORD
Title: Privacy Policy | TiO2 Malaysia
Meta: Learn how TiO2 Malaysia handles general business inquiry data, retention, necessary Cookies and privacy requests.
H1: Privacy Policy

Keyword boundary: Privacy owns legal transparency only and must not compete with commercial or informational landing pages.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/legal-privacy/04_planning/LEGAL_PRIVACY_GATE5_FULL_VISUAL_SPEC_V0.1.md>)

## 实现与验收

本页已在本地 WordPress 实现，运行态验收结果与待解决依赖记录于下方；不继承旧项目的开发或上线状态。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Sending form notifications by email remains a later task. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/LEGAL-PRIV-EN.json`. The prior Web3Forms disclosures were adapted to the actual WordPress Contact receiver and temporary session state. The later GA4 update discloses optional measurement after consent. Production hosting details and legal review remain open before release. The original source is retained as an approved planning input, not rendered verbatim where its old data flow conflicts with this build.

Local integration found the untouched WordPress Core starter privacy draft at `/privacy-policy/`. `scripts/relocate-core-privacy-draft.php` strictly verifies and preserves that unpublished draft under a separate slug before this owned page is imported. The import then points WordPress's privacy-page option to this owned English page, without adopting the starter draft.

Local review 2026-09-23: `/privacy-policy/` returns HTTP 200 with the expected SEO and one H1. The WordPress starter draft remains unpublished; the owned page is selected as the Core privacy page. Browser review at 1440/768/390 and screenshots are in `.local/utility-http/`. Fresh browser inspection found no Cookie or Local Storage item on this page and no non-local requests. Production host/provider disclosures and legal approval remain open; local review is not publication approval.

Domain audit 2026-09-23: the privacy contact address is updated to `info@tio2products.com` in the seed and the isolated 18080 preview.

GA4 review 2026-09-23: the seed now discloses optional GA4 page and usage measurement only after explicit consent, the stored choice, and withdrawal. The policy migration passed read-only preflight on the isolated preview; production content is unchanged. Legal and production data-processing review remain open before release.
