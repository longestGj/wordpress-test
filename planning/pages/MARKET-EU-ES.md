# MARKET-EU-ES — Spain

Status: ACCEPTED

URL: `/markets/spain/`
Family: Market procurement landing page · EN

## 页面职责

Own Spain-specific supplier and procurement intent; link to EU-level compliance context.

## 内容与事实

- [Current content input](<../inputs/pages/markets/spain/04_planning/MARKET-EU-ES_GATE2_FULL_BUYER_CLEAN_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide supplier spain
Title: Titanium Dioxide Supplier for Spain | TiO2Products
Meta: Explore Malaysia-origin titanium dioxide for your project in Spain. Review product grades and document needs, then request a quote for your requirements.
H1: Titanium Dioxide Supplier for Spain

Keyword boundary: Country page owns explicit country modifier; EU page owns Europe/EU modifier; Resources owns detailed trade-policy queries.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/markets/spain/04_planning/gate5-v0.1/MARKET-EU-ES_GATE5_COMPLETE_VISUAL_V0.1.html>)

## 实现与验收

本页已完成原生 WordPress Page、本地导入与运行态验收。ACCEPTED 仅指本地页面验收；生产发布、时效性官方来源复核及尚未上线的表单接收流程仍需另行完成。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

### 本地验收（2026-09-23）

- 页面：`http://localhost:8080/markets/spain/`；本地站点 `home/siteurl` 均为 `http://localhost:8080`，数据库写入前保存了忽略目录备份。显式归属迁移确认 0 页需改动，再导入 11 个稳定身份 Page。
- 当前 Page Spec 的 H1、SEO、Buyer Clean 正文和独有国家内容通过 `python tests/market-content.py`；`python tests/market-http.py` 对 11 页 × 1440/768/390 检查 200、title/meta/canonical、noindex、真实站内链接、Markets Hub 入口、移动菜单、FAQ 键盘焦点和横向溢出。真实截图位于忽略目录 `.local/market-http/MARKET-EU-ES-{1440,768,390}.png`。
- `tests/market-import.php` 在本地站点验证重复导入保留后台正文与 SEO、拒绝无归属页面，并恢复内容、SEO、归属和新增测试修订。Products、Applications、Documents、Resources、七个根页面的相关 HTTP 回归均通过。
- 尚未配置 Request a Quote、Request Documents、Request Sample 接收页；市场正文中的不可用独立动作已隐藏，站点共用 Header/Footer 的 RFQ 仍由后续全站转化任务处理。未执行邮件或生产发布；时效性贸易信息需在 RELEASE 前核对官方来源。

### 定向修订（2026-09-24）

- 沿用已实现的共享 EU 国家页四级 Breadcrumb Schema，无需再次修改主题。Spain 内容只补入简短的本国产业结构背景，并明确这不证明 TiO₂ Grade 适用性；保留 Coatings、Plastics、Masterbatch 原有路径区分。
- RFQ 与 Documents 文案去除当前表单控件说明；文档提供与适用范围需按 Grade 和请求情境审查。保留已批准的 COO 原句，并明确不承诺逐票签发或海关接受。不在前台添加行业来源复核日期。
- 一手行业协会来源：[ASEFAPI 会员行业分类](https://asefapi.es/asociados/)（机构定位另见 [Conócenos](https://asefapi.es/conocenos/)）：支持西班牙 decorative/construction、industrial paints、printing inks 领域；页面不显示发布日期，HTML 更新元数据为 2026-09-10。[ANAIP Compuestos y Masterbatches 行业组](https://anaip.es/divisiones/industria/compuestos-y-masterbatches/grupo-sectorial-de-compuestos-y-masterbatches/)：支持该西班牙塑料行业协会设有 compounds/masterbatches 行业组；HTML 发布元数据为 2017-03-08，更新元数据为 2025-07-01。两项于 2026-09-24 核查，官方 URL 返回 HTTP 200；不据此推断 TiO₂ 需求、市场份额、Grade 使用或性能。
- 本地数据库备份后，以稳定归属和三个修改模块的哈希校验更新页面；重复运行无改动。`market-content.py` 与 `market-http.py` 通过，后者覆盖 11 页、三个宽度、SEO、链接、响应式显示及可见/JSON-LD 面包屑一致性。未执行生产发布。
