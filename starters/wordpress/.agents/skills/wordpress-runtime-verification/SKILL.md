---
name: wordpress-runtime-verification
description: Use when accepting or reviewing changed native WordPress behavior where saved content, templates, dependencies, revisions or visitor actions must be verified in the running site.
---

# WordPress runtime verification

Status: **DRAFT**. Verify the changed user outcome, not a fixed checklist for every task.

Establish the running URL, project/config identity and code version. Choose a representative affected record and trace **saved WordPress data → PHP rendering → HTTP → browser/action result**. A code scan is not runtime evidence; a screenshot cannot prove persistence.

| Change or claim | Evidence needed when applicable |
|---|---|
| Text/link edit | Final saved value, actual rendered value/destination, relevant layout |
| Template/style | Actual content at desktop/tablet/mobile, keyboard/focus and overflow; console/network failures relevant to it |
| Structured field | Saved type/value, displayed consumer and affected structured data |
| Revision support | Save A → change to B → restore A → read final data and rendered consumers; test legacy missing fields separately |
| Import | First creation, repeat import preserving editor changes, foreign ownership/identity collision refusal |
| Plugin dependency | Activation and safe Theme output with the dependency unavailable in an isolated test |
| Form/action | Valid/invalid submission, actual intended persisted/received result, duplicate/error behavior; mock success is only a UI check |
| Backup | Restore database and uploads into a distinct target; verify page/media and editing, not just archive existence |

For forms, mail sending and inbox receipt are different claims. If mail is deferred/blocked, say so and check only authorized local handling. A success banner does not establish either delivery or a saved record.

Check content/fact restrictions and relevant SEO (title/H1/meta/canonical/robots/schema) against the approved inputs. Missing or unsupported claims are findings even if the page loads. Do not infer a production performance result from localhost or call a few keyboard checks a complete accessibility audit.

Mutation tests must verify the actual target is local/test, use uniquely identified fixtures, serialize writes and restore prior state. Do not deactivate production dependencies to prove a point. If a required target or action is unavailable, mark that check **not verified** and explain the limit; do not silently substitute a mock.

Report concise pass/fail/not-verified results with the observed target and evidence. Ordinary link changes do not require re-research, all Skills, or the full data/import/revision suite. Keep results in the existing PR or Page Spec, without a separate acceptance-manifest system.
