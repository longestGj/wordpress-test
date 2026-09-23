# Domain and site route audit — 2026-09-23

This is the pre-release snapshot. For the subsequently published new site, see [Production acceptance release](production-release-2026-09-23.md).

The confirmed public domain is `https://tio2products.com/`; the user confirmed that `info@tio2products.com` receives mail. The company brand name “TiO2 Malaysia” remains in titles and copy. Historical artifacts under `planning/inputs` are retained as source evidence rather than edited as live pages.

## Local WordPress review

- `http://localhost:8080/`: 58 real routes from `planning/SITE_MAP.csv` returned HTTP 200. The remaining `SYS-404` row is the non-URL marker `RUNTIME_FALLBACK`. Every 200 page had a canonical element; no rendered link pointed to the former domain, and no broken internal route was found. The four utility Pages still carried the former email in this concurrently used preview at audit time; its database was deliberately left untouched.
- `http://localhost:18080/`: the four owned utility Pages were updated with `scripts/migrate-public-contact-email.php`. A second run changed zero Pages. The resulting HTTP audit found no rendered old-domain or old-email references. Of the real routes, 55 returned 200 and three request-form routes returned 404 because this isolated preview predates their separate implementation.
- The main preview returns 404 for an unknown path. Its WordPress sitemap currently returns 404 because `blog_public=0` until release; preview pages are not for indexing.
- `planning/SEO_MAP.csv` now has 58 canonicals under `https://tio2products.com/`, one 404 marker without a canonical, and no former-domain canonical. The four utility Page seeds and their generator now use the confirmed mailbox. The explicit migration preserves other editor changes on existing owned Pages.

## Public domain and GSC

- The current public site responds at `https://tio2products.com/`. It still serves the existing production release; this audit does not claim that the new WordPress site has been published there.
- The supplied `googleaa2e91750b47f47a.html` was placed in the current production WordPress document root. A public GET to `https://tio2products.com/googleaa2e91750b47f47a.html` returned 200 with the exact supplied verification text; the server copy matched the source SHA-256. The file is also included in the new WordPress image and restored to the document root by its entrypoint, so future container recreation retains it.
- GSC ownership still needs the account-side **Verify** action. Sitemap submission must wait until the new production site is public and indexing is enabled.

## Open release checks

- The three request pages are being developed separately. Their content and receiver checks must be included in the final full-site acceptance.
- The English and Malay privacy wording and production hosting details still require review. GA4 had no Measurement ID at the time of this domain audit; the later `G-SY6PZPX0VR` opt-in implementation is recorded in `ga4-opt-in-2026-09-23.md`.
- Production publication of the new site remains a separate release step under `docs/WORKFLOW.md` and `docs/RELEASE.md`.
