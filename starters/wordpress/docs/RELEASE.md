# Release boundary

The included Compose file is a local development environment, not a production deployment recipe. A localhost test passing does not authorize publication.

Before requesting final release approval, prepare a reviewable site and establish:

- Actual domain, HTTPS, hosting, access controls, supported runtime and operational owner.
- Approved copy/facts/assets, redirects, canonicals, robots and sitemap appropriate to the actual public site.
- Working visitor actions and real receiving/handling process. If email remains deferred, do not label email delivery as tested or successful.
- Applicable privacy/consent requirements, data retention and handling choices confirmed for the site.
- Matching code, database and uploads backup with a verified recovery path; rollback implications for any schema changes.
- Review findings resolved or explicitly accepted; final release scope and remaining limitations visible to the approver.

Only an explicit user publication decision authorizes production changes. Local safety controls must be handled deliberately in a separate production configuration; never silently remove noindex or enable mail to make a local test look complete.

After an authorized deployment, smoke-test homepage, representative content, navigation, real conversion path, mobile, public index settings, missing-page status and sitemap. Record actual results in the release PR or review result. If rollback is needed, consider database/media compatibility as well as code.
