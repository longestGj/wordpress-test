# WordPress 工作流程

PLAN → READY → BUILD → REVIEW → RELEASE。

- PLAN：确认页面目的、内容、事实来源、URL、SEO、CTA 和视觉；复用已有批准资料，不重复研究。
- READY：`planning/pages/<ID>.md` 信息足以实现。页面状态为 READY；有未解决的实际缺口就记录在本页，不制造交接包。
- BUILD：状态 BUILDING，在真实 WordPress 实现。使用共享模板与 Core；导入保护后台编辑。
- REVIEW：状态 REVIEW，检查正文、事实、SEO、桌面/平板/手机、链接/表单、键盘与性能，并完成适用代码审核。问题修复后标 ACCEPTED，结果写 PR 或现有验收记录。
- RELEASE：用户明确授权后发布，检查备份/回退、表单接收、索引、canonical、sitemap 和冒烟结果，最后标 RELEASED。

当前状态唯一位置是 Page Spec：PLANNED / READY / BUILDING / REVIEW / ACCEPTED / RELEASED。站点地图只导航。PLAN 是动作，PLANNED 是状态。

开发发现事实或职责问题时回 PLAN，只处理受影响部分。不恢复 Gate、Bundle、Workset 或跨仓库 Contract 流程。正常变更只需同一分支内的规格、代码和测试。
