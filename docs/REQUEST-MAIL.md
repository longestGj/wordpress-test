# Request email notifications

Quote, document and sample requests are saved as private WordPress records before a staff notification is attempted. The public confirmation confirms the saved record only. Mail status is visible only under **Business requests** in WordPress admin. An administrator can retry a failed or unconfigured notification; a notification already accepted by the mail transport is not sent again.

## Local Gmail configuration

Set these three variables in the ignored project `.env` file. Use the same Gmail address for `TIO2_MAIL_USER` and `TIO2_MAIL_TO` when the mailbox both sends and receives. Do not use the normal Google Account password or commit the app password.

```dotenv
TIO2_MAIL_USER=sender@gmail.com
TIO2_MAIL_TO=recipient@gmail.com
TIO2_MAIL_APP_PASSWORD=
```

The last value must be a Gmail app password created for this WordPress installation. Google requires 2-Step Verification for app passwords; some account security configurations do not offer them. See [Google Account Help](https://support.google.com/accounts/answer/185833?hl=en). WordPress uses Gmail SMTP over TLS on port 587. After editing `.env`, recreate the local WordPress container so it receives the new environment:

```powershell
docker compose up -d --force-recreate wordpress
```

Before enabling notification on an existing local database, update the two owned Privacy Policy Pages with `docker compose run --rm cli eval-file /workspace/scripts/update-request-email-policy.php`. This targeted update preserves other editor text and refuses an edited request paragraph for manual review.

The `accepted` status means `wp_mail()` returned success from its transport. Confirm arrival in the mailbox with a synthetic request; WordPress cannot prove Inbox placement. A failed notification leaves the saved request intact. The local test `tests/request-mail-runtime.php` intercepts `wp_mail()` and sends nothing, creates synthetic records and removes them.

An interrupted send or failed status write can leave an uncertain outcome. The admin retry then requires an explicit mailbox check; it never automatically resends that uncertain message.

If the local network reaches Gmail only over IPv6, set `TIO2_MAIL_IPV6=1` and `TIO2_NETWORK_IPV6=true` in `.env`. Recreate the Compose network with `docker compose down` followed by `docker compose up -d`; retain all volumes (never use `-v`). Gmail addresses are resolved at send time and TLS still verifies the `smtp.gmail.com` certificate. `scripts/check-gmail-transport.php` verifies the TLS connection without authenticating or sending mail.

Production mail credentials and deployment are separate from this local setup.

## Local verification — 2026-09-23

Gmail authentication over verified TLS passed. All three public request forms saved their synthetic records and Gmail accepted each notification exactly once. Duplicate submissions reused the receipt; invalid input retained its values. Test records and idempotency claims were removed. Both owned privacy Pages were updated, and a second migration made no changes. The user confirmed receipt of all three notifications (quote #332, document #333, sample #334). Production is not deployed.
