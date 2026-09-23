# MARKET-EU-001 — European Union

Status: ACCEPTED

URL: `/markets/european-union/`
Family: Market procurement landing page · EN

## 页面职责

Own Europe/EU supplier intent, EU compliance context, origin documentation, applications, and country-page routing.

## 内容与事实

- [Current content input](<../inputs/pages/markets/04_planning/MARKET-EU-001_GATE2_FULL_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide supplier europe
Title: Malaysia Titanium Dioxide Supplier for EU Buyers | TiO2 Malaysia
Meta: Evaluate titanium dioxide supply for EU procurement by application, grade, documents, origin and import requirements. Request a quote from TiO2 Malaysia.
H1: Malaysia-Origin Titanium Dioxide for European Union Buyers

Keyword boundary: Country page owns explicit country modifier; EU page owns Europe/EU modifier; Resources owns detailed trade-policy queries.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/markets/04_planning/visual-designs/MARKET-EU-001_GATE5_FULL_VISUAL_SPEC_V0.1.md>)
- [MARKET-EU-001_GATE5_FULL_DESKTOP_1440_V0.1.png](<../inputs/pages/markets/04_planning/visual-designs/market-eu-001/v0.1/MARKET-EU-001_GATE5_FULL_DESKTOP_1440_V0.1.png>)
- [MARKET-EU-001_GATE5_FULL_MOBILE_390_V0.1.png](<../inputs/pages/markets/04_planning/visual-designs/market-eu-001/v0.1/MARKET-EU-001_GATE5_FULL_MOBILE_390_V0.1.png>)
- [MARKET-EU-001_GATE5_MOBILE_MENU_OPEN_FOCUS_390_V0.1.png](<../inputs/pages/markets/04_planning/visual-designs/market-eu-001/v0.1/MARKET-EU-001_GATE5_MOBILE_MENU_OPEN_FOCUS_390_V0.1.png>)
- [MARKET-EU-001_GATE5_FAQ_ALL_EXPANDED_DESKTOP_1440_V0.1.png](<../inputs/pages/markets/04_planning/visual-designs/market-eu-001/v0.1/MARKET-EU-001_GATE5_FAQ_ALL_EXPANDED_DESKTOP_1440_V0.1.png>)
- [MARKET-EU-001_GATE5_FAQ_ALL_COLLAPSED_MOBILE_390_V0.1.png](<../inputs/pages/markets/04_planning/visual-designs/market-eu-001/v0.1/MARKET-EU-001_GATE5_FAQ_ALL_COLLAPSED_MOBILE_390_V0.1.png>)
- [MARKET-EU-001_GATE5_DOCUMENTS_FOCUS_DESKTOP_1440_V0.1.png](<../inputs/pages/markets/04_planning/visual-designs/market-eu-001/v0.1/MARKET-EU-001_GATE5_DOCUMENTS_FOCUS_DESKTOP_1440_V0.1.png>)

## 实现与验收

本页已完成原生 WordPress Page、本地导入与运行态验收。ACCEPTED 仅指本地页面验收；生产发布、时效性官方来源复核及尚未上线的表单接收流程仍需另行完成。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

### 本地验收（2026-09-23）

- 页面：`http://localhost:8080/markets/european-union/`；本地站点 `home/siteurl` 均为 `http://localhost:8080`，数据库写入前保存了忽略目录备份。显式归属迁移确认 0 页需改动，再导入 11 个稳定身份 Page。
- 当前 Page Spec 的 H1、SEO、Buyer Clean 正文和独有国家内容通过 `python tests/market-content.py`；`python tests/market-http.py` 对 11 页 × 1440/768/390 检查 200、title/meta/canonical、noindex、真实站内链接、Markets Hub 入口、移动菜单、FAQ 键盘焦点和横向溢出。真实截图位于忽略目录 `.local/market-http/MARKET-EU-001-{1440,768,390}.png`。
- `tests/market-import.php` 在本地站点验证重复导入保留后台正文与 SEO、拒绝无归属页面，并恢复内容、SEO、归属和新增测试修订。Products、Applications、Documents、Resources、七个根页面的相关 HTTP 回归均通过。
- 尚未配置 Request a Quote、Request Documents、Request Sample 接收页；市场正文中的不可用独立动作已隐藏，站点共用 Header/Footer 的 RFQ 仍由后续全站转化任务处理。未执行邮件或生产发布；时效性贸易信息需在 RELEASE 前核对官方来源。
