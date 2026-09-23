# MARKET-BR-PT — Brazil Portuguese

Status: ACCEPTED

URL: `/pt-br/markets/brazil/`
Family: Localized market procurement landing page · PT-BR

## 页面职责

Own Portuguese-language Brazil supplier, price, coatings, and plastics intent.

## 内容与事实

- [Current content input](<../inputs/pages/markets/brazil/04_planning/MARKET-BR-PT_GATE2_FULL_BUYER_CLEAN_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: fornecedor de dióxido de titânio
Title: Fornecedor de dióxido de titânio para o Brasil | TiO2 Malaysia
Meta: Dióxido de titânio originário da Malásia para compradores no Brasil. Conheça grades para tintas, plásticos e masterbatch. Solicite documentos ou uma cotação.
H1: Dióxido de titânio originário da Malásia para compradores no Brasil

Keyword boundary: PT-BR page owns Portuguese queries; English Brazil page owns English queries; use hreflang after localization approval.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/markets/brazil/04_planning/gate4-pt-v1.1/MARKET-BR-PT_GATE4_COMPLETE_VISUAL_V1.1.html>)

## 实现与验收

本页已完成原生 WordPress Page、本地导入与运行态验收。ACCEPTED 仅指本地页面验收；生产发布、时效性官方来源复核及尚未上线的表单接收流程仍需另行完成。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

### 本地验收（2026-09-23）

- 页面：`http://localhost:8080/pt-br/markets/brazil/`；本地站点 `home/siteurl` 均为 `http://localhost:8080`，数据库写入前保存了忽略目录备份。显式归属迁移确认 0 页需改动，再导入 11 个稳定身份 Page。
- 当前 Page Spec 的 H1、SEO、Buyer Clean 正文和独有国家内容通过 `python tests/market-content.py`；`python tests/market-http.py` 对 11 页 × 1440/768/390 检查 200、title/meta/canonical、noindex、真实站内链接、Markets Hub 入口、移动菜单、FAQ 键盘焦点和横向溢出。真实截图位于忽略目录 `.local/market-http/MARKET-BR-PT-{1440,768,390}.png`。
- `tests/market-import.php` 在本地站点验证重复导入保留后台正文与 SEO、拒绝无归属页面，并恢复内容、SEO、归属和新增测试修订。Products、Applications、Documents、Resources、七个根页面的相关 HTTP 回归均通过。
- 尚未配置 Request a Quote、Request Documents、Request Sample 接收页；市场正文中的不可用独立动作已隐藏，站点共用 Header/Footer 的 RFQ 仍由后续全站转化任务处理。未执行邮件或生产发布；时效性贸易信息需在 RELEASE 前核对官方来源。
- 巴西英语与葡语页面均已完整发布到本地路径；双向 `en`/`pt-BR` hreflang、HTML 语言和可见语言切换已在真实页面验证。
