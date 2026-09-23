# TiO2 Atlas Homepage Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (- [ ]) syntax for tracking.

**Goal:** Build a reviewable TiO2 Atlas homepage sample with original identity, layout, copy, and safe WordPress migration.

**Architecture:** Home remains an owned editable WordPress Page. A source HTML file generates its seed; the theme supplies Home-only brand assets and CSS. An exact-match, local-only migration updates an existing Home Page; an isolated Compose project supplies a separate preview database.

**Tech Stack:** WordPress 7.1.1, PHP 8.3, WP-CLI, SVG, CSS, Python 3, Playwright, Docker Compose, PowerShell.

**Spec:** docs/superpowers/specs/2026-09-23-tio2-atlas-home-design.md

## Global Constraints

- Domain: tio2products.com; brand: TiO2 Atlas; operator: IKHLAS TITANIUM (MALAYSIA) SDN. BHD.
- HOME-001 only; preserve all 14 approved grades, four groups, verified company/origin facts, and current route destinations.
- Theme owns presentation; tio2-products owns domain data, editor behavior, and validation.
- Repeat import preserves editor changes; migration requires _tio2_owner, home identity, local host, known old content and SEO, and idempotence.
- No production publication, indexing, or outbound email.
- Verify 1440, 768, 390 widths; links, SEO, keyboard, focus, contrast.

## Review Focus

1. Editor-modified Home body or SEO: migration refuses without any write (Task 3 fixture).
2. Non-local site URL: migration refuses before reading/writing page state (Task 3 fixture).
3. Other pages: old logo and footer remain while the sample is pending (Task 2 and 4 HTTP tests).
4. Missing or wrong grade route: exactly 14 matching links are required (Task 1 and 4).
5. Small screens and keyboard: no overflow, menu works, focus remains visible (Task 2 and 4).

---

### Task 1: Home markup and seed

**Files:**
- Create: data/pages/home-atlas.html — editable markup source.
- Create: scripts/generate-atlas-home.py — writes only Home seed fields.
- Modify: data/pages/home.json — generated initialization seed.
- Modify: planning/pages/HOME-001.md — new visual reference, BUILDING status.
- Modify: tests/home-product-cards.py — replace obsolete card assertions with Atlas directory assertions.

**Interfaces:** Reads data/product-discovery.json; produces seed content, main_class “hub hub-home atlas-home”, H1 and SEO used by Tasks 2–4.

- [ ] **Step 1: Write failing content tests.** Parse the seed HTML and assert one exact H1, sections atlas-hero, atlas-paths, atlas-grade-index, atlas-procurement, atlas-company, atlas-rfq; no “TiO2 Malaysia” or old hero image; and exactly one anchor per grade with matching route.

~~~python
rows = json.loads((ROOT / 'data/product-discovery.json').read_text(encoding='utf-8'))['rows']
soup = BeautifulSoup(HOME['content'], 'html.parser')
assert [h.get_text(' ', strip=True) for h in soup.select('h1')] == [
    'Titanium dioxide products from Malaysia, mapped to your next decision.'
]
links = soup.select('#atlas-grade-index a[data-grade]')
assert len(links) == 14
assert {a.get_text(strip=True): a['href'] for a in links} == {
    row['grade']: row['url'] for row in rows
}
~~~

- [ ] **Step 2: Verify red.** Run python tests/home-product-cards.py; expect failure on the old H1/old cards.
- [ ] **Step 3: Build all six sections in home-atlas.html.** Hero links to /products/ and /request-a-quote/ with an aria-hidden route diagram and no numeric axis. Paths link to /applications/, /products/, /markets/. Grade index contains the exact 14 grade-to-route links from product-discovery.json in the existing four groups. Procurement links to /documents/ and /request-a-quote/. Company names the exact operator and links to /about/. Final CTA asks for destination, application, grade if known, quantity, and document needs. Use new sentences grounded in HOME-001 and ABOUT-001; do not copy old Home prose.
- [ ] **Step 4: Generate JSON with this exact field contract.** The generator wraps HTML in a WordPress HTML block, sets main_class above, SEO title to “Titanium Dioxide Products from Malaysia | TiO2 Atlas”, description to “Explore titanium dioxide grades from Malaysia by application, product group and destination market with TiO2 Atlas.”, H1 to the test string, source to the design spec path, and previous_content_hash to SHA-256 of the prior seed content. Use ensure_ascii=False and indent=2.

