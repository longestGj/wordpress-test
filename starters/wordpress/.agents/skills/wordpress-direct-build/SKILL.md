---
name: wordpress-direct-build
description: Use when implementing approved pages, templates or domain behavior directly in an existing native WordPress local or staging site.
---

# WordPress direct build

Status: **DRAFT**. Scope follows the user's task and project rules.

Read the affected Page Spec/approved source and inspect current Theme, plugins and saved data before editing. Locate the real template and current URL; do not assume a historical prototype is the running implementation. For a single approved link change, identify its owner, make that change and check its destination—do not restart site strategy or modeling.

## Build in the real runtime

1. Identify the actual local/staging instance and the minimal files/records to change. In a starter copy, run its preflight before Compose mutations. An available Docker daemon is not proof you selected the right website.
2. Use Core content and rendering APIs first. Keep layout/style in Theme, any domain storage/validation in a justified plugin, and facts in their approved records. Extend existing components rather than creating a second HTML prototype site or a parallel CMS.
3. Preserve editable content and current business semantics. Before import/update, verify ownership plus stable identity, compare approved input with saved values and editor changes, and report collisions. Do not adopt a page merely because its slug matches. An import that preserves its body can still wrongly adopt it into navigation.
4. Keep reusable templates free of one record's facts. Handle missing/disabled values without inventing availability or claims. If Theme calls domain-plugin functions, guard the dependency and verify safe output when unavailable. Plugin activation should call its own registration functions, not fire all of `init`.
5. Run checks appropriate to the changed behavior and inspect the real page. For data-model/import/revision changes, include persistence and relevant recovery scenarios; for plain copy edits, use focused saved-content and HTTP/browser checks. Do not run destructive fixtures on an unverified target.

Use one database writer. Back up runtime data before a consequential migration; Git alone is not the backup. Restore/delete test fixtures, but never erase unrelated editor work. Revisit only facts or decisions that actually block this change.

Report changed behavior, actual checks and unresolved limits in the existing PR/Page Spec. Local publish/save is distinct from production release. This method grants no authority for mail delivery, deployment, external messages or subagents.
