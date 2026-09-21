# Remaining Products and Root Pages — Implementation Plan

Goal: test the simplified READY → BUILD → REVIEW workflow on the remaining 13 grades and seven root pages in this new local WordPress.

Implementation: use executing-plans inline; one independent review at the end. User has requested plan followed by execution; no second planning approval is required. Source approval is accepted, not re-audited.

## Scope and source

Planning repository snapshot: `longestGj/tio2mydesign@765c66ed2b2d9d42cacab9009c7b17830cfdedf5`. This is a content and design input, not an inherited runtime or gate system.

- Products: M-510, M-896, M-996, M-2196, M-895, M-200, M-108, M-210, M-340, M-886, M-52, M-2377, CR901. Keep existing M-350.
- Root pages: Home `/`, Markets `/markets/`, Products `/products/`, Applications `/applications/`, Documents `/documents/`, Resources `/resources/`, About `/about/` (use source canonical path if different).
- Shared primary navigation and page SEO, responsive layouts, meaningful interactions.
- Child application/market/process/resource pages and three request receivers are a later batch. Preserve approved fixed RFQ navigation, mark these dependencies in review; never claim submissions work.
- Keep local noindex/nofollow. No production changes, deployment, tracking, or mail sending.

## Architecture

Product facts stay in WordPress Product records and private application/process taxonomies. Extend the shared template only for real source differences such as two-column technical tables. Root content uses editable native WordPress Page content and metadata; approved visual HTML is adapted into server-rendered content, with scoped theme styles and freshly implemented interactions. No prototype scripts, historical IDs or readiness simulators enter runtime. Products remains the unique CPT archive route, consuming an editable hub content Page without creating a competing `/products/` Page route.

## Tasks

- [x] 1. Back up database and custom source. Add source adapters in `scripts/prepare-batch.py` and generated seeds in `data/`. Record source paths in seed metadata. Read exact contracts; do not infer product claims.
- [x] 2. Add meaningful tests in `tests/batch-products.php`: two-column versus three-column data, all 14 identities, application assignments, repeat import preserving editor changes. Run before implementation. Extend `tio2-products.php`, `blank.json`, `single-product.php` and importer; import the 13 approved grades. Run old product-model tests and new tests.
- [x] 3. Adapt the seven approved root designs into native Page content; keep module order and copy. Implement shared page renderer and SEO metadata fields, scoped theme CSS, application selection and native FAQ interactions. Render interactive results in initial HTML. Import idempotently and set Home and navigation.
- [x] 4. Check actual HTTP for 21 pages (14 products + seven roots): 200, one H1, expected content, table data, title/meta/canonical, noindex, no leaked internal IDs/prototype controls. Verify root interactions and unbuilt-link behavior, backend edit/restore and repeat imports.
- [x] 5. Browser review at desktop 1440, tablet 768 and mobile 390; include 320 boundary on root layouts. Check representative product variations, all seven root layouts, selector/FAQ/menu keyboard behavior and no horizontal overflow. Independent code review, fix material findings, update this file with real results and remaining dependencies.

## Review focus

1. M-350 must remain byte-for-byte unchanged in stored product content during bulk imports.
2. A missing Standard column is not an invented specification or a page full of fabricated values.
3. Product application facts must not be replaced by the hub's separate discovery relationship matrix.
4. Admin edits must survive repeat imports; every page's SEO/content must remain editable.
5. Unbuilt targets and receivers must not masquerade as working pages/forms or inflate structured data.

## Execution notes

- Workspace is not a Git repository. Keep source/database backup and this single review record; do not fabricate commits or create a workflow package.
- Local URL remains the local canonical environment. Production canonical/indexing is a release configuration step, not a claim of live deployment.

## Review result — 2026-09-21

Local batch implementation and the checks below completed. This is not production release acceptance.

- 14 Product records (existing M-350 plus 13 new) and seven root routes return real WordPress pages.
- Product table variants preserve Standard, Typical value and Test method only where supplied; CR-901 retains its separate specialty content and no invented process taxonomy.
- Exact technical cell values, approved page headings/paragraphs, product application copy, initial title/meta, single H1, local canonical and noindex passed actual HTTP comparison.
- Product discovery uses the approved ordered six groups: 8/8/7/4/2/1. The editable directory, selector relationships and ItemList share page metadata; these are separate from technical product application facts.
- `product-model.php`, `batch-products.php`, `discovery-editor.php`, `cms-import.php`, `http-review.py`, `batch-http.py` and `review-fixes.py` passed. PHP syntax checks passed across the custom theme/plugin.
- CMS test saved native Home content and SEO, verified real HTTP, reran both imports and proved edits survived; Product M-510 test edits survived too. M-350 data was compared before/after those imports. All temporary edits were restored. M-350's actual authenticated admin form save → front-end/Schema → restore also passed.
- Browser geometry: all seven roots at 1440/768/390/320. Fixed Products' 320px step-card overflow and rechecked it. Product variations M-350/M-510/M-108/M-340/CR-901 tested at 1440/768/390 with correct columns and no horizontal overflow.
- Visually inspected all seven mobile roots, desktop Home/Products/Documents/About, and tablet Documents. Verified Paper selector returns M-350/M-2377, native FAQ Enter interaction, menu Shift+Tab focus wrap and Escape focus return, Documents empty selection validation and explicit disconnected-receiver feedback. No console errors observed in the final inspected session.
- Independent reviewer found two material issues: unresolved nested About raster references and missing discovery editing controls. Both fixed and covered by regression tests. About map/flags were then verified visually and loaded image dimensions confirmed. No deferred reviewer minors.

### Decisions and limits

- Ruling: use native HTML blocks for the seven fixed root layouts, plus ordinary fields for SEO and discovery. This preserves the approved designs without building a page-builder framework; the tradeoff is that root prose editing currently requires editing text within HTML, rather than a fully visual field interface.
- Ruling: retain approved fixed RFQ links as visible dependencies, while unavailable child actions are plain labels. RFQ currently leads to an unbuilt route; form submission, actual receipt, spam handling and product-to-receiver integration are **not tested / not ready**.
- Ruling: local canonical and minimal page/product structured data describe this preview. Full production entity/schema parity, final domain, sitemap and indexing remain release work; do not interpret basic SEO checks as all production SEO requirements passing.
- Images retain approved source rendering. Home's PNG is about 1.9 MB and About's shared image sheet is about 5.5 MB. No production-network performance or Core Web Vitals claim is made; optimize asset delivery before release.
- Native devices, screen readers, comprehensive accessibility audit and production/network performance were not tested. This batch tests pages and the simplified development path; there is no measured old-workflow time baseline, so no numerical efficiency saving is claimed.
- Backups: `backups/before-remaining-pages.sql` and `backups/before-remaining-pages-source.zip`. No remote repository, pre-existing WordPress, or production deployment was changed.
