# Legal / Privacy No-Terms TITAN Pattern Decision V1.0

## 0. Control

| Field | Value |
|---|---|
| Project | TiO2 Malaysia / `site_scope=tio2-my` |
| Decision date | 2026-09-02 |
| Status | `USER_APPROVED / ACTIVE_ARCHITECTURE_OVERRIDE` |
| Decision source | User approved the Controller's explicit interpretation of following the TITAN pattern without a standalone Terms page |
| Supersedes | The `LEGAL-TERMS-EN` record, 58-page total, Terms Footer link, Terms Brief requirement and governing-law/dispute open item in `LEGAL_PRIVACY_ARCHITECTURE_CHANGE_AND_USER_DECISIONS_V1.0.md` |
| Preserves | Privacy EN, Privacy BM, Cookie Policy, Cookie Settings, Advanced Consent Mode and authorized local TITAN consent-code reuse |
| Implementation authority | None in `D:\23MySec`; implementation remains subject to a separately authorized Gate 7/8 task in `D:\16Wordpress_nextjs` |

This is the current authority where earlier legal/privacy records conflict with it. Earlier records remain available for decision history and are not rewritten as if their original approval never occurred.

## 1. Approved architecture change

The user approved all of the following on 2026-09-02:

1. Do not create a standalone TiO2 Malaysia `Terms of Use` page.
2. Remove planned page `LEGAL-TERMS-EN` and route `/terms-of-use/` from the effective architecture.
3. Do not show a `Terms of Use` link in the Global Footer.
4. Preserve these three legal/privacy pages:
   - `LEGAL-PRIV-EN` — `/privacy-policy/`;
   - `LEGAL-PRIV-MS` — `/ms/privacy-policy/`;
   - `LEGAL-COOKIE-EN` — `/cookie-policy/`.
5. Preserve `Cookie Settings` as a shared functional consent control, not an indexable page.
6. Preserve Advanced Consent Mode and the enhanced custom Consent Manager based on the user-authorized local TITAN code.
7. Close the previous Terms governing-law and dispute-resolution decision because no Terms page will be drafted under the approved architecture.

Effective approved page count:

`54 + 3 = 57 pages`

## 2. TITAN pattern boundary

The approved phrase “follow the TITAN pattern” means the specific legal/consent structure presented to and approved by the user:

- no standalone Terms page or Footer Terms link;
- Privacy remains available;
- TiO2 Malaysia additionally keeps its already approved Cookie Policy;
- Analytics/Cookie Settings remains available to reopen preferences;
- the consent implementation may reuse and adapt the user's local TITAN code under the separate Advanced Consent decision.

It does not authorize copying TITAN company data, domain, policy copy, production identifiers, tracking IDs, form credentials or product facts.

## 3. Impact analysis

| Area | Approved effect |
|---|---|
| Page architecture | 58 becomes 57; one planned `NO_PRIMARY_KEYWORD` page is removed |
| SEO | No commercial keyword ownership changes; `/terms-of-use/` must not enter sitemap, Canonical, hreflang or index inventory |
| Header | No change |
| Footer | Show Privacy Policy, Cookie Policy and Cookie Settings; omit Terms of Use |
| Forms | Link to the applicable Privacy Policy; do not depend on a nonexistent Terms route |
| Consent | Advanced Mode and its disclosure/verification contract remain active |
| Content | No governing-law, jurisdiction or dispute clause is drafted as a standalone Terms policy |
| Data/CMS | Do not create or query a `LEGAL-TERMS-EN` record for `site_scope=tio2-my` |
| Routes/build | `/terms-of-use/` is not an approved route and must not be included as a release dependency |
| Page briefs | Three legal/privacy Briefs are required instead of four |

## 4. Risks and limits

- Removing a general website Terms page does not remove obligations created by quotations, purchase orders, sales contracts, Privacy/Cookie rules or applicable law.
- Commercial terms presented in RFQs, quotations or contracts remain outside this website-page decision and must not be invented from the absence of a website Terms page.
- If future business, legal or platform requirements make Terms necessary, reintroducing the page requires a controlled page-architecture change and user approval.
- Privacy, Cookie and Consent disclosures must still match production-equivalent data flows and storage behaviour.

## 5. Synchronization and rollback contract

Required successor synchronization:

1. consolidated Page Registry must contain 57 records and omit `LEGAL-TERMS-EN`;
2. page-keyword master must add only the three retained `NO_PRIMARY_KEYWORD` pages;
3. successor Global Footer specification must omit Terms and include Privacy, Cookie Policy and Cookie Settings;
4. affected conversion Brief successors must remove Terms as a release dependency;
5. sitemap, route, Canonical, hreflang and QA inventories must not expect `/terms-of-use/`;
6. Legal/Privacy Playbook and Gate 7 handoff must cite this decision as the current override.

Rollback is straightforward but not automatic: a later user-approved change would restore a Terms page as a new controlled architecture version, restore the Footer link and dependencies, and change the effective page count from 57 to 58.

## 6. Authority boundary

This approval changes planning and governance only. It does not authorize code, CMS changes, route deletion in an implemented environment, deployment, DNS, publication or indexing from `D:\23MySec`.
