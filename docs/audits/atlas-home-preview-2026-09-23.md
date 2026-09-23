# TiO2 Atlas Home sample — local acceptance

Date: 2026-09-23  
Page: HOME-001 (`/`)  
Scope: homepage sample only  
Preview: <http://localhost:18081/> on this machine, Compose project `tio2-atlas-home`

## Isolation and release state

- Separate Compose project and database/WordPress volumes from the main `localhost:8080` preview. Only loopback port `18081` is exposed.
- `home` and `siteurl` are `http://localhost:18081`; `blog_public` is `0`, and the HTTP response contains `noindex`. `TIO2_MAIL_USER`, `TIO2_MAIL_TO`, and `TIO2_MAIL_APP_PASSWORD` are empty in the CLI container.
- No production import or deployment was made. The existing public domain remains `tio2products.com`; WordPress derives the preview canonical from its local `home` value.

## Design difference from the reference site

Compared with <https://tio2malaysia.com/> on 2026-09-23:

| Element | Reference homepage | Atlas sample |
| --- | --- | --- |
| Identity | TiO2 Malaysia logo/icon | Original geometric A logo, reverse logo and A favicon; homepage header and footer say TiO2 Atlas |
| Palette | Blue/teal and white | Warm paper `#F7F4EC`, graphite `#202B2B`, oxide red `#9C3D22` |
| Hero | “Malaysia Titanium Dioxide for Industrial Buyers” and powder image | “Titanium dioxide products from Malaysia, mapped to your next decision.” and CSS route map |
| Sequence | Starting cards, markets, grades, applications, company, documents, resources/FAQ, RFQ | Hero, decision paths, 14-grade index, procurement, company, RFQ |
| Prose | Reference introductory and section wording | New Atlas wording within the same approved product/company facts |

Shared grade names, official company identity and buyer routes are intentional facts and navigation. The other WordPress pages still use the current TiO2 Malaysia presentation while this homepage is reviewed.

## Verification

- `python tests/home-product-cards.py`: 3 passed; six sections, new brand/SEO seed, 14 correct grade URLs and buyer/company routes.
- `python tests/atlas-home-browser.py`: 2 passed; standalone visual and responsive checks.
- `python tests/planning-integrity.py`: passed; 59 unique specs and all references resolve.
- `python tests/atlas-home-http.py`: 4 passed against real WordPress HTTP; title, description, H1, canonical, logo, favicon, noindex, 14 grade destinations and every in-main link returned 200; About retains the prior logo; no horizontal overflow at 1440/768/390; visible keyboard focus on light hero and pale-orange focus on dark grade/footer links; mobile menu Enter/Escape behavior. Also checks the Home navigation indicator uses oxide red.
- `tests/atlas-home-import.php` through the local CLI, twice: passed; both known old bodies migrate, repeat is a no-op, edited body/SEO/ownerless/nonlocal cases refuse writes, and test fixtures are restored.
- PHP lint: `scripts/migrate-atlas-home.php`, `tests/atlas-home-import.php`, theme `functions.php`, `header.php`, `footer.php` all passed.
- Contrast ratios checked during visual implementation: white on oxide red 6.77:1; warm paper on graphite 13.25:1; pale orange on graphite 6.11:1.
- Screenshots captured and inspected: `.local/atlas-http/home-1440.png`, `home-768.png`, `home-390.png` (local, ignored from Git).

## Next stage

After the homepage sample is reviewed, plan the sitewide identity migration: shared header/footer/favicon first; then hub, market, application, product, document and resource pages; then request, legal, SEO and email wording. Preserve approved technical facts, stable URLs and editor changes. Check the chosen name for trademark/brand use before public release. Production publication requires a separate authorized release with its own backup, forms, indexing and canonical checks.
