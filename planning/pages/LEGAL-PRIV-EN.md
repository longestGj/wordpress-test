# LEGAL-PRIV-EN — Privacy Policy / Privacy Notice

Status: ACCEPTED

URL: `/privacy-policy/`
Family: Legal / privacy page · EN

## 页面职责

Explain verified personal-data processing, recipients, retention, rights, transfers and privacy contact routes.

## 内容与事实

- [Current content input](<../inputs/pages/legal-privacy/04_planning/LEGAL-PRIV-EN_GATE2_FULL_COPY_V0.2.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: NO_PRIMARY_KEYWORD
Title: Privacy Policy | TiO2Products
Meta: Learn how TiO2Products handles general business inquiry data, retention, necessary Cookies and privacy requests.
H1: Privacy Policy

Keyword boundary: Privacy owns legal transparency only and must not compete with commercial or informational landing pages.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/legal-privacy/04_planning/LEGAL_PRIVACY_GATE5_FULL_VISUAL_SPEC_V0.1.md>)

## 实现与验收

本页已在本地 WordPress 实现，运行态验收结果与待解决依赖记录于下方；不继承旧项目的开发或上线状态。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. The user confirmed review of the three policy Pages on 2026-09-23; production deployment and release checks remain separate. Runtime does not consult this specification or planning source hashes.

## WordPress implementation (2026-09-23)

Native page seed: `data/utility/LEGAL-PRIV-EN.json`. The prior Web3Forms disclosures were adapted to the actual WordPress Contact receiver and temporary session state. The GA4 update discloses optional measurement after consent. The policy now identifies Oracle Cloud Infrastructure in US West (Phoenix) for public WordPress/MariaDB hosting and the possible United States processing of submitted data. The original source is retained as an approved planning input, not rendered verbatim where its old data flow conflicts with this build.

Local integration found the untouched WordPress Core starter privacy draft at `/privacy-policy/`. `scripts/relocate-core-privacy-draft.php` strictly verifies and preserves that unpublished draft under a separate slug before this owned page is imported. The import then points WordPress's privacy-page option to this owned English page, without adopting the starter draft.

Local review 2026-09-23: `/privacy-policy/` returns HTTP 200 with the expected SEO and one H1. The WordPress starter draft remains unpublished; the owned page is selected as the Core privacy page. Browser review at 1440/768/390 and screenshots are in `.local/utility-http/`. Fresh browser inspection found no Cookie or Local Storage item on this page and no non-local requests. Local review is not production publication approval.

Domain audit 2026-09-23: the privacy contact address is updated to `info@tio2products.com` in the seed and main local WordPress Page; a repeat exact migration changed zero Pages.

GA4 review 2026-09-23: the seed discloses optional GA4 page and usage measurement only after explicit consent, the stored choice, and withdrawal. The policy migration updated the three owned main local policy Pages and changed zero on repeat; Contact/Gmail disclosures and other editor text were preserved. Browser tests confirmed no Google request before consent or after rejection and a sanitized page URL after opt-in. Production content is unchanged.

Integrated local review 2026-09-23: main WordPress SEO, ownership, Core privacy selection, links, 1440/768/390 layout, keyboard focus and local storage checks passed. Contact/Gmail and GA4 passages are present in the rendered Page, with no legacy contact email. The user confirmed that the English, Malay and Cookie policy reviews passed. Production-host metadata supplied by the primary task identified Oracle `phx`; [Oracle's region list](https://docs.oracle.com/en-us/iaas/Content/General/Concepts/regions.htm) maps that key to US West (Phoenix). `scripts/update-hosting-policy.php` updated the two owned local privacy Pages, preserved unrelated editor content, and changed zero Pages on repeat. The rendered policy now explains possible United States processing without inventing a log or email retention period. Reciprocal `en`/`ms-MY` hreflang is active only while both owned privacy Pages are published. Local Page accepted; deployment and the real production smoke check still need separate release authorization.
