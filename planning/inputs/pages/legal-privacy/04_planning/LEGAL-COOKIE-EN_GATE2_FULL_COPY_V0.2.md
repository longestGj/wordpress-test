# Cookie Policy — Gate 2 Full Copy V0.2

## 0. Control

| Field | Value |
|---|---|
| Page ID | `LEGAL-COOKIE-EN` |
| URL | `/cookie-policy/` |
| Date | 2026-09-02 |
| Status | `USER_APPROVED / GATE_2_TARGETED_REVISION_CLOSED / CURRENT_AUTHORITY / PRODUCTION_INVENTORY_REQUIRED_BEFORE_RELEASE` |
| Upstream approval | `LEGAL-PRIVACY-G2-SKELETON-PCR-01 = USER_APPROVED / CLOSED`; full-copy V0.1 approved; targeted Buyer Clean revision approved 2026-09-02 |
| Publication | Not authorised; production Cookie/Local Storage capture remains required |

The first buyer-visible version below is for the currently verified state: no active optional Analytics or advertising technology. Section 2 contains the approved replacement copy to use only after Google measurement is implemented and verified. The two versions must never render together.

---

## 1. Buyer-visible copy — current no-Analytics state

# Cookie Policy

**Last updated: 2 September 2026**

This Cookie Policy explains how TiO2 Malaysia uses Cookies and similar technologies, which technologies are active, and how you can manage available preferences.

For information about our broader handling of personal data, read our [Privacy Policy](/privacy-policy/).

Actions: **MANAGE COOKIE SETTINGS** · **READ OUR PRIVACY POLICY**

## Cookies and Similar Technologies

Cookies are small text records that a website may ask a browser to store. Websites can also use other browser technologies, including Local Storage, to retain limited information between pages or visits.

TiO2 Malaysia uses the term “Cookies and similar technologies” to cover these mechanisms collectively. An item in Local Storage is not technically a Cookie, and our inventory identifies the relevant type.

## Categories We Use

### Necessary

Necessary technologies support functions such as site delivery, security and remembering a privacy choice. They are not used to grant optional Analytics, advertising storage or advertising personalisation.

### Analytics

Analytics technologies help a website understand aggregate use. **No optional Analytics technology is active in the currently verified TiO2 Malaysia configuration.** Google Analytics, Google Tag Manager and Vercel Web Analytics are not treated as active simply because they are planned or supported by the architecture.

### Advertising and personalisation

No advertising or advertising-personalisation category is active in the currently verified configuration. Google Ads is not enabled. Advertising storage, advertising user data and advertising personalisation remain denied under the approved consent direction.

## Current Cookie and Storage Inventory

The currently approved consent implementation may create the following first-party storage record after the preference interface is introduced:

| Name | Provider | Type | Purpose | Duration | Category |
|---|---|---|---|---|---|
| `tio2_my_consent_v1` | TiO2 Malaysia | Local Storage | Remembers the applicable privacy preference and consent-text version for this site only | Until browser storage is cleared or the consent version is replaced | Necessary |

At the date shown above, no optional Analytics or advertising Cookie is active. If the technologies used by this site change, we will update this inventory and the date at the top of this policy.

## How Advanced Consent Mode Works

Google Advanced Consent Mode is an approved implementation direction but Google measurement is not currently active. No Google measurement transmission should be inferred from this direction alone.

If Google measurement is enabled later, we will update this policy and the consent interface before activation. The implementation will set `analytics_storage`, `ad_storage`, `ad_user_data` and `ad_personalization` to `denied` before measurement commands. The updated disclosure will explain that consent-aware Google tags may send limited cookieless signals while storage is denied.

Advertising personalisation will not be granted under the currently approved configuration.

## Manage or Withdraw Your Choice

Use **Cookie Settings** in the Footer to review the current status. While no optional Analytics technology is active, the interface will not ask you to accept a non-existent Analytics service.

If optional Analytics is introduced later, it will be off by default. You will be able to choose **Accept analytics** or **Necessary only**, and you will be able to withdraw an Analytics choice through the same Footer control. Withdrawing a choice will update the applicable consent state for future activity; it does not undo processing that was lawful before withdrawal.

## Browser Controls

Most browsers allow you to view, delete or block Cookies and site data. Blocking all browser storage may prevent a preference from being remembered and may affect functions that genuinely require storage. Clearing site data may cause the preference interface to appear again.

Browser settings operate separately from the controls provided by TiO2 Malaysia. Refer to your browser’s help information for instructions specific to your browser and device.

## Changes and Contact

We update this Cookie Policy when the active technology, purpose, provider or duration changes. The date at the top identifies the latest version.

For questions about Cookies, browser storage or privacy choices, email **info@tio2malaysia.com**.

