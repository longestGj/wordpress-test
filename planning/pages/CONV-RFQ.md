# CONV-RFQ — Request a Quote

Status: ACCEPTED

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
Title: Request a Titanium Dioxide Quote | TiO2Products
Meta: Request a titanium dioxide quotation through TiO2Products by providing your grade, application, quantity in metric tonnes and destination for review.
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

已在隔离的本地 WordPress 实例实现原生 Page 与报价申请表。已验证服务端必填值、正数 MT、存入私有记录、重复提交回执、错误后保留输入、1440/768/390 页面布局；产品页传入已发布 Grade。数据库测试使用合成资料并清理。Gmail 通知已实现为保存后的独立步骤，发送状态与重试只在管理后台显示；2026-09-23 本机 Gmail TLS 与身份验证通过，三个申请页各一条真实通知均获 Gmail 接受（每条一次），重复提交与无效输入回归通过，测试记录已清理；用户已确认三封通知全部收到。正式发布仍须单独授权。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Gmail notification code is implemented; local authentication and one SMTP-accepted notification per form passed on 2026-09-23; the user confirmed receipt of all three notifications. Runtime does not consult this specification or planning source hashes.

Integrated local acceptance 2026-09-23: the main `/request-a-quote/` Page passed SEO, owned-Page, link and 1440/768/390 browser checks. A new synthetic HTTP submission reached the browser-bound Thank You receipt; repeat POST reused that receipt, and invalid email retained the input with an error. Test mail was intercepted, and the private record and duplicate claim were removed. The earlier real quote notification was confirmed received by the user. Local Page accepted; production publication still needs separate authorization.
