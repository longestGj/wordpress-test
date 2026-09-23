# HOME-001 — Home

Status: REVIEW

URL: `/`
Family: Homepage · EN

## 页面职责

Introduce industrial titanium dioxide products, the operating company, application and technical evaluation paths, documents and sourcing support. Route buyers to product and specialist pages before destination markets.

## 内容与事实

- [Earlier visual and content reference](<../inputs/pages/home/04_planning/visual-designs/home-root-page-hero-v1.4/homepage-root-page-hero-preview-v1.4.html>)
- [WordPress initialization seed](../../data/pages/home.json) — initialization only; preserve live editor changes.
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide supplier
Title: Titanium Dioxide Supplier & Products | TiO2Products
Meta: Explore industrial titanium dioxide pigment grades for coatings, plastics, masterbatch and printing inks. Review technical information and request sourcing support.
H1: Titanium Dioxide Products for Industrial Applications

Keyword boundary: Home owns broad industrial TiO₂ sourcing intent; Products owns grade and pigment selection; Application and Process pages own their specialist queries; About owns company and origin proof; Market pages own destination-country intent.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/home/04_planning/visual-designs/home-root-page-hero-v1.4/homepage-root-page-hero-preview-v1.4.html>)

## 实现与验收

2026-09-23 homepage positioning update for review: the new source order is Hero → Company → Products → Applications → Technical Evaluation → Process → Resources → Documents → Markets → RFQ. The existing four groups and fourteen grade destinations remain. Application copy lists evaluation factors, not grade performance promises. Malaysia remains in the verified operating-company context. The Home-only schema graph relates WebPage, WebSite and Organization. A guarded one-time migration updates only an unchanged owned Home baseline; it refuses other editor edits and requires separate release authorization for production. No production update is part of this review.

Earlier Home implementation was accepted and deployed; this positioning update remains in REVIEW until the new content and schema are approved.
Native WordPress Page; Products Hub renders through the Product archive.

2026-09-23 Home product cards: desktop headings keep all four cards expanded; at widths up to 560px the cards remain keyboard-operable accordions with matching expand/collapse symbols. All 14 grade labels link to their corresponding published product detail routes. Verified on an isolated local WordPress preview and the private server stage at `http://localhost:18080/` with desktop/mobile browser checks, all 14 destination pages, and visual screenshots. The server stage remained `noindex, nofollow`.

Existing installations preserve editor content on repeat import. After a database backup, run `wp eval-file /workspace/scripts/migrate-home-product-cards.php` once in the loopback preview container to link only the 14 approved grade labels on the owned front page; the migration validates product destinations and is idempotent. A production run requires separate authorization, a backup, and the script's explicit production switch.

Production acceptance deployment on 2026-09-23: after an isolated database backup and an explicit dry run, the approved Home card migration linked all 14 grade labels on the owned front page. Public desktop/mobile browser checks confirmed the cards' expansion behavior, all grade links and destinations, and no horizontal overflow. The [release and rollback evidence](../../docs/audits/production-release-2026-09-23.md) is recorded; final user acceptance is pending.

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.
