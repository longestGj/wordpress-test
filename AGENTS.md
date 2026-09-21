# Native WordPress site

- Use WordPress Core first. No Next.js, headless frontend, page builder or custom CMS framework.
- Theme owns presentation; `tio2-products` owns domain data, editor behavior and validation.
- Approved planning content is build input. Provenance is audit information, never a runtime permission tied to a commit or filename.
- Page ownership uses `_tio2_owner` plus its stable page identity. Run the explicit ownership migration for legacy installs before import; never adopt by slug alone.
- Preserve editor changes on repeat import. Do not infer product claims or synchronize taxonomy, presentation copy and Discovery automatically.
- Product term snapshots live on revisions only. Keep taxonomy authoritative at runtime. Old revisions may lack snapshots; never guess historical assignments.
- Keep work bounded to the requested pages/fixes. Email and production deployment require their own requested work; production publication requires explicit approval.
- Run relevant PHP/HTTP regressions. Database-mutating tests must refuse non-local sites and restore fixtures. Coordinate a single database writer.
- Git stores code and seeds, not the live WordPress database or uploads. Never commit credentials or backups.