~~~python
seed = json.loads(seed_path.read_text(encoding='utf-8'))
old_content = seed['content']
was_atlas = seed.get('main_class') == 'hub hub-home atlas-home'
seed['content'] = '<!-- wp:html -->\n' + html_path.read_text(encoding='utf-8').strip() + '\n<!-- /wp:html -->'
seed['main_class'] = 'hub hub-home atlas-home'
seed['seo_title'] = 'Titanium Dioxide Products from Malaysia | TiO2 Atlas'
seed['seo_description'] = 'Explore titanium dioxide grades from Malaysia by application, product group and destination market with TiO2 Atlas.'
seed['h1'] = 'Titanium dioxide products from Malaysia, mapped to your next decision.'
seed['source'] = 'docs/superpowers/specs/2026-09-23-tio2-atlas-home-design.md'
if not was_atlas:
    seed['previous_content_hash'] = hashlib.sha256(old_content.encode('utf-8')).hexdigest()
~~~

- [ ] **Step 5: Verify green and commit.** Run generator, python tests/home-product-cards.py, python tests/planning-integrity.py. Commit the five Task 1 files.

### Task 2: Home-only logo, icon, and CSS

**Files:**
- Create: wp-content/themes/tio2/assets/atlas-logo.svg, atlas-logo-reverse.svg, atlas-mark.svg.
- Modify: wp-content/themes/tio2/header.php, footer.php, functions.php.
- Replace: wp-content/themes/tio2/assets/hub-home.css.
- Create: tests/atlas-home-browser.py.

**Interfaces:** Consumes atlas-home class and six section IDs; outputs Home-only identity while all other pages retain current chrome.

- [ ] **Step 1: Write failing browser tests.** Render seed HTML plus site.css and hub-home.css in Playwright at 1440/768/390. Assert six sections in order, 14 links, no overflow and first CTA focus outline. Add assertions that PHP selects the new logo only with is_front_page().

~~~python
for width in (1440, 768, 390):
    page.set_viewport_size({'width': width, 'height': 900})
    assert page.evaluate('document.documentElement.scrollWidth <= innerWidth')
    assert page.locator('#atlas-grade-index a[data-grade]').count() == 14
    link = page.locator('#atlas-hero a').first
    link.focus()
    assert link.evaluate("(el) => getComputedStyle(el).outlineStyle !== 'none'")
~~~

- [ ] **Step 2: Verify red.** Run python tests/atlas-home-browser.py; expect missing Atlas section/style failure.
- [ ] **Step 3: Create SVG identity.** Use a 48x48 simplified A mark with this geometry; compose 240x56 logo variants with TiO2 Atlas wordmark and dark/light colors. Do not reuse old orbit, sphere, Malaysia silhouette or gradient paths.

~~~xml
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" role="img" aria-label="TiO2 Atlas mark">
  <path d="M7 40 22 8h4l15 32M14 29h20" fill="none" stroke="#202B2B" stroke-width="4" stroke-linecap="square"/>
  <path d="M5 29h5M38 29h5" fill="none" stroke="#9C3D22" stroke-width="2"/>
</svg>
~~~

- [ ] **Step 4: Guard shared chrome.** Header/footer choose atlas-logo SVG, matching alt and new footer copy only when is_front_page(); otherwise retain current assets/text. functions.php emits a rel=icon link to atlas-mark.svg only on Home. Do not alter navigation records.

~~~php
$atlas = is_front_page();
$logo = $atlas ? 'atlas-logo.svg' : 'logo.svg';
$alt = $atlas ? 'TiO2 Atlas' : 'TiO2 Malaysia';
echo '<img class="logo" src="'.esc_url(get_template_directory_uri().'/assets/'.$logo).'" alt="'.esc_attr($alt).'" width="180" height="60">';
~~~

