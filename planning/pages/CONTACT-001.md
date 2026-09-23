# CONTACT-001 — Contact

Status: REVIEW

URL: `/contact/`
Family: Utility contact page · EN

## 页面职责

Provide verified company contact channels and route commercial users to RFQ.

## 内容与事实

- [Current content input](<../inputs/pages/contact/04_planning/CONTACT-001_GATE2_FULL_BUYER_CLEAN_COPY_V0.1.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: NO_PRIMARY_KEYWORD
Title: Contact TiO2 Malaysia | General Inquiries
Meta: Contact TiO2 Malaysia with a general company or business inquiry, or use the dedicated pages to request a quote, product documents or a sample.
H1: Contact TiO2 Malaysia

Keyword boundary: Contact is a utility page and must not be optimized as a commercial supplier landing page.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/contact/04_planning/gate4-v0.2/CONTACT-001_GATE4_COMPLETE_VISUAL_V0.2.html>)

## 实现与验收

本页已在本地 WordPress 实现，运行态验收结果与待解决依赖记录于下方；不继承旧项目的开发或上线状态。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Contact email notifications are implemented and locally tested; production deployment remains separate. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/CONTACT-001.json`. The general form validates on the server and creates a private `tio2_inquiry` record in local WordPress. After private storage, it attempts a staff notification through the existing Gmail configuration. Its success text confirms receipt without exposing mail status. Errors and success state use a 10-minute server-side record bound to the `tio2_flow` session Cookie. The Quote, Documents and Sample links use their now-active receivers.

Local review 2026-09-23: `/contact/` returns HTTP 200 with the expected title, description, canonical and one H1. `tests/utility-form-http.py` verified invalid-field and nonce rejection with retained entries, then saved one private local inquiry and removed that exact fixture. `tests/utility-import.php` verified repeat import preserves edited SEO and rejects a slug without project ownership, then restored the metadata fixture. `tests/utility-retention.php` verified three-year cleanup with a disposable fixture. `tests/utility-browser.py` checked 1440/768/390, keyboard menu and Cookie Settings focus, and saved screenshots under `.local/utility-http/`. Those route limitations were subsequently resolved by the three request receivers; production publication still needs separate authorization.

Domain audit 2026-09-23: the displayed contact address is updated to `info@tio2products.com`, confirmed by the user as receiving mail. The owned Page in the isolated 18080 preview was migrated without replacing other editor content; a repeat migration changed zero Pages.

## Contact notification verification (2026-09-23)

Contact uses the shared Gmail transport and existing site recipient. A browser-session submission token claims an atomic server-side key before private storage; repeated posts reuse the saved record and do not send again. Claims are scheduled for cleanup after one day. Mail failure leaves the saved receipt intact. Notification status and nonce-protected retry are available only to administrators under General inquiries; an uncertain send requires a mailbox check before retry.

`tests/contact-mail-runtime.php` first failed for missing duplicate protection, then passed private storage, duplicate/concurrent submission, complete mail body, failure, accepted suppression and confirmed retry checks with intercepted transport and fixture cleanup. The existing request-mail runtime and settings tests still pass. `tests/utility-form-http.py` passed with forced mail failure and with `--send-real-mail`: invalid fields/nonce rejected, receipt remained generic, repeat POST sent no duplicate, guest retry was blocked. Gmail accepted the single real Contact notification #352 with one attempt; the user confirmed receipt of that message. Both HTTP records (#348, #352) were removed with their submission claims. Utility HTTP/SEO/404, browser-bound receipt, import edit-preservation and PHP lint checks passed. Independent review found no Critical/Important issues.

EN/MS Privacy and Cookie seeds now disclose Contact notification, Gmail processing, independent email retention and server-side duplicate prevention. `scripts/update-contact-mail-policy.php` updated three owned local Pages; a repeat run changed zero. It preserves other content and refuses changed target passages. The database was backed up locally before migration. Existing GA4 seed text and contact address were preserved; the separate GA4 policy migration has not yet been applied to the main local database.
