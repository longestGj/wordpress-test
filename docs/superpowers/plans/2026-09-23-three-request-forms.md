# Three WordPress Request Forms Implementation Plan

> **For agentic workers:** Use superpowers:executing-plans to implement this plan task by task. Steps use checkboxes for tracking.

**Goal:** Build Quote, Documents and Sample Pages whose validated submissions become private WordPress records; connect a later, separately configured Gmail notification without depending on email for receipt.

**Architecture:** Native Page seeds carry approved visible copy and a form shortcode. `tio2-products` owns server validation, form processing, private records, ready flags and receipt issuance. The theme owns responsive presentation. Reuse the existing `tio2_flow` session and Thank You receipt. Do not call Web3Forms, send mail or add a Gmail credential in this batch.

**Tech Stack:** WordPress Core Page/CPT, PHP 8.3, existing theme and plugin, WP-CLI and local HTTP/browser tests.

**Spec:** `planning/pages/CONV-RFQ.md`, `planning/pages/CONV-DOC.md`, `planning/pages/CONV-SAMPLE.md` and their selected `planning/inputs` content/visual references.

## Global Constraints

- Keep `_tio2_owner` plus stable `_tio2_page_id`; run explicit legacy ownership migration before import; repeat import preserves editor changes.
- Store only private request records. No external form service, uploads, email send, shipping/availability inference or production publication.
- Validate all fields server-side; nonces, honeypot, idempotent double submit, rate limit, input retention and 10-minute session-bound receipt are required.
- Quote has one Product/Grade, Application and positive quantity in MT. Documents has 14 Grades and five multi-select document types. Sample accepts an explicit unknown-grade choice.
- Field limits, copy order, privacy link and user-editable prefill follow selected source material. Do not trust query strings or hidden metadata for required answers.
- Database tests run only on a local site and restore every test fixture; one database writer at a time.

## Review Focus

- Unknown or malicious URL prefill stays neutral and does not become a product recommendation.
- Double POST creates one record and one receipt.
- Invalid values and simulated storage failure keep buyer entries; no false success.
- Only owned published page routes and published allowed Grades are used; no private record leaks through REST/search.
- Valid document request with only Other needs Additional Requirements; mixed choices do not.

## Tasks

### 1. Request record model and validation

- [ ] Add failing local tests for allowed values, required fields, lengths, quantity and Other-only rule.
- [ ] Add private request CPT, status metadata and three form definitions in `wp-content/plugins/tio2-products/requests.php`.
- [ ] Verify tests; inspect retention and admin capability behavior.

### 2. Submission and receipt

- [ ] Add failing tests for nonce, session, honeypot, rate limit, duplicate POST, storage failure and receipt binding.
- [ ] Implement `admin-post.php` receiver, transient-backed input recovery and Thank You receipt issuance in plugin.
- [ ] Verify tests against local WordPress with restored fixtures.

### 3. Three Pages and links

- [ ] Create three native Page seeds using selected copy, SEO and shortcodes; implement local-only preflight import in `scripts/import-requests.php`.
- [ ] Render three forms, editable prefills, error summary, privacy and task-switch links; theme CSS/JS at 1440/768/390.
- [ ] Connect destination settings only after local save/receipt tests pass; preserve existing Product/Document links.
- [ ] Update each Page Spec to REVIEW and record local validation evidence.

### 4. Acceptance

- [ ] PHP lint, content and HTTP tests, real POST/duplicate/failure tests with fixture cleanup.
- [ ] Browser desktop/tablet/mobile, keyboard/focus, source links and admin-only request detail.
- [ ] Independent code review; resolve findings, commit and integrate under one database writer.
- [ ] Leave Gmail configuration and real send/receipt verification for the next task.
