# Legacy domain 301 consolidation

`tio2malaysia.com` is served by a separate Nginx host. On 2026-09-24, its `/resources/non-china-titanium-dioxide/` returned 200 with an old-domain canonical, while the corresponding `tio2products.com` page also returned 200. Both public sitemaps listed the same 57 paths. This package prepares direct, path-preserving 301 redirects for precisely those 57 routes.

The [candidate Nginx file](tio2-production-adoption.conf) replaces `/etc/nginx/conf.d/tio2-production-adoption.conf` on the legacy host. It retains the existing certificate, ACME challenge, maintenance condition, upstream and loopback listener. `/sitemap.xml` redirects to the new WordPress sitemap, and the old robots response names only the new sitemap. Unmatched paths continue to the old upstream for separate mapping review. In particular, `/thank-you/` is excluded because its receipt context cannot be assumed to match the new site's database. The `cms.tio2malaysia.com` site is outside this file.

Regenerate the candidate only after re-auditing both public sitemaps:

```text
python scripts/check-legacy-domain.py
python scripts/build-legacy-domain-redirect.py --check
python tests/legacy-domain-nginx.py
```

The legacy `deploy` SSH account can read the current Nginx configuration but cannot use passwordless `sudo`; installation requires an administrator with system-level privileges. Copy the candidate to the legacy host, back up the current `/etc/nginx/conf.d/tio2-production-adoption.conf`, install the candidate in its place, run `nginx -t`, then reload Nginx. If the syntax check or public smoke test fails, restore the backup and reload. Keep the backup until the 57 redirects have been verified.

After installation, run `python scripts/check-legacy-domain.py --postdeploy` from the repository. It checks all 57 old HTTPS routes, the target page over HTTP and `www`, the target canonical/OG/Schema, and old sitemap/robots behavior. Also inspect any legacy routes not present in the matched list before giving them individual mappings. Do not send all unknown paths to the new homepage.
