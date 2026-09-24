# PRODUCT-PROC-SU — Sulfate Process Titanium Dioxide

Status: ACCEPTED

URL: `/products/sulfate-process-titanium-dioxide/`
Family: Process aggregation page · EN

## 页面职责

Explain sulfate-process selection, include sulphate spelling, and aggregate verified sulfate grades.

## 内容与事实

- [Current content input](<../inputs/pages/products/sulfate-process/04_planning/PRODUCT-PROC-SU_GATE2_FULL_BUYER_CLEAN_COPY_V0.2.md>)
- [WordPress initialization seed](../../data/process-applications/sulfate.json) — initialization only; preserve live editor changes.
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: sulfate process titanium dioxide
Title: Sulfate Process Titanium Dioxide Grades | TiO2Products
Meta: Explore five Malaysia-origin sulfate process titanium dioxide Grades by application, then review product details, request documents or request a quote.
H1: Sulfate Process Titanium Dioxide

Keyword boundary: Process page owns sulfate/sulphate category intent; comparison guide owns versus intent; grade pages own model intent.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/products/sulfate-process/04_planning/gate4-v0.1/PRODUCT-PROC-SU_GATE4_COMPLETE_VISUAL_V0.1.html>)

## 实现与验收

当前本地实现已完成并在对应批次验收；ACCEPTED 不代表正式上线或所有外部目标已连接。
Native WordPress Page; Products Hub renders through the Product archive.

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

## 2026-09-24 定向精修

Local implementation adds a hero-adjacent Sulfate/Sulphate at-a-glance summary, cites the U.S. EPA Technical Support Document (section 9.6.3) for the general process description, and removes form-field labels from the document and RFQ guidance. The shared Process schema emits `CollectionPage` and filters ItemList entries through `product_process`; Application detail pages retain `WebPage`. The H1, SEO fields, five verified Grade mappings and Grade-specific descriptions, and process-versus-suitability boundary remain unchanged.

The exact-copy local migration preserves other editor content and refuses altered target passages. Local dry run, idempotence and fixture restoration passed; a temporary M-350 comparison link stayed out of the Sulfate ItemList. Local HTTP tests passed for Sulfate, Chloride and all five Application detail pages. Visual checks at 1440/768/390/320px found no horizontal overflow; the new summary was inspected at tablet and mobile widths. Production publication is separate.
