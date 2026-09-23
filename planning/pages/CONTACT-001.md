# CONTACT-001 — Contact

Status: REVIEW

URL: `/contact/`
Family: Utility contact page · EN

## 页面职责

Provide verified company contact channels and route commercial users to RFQ.

## 内容与事实

- [Current content input](<../inputs/pages/contact/04_planning/CONTACT-001_GATE2_FULL_BUYER_CLEAN_COPY_V0.1.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: NO_PRIMARY_KEYWORD
Title: Contact TiO2 Malaysia | General Inquiries
Meta: Contact TiO2 Malaysia with a general company or business inquiry, or use the dedicated pages to request a quote, product documents or a sample.
H1: Contact TiO2 Malaysia

Keyword boundary: Contact is a utility page and must not be optimized as a commercial supplier landing page.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/contact/04_planning/gate4-v0.2/CONTACT-001_GATE4_COMPLETE_VISUAL_V0.2.html>)

## 实现与验收

本页已在本地 WordPress 实现，运行态验收结果与待解决依赖记录于下方；不继承旧项目的开发或上线状态。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/CONTACT-001.json`. The general form validates on the server and creates a private `tio2_inquiry` record in local WordPress. It does not send email; its success text confirms local receipt only. Errors and success state use a 10-minute server-side record bound to the `tio2_flow` session Cookie. The dedicated request links are disabled until their receivers are ready.

Local review 2026-09-23: `/contact/` returns HTTP 200 with the expected title, description, canonical and one H1. `tests/utility-form-http.py` verified invalid-field and nonce rejection with retained entries, then saved one private local inquiry and removed that exact fixture. `tests/utility-import.php` verified repeat import preserves edited SEO and rejects a slug without project ownership, then restored the metadata fixture. `tests/utility-retention.php` verified three-year cleanup with a disposable fixture. `tests/utility-browser.py` checked 1440/768/390, keyboard menu and Cookie Settings focus, and saved screenshots under `.local/utility-http/`. The Quote, Documents and Sample routes remain unavailable pending their separate receivers; this prevents full page acceptance and release.
