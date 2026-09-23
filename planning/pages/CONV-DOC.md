# CONV-DOC — Request Documents

Status: REVIEW

URL: `/request-documents/`
Family: Utility conversion page · EN

## 页面职责

Capture controlled document requests by company/contact context, product grade, document type, and optional application context; Country / Region is contact/company location only and never determines document version, applicability, or scope.

## 内容与事实

- [Current content input](<../inputs/pages/conversion/04_planning/CONV-DOC_CONTENT_ARCHITECTURE_V0.5.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: NO_PRIMARY_KEYWORD
Title: Request Documents | TiO2 Malaysia
Meta: Submit a controlled request for titanium dioxide product, safety, quality, COA, origin or supplier-qualification documentation for human review.
H1: Request Documents

Keyword boundary: Utility page captures controlled requests only; informational document pages own search intent, Market pages own market content, and no country/region value controls document availability or applicability.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/conversion/04_planning/visual-designs/CONV-DOC_FULL_VISUAL_DESIGN_V0.6.md>)
- [CONV-DOC_G5_DESKTOP_1440_DIRECTED_REPAIR_V0.6.png](<../inputs/pages/conversion/04_planning/visual-designs/assets/CONV-DOC_G5_DESKTOP_1440_DIRECTED_REPAIR_V0.6.png>)
- [CONV-DOC_G5_MOBILE_390_DIRECTED_REPAIR_V0.6.png](<../inputs/pages/conversion/04_planning/visual-designs/assets/CONV-DOC_G5_MOBILE_390_DIRECTED_REPAIR_V0.6.png>)
- [CONV-DOC_G5_MOBILE_390_MENU_OPEN_V0.5.png](<../inputs/pages/conversion/04_planning/visual-designs/assets/CONV-DOC_G5_MOBILE_390_MENU_OPEN_V0.5.png>)

- [CONV-DOC_G5_INTERACTION_STATES_DIRECTED_REPAIR_V0.6.png](<../inputs/pages/conversion/04_planning/visual-designs/assets/CONV-DOC_G5_INTERACTION_STATES_DIRECTED_REPAIR_V0.6.png>)
- [CONV-DOC_G5_TABLET_768_DIRECTED_REPAIR_V0.6.png](<../inputs/pages/conversion/04_planning/visual-designs/assets/CONV-DOC_G5_TABLET_768_DIRECTED_REPAIR_V0.6.png>)

## 实现与验收

已在隔离的本地 WordPress 实例实现原生 Page 与文档申请表。已验证 14 个已发布 Grade、五类文档多选、仅选 Other 时说明必填、私有记录与回执、错误保留输入、1440/768/390 页面布局；文档指南的选择可带入申请页。数据库测试使用合成资料并清理。Gmail 通知已实现为保存后的独立步骤，发送状态与重试只在管理后台显示；真实投递仍待本机凭据与收件验证。正式发布仍须单独授权。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Gmail notification code is implemented; real authentication and Inbox receipt verification remain pending. Runtime does not consult this specification or planning source hashes.
