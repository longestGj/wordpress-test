# Process and Application pages

Scope approved 2026-09-21: Chloride, Sulfate, Coatings, Plastics, Masterbatch, Printing Inks, Paper. Build, test and independent code review. Other content is outside this batch; Resources belongs to task 01开发01.

Use approved Buyer Clean copy and frozen visual sources at planning commit 765c66ed2b2d9d42cacab9009c7b17830cfdedf5. No renewed research or approval workflow. Shared header/footer and native WordPress Pages. Preserve approved grade order and neutral navigation; do not convert discovery lists into new product technical claims.

Implementation: seven explicit page seeds and page-scoped styles; WordPress content holds editable native text blocks and controlled complex table/card HTML. Application Pages are children of Applications. Two process Pages use exact /products/ routes without changing the Product archive or private hub. No general route registry.

Validation: test missing routes first; compare complete approved visual-body text after allowed unavailable-link handling, headings, tables, grade order, SEO and canonical; check actual 1440/768/390 layouts, keyboard anchors, no overflow. Reimport preserves editor changes and rejects ownership collisions. Existing product/Hub content and Discovery unchanged. Form destinations remain unavailable until separately implemented/tested. Local noindex persists.

Database coordination: resource task currently has the write window. Prepare files and run read-only checks until it releases the window. No parallel database mutation tests.

## Implementation and verification

- Seven Pages imported: chloride 99, coatings 100, masterbatch 101, paper 102, plastics 103, printing-inks 104, sulfate 105. Product/archive URLs remain native; two exact process rewrites capture the complete Page slug for WordPress verbose matching.
- Seven content destinations connected. Existing Product and Hub links resolve automatically; RFQ/Sample/Documents receivers remain outside scope and page-owned unavailable actions are explicitly disabled.
- Paragraphs/headings/groups use native blocks where their structure permits. Lists/cards/tables with fixed semantics remain HTML blocks; this is not a redesign of all page editing.
- Source visible text, headings, table cells/labels and source URLs preserved. Public schema is WebPage/BreadcrumbList; process pages also expose their visible grade ItemList. Application pages do not introduce product suitability Schema.
- First route test failed with 404; all seven now pass real HTTP, exact body text, Title/Meta/canonical/noindex, anchors and actual internal links.
- Import tests passed: edits preserved on rerun, missing ownership rejected, existing hierarchical process Page rejected. Test content restored in finally.
- Browser: all seven at 1440/768/390 (21 combinations), one H1, no horizontal page overflow; desktop hero layouts inspected, mobile table labels inspected, native anchors focus the target. Not a physical-device or screen-reader certification.
- Existing PHP model/discovery/CMS/revision/relationship/activation tests and Python HTTP/review/ownership/dependency tests passed. Plugin safely returns 503 while disabled and recovers afterward. PHP syntax checks passed.
- Compared pre-batch backup with existing Page/Product title, excerpt, body, slug, status and project meta plus Discovery/front-page options: zero differences. Only seven intended content targets were added.
- Independent code review completed; hierarchical process path collision finding fixed and rechecked, no remaining actionable findings.

Rulings and fixes:
- Classic WordPress adds a legacy group inner container. Temporarily bypass that one callback only during topic block rendering and restore it in finally, preserving approved direct-child grids without changing other pages.
- Preserve mobile data-label attributes; convert source data-module styling selectors to scoped semantic classes instead of leaking governance attributes.
- Text-only containers use HTML blocks, avoiding raw inner text in native Group serialization.
- Masterbatch hero heading inherits its approved white foreground rather than the global navy heading rule.
- Native WordPress editor parser validation: 429 blocks across seven seeds, zero invalid blocks (including 65 editable blocks on Plastics). Article naming uses core Group's supported ariaLabel instead of an unsupported custom attribute, preserving its accessible name.
- Reactivation regression exposed missing process rewrites after the plugin activation flush. Register the two process routes inside the plugin's own content-registration function; dependency test now checks process HTTP recovery as well as Home and Products.
- Initial imported block repair uses explicit previous-content SHA-256 guards in refresh-process-applications.php; reruns cannot overwrite later editor changes.

Backups: backups/before-topics.sql (ignored). No email, analytics, deployment, source re-approval or product relationship changes.
