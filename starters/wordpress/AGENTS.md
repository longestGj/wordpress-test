# Native WordPress project

- Read `planning/SITE_BRIEF.md` for this site's facts and unknowns. For page work, use the single `planning/pages/<PAGE_ID>.md` referenced by `planning/SITE_MAP.csv`; SEO ownership lives in `planning/SEO_MAP.csv`. Follow `docs/WORKFLOW.md`.
- Placeholder instructions are not approved copy. Reuse approved inputs; research only a material new question, missing fact or changed requirement. Do not invent claims, customer identity or product parameters.
- Core first: choose Page/Post before a justified CPT. Theme owns presentation; a project plugin owns any future domain model and validation. No page builder, headless frontend, second prototype site or generic CMS engine by default.
- Use `planning/CONTENT_MODEL.md` and `planning/DESIGN_SYSTEM.md` for actual project decisions. Never copy a previous customer's fields, taxonomy or branding just because they exist in an example.
- If importing later, verify explicit ownership plus stable identity before updating or linking an existing record. Slug matching is insufficient. Preserve editor changes; report conflicts instead of silently adopting, overwriting or synchronizing approved relationships.
- Verify changed behavior in the actual WordPress runtime. Report what was checked and what remains untested. Use scoped tests; ordinary text/link edits do not require every database fixture.
- Before local Compose mutations, run `scripts/preflight.py` with this copy's project/port, and `--resume` for verified existing resources. Database writers must be serialized; fixtures must refuse non-local targets and clean up their own records.
- Git contains code/planning, not the live database, uploads, secrets or backups. Follow `docs/RECOVERY.md`. Do not reset or remove volumes for convenience.
- Local mail is blocked. Email integration and production deployment need their own requested scope; publication requires explicit user approval. A local published Page is not a production release.
- The three project Skills are DRAFT methods for relevant tasks. Their presence does not authorize delegation, external messaging, deployment or global installation.
