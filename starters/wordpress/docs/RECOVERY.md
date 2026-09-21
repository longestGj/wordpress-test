# Backup and recovery to a new local copy

Run from the correct project root. Stop editing while exporting so SQL and media describe the same point in time. Use private storage for backups: SQL can include passwords hashes and personal data. Never commit them. These commands assume a disposable **new recovery target**, not an existing website.

## Source

First run preflight with the actual source identity and `--resume`. Inspect `docker compose ps` and the named volumes before proceeding. Export to a new, timestamped directory (never overwrite a previous backup):

```powershell
$backupId = Get-Date -Format 'yyyyMMdd-HHmmss'
New-Item -ItemType Directory "backups/$backupId" -ErrorAction Stop
docker compose run --rm cli db export "/workspace/backups/$backupId/database.sql"
if ($LASTEXITCODE -ne 0) { throw 'DB export failed' }
docker compose exec -T wordpress tar -czf /tmp/starter-uploads.tar.gz -C /var/www/html/wp-content uploads
if ($LASTEXITCODE -ne 0) { throw 'Uploads export failed (a site without uploads needs an explicitly empty archive)' }
docker compose cp wordpress:/tmp/starter-uploads.tar.gz "backups/$backupId/uploads.tar.gz"
if ($LASTEXITCODE -ne 0) { throw 'Archive copy failed' }
git rev-parse HEAD | Set-Content "backups/$backupId/code-commit.txt"
docker compose run --rm cli option get home | Set-Content "backups/$backupId/source-url.txt"
if ($LASTEXITCODE -ne 0) { throw 'Source URL read failed' }
Get-Item "backups/$backupId/database.sql", "backups/$backupId/uploads.tar.gz" | Select-Object Name,Length
Get-FileHash "backups/$backupId/database.sql", "backups/$backupId/uploads.tar.gz"
```

Verify both files are nonempty, check `tar -tzf` inside the container, and keep the code commit available. For an empty site, create the uploads directory before archiving; do not silently omit media. A hash verifies integrity, not restorability. Containers' `/tmp` files are not durable backups. Commands use container files plus `compose cp`, avoiding binary redirection through PowerShell.

## New target

1. Copy tracked starter files at the recorded code commit into a **new directory**, initialize Git, create fresh `.env` with a different project name, free port and fresh DB secrets. Run README preflight and installation. Check both source and target container names, Compose directory labels and volume names: all must differ. Do not use `down -v` to manufacture a clean target.
2. Copy only the chosen backup into the target's `backups/restore/` (create it first). Never copy the source `.env`.
3. Record the source URL from the backup. Set `$targetUrl` to the new loopback URL and `$sourceUrl` to the exact recorded source URL. Confirm the target is disposable; SQL import replaces its WordPress data and user accounts. Restored admin login comes from the **source database**, not the new target's `.env`.

```powershell
docker compose run --rm cli db import /workspace/backups/restore/database.sql
if ($LASTEXITCODE -ne 0) { throw 'DB import failed' }
docker compose run --rm cli search-replace $sourceUrl $targetUrl --all-tables-with-prefix --skip-columns=guid --dry-run
if ($LASTEXITCODE -ne 0) { throw 'Replacement preview failed' }
# Inspect preview and identities before the following write:
docker compose run --rm cli search-replace $sourceUrl $targetUrl --all-tables-with-prefix --skip-columns=guid
if ($LASTEXITCODE -ne 0) { throw 'URL replacement failed' }
docker compose cp backups/restore/uploads.tar.gz wordpress:/tmp/starter-uploads-restore.tar.gz
if ($LASTEXITCODE -ne 0) { throw 'Archive copy failed' }
docker compose exec -T wordpress tar -tzf /tmp/starter-uploads-restore.tar.gz
# Inspect: only relative uploads/ paths; no absolute paths or .. components.
# Use only your own trusted backup archive.
docker compose exec -T wordpress tar -xzf /tmp/starter-uploads-restore.tar.gz -C /var/www/html/wp-content
if ($LASTEXITCODE -ne 0) { throw 'Media restore failed' }
docker compose exec -T wordpress chown -R www-data:www-data /var/www/html/wp-content/uploads
if ($LASTEXITCODE -ne 0) { throw 'Media ownership failed' }
docker compose run --rm cli rewrite flush --hard
if ($LASTEXITCODE -ne 0) { throw 'Rewrite failed' }
```

`search-replace` handles serialized values; never substitute raw SQL text. Do not change GUIDs. Keep local mu-plugin protections enabled throughout. These steps restore the supplied starter's database and uploads; if a future plugin stores files elsewhere, extend the backup scope before relying on this procedure.

## Prove the restore

Read target `home` and `siteurl`, inspect a known Page's title/body/status, open its clean permalink, compare a known media HTTP body hash to the source, and edit/save in the target's Core editor. Check both source and target afterwards to show the change stayed isolated. Run the Core fixture and HTTP smoke at the target URL. Save source/target identities, code commit, hashes and actual observations with the backup. Do not call a recovery successful merely because import exited zero.
