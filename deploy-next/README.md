# Isolated WordPress stage on the existing server

The current public site remains the old `tio2products` Compose project on ports 80/443. The new native WordPress stage is a separate `tio2products-next-stage` project under `/home/deploy/tio2products-next/stage`, with its own MariaDB and uploads volumes. Apache is published only at `127.0.0.1:18080` on the server. Nothing in this stage configuration replaces the public Caddy service or changes DNS.

## Proven state on 2026-09-23

- Public server: `deploy@129.146.71.11` (ARM64). Old release: `bed69fff43a5e26b441154ffe4f4f44df11a319b`.
- Old database, uploads, environment file, and active release were copied to ignored `D:\33wordpress\.local\production-backups\20260923T031104Z-bed69fff43a5`. SQL and uploads checksums matched the server; SQL restored into an isolated MariaDB with 12 tables.
- The old backup timer was inactive at inspection. Take a **new** off-server backup immediately before cutover to capture later inquiries.
- The first stage bootstrap imported 14 products, seven root pages, seven process/application pages, eight resource pages, and three document guides. A second bootstrap preserved the existing content. Home, Products, M-350, Applications, Resources, Documents and REACH returned 200; RFQ returned 404 because its receiver is not built. Stage remained noindex and the public site remained 200. The market and utility pages were integrated into the code later and require a stage rebuild/reimport before claiming stage acceptance.

## Preview

From a trusted computer with the production SSH private key, forward local port 18080 to the server's stage port:

```text
ssh -N -L 127.0.0.1:18080:127.0.0.1:18080 -i <private-key> deploy@129.146.71.11
```

Open `http://localhost:18080/` while the tunnel is running. The stage URL is intentionally loopback-only; do not publish this port or set stage `PUBLIC_URL` to the live domain.

## Rebuild from a reviewed commit

Build the stage bundle from the reviewed code, containing `.dockerignore`, `deploy-next/Dockerfile`, `entrypoint.sh`, `bootstrap-stage.sh`, `compose.stage.yaml`, `wp-content/themes/tio2`, `wp-content/plugins/tio2-products`, `data`, and `scripts`. Transfer it over verified SSH and extract it under `/home/deploy/tio2products-next/stage`. Preserve the existing mode-600 `.env` and the named database/uploads volumes. Then run from that stage directory:

```bash
docker compose --env-file .env -f deploy-next/compose.stage.yaml build wordpress
docker compose --env-file .env -f deploy-next/compose.stage.yaml up -d --no-build
bash deploy-next/bootstrap-stage.sh
```

`bootstrap-stage.sh` accepts CRLF or LF environment files, refuses a non-loopback URL, and preserves existing imported pages on repeat runs. It must remain a stage-only operation. Do not run database-mutating test fixtures against this server.

## Cutover gates

Do not stop the old site or delete its volumes until the market and legal/system branches are integrated and tested in this stage, RFQ/Sample/Documents receivers are complete and independently accepted, local-only import restrictions have a reviewed release path, and a production Caddy/HTTPS switch with a rehearsed rollback has been implemented. Immediately before any switch, make and restore-test a fresh off-server backup of the old database and uploads, retaining the old environment and release. The existing old-repo rollback script restores code but does **not** undo database writes, so it is insufficient by itself for a new-site cutover. Production publication also requires the explicit approval stated in `docs/RELEASE.md`.
