# Resource pages — local WordPress implementation

Scope: eight approved resource pages (RES-ORIGIN, RES-PROC, RES-CHEMOURS,
RES-R706, RES-TRADE-EU/UK/IN/BR) and automatic Resources Hub link readiness.
Base: c55c4d9b3f04e03042983a292b9841cd4748cfea. Source snapshot:
longestGj/tio2mydesign@765c66ed2b2d9d42cacab9009c7b17830cfdedf5.

## Brief and implementation plan

- [x] Resolve each current handoff to approved copy, visual and metadata.
- [x] Preserve the approved layouts with scoped resource CSS; ordinary body
  content uses native editable WordPress blocks and semantic tables/disclosures.
- [x] Add resource-only rendering/SEO and a preflighted, repeatable importer.
  Existing pages require matching source identity; administrator edits survive.
- [x] Import within the resource-only database window, after backup; compare
  the existing products, root pages, Discovery and configuration before/after.
- [x] Verify exact content, metadata, local canonical/noindex, links, keyboard
  interactions and 1440/768/390 layouts; independently review and commit only
  resource-owned files locally.

Shared interfaces: main task owns functions.php/page.php integration;
resources.php provides tio2_is_resource_page($id) and
tio2_render_resource_page($id), emitting one main without header/footer.
Do not use _tio2_hub_key for resource content. Existing title metadata remains
_tio2_seo_title; the plugin owns its existing editor.

Ruling: implement in the explicitly requested shared checkout/feature branch,
not a second worktree or database. Main task and resource task coordinate file
ownership and a single database writer. No global settings or product mutations.

Ruling: source Gate documents supply approved content/design, not an inherited
runtime or renewed approval flow. No production domain, indexing, mail, analytics,
Next.js, generic schema CMS, or page registry is introduced. Preserve original
research dates; no importer/build timestamp becomes a research date.

Ruling: unavailable related destinations must not appear to work. RES-PROC's
two process actions form an atomic pair. No Article/TechArticle metadata is
invented; WebPage and matching visible BreadcrumbList are the local baseline.

## Progress / evidence

- Backed up database to ignored backups/before-res-proc.sql before modifications.
- RES-PROC authorities: Gate2 Content
  Architecture V0.3; Gate5 Full Visual V0.1; Gate7 Handoff V0.1, as reaffirmed by
  the current comparative review manifest V0.16.

## Delivered pages

All routes below are local to http://localhost:8080 and remain noindex/nofollow.

| Identity | Path |
|---|---|
| RES-ORIGIN | /resources/non-china-titanium-dioxide/ |
| RES-PROC | /resources/chloride-vs-sulfate-titanium-dioxide/ |
| RES-CHEMOURS | /resources/chemours-titanium-dioxide-alternatives/ |
| RES-R706 | /resources/ti-pure-r-706-alternative/ |
| RES-TRADE-EU | /resources/eu-titanium-dioxide-anti-dumping-duty/ |
| RES-TRADE-UK | /resources/uk-titanium-dioxide-anti-dumping-investigation/ |
| RES-TRADE-IN | /resources/india-titanium-dioxide-anti-dumping-duty/ |
| RES-TRADE-BR | /resources/brazil-titanium-dioxide-anti-dumping-duty/ |

Resources Hub gains article navigation inside its three existing research-path
cards (1 sourcing, 3 technical, 4 trade), with no change to stored root body or
seed. Links use owned, published, unprotected Resource Pages and approved source
order. Shared navigation marks Resources as the ancestor section. Ordinary
copy is editable in native paragraph, heading, list, group and details blocks;
technical tables retain controlled semantic HTML and mobile labels.

## Source and review evidence

- `data/resources/source/source-map.json` records seven exact copy/visual inputs;
  RES-PROC's fixed copy/visual inputs are in `scripts/prepare-resources.py`.
  Seeds record the pinned commit and provenance. No source checkout is a runtime
  dependency. The adapter requires the ignored pinned source cache, Python
  beautifulsoup4/tinycss2 and, for PROC only, build-time Tailwind 3.4.17.
- RES-ORIGIN has no approved HTML in the pinned tree. Its build-only
  `data/resources/source/origin.html` is reconstructed from Content Architecture
  V0.2 and Full Visual Specification V0.1, with the approved desktop PNG reviewed.
  This file is an initial-content adapter input, not a second live editable body.
