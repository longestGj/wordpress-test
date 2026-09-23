# CONTACT-001 — Contact

Status: BUILDING

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

当前 WordPress 尚未实现。本页迁入已有策划输入，不继承其他旧项目的开发/上线状态。进入 READY 时确认本页行为、视觉补充和相关目标已经清楚；不重新调查已批准产品事实。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/CONTACT-001.json`. The general form validates on the server and creates a private `tio2_inquiry` record in local WordPress. It does not send email; its success text confirms local receipt only. Errors and success state use a 10-minute server-side record bound to the `tio2_flow` session Cookie. The dedicated request links are disabled until their receivers are ready. Local HTTP, desktop/tablet/mobile, keyboard and database restoration checks remain for the single database writer's integration pass.
