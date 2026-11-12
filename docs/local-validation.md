# Local baseline evidence — issue #2

Date: 12 November 2026. Baseline: `15067cf`.
Architecture dependency: owner-approved PHP/MySQL, same-origin, incremental
direction in roadmap #1. No ADRs or repository AGENTS.md/SKILL.md existed at baseline.
No existing PRs were returned by the repository PR search before implementation.

## Results in the implementation environment

- Docker CLI/Desktop: not installed/on PATH; standard installation directory absent.
- WSL: listing distributions denied by the host (`WSL/E_ACCESSDENIED`).
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
- Container build/start, authenticated DB readiness, actual schema execution,
  email delivery/capture, photo writes and recreation/persistence: **not run**.
- Fresh-checkout execution of the documented Docker commands: **not run**.
- Browser/device parity journeys: **not run**.

The temporary Compose executable validates configuration only; no Docker Engine
is available. The smoke script is provided for a Docker-capable reviewer. Its existence is
not runtime acceptance evidence. Record command results here before treating
runtime-dependent acceptance criteria as verified.

## Pre-existing failures identified by source inspection

These are observations from the original code, not reproduced runtime results:

| Area | Observation | Follow-up scope |
| --- | --- | --- |
| Camera | Buttons submit the form while an XHR also posts; navigation may cancel upload | #10 |
| Image input | Overlay path uses PNG decoding even for direct JPEG payloads; validation and limits are incomplete | #6 |
| Sessions/ownership | Redirects do not consistently terminate execution; deletion ownership is not enforced | #3, #5 |
| Activation/recovery | Activation and recovery share a reusable key; reset POST redirects when query parameters are absent | #4 |
| Invalid login | Missing user rows are indexed before password verification; may emit warnings or fail on newer PHP | Later identity work; characterize on chosen runtime |
| Data | No relational/uniqueness constraints beyond primary keys; initialization does not retrofit them | #7 |

## Review evidence to collect

Use README commands on new disposable volumes, then record:

| Check | Current status |
| --- | --- |
| Build and localhost app/Mailpit access | Pending Docker |
| GD PNG/JPEG and PDO MySQL | Pending Docker |
| Initialization twice, including existing records | Pending Docker |
| Gallery, registration, activation mail/link | Pending Docker |
| Recovery mail/link; password change behavior | Pending Docker/browser |
| Camera PNG upload/overlay and saved-photo GET | Pending Docker |
| Real browser capture, file input, all overlays | Pending browser |
| Likes/comments, notification mail, profile, deletion, logout | Pending browser |
| DB record and photo bytes after container recreation | Pending Docker |

Do not publish real account data, credentials, tokens or private media as evidence.
