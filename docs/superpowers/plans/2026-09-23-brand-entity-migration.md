# Brand and Entity Migration Implementation Plan

> **For agentic workers:** Execute this plan in one isolated worktree. The user's supplied brand architecture is the controlling specification; deliver the requested single focused commit. Do not publish production.

**Goal:** Make TiO2Products the consistent website/commercial brand while preserving IKHLAS TITANIUM (MALAYSIA) SDN. BHD. as operator/manufacturer and Malaysia as geography.

**Architecture:** Update shared theme chrome and home entity Schema once, then migrate active seed content and local WordPress posts through guarded, page-identity-checked exact replacements. Keep historical old-state strings in migration preconditions. Audit all active routes and responsive logo views before integration.

**Tech Stack:** Native WordPress, PHP 8.3, MariaDB, JSON seed files, Python/Playwright HTTP checks, SVG.

**Spec:** User-supplied Brand / Entity Architecture Migration prompt, 2026-09-23 (`C:/Users/longe/.codex/attachments/e5516b38-0d96-46c0-a9bb-fbb223b8b5db/已粘贴的文本.txt`).

## Global constraints

- Standard brand spelling: `TiO2Products`; website: `tio2products.com`.
- Operator/manufacturer/legal name: `IKHLAS TITANIUM (MALAYSIA) SDN. BHD.`.
- Preserve factual Malaysia, Taiping, Perak and Port Klang references.
- No blanket repository replacement, product claim changes, taxonomy changes, URL changes or production publication.
- Page ownership and editor changes are protected; local migration is exact, preflighted, idempotent and reversible through a local backup.
- Keep prior unrelated dirty files outside this worktree and outside the commit.

## Review focus

1. Existing editor modifications outside each target sentence survive migration; altered targets cause refusal before any write.
2. Old-state migration fixtures remain valid while rendered pages, metadata, logo and current seeds have no deprecated website identity.
3. Site brand is never used as the legal manufacturer; product claims and origin statements remain attached to the company.
4. Portuguese and Malay market/legal content retains its language and meaning.
5. Logo wordmark remains legible on light/dark backgrounds at desktop and mobile widths without header overflow.

## Tasks

### 1. Establish a failing brand contract

- Create a read-only browser/HTTP test covering Home, Products, Applications, About, one product and application detail, Documents, Markets, legal/contact/request pages, and all local active routes.
- Assert header/footer logo alt and visible SVG wordmark, SEO titles, Home WebSite/Organization identities, About relationship, and absence of active `TiO2 Malaysia` brand output.
- Run against the unmodified local preview to verify an expected failure at the old logo/metadata.

### 2. Update common brand assets and entity Schema

- Replace outlined `MALAYSIA` wordmark in both SVG assets with a legible `TiO2Products` text implementation while retaining the symbol.
- Update header/footer alt and aria names, Footer operator relationship, 404 title, plugin request mail branding, and theme/plugin display name where it denotes the site brand.
- Add `legalName` to the existing Organization node. Add a distinct Brand node and connect it without creating a second Organization.
- Verify XML, PHP syntax, JSON-LD and desktop/mobile rendered screenshots.

### 3. Update active seeds and planning controls

- Use structured JSON field edits for SEO suffixes and explicit, context-reviewed copy changes for market, application, resource, contact and legal text.
- Add a concise brand/operator explanation to About and keep Home's existing relationship statement.
- Synchronize source-derived text fields and current Page Spec/SEO_MAP title records; do not rewrite historical planning inputs.
- Add `planning/BRAND-NAMING-POLICY.md`. Audit remaining old-string locations and classify each.

### 4. Migrate the local WordPress database safely

- Produce a manifest of exact old/new content and metadata changes with stable owner identity for each page.
- Preflight every target on an isolated database clone, refuse mismatched or edited target copy, then apply only matching passages. Check idempotence and preservation of an unrelated editor marker in fixtures.
- Back up the main local database, apply once, and verify a repeat run reports no pending changes.

### 5. Verify, review and integrate

- Run full brand contract, existing PHP/HTTP regressions, malformed/edited-target migration fixtures, and 1440/768/390 screenshots for logo/chrome and representative pages.
- Perform a read-only review before main integration. Commit the brand migration as one focused commit, then integrate and push `main` after a clean remote divergence check.
- Report files, SEO changes, Schema identities, active old-string count, classified historical remnants, test coverage and any unresolved entity ambiguity. Production publication remains a separate requested task.
