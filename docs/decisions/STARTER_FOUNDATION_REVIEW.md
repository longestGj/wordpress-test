# WordPress Starter foundation — verification record

Date: 2026-09-21. Plan: [foundation implementation](../superpowers/plans/2026-09-21-wordpress-starter-foundation.md). Implementation base: `7075312`, branch `feat/wordpress-starter`. This records a **foundation candidate**, not validation of a completed second business website.

## Scope and versions

Only `starters/wordpress/` plus this report and plan progress are in scope. Core Page/Post, a minimal PHP theme, isolated local Compose, backup/recovery instructions, neutral planning and three DRAFT methods. No Product CPT, domain plugin, importer, RFQ, SMTP, production deployment or global skill installation.

Actual Windows host tests: Docker Engine 29.6.2, Compose 5.3.1, Python 3.12, WordPress 7.1.1, PHP 8.3.33, WP-CLI 2.12.0, MariaDB 11.4.12. WordPress version choice was checked against its [7.1.1 release documentation](https://wordpress.org/documentation/wordpress-version/version-7-1-1/). Images remain version tags; these checks are not a vulnerability scan or cross-platform certification.

## Runtime and recovery evidence

| Environment | URL | Distinct data volumes | Role |
|---|---|---|---|
| starterlab-a | http://127.0.0.1:18081 | starterlab-a_database / starterlab-a_wordpress | Authoring and source backup |
| starterlab-b | http://127.0.0.1:18082 | starterlab-b_database / starterlab-b_wordpress | Tracked-files-only fresh copy |
| starterlab-c | http://127.0.0.1:18083 | starterlab-c_database / starterlab-c_wordpress | Tracked-files-only recovery target |

Each copy owns its Compose directory/configuration labels. B refuses A's project identity even with `--resume`; a fresh project using occupied port 18081 is refused without mutations. A resumes only from its original directory. The preflight unit suite passed 5 tests after its initial missing-implementation failure.

Core fixture passes on A and C: local-only guard, create/update a Page, save/restore actual revision content, delete fixtures, no Product dependency, intercepted `wp_mail` returns false. HTTP smoke checks Core output, one title, main/skip link, noindex, absence of business branding, actual missing-object 404.

Browser evidence on A: actually saved title, paragraph, generated image and link in the Core block editor. The public Page renders the saved values; image decoded at its expected 120px width; 1440/768/390 viewports have no horizontal overflow. Keyboard skip link reaches main; 390px screenshot visually inspected. This is a minimal neutral layout, not final visual design or an accessibility audit.

Backup at runtime code `06acbd1`: local ignored `backups/foundation-20260921/`, SQL 1,246,168 bytes, uploads archive 352 bytes. Media relative path `uploads/2026/09/fixture.png`. Source/generated/restored HTTP SHA256 all:
`1233ecb494c5e9937ef89c7eda7d56215628e0519a78df6661316aae0808db03`.

Actual commands followed [RECOVERY](../../starters/wordpress/docs/RECOVERY.md): container DB export and tar, compose cp, import into C's newly initialized volumes, serialized-aware search-replace dry-run then execution (8 replacements, GUID skipped), trusted uploads archive extraction and permissions. C `home`/`siteurl` both report port 18083. Page 13 restored as published with its title/body/link/image; a subsequent **browser editor save** changed C's title to “Recovered and edited independently”. A retained “Starter verification page revised”. Both media HTTP hashes still match. Core fixture and HTTP smoke pass after recovery.

Observed upstream CLI warnings: initial `core install` emitted an undefined `HTTP_HOST` warning; DB tooling printed its SSL verification warning on the local Docker network; CLI hard rewrite flush warned about .htaccess configuration. All commands succeeded, actual clean permalinks/editor/restore passed. These are recorded, not suppressed or treated as production-readiness evidence.

Original TiO₂ containers remain `653d527a9e04` and `4921069ffdc4`, on original `tio2-wordpress_*` volumes and port 8080. No original website database writes were performed.

## Planning and Skill observations

Neutral planning has only header rows in SITE_MAP/SEO_MAP, unconfirmed business inputs, and one current status per Page Spec. Markdown links and exact CSV headers checked. Project rules distinguish Theme presentation, optional domain plugins and runtime data without precreating a business model.

Three SKILL.md files pass the skill-creator validator under Python UTF-8 mode. The host's default GBK decoding initially failed on a Unicode arrow; rerunning with `PYTHONUTF8=1` validated all three. No user/global config was changed.

Fresh read-only Codex CLI sessions in copied project B used the desktop-bundled CLI **0.155.0-alpha.9.2**, model gpt-6-astra. The PATH CLI 0.142.0 could not use that model and was not used for the result. The successful session identified all three `wordpress-*` skills from its provided catalog before reading their files, then explicitly loaded them. Filesystem existence alone was not the discovery evidence.

| Probe | Observed result | Limit |
|---|---|---|
| No candidate Skills baseline | Already distinguished equipment CPT/typed fields from service Pages; preserved editor changes and requested real outcome evidence | No demonstrated broad capability improvement |
| Explicit three-Skill scenarios | Correctly differentiated equipment and service models; named ownership plus identity, no silent synchronization, actual revision/legacy missing-field checks, plugin-unavailable behavior and real form outcomes | Reasoning only; no domain implementation in this foundation |
| Separate implicit link-only scenario | Loaded direct-build and runtime-verification, did not load content-modeling; chose saved href/render/destination checks without full research/import/revision suite | One observation; not deterministic automatic routing |
| Author applied direct-build/runtime methods to actual fixture | C Page 13 link changed in editor from homepage to homepage `#main-content`; public href checked, then original body restored through editor save and rechecked | One scoped local reversible exercise, not proof across future projects |

All three remain **DRAFT**. Baseline answers were already good; this is discovery/scope validation, not a proven RED→GREEN behavioral improvement. No skills installed globally, no custom agents introduced, no messages sent externally. Full second-site field modeling and recovery need later real-project trials.

## Final validation status

B fresh-copy Core fixture, HTTP smoke and 5 preflight unit tests pass. PHP lint passes all 9 PHP files across Theme, mu-plugin, initialization and fixture. A/C browser and backup evidence above remains applicable because subsequent changes only added documentation/Skills and checkout line-ending attributes.

Initial packaging scan: 32 tracked candidate files; no `.env`, backups, uploads or caches. A separate clean Git clone of B contained all 32 files, shell scripts retained LF on Windows, and all 5 preflight tests passed there. The only old-site strings occur in the HTTP test's **forbidden-output assertions**, not runtime dependencies. B/C installed from tracked runtime files; subsequent planning/Skill additions do not change that runtime. All starter Markdown links resolve and the root planning-integrity check still passes (59 specs, 36 locally accepted). Final package adds one checker-regression test file (33 files total).

The original checkout's pre-existing untracked `docs/build-briefs/COMPLETE-SITE-PLAN.md` remains untracked and untouched. `main` remains `f624280`; this task has not merged, pushed or deployed.

## Independent review and fixes

One fresh-context gpt-6-astra reviewer inspected `7075312..b5b0b62` read-only, checked design/plan/evidence and independently ran the 5 preflight tests. It found no demonstrated data-loss, cross-copy adoption or business-binding defect. Its verdict was **with fixes**, not an unconditional approval of the later fix commit; fixes were verified by the author rather than a second review.

1. **Important: missing saved-Page HTTP regression.** The original HTTP smoke only checked homepage shell; it would not detect omitted page content. Added required known Page path, expected H1 and body inputs. Four regression cases failed before implementation; after the fix they pass (entity/inline-markup handling, missing H1, missing body, script text not substituting for body). Combined unit suite: **9/9**. The fixed checker passes against actual saved Pages on A, B and C, including the recovered/independently edited Page. This is additional coverage, not a claim the original browser evidence failed.
2. **Design/plan conflict: status vocabulary.** Reviewer classified this as minor; executor treated it as a concrete handoff ambiguity to resolve while reconciling approved documents. The design's `PLANNED / READY / BUILDING / REVIEW / ACCEPTED / RELEASED` is authoritative. README and WORKFLOW now use it; action labels remain PLAN/READY/BUILD/REVIEW/RELEASE. ACCEPTED does not authorize publication. The plan's wording is reconciled accordingly; no runtime behavior changes.

No other findings remain deferred. Review exclusions were explicitly considered:

- Production, SMTP, forms and domain models remain outside this foundation because those are separately authorized future work; the candidate does not prove a complete customer conversion path.
- General Skill efficacy/deterministic routing remain unproven; keep DRAFT and validate on the next real site rather than infer effectiveness from one probe.
- Other platforms and current image vulnerability status were not independently established; Windows behavior and actual image versions are the supported evidence, not a security certification.
- Reviewer did not repeat mutating editor/restore/isolation experiments; it reviewed the implementation and recorded author evidence. Those runtime experiments are author-verified, not independently repeated.
- Complete accessibility/performance remains unverified; responsive geometry, keyboard skip/focus and readable layout are narrower observations.

Implementation decisions: one existing plan/ledger instead of extra per-task brief files (tradeoff: less separate handoff traceability); DRAFT despite a good no-Skill baseline (effectiveness remains unproven); status conflict resolved to the approved design (future integrations must use that vocabulary).

Final rerun after fixes: 33-file clean Git checkout excludes secrets/backups and preserves LF shell files; all local Markdown links resolve; **9/9 Python tests**, actual B saved-Page HTTP check, A Core fixture, **9/9 PHP lint**, A saved-Page HTTP check and root planning-integrity check pass. Earlier final-checker runs also passed on C's recovered Page. Re-running A's initialization explicitly preserved the installation; its edited Page title and rendered body remained intact. Real HTTP checks on all three environments confirmed `X-Robots-Tag: noindex` and sitemap 404. No runtime code was changed by the review fixes.

**Conclusion: the minimal technical candidate passes its scoped foundation checks after the fixes.** It is ready for review/integration as a candidate, not a mature universal template. Next phases remain new-site preparation methods, an approved business model with a real local conversion path, then a real second-site trial. No automatic merge, push, release or email work is included.
