# Content model — project decisions

Current foundation: Core Page and Post, Core Media, menus, blocks and revisions; no domain CPT/taxonomy/meta. This is a starting implementation, not a decision that every business must use only Pages.

For each proposed record type, document:

| Decision | Fill from this project |
|---|---|
| Meaning and audience | What real thing is this record? |
| Core object | Page/Post, or why a CPT is necessary |
| Identity and URL | Stable identity; URL owner; existing-record ownership |
| Fields | Type, units, allowed values, requiredness, source and edit location |
| Relationships | Meaning, authority, validation and deletion behavior |
| Rendering | Reusable template/blocks; empty or unavailable values |
| Changes and history | Editor/import ownership, revision/restore support for non-Core data |

Repeated independently edited records with their own lifecycle can justify a CPT. A few ordinary service pages do not need fictional product numbers. Taxonomy groups records; Meta stores per-record values; Blocks structure editable body content; templates render records. Do not encode technical facts only in Theme HTML.

When the business has relationship facts, explanatory copy and selection/navigation rules, describe them separately. They may be related without sharing one authority. Do not infer one from another or silently synchronize them. Choose checks appropriate to the actual project.

No new model is approved by this skeleton. Add actual decisions here when confirmed; put page-specific copy and current status in its Page Spec.