Actions: **MANAGE COOKIE SETTINGS** · **READ OUR PRIVACY POLICY**

---

## 2. Conditional buyer-visible replacement — only when Google Analytics measurement is active

This section is an activation-controlled replacement for the relevant paragraphs in Sections 1. It must not render until Gate 8/9 proves that the final tag inventory, denied defaults, consent updates and production network behaviour match the copy.

### Replacement Hero sentence

This Cookie Policy explains how TiO2 Malaysia uses Cookies and similar technologies, which technologies are active, and how you can manage optional Analytics preferences.

### Replacement Analytics category

Analytics helps us understand aggregate website use, such as which pages are visited and how the site performs. Google Analytics is active only under the consent behaviour described below. Analytics storage is off by default and is enabled only after you choose **Accept analytics**.

### Replacement Advanced Consent Mode section

TiO2 Malaysia uses Google Advanced Consent Mode. Before you make a choice, `analytics_storage`, `ad_storage`, `ad_user_data` and `ad_personalization` are set to `denied`.

When consent is denied, consent-aware Google tags may load and send limited cookieless signals. These signals can include consent status and limited technical or event information, but they do not use Analytics Cookies while `analytics_storage` remains denied. If you choose **Accept analytics**, `analytics_storage` changes to `granted`; the three advertising-related states remain `denied`.

| Choice | `analytics_storage` | `ad_storage` | `ad_user_data` | `ad_personalization` |
|---|---|---|---|---|
| Before a choice | denied | denied | denied | denied |
| Necessary only | denied | denied | denied | denied |
| Accept analytics | granted | denied | denied | denied |
| Withdraw Analytics | denied | denied | denied | denied |

You can change your choice at any time through **Cookie Settings** in the Footer.

### Required Analytics inventory rows

Do not publish a generic list. Gate 8/9 must insert the exact production-observed Google Cookie and request inventory, including the actual names, domains, purposes, durations and consent conditions. Typical names are not sufficient evidence.

---

## 3. Shared Cookie Settings copy

### 3.1 When no optional Analytics is active

**Title:** Cookie settings

**Body:** No optional Analytics or advertising technology is currently active on this site. Necessary functions may use browser storage to operate the site and remember an available privacy setting.

**Actions:** Close · Read Cookie Policy

Do not show a first-visit consent request in this state.

### 3.2 When verified Google Analytics measurement is active

**Title:** Analytics preferences

**Body:** Optional analytics helps us understand how this website is used. If you choose Necessary only, optional storage remains off, but limited cookieless measurement signals may still be sent to Google.

**Actions:** Accept analytics · Necessary only · Cookie Policy

Detailed setting:

- **Necessary — Always active.** Supports site operation, security and remembering this site’s privacy choice.
- **Analytics — Off by default.** Allows aggregate Google Analytics measurement after you accept it. Advertising storage and personalisation remain off.

When reopening an existing choice:

**Actions:** Save preferences · Accept analytics · Necessary only · Close

Closing without saving must preserve the existing choice. Withdrawal must apply `denied` immediately and return focus correctly to the Footer button when the interface closes.

---

## 4. Internal release controls — do not render

- `tio2_my_consent_v1` is the Gate 2 proposed key. Gate 7 may change the exact implementation name only through a documented contract update; the published inventory must match the final name exactly.
- Verify the key, value shape, consent version, duration, blocked-storage behaviour and cross-`site_scope` isolation.
- Run clean-browser production capture at denied default, Necessary only, Accept analytics and withdrawal.
- Any unlisted storage item, unexpected Google request, advertising identifier or active Vercel Web Analytics request blocks release.
- Do not display Analytics controls when no optional Analytics is active.
- Do not display Advertising or Personalisation controls under the current approved stack.

## 5. SEO / GEO / Schema contract

| Field | Value |
|---|---|
| Title | `Cookie Policy | TiO2 Malaysia` |
| Meta description | `Learn which Cookies and similar technologies TiO2 Malaysia uses and how to review or change available Analytics preferences.` |
| Canonical | `https://tio2malaysia.com/cookie-policy/` |
| Hreflang | Not applicable |
| Primary keyword | `NO_PRIMARY_KEYWORD` |
| Schema | `WebPage` + `BreadcrumbList`; `inLanguage=en` |
| Header current item | None |
| Robots direction | `index,follow`, subject to Gate 10 review |



## 6. V0.2 targeted change record

- User approval date/source: 2026-09-02 / current project-control conversation.
- Replaced the buyer-visible production-testing sentence with a public-facing inventory-update statement.
- No change to facts, URL, Canonical, module order, CTA, consent-state matrix, inventory evidence boundary or release controls.
- `LEGAL-COOKIE-EN_GATE2_FULL_COPY_V0.1.md` remains preserved as the historical approved baseline; V0.2 is the current Gate 2 authority.

