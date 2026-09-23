# LEGAL-COOKIE-EN — Cookie Policy

Status: REVIEW

URL: `/cookie-policy/`
Family: Legal / cookie page · EN

## 页面职责

Explain verified Cookies, Local Storage, consent categories, providers, durations and preference controls.

## 内容与事实

- [Current content input](<../inputs/pages/legal-privacy/04_planning/LEGAL-COOKIE-EN_GATE2_FULL_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: NO_PRIMARY_KEYWORD
Title: Cookie Policy | TiO2 Malaysia
Meta: Learn which necessary Cookies TiO2 Malaysia uses for its contact form and how to review browser storage.
H1: Cookie Policy

Keyword boundary: Cookie Policy owns storage and consent transparency only; Cookie Settings remains a non-page functional control.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/legal-privacy/04_planning/LEGAL_PRIVACY_GATE5_FULL_VISUAL_SPEC_V0.1.md>)

## 实现与验收

本页已在本地 WordPress 实现，运行态验收结果与待解决依赖记录于下方；不继承旧项目的开发或上线状态。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Sending form notifications by email remains a later task. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/LEGAL-COOKIE-EN.json`. The initial local review covered the necessary `tio2_flow` session Cookie, temporary server state, and WordPress administrator authentication. The later GA4 implementation adds an optional analytics choice and its disclosed storage only on the indexable production domain. Clean-browser production inventory remains a release check.

Local review 2026-09-23: `/cookie-policy/` returns HTTP 200 with expected SEO and one H1. A clean browser retained no Cookie or Local Storage on the privacy page; visiting Contact set only the HttpOnly browser-session `tio2_flow` Cookie, with Local Storage still empty and network requests confined to localhost. Browser review at 1440/768/390 verified the mobile inventory cards and Cookie Settings focus; screenshots are in `.local/utility-http/`. Production inventory and legal review remain open.

Domain audit 2026-09-23: the cookie contact address is updated to `info@tio2products.com` in the seed and the isolated 18080 preview.

GA4 review 2026-09-23: the seed now describes the confirmed `G-SY6PZPX0VR` stream, optional GA4 Cookies and Local Storage choice. The theme only renders consent controls on the indexable HTTPS production host; no Google tag is requested before consent or after rejection. A scoped Page migration passed dry-run preflight on the isolated 18080 preview without changing its database. Production publication, clean-browser inventory, and legal review remain open.
