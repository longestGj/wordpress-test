# MARKET-EU-IT — Italy

Status: ACCEPTED

URL: `/markets/italy/`
Family: Market procurement landing page · EN

## 页面职责

Own Italy-specific supplier and procurement intent; link to EU-level compliance context.

## 内容与事实

- [Current content input](<../inputs/pages/markets/italy/04_planning/MARKET-EU-IT_GATE2_FULL_BUYER_CLEAN_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide supplier italy
Title: Titanium Dioxide Supplier for Italy | TiO2Products
Meta: Evaluate Malaysia-origin titanium dioxide for coatings, compound, masterbatch and packaging-printing projects in Italy. Review products, documents and quote inputs.
H1: Titanium Dioxide Supplier for Italy

Keyword boundary: Country page owns explicit country modifier; EU page owns Europe/EU modifier; Resources owns detailed trade-policy queries.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/markets/italy/04_planning/gate4-v0.1/MARKET-EU-IT_GATE4_COMPLETE_VISUAL_V0.1.html>)

## 实现与验收

本页已完成原生 WordPress Page、本地导入与运行态验收。ACCEPTED 仅指本地页面验收；生产发布、时效性官方来源复核及尚未上线的表单接收流程仍需另行完成。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

### 本地验收（2026-09-23）

- 页面：`http://localhost:8080/markets/italy/`；本地站点 `home/siteurl` 均为 `http://localhost:8080`，数据库写入前保存了忽略目录备份。显式归属迁移确认 0 页需改动，再导入 11 个稳定身份 Page。
- 当前 Page Spec 的 H1、SEO、Buyer Clean 正文和独有国家内容通过 `python tests/market-content.py`；`python tests/market-http.py` 对 11 页 × 1440/768/390 检查 200、title/meta/canonical、noindex、真实站内链接、Markets Hub 入口、移动菜单、FAQ 键盘焦点和横向溢出。真实截图位于忽略目录 `.local/market-http/MARKET-EU-IT-{1440,768,390}.png`。
- `tests/market-import.php` 在本地站点验证重复导入保留后台正文与 SEO、拒绝无归属页面，并恢复内容、SEO、归属和新增测试修订。Products、Applications、Documents、Resources、七个根页面的相关 HTTP 回归均通过。
- 尚未配置 Request a Quote、Request Documents、Request Sample 接收页；市场正文中的不可用独立动作已隐藏，站点共用 Header/Footer 的 RFQ 仍由后续全站转化任务处理。未执行邮件或生产发布；时效性贸易信息需在 RELEASE 前核对官方来源。

### 定向修订（2026-09-24）

- 对齐 EU 国家页可见面包屑的结构化数据层级；Italy 及其他五个 EU 国家页为 Home → Markets → European Union → Country。EU 总览与独立国家页仍为三级。
- 移除依赖当前 RFQ/Documents 表单字段名称或选项的正文说明，将 Masterbatch 应用入口放入 Compound and Masterbatch 卡片；保留 COO 批准句及其海关适用边界。移除面向买家的“checked”日期，来源复核信息记录如下。
- 一手行业协会来源复核：[Federchimica AVISA](https://www.federchimica.it/associazioni/avisa)，页面未显示发布日期，支持 wood/industrial coatings 及所列 printing-ink 应用；[Unionplast](https://www.federazionegommaplastica.it/chi-siamo/unionplast/)，页面未显示发布日期，支持意大利塑料加工行业协会背景；[AMAPLAST compounding extrusion lines member directory](https://www.amaplast.org/it/pagine/soci/lista_soci.aspx?id=01.02.02.02.10)，页面未显示发布日期，支持设备行业中 compounding extrusion lines 目录背景。三项于 2026-09-24 复核；不据此推断 TiO₂ 需求、Grade 适用性或认证。
- 本地数据先备份，再通过归属及模块哈希校验更新页面；重复执行无改动。`market-content.py` 和 `market-http.py` 通过；后者覆盖 11 个市场页、三个宽度及两页正文边界。未执行生产发布。
