# Local baseline evidence — issue #2

Date: 12 November 2026. Baseline: `15067cf`.
Architecture dependency: owner-approved PHP/MySQL, same-origin, incremental
direction in roadmap #1. No ADRs or repository AGENTS.md/SKILL.md existed at baseline.
No existing PRs were returned by the repository PR search before implementation.

## Initial static checks

- Docker was initially unavailable; the owner subsequently installed Docker Desktop.
- Host PHP: initially absent. Downloaded a temporary official PHP 8.2.35 Windows
  CLI and verified its SHA-256 against official release metadata.
- PHP syntax: **passed**, all 23 PHP files, including the new smoke script.
- Entrypoint shell syntax: **passed**, `bash -n docker/entrypoint.sh`.
- Compose configuration: **passed**, using a temporary official Compose v2.40.3
  executable verified against its release SHA-256. Parsed `config --format json`
  with synthetic environment values; checked blank-credential rejection, the
  three services, localhost bindings, absence of DB host ports, authenticated
  readiness dependency, web health check and named volume destinations.
- Custom `WEB_PORT`/`APP_URL`/`MAIL_PORT` interpolation: **passed**.
- Executed PHP configuration: **passed** default URL, custom URL with trailing
  slash normalization, and environment-supplied database DSN/user/password.
- Missing-driver database failure: **passed**, nonzero CLI exit with a generic
  diagnostic and no password disclosure. This does not prove MySQL connectivity.
- `.env` and upload ignore rules; final diff whitespace check: **passed**.

## Docker and browser validation after installation

Docker Desktop 4.98.0, Engine 29.9.2, Compose v5.6.1, Linux/amd64 backend.
Validated application commit `78a5d4e` from a fresh `git archive HEAD` source
snapshot in a temporary directory. Copied `.env.example` and generated distinct
random local passwords without printing them. Project `camagru-issue2-check`
used newly created, separate database/photo volumes, with no existing user data.
The final follow-up changes only documentation.

Executed the README sequence with `-p camagru-issue2-check` added:

```sh
docker compose up --build -d --wait
docker compose exec web apache2ctl -t
docker compose exec web sh -c "find . -name '*.php' -print0 | xargs -0 -n1 php -l"
docker compose exec --user www-data web test -w /var/www/html/uploads
docker compose exec web php tests/smoke.php
# Browser parity checks against localhost, then repeat smoke with populated tables.
docker compose exec web php tests/smoke.php
docker compose up -d --force-recreate --wait
docker compose exec web php tests/smoke.php --verify-persistence
docker compose down
docker compose up -d --wait
docker compose exec web php tests/smoke.php --verify-persistence
```

Build and initial startup **passed**. All three services became healthy. The
container's Apache configuration and all 23 PHP files passed syntax checks.
The README lint command originally lost its glob quoting in Windows PowerShell;
the corrected quoting shown above was executed successfully.

The smoke script **passed** on new and populated volumes: actual GD PNG/JPEG and
PDO MySQL, schema initialization twice with unchanged counts in all four tables,
gallery, captcha rendering, registration, captured activation/recovery links,
activation state, active login, camera page, synthetic PNG upload with Batman
overlay, saved-photo HTTP response and database record. Apache's user can write
to the photo directory. Both persistence checks **passed**, preserving the
database record, photo volume marker and exact saved-photo SHA-256 after forced
container recreation and after `down`/`up` without deleting volumes.

Additional host HTTP checks **passed** for the app and blocked `.env`, config,
Docker configuration, test scripts and private smoke-marker paths. A synthetic
`cle` query probe was absent from captured web logs, confirming access-log query
redaction.

Headless installed Chrome, driven by temporary Playwright tooling outside the
repository, used only synthetic accounts/images and a simulated camera. Browser
registration/activation/login, native camera capture with all three overlays,
local PNG input with all three overlays, same-origin upload requests, profile
display/settings, likes/comments with notification capture, owner photo deletion
(file returns 404), logout redirect, recovery password change followed by login
with the new password, and Mailpit's browser UI all **passed**. No changes to app
event handlers were made for these checks. The file-input switch required keyboard
activation because the existing footer intercepted mouse clicks at the default
1280x720 viewport.

Physical webcam/device coverage and exhaustive parity/accessibility testing remain
outstanding. Successful synthetic-camera checks do not resolve the existing
form/XHR race or establish authorization/security correctness. The destructive
reset command was deliberately not needed or executed.
Validation containers were stopped and removed afterward with `docker compose
down`; the isolated synthetic database/photo volumes were retained.

## Pre-existing observations and limitations

The table distinguishes runtime findings from observations in the original source:

| Area | Observation/evidence | Follow-up scope |
| --- | --- | --- |
| Camera layout | Reproduced: fixed footer intercepts mouse clicks on file-input toggle at 1280x720; keyboard activation works | #14 |
| Sessions | Reproduced: login/registration emit `PHP Request Shutdown: Skipping numeric key` warnings; source assigns PDO's default numeric/associative row directly to the session | Later identity work |
| Camera requests | Source: buttons submit the form while an XHR also posts; potential cancellation race. Not reproduced in successful Chrome checks | #10 |
| Image input | Source: overlay path uses PNG decoding even for direct JPEG payloads; validation and limits are incomplete | #6 |
| Sessions/ownership | Source: redirects do not consistently terminate execution; deletion ownership is not enforced | #3, #5 |
| Activation/recovery | Source: activation and recovery share a reusable key. Actual synthetic recovery/password change passed | #4 |
| Invalid login | Source: missing user rows are indexed before password verification; invalid-account behavior not exercised | Later identity work |
| Data | Source: no relational/uniqueness constraints beyond primary keys; initialization does not retrofit them | #7 |

## Acceptance evidence summary

| Check | Result |
| --- | --- |
| Build and localhost app/Mailpit access | Passed from fresh source and new volumes |
| GD PNG/JPEG and PDO MySQL | Passed in container |
| Initialization twice, including existing records | Passed on new and populated volumes |
| Gallery, registration, activation mail/link | Passed HTTP and browser |
| Recovery mail/link; password change/new login | Passed HTTP and browser |
| Camera PNG upload/overlay and saved-photo GET | Passed |
| Browser camera, file input, all overlays | Passed with simulated camera and keyboard toggle; physical devices untested |
| Likes/comments, notification mail, profile, deletion, logout | Passed synthetic browser checks; not a security assessment |
| DB record and photo bytes after recreation and down/up | Passed |

Do not publish real account data, credentials, tokens or private media as evidence.