- [ ] **Step 5: Style Home only.** Use #F7F4EC paper, #202B2B ink, #9C3D22 action. Make offset hero, route choices, grade directory, paired procurement panel, company band and CTA. Use system sans body, Georgia editorial titles, ui-monospace grade labels. Controls at least 44px high; 3px focus outline; 900px/600px breakpoints; reduced-motion rule.

~~~css
.atlas-home{--atlas-paper:#f7f4ec;--atlas-ink:#202b2b;--atlas-action:#9c3d22;color:var(--atlas-ink);background:var(--atlas-paper)}
.atlas-home :is(a,button,summary):focus-visible{outline:3px solid var(--atlas-action);outline-offset:3px}
.atlas-home .atlas-hero-grid{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr);gap:clamp(2rem,5vw,6rem)}
@media(max-width:900px){.atlas-home .atlas-hero-grid{grid-template-columns:1fr}}
@media(prefers-reduced-motion:reduce){.atlas-home *{scroll-behavior:auto!important}}
~~~

- [ ] **Step 6: Verify and commit.** Run browser test, PHP lint, contrast check; inspect SVG at 16/32/180px, and screenshots at all three widths. Commit Task 2 files.

### Task 3: Guarded local migration

**Files:**
- Modify: compose.yaml — make HTTP port configurable, default 8080.
- Create: scripts/migrate-atlas-home.php.
- Create: tests/atlas-home-import.php.
- Create: tests/fixtures/home-pre-atlas.json — old seed copied from commit 362b578 for exact migration fixtures.
- Modify: planning/pages/HOME-001.md.

**Interfaces:** Reads generated home.json; updates only an owned local Home once; returns zero changes on repeat.

- [ ] **Step 1: Start independent preview for fixtures.** In compose.yaml, set the WordPress port as below. From this worktree, launch project tio2-atlas-home with the existing ignored D:\33wordpress\.env. Keep all mail variables empty. Install WordPress from the CLI container, activate plugin then theme, import the site data, and assert home/siteurl localhost:18081 and blog_public=0. These volumes are separate from the main project.

~~~yaml
ports:
  - "127.0.0.1:${TIO2_HTTP_PORT:-8080}:80"
~~~

~~~powershell
$env:TIO2_HTTP_PORT = '18081'
$env:TIO2_MAIL_USER = ''
$env:TIO2_MAIL_TO = ''
$env:TIO2_MAIL_APP_PASSWORD = ''
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home up -d
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli sh -lc 'wp core install --url=http://localhost:18081 --title="TiO2 Atlas Preview" --admin_user="$WP_ADMIN_USER" --admin_password="$WP_ADMIN_PASSWORD" --admin_email="$WP_ADMIN_EMAIL" --skip-email'
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli plugin activate tio2-products
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli theme activate tio2
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli option update permalink_structure '/%postname%/'
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli option update blog_public 0
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/import-batch.php
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/import-pages.php
~~~

Import the remaining route families serially; relocate only an untouched Core privacy draft before the utility import. Confirm the two URL options with WP-CLI before any migration test.

~~~powershell
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/import-process-applications.php
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/import-markets.php
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/import-documents.php
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/import-resources.php
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/import-requests.php
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/relocate-core-privacy-draft.php
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli eval-file /workspace/scripts/import-utility-pages.php
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli option get home
docker compose --env-file D:\33wordpress\.env -p tio2-atlas-home run --rm cli option get siteurl
~~~
- [ ] **Step 2: Write failing DB fixture.** Follow tests/market-import.php and load tests/fixtures/home-pre-atlas.json from commit 362b578. Save body, modified timestamps, main class, SEO title/description, revision IDs. Exercise accepted old body SHA-256 values 445d46c812e2670b7065ec71573f66b67497440c6724a7ea79edb443919e164e and e9c160c32e846e2196af266fd13129ad9d757046a0f7bfe245950ee38f5b7dd5; repeat no-op; edited body/SEO refusal; ownerless collision; non-local URL refusal. Restore all saved state and remove test-created revisions.
- [ ] **Step 3: Verify red.** Run WP-CLI eval-file tests/atlas-home-import.php against the isolated preview; missing script must fail.
- [ ] **Step 4: Implement guard before write.** Require localhost or 127.0.0.1, published page_on_front, tio2_owns_page with _tio2_hub_key home. Compare current body hash to the two known hashes and old SEO meta; if body and metadata already match new seed, return success without writes. Reject all unknown edits. Update post_content with wp_slash and the three metadata values, then verify read-back.

