# Five scoped foundation fixes

Approved scope: product revisions; warning-only taxonomy/copy consistency; direct activation registration; safe theme dependency failure; page import ownership.

Preserve 14 products, seven roots, M-350 and Discovery. No rename, architecture change, new page content, email or deployment.

Work on existing feat/complete-site branch. Backup: backups/before-hardening.sql.

Validation: targeted failing/passing integration tests, existing PHP/Python suite, content snapshots, final independent review.

Progress:
- Revision: observed failure before registration; native revision restore and meta-only save pass after registration.
- Relationships: observed missing warning; both mismatch directions now warn without changing terms, meta or Discovery.
- Activation: observed global init redispatch; direct registration passes.
- Dependency: observed HTTP 500 with plugin disabled; guarded theme now returns safe 503, admin notice, and recovers after activation.
- Ownership: observed importer accepting foreign ownership; full preflight now rejects missing/mismatched provenance before writes, preserves seven owned pages.
- Independent review: no actionable findings in the five scoped changes.
- Regression: PHP integration tests and Python HTTP/editor tests pass; PHP syntax checks pass. Run mutating tests sequentially against this local instance.
- Preservation: compared pre-change database backup with current product/page title, excerpt, body, status, slug, project meta, taxonomy relationships and Discovery/destination/front-page options: no differences.

Ruling: existing cms-import test saved a filtered private page title, appending `Private:` on each run. Save the raw post_title instead and restore the exact pre-task title from backup; do not editorially clean the historical title in this task.

Ruling: use the existing `_tio2_hub_key` plus `_tio2_source` as ownership evidence for existing pages, avoiding a migration that guesses ownership from slugs. Preflight all seeds before any writes.
