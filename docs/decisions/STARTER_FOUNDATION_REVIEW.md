# WordPress Starter foundation — verification record

Date: 2026-09-21. Plan: [foundation implementation](../superpowers/plans/2026-09-21-wordpress-starter-foundation.md). Implementation base: `7075312`, branch `feat/wordpress-starter`. This records a **foundation candidate**, not validation of a completed second business website.

## Scope and versions

Only `starters/wordpress/` plus this report and plan progress are in scope. Core Page/Post, a minimal PHP theme, isolated local Compose, backup/recovery instructions, neutral planning and three DRAFT methods. No Product CPT, domain plugin, importer, RFQ, SMTP, production deployment or global skill installation.

Actual Windows host tests: Docker Engine 29.6.2, Compose 5.3.1, Python 3.12, WordPress 7.1.1, PHP 8.3.33, WP-CLI 2.12.0, MariaDB 11.4.12. WordPress version choice was checked against its [7.1.1 release documentation](https://wordpress.org/documentation/wordpress-version/version-7-1-1/). Images remain version tags; these checks are not a vulnerability scan or cross-platform certification.

## Runtime and recovery evidence

| Environment | URL | Distinct data volumes | Role |
|---|---|---|---|
| starterlab-a | http://127.0.0.1:18081 | starterlab-a_database / starterlab-a_wordpress | Authoring and source backup |
| starterlab-b | http://127.0.0.1:18082 | starterlab-b_database / starterlab-b_wordpress | Tracked-files-only fresh copy |
| starterlab-c | http://127.0.0.1:18083 | starterlab-c_database / starterlab-c_wordpress | Tracked-files-only recovery target |

Each copy owns its Compose directory/configuration labels. B refuses A's project identity even with `--resume`; a fresh project using occupied port 18081 is refused without mutations. A resumes only from its original directory. The preflight unit suite passed 5 tests after its initial missing-implementation failure.

Core fixture passes on A and C: local-only guard, create/update a Page, save/restore actual revision content, delete fixtures, no Product dependency, intercepted `wp_mail` returns false. HTTP smoke checks Core output, one title, main/skip link, noindex, absence of business branding, actual missing-object 404.

Browser evidence on A: actually saved title, paragraph, generated image and link in the Core block editor. The public Page renders the saved values; image decoded at its expected 120px width; 1440/768/390 viewports have no horizontal overflow. Keyboard skip link reaches main; 390px screenshot visually inspected. This is a minimal neutral layout, not final visual design or an accessibility audit.

Backup at runtime code `06acbd1`: local ignored `backups/foundation-20260921/`, SQL 1,246,168 bytes, uploads archive 352 bytes. Media relative path `uploads/2026/09/fixture.png`. Source/generated/restored HTTP SHA256 all:
`1233ecb494c5e9937ef89c7eda7d56215628e0519a78df6661316aae0808db03`.

Actual commands followed [RECOVERY](../../starters/wordpress/docs/RECOVERY.md): container DB export and tar, compose cp, import into C's newly initialized volumes, serialized-aware search-replace dry-run then execution (8 replacements, GUID skipped), trusted uploads archive extraction and permissions. C `home`/`siteurl` both report port 18083. Page 13 restored as published with its title/body/link/image; a subsequent **browser editor save** changed C's title to “Recovered and edited independently”. A retained “Starter verification page revised”. Both media HTTP hashes still match. Core fixture and HTTP smoke pass after recovery.

Observed upstream CLI warnings: initial `core install` emitted an undefined `HTTP_HOST` warning; DB tooling printed its SSL verification warning on the local Docker network; CLI hard rewrite flush warned about .htaccess configuration. All commands succeeded, actual clean permalinks/editor/restore passed. These are recorded, not suppressed or treated as production-readiness evidence.

Original TiO₂ containers remain `653d527a9e04` and `4921069ffdc4`, on original `tio2-wordpress_*` volumes and port 8080. No original website database writes were performed.

## Remaining verification

Planning skeleton, Skill discovery/usage probes, final tracked-only packaging check and independent whole-branch review are pending. Do not treat this intermediate record as full plan completion.
