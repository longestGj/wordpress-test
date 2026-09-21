# TiO2 WordPress — local development

Fresh standalone WordPress. No old site code, database or configuration is imported.

## Access

- Site: http://localhost:8080/
- Dashboard: http://localhost:8080/wp-admin/
- Admin username and password: `WP_ADMIN_USER` and `WP_ADMIN_PASSWORD` in local `.env`.
- `admin@example.test` is a local placeholder, not a receiving mailbox.

## Run

Start Docker Desktop, then run from this directory:

```powershell
docker compose up -d
docker compose ps
```

Stop services without deleting content:

```powershell
docker compose stop
```

WordPress CLI:

```powershell
docker compose run --rm cli core version
```

## Storage and scope

Docker volumes `tio2-wordpress_database` and `tio2-wordpress_wordpress` persist the database and site files, including uploads. Container recreation preserves them. Do not use `docker compose down -v` unless intentionally erasing this site. These volumes are persistence, not backups.

The web port binds only to 127.0.0.1; the database has no host port. This is a local development site, not a deployed staging or production site. Search engine visibility is disabled. Outbound email is not configured.

`.env` contains generated local credentials and is excluded from Git. `.env.example` documents the required variables. Keep the actual `.env` when restarting this installation; changing its database password does not automatically change an initialized database user's password.

## Products

- M-350: http://localhost:8080/products/m-350/
- Product Hub and all 14 grades: http://localhost:8080/products/
- Admin: Products → M-350 → Edit. Title and excerpt use WordPress fields; grouped text, parameters and SEO use the Product content panel. Lists support adding, removing and moving entries. Application/Process terms are in the sidebar.
- Products → Destinations connects published pages to product links. Form destinations require an explicit “Receiving workflow tested” setting after functional acceptance. No receivers are configured yet.

The `tio2-products` plugin owns data. The `tio2` theme owns templates and styles. Both directories are bind-mounted from `wp-content/`, so local source changes are immediately available. Approved M-350 initialization data is `data/m350.json`; runtime content lives in WordPress. Import is one-time and preserves an existing product:

```powershell
docker compose run --rm cli eval-file /workspace/scripts/import-product.php
docker compose run --rm cli eval-file /workspace/tests/product-model.php
```

`tests/http-review.py` tests actual authenticated editor saving and restores the approved parameter afterward; it needs Python with requests and beautifulsoup4. Run only against this local development site.

Home, Markets, Products, Applications, Documents, Resources and About now run in this local WordPress. The seven-item shared navigation links to these pages. Root copy is in Pages as an HTML block preserving the fixed approved layout; text/link changes currently use the native block's HTML editor. SEO has separate fields. Products Hub content is the private `Products` Page (`product-hub-content`), rendered only at the public Product archive route. Its directory, discovery relationships and Not Sure guidance have a separate editable field panel; these relationships do not overwrite technical product applications.

RFQ, Sample and Request Documents receivers, child market/application/process/resource pages, legal and consent experiences remain later work. Fixed global/page RFQ links currently have no receiver; this blocks release. Other missing child destinations render as unavailable labels, and product-context form actions remain hidden. Documents selection works locally but explicitly reports the disconnected receiver. No analytics or nonessential tracking is added. Production SEO/schema expansion and indexing require release review. Large approved PNG assets still need delivery optimization before production performance acceptance.

Batch initialization is idempotent and preserves editor changes:

```powershell
docker compose run --rm cli eval-file /workspace/scripts/import-batch.php
docker compose run --rm cli eval-file /workspace/scripts/import-pages.php
docker compose run --rm cli eval-file /workspace/tests/batch-products.php
docker compose run --rm cli eval-file /workspace/tests/discovery-editor.php
docker compose run --rm cli eval-file /workspace/tests/cms-import.php
```

`tests/batch-http.py` compares actual responses to product and page seeds, including technical cells, copy and metadata. `scripts/prepare-batch.py` and `prepare-roots.py` are one-time planning adapters, not runtime dependencies. Their pinned source cache lives under ignored `.local/batch-source`; root adaptation requires beautifulsoup4 and tinycss2. Normal WordPress editing does not use these scripts. See `docs/build-briefs/REMAINING-PAGES-PLAN.md` for the completed batch review.

Before product development, the database was exported to ignored `backups/before-products.sql`. Site files remain in the named WordPress volume; custom source is now on disk. Reverting this feature requires both restoring the matching database backup and switching back to the default theme/plugin configuration; do not restore over later edits without taking another backup.
