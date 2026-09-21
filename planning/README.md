# 当前策划入口

从 `pages/<Page ID>.md` 开始，只读取本页引用的内容、事实与视觉。`SITE_MAP.csv` 查身份和 URL；`SEO_MAP.csv` 查关键词职责与最终 SEO。页面状态只写在 Page Spec，不在表格、Manifest 和交付回执中重复维护。

`inputs/` 只保存迁入的必要原始输入，保留原路径是为了源文件与本地转换脚本可核验，并不保留旧工作流。原文件中的 Gate、Next.js、D32、site_scope、审批和派发措辞属于历史语境；本仓库 AGENTS、Page Spec 与当前用户决定优先。不要从这些输入启动旧 Agent/Skill 或跨仓库交接。

`products/tds/` 是技术事实输入，`RELATIONSHIPS.csv` 保留允许和禁止公开映射的区别。分类关系、产品页文案和选型导航是三个不同对象，不自动互相推导。

初次迁入的原始文件保持字节一致，来源映射记录在 `../docs/decisions/PLANNING_SOURCE_MAP.csv`。这是一次迁移的追溯记录，不是运行时白名单或每页生命周期清单。后续规格修改用 Git diff；不要追加新的 Manifest 系统。

已实现页面的 `ACCEPTED` 仅指当前本地实现与已记录的验收范围。未实现页面保持 `PLANNED`，不因旧项目的验收通过而改成已开发。正式发布另行授权。历史研究保留原日期，本次迁移不构成事实重新核定。

页面首次建站可从批准内容生成 `data/` 种子。导入后 WordPress 是日常编辑位置；不得用种子覆盖后台修改。新事实或页面职责变化先更新规格；仅后台文案润色不要求再造一份全文副本。

历史仓库：longestGj/tio2mydesign，迁移基线 `765c66ed2b2d9d42cacab9009c7b17830cfdedf5`。它暂时保留原样，并未归档；日常开发从本目录开始。
