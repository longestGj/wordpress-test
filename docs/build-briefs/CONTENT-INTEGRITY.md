# Content integrity follow-up

Approved scope: address the review of main 846252d without changing approved body copy, product facts or the WordPress architecture.

- Resource navigation now uses stable project ownership and Resource identity; provenance cannot hide a published, owned child page.
- All three importers use stable ownership. Topic pages persist the approved page_id. An explicit, idempotent migration preflights legacy identity AND original provenance for the entire batch before stamping markers. It migrated 22 local pages without modifying their content or source records.
- Application warnings cover enabled copy pointing to an absent taxonomy term. The reverse inference was removed because approved M-510 copy combines directions. Process warnings compare assigned terms with the key, allowing CR-901's intentionally empty key and taxonomy.
- Product relationship snapshots live only on revision objects via the metadata API. Saving taxonomy-only changes creates a revision. Term IDs survive renames. Missing legacy snapshots or deleted historical terms leave current relationships intact and produce a warning; terms are not recreated and partial historical assignments are not applied.
- Page SEO title/description and Discovery use native revisioned meta. Tests cover restore plus SEO-only and Discovery-only saves.
- Product validation rejects blank always-visible headings/SEO and blank enabled application fields. Receiver-only titles and CTA labels are required when that receiver is ready; unavailable optional sections and disabled application drafts remain permitted. Receiver activation still needs its separate functional acceptance.
- Topic classification test fails on linked grades without a matching term, while omitted grades are advisory because approved pages may be curated. No data synchronization.
- Added short AGENTS.md and local-only guards to mutating hardening/import PHP and CLI tests. The existing authenticated HTTP test is fixed to localhost.

Validation: failing tests first reproduced missing revisions, blank-field acceptance and source-dependent import/navigation. Then content-integrity, ownership identity/migration, relationship, existing model/CMS/Discovery/revision/activation/resource tests passed. Simulated non-local home was rejected by the local guard. PHP syntax checks passed. Independent read-only code review checked WordPress hook ordering and found no blocking issues. All eight Python HTTP/import/dependency regressions passed, covering M-350, the other 13 products, seven roots, seven topics and eight resources. Final database checks found 22 owned pages and zero taxonomy snapshots stored on runtime Product records.

Historical limits: snapshots are prospective; this cannot reconstruct old facts absent from revisions. Restoring post revisions does not cover destination settings, media files, or the whole database. RFQ and email remain outside this patch; no production release.
