# APP-PLAS — TiO2 for Plastics

Status: ACCEPTED

URL: `/applications/titanium-dioxide-for-plastics/`
Family: Application landing page · EN

## 页面职责

Own generic Plastics use-case intent and recommend verified grades.

## 内容与事实

- [Current content input](<../inputs/pages/applications/plastics/04_planning/APP-PLAS_GATE2_FULL_BUYER_CLEAN_COPY_V0.3.md>)
- [WordPress initialization seed](../../data/process-applications/plastics.json) — initialization only; preserve live editor changes.
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide for plastics
Title: Titanium Dioxide for Plastics | Grade Evaluation
Meta: Compare TiO2 candidates in a defined plastic resin, process, specimen and exposure. Review Product Grades and prepare a document, sample or quotation request.
H1: Titanium Dioxide for Plastics

Keyword boundary: Application page owns generic use-case intent; grade pages own exact model intent and link back for broader selection.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/applications/plastics/04_planning/gate4-v0.1/APP-PLAS_GATE4_COMPLETE_VISUAL_V0.1.html>)

## 实现与验收

当前本地实现已完成并在对应批次验收；ACCEPTED 不代表正式上线或所有外部目标已连接。
Native WordPress Page; Products Hub renders through the Product archive.

2026-09-23 定向精修验收：Hero 增加可见面包屑，Hero 后增加仅概括现有正文的七项速览，RFQ 文案去除表单字段名依赖。8 个 Grade、主体技术章节、Plastics / Masterbatch 边界、技术来源与 SEO Title 保持不变。通用 application-detail `ItemList` 已依据公开产品链接、`product_application` 分类和 published 状态输出 8 项；本地验证覆盖链接、Schema、1440/768/390 视口和移动端表格。正式站点尚未发布。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.
