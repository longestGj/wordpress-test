# Small website workflow

`PLAN → READY → BUILD → REVIEW → RELEASE`

**PLAN** resolves the material unknowns: audience, purpose, facts, content model, URL/SEO responsibility and visual direction. Reuse approved inputs; reopen research only when a relevant fact or requirement changes. A question that blocks one page need not stop unrelated authorized work.

**READY** means its single Page Spec contains complete approved copy or exact approved sources, supported facts/restrictions, URL/SEO references, actions with expected outcomes, visual reference and acceptance criteria. Do not turn placeholder text into approved facts. No duplicate handoff package.

**BUILD** implements directly in local/staging WordPress. Core first; edit the actual Theme and any justified domain plugin. Preserve editor data and explicit ownership. A reusable template may serve many records, but each record still needs approved data. No second full HTML prototype website.

**REVIEW** checks the actual result: content/facts, desktop/tablet/mobile, SEO, links and real action outcomes, accessibility/performance. Use tests relevant to the change; record result and remaining issues in the PR or current Page Spec. Screenshots do not prove database writes or delivery. Review failures return to BUILD.

**RELEASE** is a separate authorized operation after applicable checks pass. Set the Page Spec to READY_TO_RELEASE, then require explicit publication approval and the conditions in [RELEASE](RELEASE.md). After deploy and smoke checks, set RELEASED.

Current page status lives **only in its Page Spec**. SITE_MAP and SEO_MAP are lookup/ownership tables, Git preserves document history, WordPress revisions preserve supported editorial history, and backups preserve runtime data. None replaces the others. A normal task needs a Page Spec, code branch/PR and review result, not extra lifecycle manifests.
