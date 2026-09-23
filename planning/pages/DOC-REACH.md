# DOC-REACH — Titanium Dioxide REACH

Status: ACCEPTED

URL: `/documents/reach/`
Family: Document / compliance page · EN

## 页面职责

Explain verified REACH-related coverage and route qualified document requests.

## 内容与事实

- [Current content input](<../inputs/pages/documents/reach/04_planning/DOC-REACH_GATE2_FULL_BUYER_CLEAN_COPY_V0.1.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide reach registration
Title: Titanium Dioxide REACH Registration: What to Verify | TiO2 Malaysia
Meta: Understand which EU REACH information titanium dioxide buyers should verify across substance identity, legal-entity scope, supply-chain role, source and review date.
H1: Titanium Dioxide REACH Registration: What Procurement Teams Should Verify

Keyword boundary: Document page owns document/compliance intent; product pages only mention grade-specific availability and link here or to the request form.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/documents/reach/04_planning/gate5/DOC-REACH_GATE5_FULL_VISUAL_SPECIFICATION_V0.1.md>)

## 实现与验收

已完成原生 WordPress Page、本地导入与运行态验收。ACCEPTED 仅指本地页面验收，不代表生产发布或文件申请接收流程已上线。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.


### 本地实现与验证（2026-09-23）

- 按当前正文及视觉输入生成 `data/documents/DOC-REACH.json`，正文存原生 Core HTML blocks，可在 Page 编辑器修改；SEO 使用现有可回修字段。来源日期保持原日期。
- 插件 `tio2-products/documents.php` 管稳定身份、产品上下文和请求入口可用性；主题 `documents.php`、`assets/documents.css/js` 管展示。未新增下载库存、证书记录或产品文件可提供性推断。
- `/documents/`、Products 目录和已发布产品详情显示三份指南链接；各指南连接真实已发布的同级页面、Documents 与 Products。未上线目标不产生有效链接，未配置接收页时移除请求动作。TDS 支持三种文档多选、一个可选 Grade 和所选产品详情链接；接收表单实际联调待其可用后完成。
- 导入脚本 `scripts/import-documents.php` 仅允许 localhost/loopback，先校验父页面归属、全部种子、slug 碰撞与重复稳定身份；重复导入保留正文和 SEO，失败回滚本次创建。旧安装必须先执行现有 `scripts/migrate-page-ownership.php`；不得仅凭 slug 收编。
- 已通过：`python tests/document-content.py`；独立 PHP 8.3.35 `tests/document-render.php`（无数据库，WP 接口 stub）；本次变更 PHP lint；`node --check wp-content/themes/tio2/assets/documents.js`；`python tests/planning-integrity.py`。
- 已通过：`python tests/document-browser.py` 的独立 seed 预览 1440/768/390 页面宽度、FAQ 键盘/焦点、TDS 多选/Grade 清除/详情链接，无浏览器脚本错误。截图在未提交的 `.local/document-preview/`。此检查不覆盖真实 WordPress chrome、编辑器或数据库。
- 独立代码审核已修复密码保护正文泄露及绝对站内链接绕过目标可用性检查，并补入无数据库回归。
- 2026-09-23 本地运行态：先备份数据库，再执行显式 ownership migration（0 页需变更）与三页导入；`tests/document-import.php` 验证重复导入保留正文和 SEO、拒绝无归属页面并恢复夹具；`tests/document-http.py` 验证三页 200、标题、SEO、canonical、noindex 及展示的站内链接。
- 真实 WordPress 浏览器检查覆盖三页 1440/768/390，无整页横向溢出及脚本错误；FAQ 可用键盘展开，TDS Grade 可选择、清空并显示对应详情链接。后台实际保存 DOC-REACH 标题、重复导入后保留编辑，再恢复原值并清理新增测试修订。截图位于忽略的 `.local/document-runtime/`。
- Request Documents 接收页尚未实现，页面因此隐藏申请动作；与接收表单的联调属于后续转化页面任务。未执行生产发布。
