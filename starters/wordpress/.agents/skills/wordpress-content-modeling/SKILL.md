---
name: wordpress-content-modeling
description: Use when deciding or changing a native WordPress content model for repeated records, relationships, editor fields, imports or revision support.
---

# WordPress content modeling

Status: **DRAFT**. A reusable decision method; not a preapproved business schema.

Read the project's content model and the affected approved page/data inputs. If the project uses other paths, follow its rules. An ordinary copy/link edit that does not change the model needs no modeling exercise.

## Make the model follow the business

| Object | Choose it for | Avoid |
|---|---|---|
| Page | Stable standalone content or a few services | A CPT for every navigation item |
| Post | Chronological editorial entries | Treating every business record as news |
| CPT | Repeated independently edited records with shared fields/lifecycle | Inventing product numbers for a service business |
| Taxonomy | Named grouping or actual reusable relationships | Encoding per-record descriptive prose as terms |
| Meta | Typed per-record data and presentation qualifiers | Duplicating an authoritative relationship without a reason |
| Block | Editable body structure | Hiding reusable facts inside layout HTML |
| Template | Rendering shared by records | Copying one page's facts into every record |

For each field record meaning, type, units, allowed/empty values, source, editor and output consumer. Unknown values remain unknown. Verify version-dependent Core capabilities in the installed environment before choosing APIs.

If relevant, distinguish **relationship fact**, **per-record explanation/display controls** and **discovery/navigation rules**. For example, a classified relationship does not automatically approve a marketing paragraph or a recommendation. Pick authority for each; validation may warn without silently changing approved data. Do not impose these layers when a simpler site has no such needs.

## Identity and change safety

- Identify existing records by explicit project ownership **and** stable identity before imports or navigation adoption. A matching slug does not authorize taking over a record. Conflicts need an explicit disposition; preserve editor changes on repeat import.
- Core revisions do not prove custom Meta or taxonomy restores. Specify which data belongs to a historical snapshot, which remains current, how absent fields in old revisions behave, and whether autosaves are supported.
- When adding custom revision support, test an actual sequence: save old values, change values, restore the old revision, then verify saved data and every affected frontend/structured-data consumer. Do not infer success from a revision row existing.
- Model/domain behavior belongs in a plugin when introduced; Theme renders it and needs a deliberate missing-dependency behavior.

Output the smallest justified model and unresolved decisions in the project's existing content-model document. For a parameterized equipment catalog and a service-only site, expect different outcomes. Do not create a new model manifest or infer business facts from an example.
