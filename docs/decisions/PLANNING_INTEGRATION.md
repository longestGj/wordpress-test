# 策划与 WordPress 整合

用户要求：以 wordpress-test 为底座，吸收 D23 当前有效策划，形成单仓库低上下文的原生 WordPress 项目。

来源为干净的 D:/23MySec，longestGj/tio2mydesign@765c66ed2b2d9d42cacab9009c7b17830cfdedf5，与当前已实现页面使用的基线一致。旧根规则中的 Next.js/D32 开发归属不进入新仓库。

迁入范围：59 个页面的身份、SEO 和单页规格入口；已开发 36 页的精确输入；后续 23 页已有的正文及视觉参考；产品技术资料、关系矩阵、关键事实决定、视觉标准。保留必要源文件及其本地依赖，不复制历史版本集合、状态巨表、Agent/Skill、workflow-packages/tooling、整套交付治理合同或实现回执（已使用的产品内容合同作为内容输入保留）。

保留旧仓库和 Git 历史，不合并旧仓库整棵 Git tree。迁移映射记录源路径、目标路径和 SHA-256；复制核验只做一次，不成为网站运行条件。

Page Spec 是当前入口；旧 build-briefs 是已经发生的验收记录，保留以免丢失测试和限制，不再作为新页面交接模板。站点地图不复制状态。规划数据不驱动运行时路由或授权。

构建适配器改为读取仓库内 planning 输入，取消对忽略的 .local 策划缓存及远端仓库下载的依赖。WordPress Theme/Plugin、数据库、已导入正文与运行行为保持原样。

整合在独立本地分支完成。远端改名与旧仓库归档，待新仓库实际完成一两个新页面并确认资料足够后再处理。

## 本次验证

- 迁入 242 份原始资料，共 50,599,378 bytes；逐份比对 D23 原文件和目标 SHA-256 一致。
- 59 个唯一 Page ID / URL 与规格引用检查通过；36 个本地已验收，23 个尚待开发。
- 五个 prepare 脚本在独立重放目录运行成功，生成的全部 data JSON 与现有种子结构逐项一致；未覆盖运行中的 WordPress 数据。
- planning-integrity、batch-http、process-applications-http、resource-http、review-fixes 全部通过。
- 独立复核发现的 SAMPLE / DOC / RFQ 最终视觉缺漏已补齐；SAMPLE 文案覆盖优先级及批准的最大长度约束已写入规格。
- 本次是资料与构建输入整合，不是对全部历史事实重新批准，也不是对尚未开发的 23 页进行网站验收。
