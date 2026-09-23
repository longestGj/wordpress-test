# Production acceptance release — 2026-09-23

The new native WordPress site is serving `https://tio2products.com/` from the isolated `tio2products-next-prod` stack. The public Caddy proxy forwards to `next_wordpress:80`. The former WordPress and database containers remain running for rollback. The deployed application image is `tio2products-next:b4209ad8e28b`, corresponding to source commit `b4209ad8e28b13517f8478dd912d19a23deec7fb`. The accepted local WordPress database was migrated into the new isolated production database to preserve editor changes; it is not kept in Git.

The former site's verified database, uploads, Caddyfile, Compose file, environment, and release references are in `/opt/tio2products/backups/pre-new-site-20260923T064006Z/`. The new site's verified post-acceptance database backup is `/home/deploy/tio2products-next/backups/live-after-qa-20260923.sql`. Both backup directories are outside Git. The old stack and its volumes have not been deleted. To return public traffic to the former site, copy the backed-up Caddyfile over `/opt/tio2products/current/Caddyfile` **in place** and restart `tio2products-caddy-1`; Caddy has its admin API disabled, so `caddy reload` does not work on this installation.

## Public checks

- All 58 planned public routes return 200 with the expected `https://tio2products.com/` canonical and no former-domain or loopback link. An unknown route and the former administrator author archive return 404.
- HTTPS and `www` redirects work. The supplied Google verification file returns 200 with the exact verification text. `robots.txt` points to the live WordPress sitemap.
- The live sitemap contains exactly the 57 URLs marked `target_sitemap=YES` in `planning/SEO_MAP.csv`: no missing or extra URL. It includes the Products archive and excludes the two noindex Portuguese locale parents, the Thank You page, and the administrator user archive.
- The Home card browser check passes on desktop and mobile: desktop headings keep the cards expanded, mobile accordions toggle, the page has no horizontal overflow, and all 14 grade links resolve to their product details. A backed-up, explicit migration updated only the owned Home card block after the database clone.
- A fresh browser makes no Google statistics request before consent or after rejection. Consent loads `G-SY6PZPX0VR`; withdrawal removes GA cookies and stops requests on the next page. The actual GA4 Realtime report remains an account-side check.
- One clearly labeled synthetic Contact, quotation, document, and sample request each produced the expected browser receipt and private record. All four notification statuses were `accepted` by Gmail SMTP. The four synthetic records were then deleted by exact ID and marker; no private inquiry/request records remain. Inbox delivery remains an account-side check.

The three policy pages were approved by the user before release. The Page Specs remain `ACCEPTED` while the user completes public acceptance and account-side GSC, GA4, and mailbox checks; this record does not claim those account actions are complete.