~~~php
$host = wp_parse_url(home_url('/'), PHP_URL_HOST);
if (!in_array($host, ['localhost', '127.0.0.1'], true)) WP_CLI::error('Atlas migration is local-only.');
$id = (int) get_option('page_on_front');
if (!$id || !tio2_owns_page($id, '_tio2_hub_key', 'home')) WP_CLI::error('Owned Home page required.');
$seed = json_decode(file_get_contents('/workspace/data/pages/home.json'), true, 512, JSON_THROW_ON_ERROR);
$current = get_post_field('post_content', $id, 'raw');
$currentTitle = get_post_meta($id, '_tio2_seo_title', true);
$currentDescription = get_post_meta($id, '_tio2_seo_description', true);
$currentClass = get_post_meta($id, '_tio2_main_class', true);
$oldHash = hash('sha256', $current);
$allowed = ['445d46c812e2670b7065ec71573f66b67497440c6724a7ea79edb443919e164e', 'e9c160c32e846e2196af266fd13129ad9d757046a0f7bfe245950ee38f5b7dd5'];
if ($current === $seed['content'] && $currentTitle === $seed['seo_title'] && $currentDescription === $seed['seo_description'] && $currentClass === $seed['main_class']) { WP_CLI::success('Atlas Home already current.'); return; }
if (!in_array($oldHash, $allowed, true)) WP_CLI::error('Home content changed; no update made.');
~~~

- [ ] **Step 5: Verify and commit.** Run fixture twice, assert no second modification or timestamp change; PHP lint; commit Task 3 files.

### Task 4: Separate preview and acceptance

**Files:**
- Create: tests/atlas-home-http.py.
- Modify: planning/pages/HOME-001.md — REVIEW then ACCEPTED only after passing.
- Create: docs/audits/atlas-home-preview-2026-09-23.md.

**Interfaces:** Produces a localhost:18081 preview using a separate Compose project and volumes, screenshots, and an acceptance record.

- [ ] **Step 1: Write failing HTTP test.** Home: 200, Atlas title/description/H1, local canonical/noindex, Atlas logo/favicon, no old hero, 14 matching grade routes and all Home links 200. About: old logo retained. Browser: menu keyboard action, focus visible, 1440/768/390 no overflow.

~~~python
assert page.locator('h1').inner_text() == 'Titanium dioxide products from Malaysia, mapped to your next decision.'
assert page.locator('header img.logo').get_attribute('alt') == 'TiO2 Atlas'
assert page.locator('#atlas-grade-index a[data-grade]').count() == 14
assert page.evaluate('document.documentElement.scrollWidth <= innerWidth')
~~~

- [ ] **Step 2: Run HTTP acceptance test.** Run against the separate localhost:18081 stack; inspect any failure before changing implementation.
- [ ] **Step 3: Prepare real Home response.** In the independent localhost:18081 stack from Task 3, run scripts/migrate-atlas-home.php. Confirm noindex and that no mail variables are configured.
- [ ] **Step 4: Verify and record.** Run HTTP, content, planning and relevant PHP tests. Capture 1440/768/390 screenshots. Compare with https://tio2malaysia.com/ on logo, hero art, section order, H1 and prose. Record evidence and rollout dependencies in docs/audits/atlas-home-preview-2026-09-23.md. Keep preview noindex, with no outbound mail.
- [ ] **Step 5: Commit acceptance.** Mark HOME-001 ACCEPTED only after passes; commit Task 4 files. Report preview URL and screenshots for user review before the separate full-site rollout.
