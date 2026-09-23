# CONV-RFQ — Request a Quote

Status: REVIEW

URL: `/request-a-quote/`
Family: RFQ conversion page · EN

## 页面职责

Capture destination, application, grade/specification, quantity, packaging, document, and sample requirements.

## 内容与事实

- [Current content input](<../inputs/pages/conversion/request-a-quote/04_planning/CONV-RFQ_GATE2_FULL_COPY_V1.1.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide quote supplier
Title: Request a Titanium Dioxide Quote | TiO2 Malaysia
Meta: Request a titanium dioxide quotation from TiO2 Malaysia by providing your grade, application, quantity in metric tonnes and destination for review.
H1: Request a Titanium Dioxide Quote

Keyword boundary: RFQ page owns quotation and purchase-action intent; commercial landing pages link here and retain supplier/product intent.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/conversion/request-a-quote/04_planning/visual-designs/CONV-RFQ_GATE5_FULL_VISUAL_SPEC_V1.0.md>)
- [CONV-RFQ_GATE5_DESKTOP_1440_BUYER_CLEAN_V1.0.png](<../inputs/pages/conversion/request-a-quote/04_planning/visual-designs/gate5_v1.0/CONV-RFQ_GATE5_DESKTOP_1440_BUYER_CLEAN_V1.0.png>)
- [CONV-RFQ_GATE5_MOBILE_390_LOGICAL_AT2X_BUYER_CLEAN_V1.0.png](<../inputs/pages/conversion/request-a-quote/04_planning/visual-designs/gate5_v1.0/CONV-RFQ_GATE5_MOBILE_390_LOGICAL_AT2X_BUYER_CLEAN_V1.0.png>)
- [CONV-RFQ_GATE5_MOBILE_MENU_OPEN_390_LOGICAL_AT2X_V1.0.png](<../inputs/pages/conversion/request-a-quote/04_planning/visual-designs/gate5_v1.0/CONV-RFQ_GATE5_MOBILE_MENU_OPEN_390_LOGICAL_AT2X_V1.0.png>)
- [CONV-RFQ_GATE5_STATE_BOARD_1440_V1.0.png](<../inputs/pages/conversion/request-a-quote/04_planning/visual-designs/gate5_v1.0/CONV-RFQ_GATE5_STATE_BOARD_1440_V1.0.png>)
- [CONV-RFQ_GATE5_MOBILE_STATE_BOARD_390_LOGICAL_AT2X_V1.0.png](<../inputs/pages/conversion/request-a-quote/04_planning/visual-designs/gate5_v1.0/CONV-RFQ_GATE5_MOBILE_STATE_BOARD_390_LOGICAL_AT2X_V1.0.png>)

- [CONV-RFQ_GATE5_TABLET_768_BUYER_CLEAN_V1.0.png](<../inputs/pages/conversion/request-a-quote/04_planning/visual-designs/gate5_v1.0/CONV-RFQ_GATE5_TABLET_768_BUYER_CLEAN_V1.0.png>)

## 内容优先级

最终 Buyer Clean V1.0 视觉规范中明确修订的面向买家文案与表单展示覆盖早期正文对应部分；其余内容仍采用正文输入。原交接材料仅作内容依据，不继承旧运行时或 Gate 状态。进入 READY 时逐项核对字段与接收行为。

## 实现与验收

已在隔离的本地 WordPress 实例实现原生 Page 与报价申请表。已验证服务端必填值、正数 MT、存入私有记录、重复提交回执、错误后保留输入、1440/768/390 页面布局；产品页传入已发布 Grade。数据库测试使用合成资料并清理。邮件通知按用户要求暂缓；正式发布仍须单独授权。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.
