# 内容模型

| 对象 | 当前权威与实现 |
|---|---|
| 产品 | WordPress `product` CPT；标题、摘要及 `_tio2_product` 产品字段；共享 Product Template |
| 技术分类/关系 | `product_application`、`product_process` Taxonomy；策划边界见 RELATIONSHIPS.csv |
| 产品页关系描述 | Product Meta 保存文案与 enabled；允许一段文案描述多个批准方向 |
| 产品选型导航 | Products Hub `_tio2_discovery`，与技术分类分开 |
| 一级页、应用、工艺、Resources | Native Page；正文在 post_content，SEO 在独立 meta；复杂表格保留受控 HTML |
| 页面身份 | `_tio2_owner` + `_tio2_hub_key` / `_tio2_page_id` / `_tio2_resource_id`；来源记录不作为运行授权 |
| 全站表现 | Theme 的 Header/Footer、模板、CSS、少量 JS |
| 申请行为 | 后续插件业务；接收流程验收后连接，邮件留到最后 |

`planning/` 说明目的、批准内容和事实边界；`data/` 是创建时的种子；WordPress 数据库保存日常编辑。种子重导入保留后台修改。不要另造第二套实时正文数据库。

产品修订保存 Product Meta 和分类快照；Page SEO 与 Discovery 支持原生修订。旧修订缺失记录无法推算。Git 管代码/规划变更；数据库和 uploads 另行备份。
