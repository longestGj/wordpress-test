# MARKET-EU-DE — Germany

Status: ACCEPTED

URL: `/markets/germany/`
Family: Market procurement landing page · EN

## 页面职责

Own Germany-specific supplier and procurement intent; link to EU-level compliance context.

## 内容与事实

- [Current content input](<../inputs/pages/markets/germany/04_planning/MARKET-EU-DE_GATE2_FULL_BUYER_CLEAN_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide supplier germany
Title: Titanium Dioxide Supplier for Germany | TiO2Products
Meta: Evaluate Malaysia-origin titanium dioxide for coatings, plastics and masterbatch procurement in Germany. Review products, documents, samples and quote inputs.
H1: Titanium Dioxide Supplier for Germany

Keyword boundary: Country page owns explicit country modifier; EU page owns Europe/EU modifier; Resources owns detailed trade-policy queries.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/markets/germany/04_planning/gate4-v0.1/MARKET-EU-DE_GATE4_COMPLETE_VISUAL_V0.1.html>)

## 实现与验收

本页已完成原生 WordPress Page、本地导入与运行态验收。ACCEPTED 仅指本地页面验收；生产发布、时效性官方来源复核及尚未上线的表单接收流程仍需另行完成。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

### 本地验收（2026-09-23）

- 页面：`http://localhost:8080/markets/germany/`；本地站点 `home/siteurl` 均为 `http://localhost:8080`，数据库写入前保存了忽略目录备份。显式归属迁移确认 0 页需改动，再导入 11 个稳定身份 Page。
- 当前 Page Spec 的 H1、SEO、Buyer Clean 正文和独有国家内容通过 `python tests/market-content.py`；`python tests/market-http.py` 对 11 页 × 1440/768/390 检查 200、title/meta/canonical、noindex、真实站内链接、Markets Hub 入口、移动菜单、FAQ 键盘焦点和横向溢出。真实截图位于忽略目录 `.local/market-http/MARKET-EU-DE-{1440,768,390}.png`。
- `tests/market-import.php` 在本地站点验证重复导入保留后台正文与 SEO、拒绝无归属页面，并恢复内容、SEO、归属和新增测试修订。Products、Applications、Documents、Resources、七个根页面的相关 HTTP 回归均通过。
- 尚未配置 Request a Quote、Request Documents、Request Sample 接收页；市场正文中的不可用独立动作已隐藏，站点共用 Header/Footer 的 RFQ 仍由后续全站转化任务处理。未执行邮件或生产发布；时效性贸易信息需在 RELEASE 前核对官方来源。

### 定向修订（2026-09-24）

- 对齐 EU 国家页可见面包屑的结构化数据层级；Germany 及其他五个 EU 国家页为 Home → Markets → European Union → Country。EU 总览与独立国家页仍为三级。
- 清理依赖当前 RFQ/Documents 表单字段名称或选项的正文说明；保留产品适用性、文档审批、样品及报价边界。移除面向买家的“checked”日期，来源复核信息记录如下。
- 一手机构来源复核：VdL [2025 German coatings market](https://www.wirsindfarbe.de/statistiken/deutscher-lackmarkt-2025)，发布 2026-02-11，支持 automotive OEM、vehicle-refinish、metal-products coatings；GKV [plastics-processing statement](https://www.gkv.de/de/service/presse/kunststoffverarbeitung-erneut-im-minus.html)，发布 2026-02-18，支持 packaging、construction products、technical parts；Hamburg Port Authority [Port Railway](https://www.hamburg-port-authority.de/en/port-railway)，页面未显示发布日期，支持港口转运企业与欧洲铁路网之间的基础设施联系。三项于 2026-09-24 复核；不据此推断本公司的运输路线、成本或时效。
- 本地数据先备份，再通过归属及模块哈希校验更新页面；重复执行无改动。`market-content.py` 和 `market-http.py` 通过；后者覆盖 11 个市场页、三个宽度以及可见/结构化面包屑一致性。未执行生产发布。
