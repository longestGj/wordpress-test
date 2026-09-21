# Native WordPress Starter — foundation candidate

Copy this directory's **tracked contents** into an empty repository for a new site. Do not copy the parent website, `.env`, backups or Docker volumes. This is a neutral Core Page/Post theme and local environment, not a finished business website. No product model, importer, enquiry form or mail delivery is supplied.

## Start locally (Windows PowerShell)

Requires Docker Desktop with Compose v2, Git and Python 3. Windows is the tested host; other hosts have not been verified. Images are specified in `compose.yaml`; reassess supported/security-patched versions when starting a real project.

1. Initialize Git in your new directory. Copy `.env.example` to `.env`. Fill every field locally, using three different generated secrets for the database user, database root and administrator. Do not paste credentials into chat, Git or reports. Choose a unique `COMPOSE_PROJECT_NAME` and free `WP_PORT` for this copy. Example identities below are placeholders; replace them with your actual values.
2. Run the read-only guard **before any Compose mutation**:

```powershell
python scripts/preflight.py --project my-new-site --port 18081
if ($LASTEXITCODE -ne 0) { throw 'Preflight failed; stop here.' }
docker compose up -d --wait db wordpress
if ($LASTEXITCODE -ne 0) { throw 'Startup failed; stop here.' }
docker compose run --rm --entrypoint sh cli /workspace/scripts/install.sh
if ($LASTEXITCODE -ne 0) { throw 'Installation failed; inspect before retrying.' }
```

3. Open `http://127.0.0.1:18081/` and `/wp-admin/` using your chosen port and local admin credentials. Use Core Pages, Posts, Media and Appearance → Menus. WordPress sample content is Core's default, not approved site content. Change or remove it in the editor when preparing the actual site.
4. For a subsequent start, use the same guard with `--resume`. It checks both working directory and Compose file labels. A same-name project belonging to another copy must be refused. Optionally add `--protect-project NAME` for environments you must never target. Never override a refusal by changing labels or using an existing volume.

`install.sh` preserves an already installed database. If a first run installed Core but failed before activating the theme, inspect that this is **your newly created local database** and explicitly finish with `docker compose run --rm cli eval-file /workspace/scripts/install.php`. This changes initial theme/permalink settings once; it is not an import or migration for an existing site.

```powershell
python -m unittest discover -s tests -p preflight_test.py
docker compose run --rm cli eval-file /workspace/tests/core-content.php
python tests/http-smoke.py --base-url http://127.0.0.1:18081
```

The PHP fixture creates and deletes its own local Page/revisions and attempts an intercepted mail. Browser checks are additional: edit a title, paragraph, image and link; save; view the real page at desktop/tablet/mobile widths and use the keyboard. HTTP 200 alone is not acceptance.

## What to fill in

Start at [SITE_BRIEF](planning/SITE_BRIEF.md), then [CONTENT_MODEL](planning/CONTENT_MODEL.md), [DESIGN_SYSTEM](planning/DESIGN_SYSTEM.md), [SITE_MAP](planning/SITE_MAP.csv) and [SEO_MAP](planning/SEO_MAP.csv). Follow [WORKFLOW](docs/WORKFLOW.md). Unknown facts stay unknown until a named source resolves them.

Create one `planning/pages/<PAGE_ID>.md` for each real page. The following field example is an instruction, **not approved content**:

```text
Page ID: <stable identity>
Status: PLAN | READY | BUILD | REVIEW | READY_TO_RELEASE | RELEASED
Purpose: <audience, problem and intended outcome>
Map / SEO: <corresponding rows in SITE_MAP.csv and SEO_MAP.csv>
Content source: <approved complete copy and exact source/version>
Facts: <supported statements, source, restrictions>
Actions and outcomes: <CTA destination; what happens after an action>
Visual reference: <approved reference or design-system rule>
Acceptance: <content, facts, SEO, responsive, function, accessibility>
Open decisions: <unresolved items, or none>
```

Page status lives only in that spec. Repository `.agents/skills` contains three **DRAFT** methods; availability depends on the Codex client discovering skills in the copied project. These are tools for particular tasks, not a mandatory per-page sequence. No agents or global installation are required.

## Data and boundaries

Git stores code and planning. **Git push does not back up the live database or uploads.** Follow [RECOVERY](docs/RECOVERY.md); retain the matching code commit with each data backup. Schema changes may require a coordinated data restore when rolling code back.

This Compose setup is local only: loopback HTTP, forced noindex, disabled sitemap and intercepted `wp_mail` returning false. Those controls are not access control, do not prevent arbitrary external HTTP requests, and do not make a site production-ready. Do not add SMTP here. [RELEASE](docs/RELEASE.md) requires a separate production configuration and explicit publication approval.

Stop this copy with `docker compose stop` after checking its identity. Do not remove volumes as routine cleanup. This candidate has no automatic backup scheduler, deployment or migration service.
