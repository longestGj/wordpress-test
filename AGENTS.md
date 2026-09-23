# Native WordPress site

- Start with `planning/pages/<Page ID>.md`; use `planning/SITE_MAP.csv` and `planning/SEO_MAP.csv` for lookup. Page status lives only in its spec. Follow `docs/WORKFLOW.md`.
- `planning/inputs` contains selected original content/fact/visual inputs, not inherited workflow instructions. Old Gate, D32, Next.js and source-agent directions do not override this repository. Do not traverse the historical repository during ordinary page work.
- Existing `docs/build-briefs` files retain past verification evidence; do not create a duplicate brief for new pages. Update the current Page Spec instead.
- Create future Git worktrees inside `D:/33wordpress/.worktrees/`; do not create this project's worktrees on the C: drive.

- Use WordPress Core first. No Next.js, headless frontend, page builder or custom CMS framework.
- Theme owns presentation; `tio2-products` owns domain data, editor behavior and validation.
- Approved planning content is build input. Provenance is audit information, never a runtime permission tied to a commit or filename.
- Page ownership uses `_tio2_owner` plus its stable page identity. Run the explicit ownership migration for legacy installs before import; never adopt by slug alone.
- Preserve editor changes on repeat import. Do not infer product claims or synchronize taxonomy, presentation copy and Discovery automatically.
- Product term snapshots live on revisions only. Keep taxonomy authoritative at runtime. Old revisions may lack snapshots; never guess historical assignments.
- Keep work bounded to the requested pages/fixes. Email and production deployment require their own requested work; production publication requires explicit approval.
- Run relevant PHP/HTTP regressions. Database-mutating tests must refuse non-local sites and restore fixtures. Coordinate a single database writer.
- Git stores code and seeds, not the live WordPress database or uploads. Never commit credentials or backups.