- Independent source audit verified all 12 trade input blobs and all 12
  research input blobs against pinned Git tree hashes. No replacement characters.
  Source audit found no substantive copy/visual mismatch in the six supplied
  trade/alternative visuals. Origin's 91 quoted strings include three governance
  tokens; all 88 buyer-facing strings are present. PROC's 128 quoted strings are
  present except the separately rendered breadcrumb, two prohibited schema names
  and the `/products/` URL (present as href rather than body text).
- Preserve the visible 5 September 2026 PROC source-review dates, 6 September
  Chemours/R706 review dates and 7 September trade research dates. No publication,
  author or research timestamp was inferred from import time. WebPage plus
  matching BreadcrumbList only; no fabricated Article/TechArticle.
- Independent read-only code/source review found one P2 issue: breadcrumb labels
  were extracted after their source nodes were removed. Fixed extraction order
  and explicit Origin/PROC labels; regenerated seeds and refreshed only owned
  resources after a full identity/source/title/content-hash preflight.
  No other material findings. Review independently confirmed Origin body parity.
- Hub integration was tested separately after discovering the old Hub had three
  research-path cards but no individual article links. Supplemental review found
  a card-order assumption; category matching now uses each approved label instead
  of position. Reordering cards cannot reclassify articles. A read-only test also
  proves repeated rendering does not duplicate navigation lists. A missing source
  ownership marker withdraws the affected link, verified by actual HTTP. The final
  focused re-review found no remaining material issue.
- Classic WordPress adds a legacy Group inner container that disrupts the approved
  grids. Resource rendering temporarily removes only that core rendering callback,
  restoring it in finally. Scope stays local to resources; no global filter change.
- Visual inspection removed an inherited product-Hero background from Chemours.
  PROC has one semantic evidence table instead of duplicated desktop/mobile prose;
  mobile tables keep labels, and EU's narrow rate table reflows into records.

## Validation performed

- `python tests/resource-http.py`: eight actual 200 routes; full seed paragraph,
  heading and cell content; Title/Meta/one H1/local canonical/noindex; approved
  breadcrumb labels and matching schema; usable internal links; no prototype
  scripts, disabled body actions or source-control labels. Hub links checked.
- `docker compose run --rm cli eval-file /workspace/tests/resource-import.php`:
  all eight existing records preserved; edited body and SEO survive another
  import; absent source/identity rejects adoption; finally restores original.
- `python tests/resource-browser.py --quick`: 24 page/width combinations at
  1440/768/390 with no horizontal overflow; native FAQ Enter interaction; no JS
  errors. WordPress's real Gutenberg parser validated 727 blocks with zero invalid
  blocks. It reads seeds in the admin JS context without saving them.
- Hub additionally checked at 1440/768/390, including long-title wrapping,
  keyboard link order and visible focus. `tests/resource-hub.php` verifies
  category/ordering stability and no duplicate lists after repeated rendering.
- Screenshots captured for all 24 combinations; visually inspected all eight
  mobile page openings, PROC desktop hero, and each table family's mobile rows.
  This does not claim full-page manual inspection of every pixel at all widths.
- `python tests/batch-http.py`: existing 13 batch products and seven root pages
  pass their HTTP/content/technical-data/SEO checks. M-350 and all existing page/
  product records are additionally covered by exact state snapshots.
- `tests/resource-snapshot.php`: before/after snapshots of all non-resource Page/
  Product body/title/status/slug/parent/meta/terms and Discovery/targets/front-page/
  noindex/permalink options match, including the concurrent task's seven new pages.
  Database mutation tests ran serially within explicit handoff windows.
- PHP syntax checks pass for resource renderer and importer.
- Backup before the final development refresh: ignored
  `backups/before-resource-refresh.sql`. Applied hash-guarded refresh retained in
  ignored `.local/resource-work/applied-resource-refresh.php`; normal importer
  remains preserve-only. No backup or temporary refresh is committed.

## Limits and remaining dependencies

- These are local implementations of approved dated research, not a fresh legal,
  regulatory or trade-fact review. External destinations were preserved; this task
  does not claim a live revalidation of every external official-source URL.
- Market, document-request, RFQ and other unavailable body destinations have no
  false working action. Their explanatory copy is preserved where appropriate.
  Brazil's Portuguese destination remains unavailable until its owner page exists.
  Global RFQ remains a shared, previously documented release dependency.
- Native-device/screen-reader testing, full accessibility audit, native browser
  200% zoom, non-Chromium coverage and production-network performance not claimed.
- No mail, analytics, DNS, production publication, indexing, push or merge.
