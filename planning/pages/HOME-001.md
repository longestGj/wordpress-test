# HOME-001 — Home

Status: BUILDING

URL: `/`
Family: Homepage · EN

## 页面职责

Own the broad Malaysia TiO2 and Malaysia supplier proposition; route buyers to markets, products, documents, and RFQ.

## 内容与事实

- [Current content input](<../inputs/pages/home/04_planning/visual-designs/home-root-page-hero-v1.4/homepage-root-page-hero-preview-v1.4.html>)
- [WordPress initialization seed](../../data/pages/home.json) — initialization only; preserve live editor changes.
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: malaysia titanium dioxide
Title: Malaysia Titanium Dioxide Supplier | TiO₂ Malaysia
Meta: Explore titanium dioxide grades, applications, destination markets and document request paths through TiO₂ Malaysia for international industrial buyers.
H1: Malaysia Titanium Dioxide for Industrial Buyers

Keyword boundary: Home owns broad commercial Malaysia supply intent; About owns origin/manufacturing proof; Market pages own destination-country intent.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/home/04_planning/visual-designs/home-root-page-hero-v1.4/homepage-root-page-hero-preview-v1.4.html>)

## 实现与验收

当前本地实现已完成并在对应批次验收；ACCEPTED 不代表正式上线或所有外部目标已连接。
Native WordPress Page; Products Hub renders through the Product archive.

2026-09-23 Home product cards: desktop headings keep all four cards expanded; at widths up to 560px the cards remain keyboard-operable accordions with matching expand/collapse symbols. All 14 grade labels link to their corresponding published product detail routes. Verified on an isolated local WordPress preview and the private server stage at `http://localhost:18080/` with desktop/mobile browser checks, all 14 destination pages, and visual screenshots. The server stage remained `noindex, nofollow`.

Existing installations preserve editor content on repeat import. After a database backup, run `wp eval-file /workspace/scripts/migrate-home-product-cards.php` once in the loopback preview container to link only the 14 approved grade labels on the owned front page; the migration validates product destinations and is idempotent. The public site requires its own release and approval.

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.

## TiO2 Atlas 首页样板（2026-09-23）

已批准的设计：[TiO2 Atlas 首页样板设计](../../docs/superpowers/specs/2026-09-23-tio2-atlas-home-design.md)。域名保持 tio2products.com，前台品牌采用 TiO2 Atlas，运营主体 IKHLAS TITANIUM (MALAYSIA) SDN. BHD. 不变。本轮仅重做首页样板；现有 source 字段保留历史输入路径作为审计线索，新 HTML 初始化内容来自 data/pages/home-atlas.html。

验收前检查新 logo/favicon、页面结构与文案相对 tio2malaysia.com 的差异，14 个产品路由、1440/768/390 屏宽、键盘焦点、SEO 与本地 noindex。现有页面内容通过受保护的一次性迁移更新；重复导入仍保留后台编辑。