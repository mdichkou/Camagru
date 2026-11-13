# Camagru

Repository AI-assisted engineering uses the [approved workflows and skill catalog](docs/ai-engineering/README.md).
See the linked evaluations for executed evidence and pending human review.

Legacy PHP/MySQL photo capture and upload application with three overlays, a
public gallery, likes/comments and account/profile pages. This is the private
local baseline for the [approved incremental roadmap](https://github.com/mdichkou/Camagru/issues/1)
and [issue #2](https://github.com/mdichkou/Camagru/issues/2), not a production setup.

## Local quick start

Requires Docker Engine/Desktop with Linux containers and Docker Compose v2
(supports `depends_on.condition` and `up --wait`). No host PHP, Node, external
email account, production credentials or existing user/media data is needed.

1. Copy `.env.example` to `.env` (`cp .env.example .env`, or
   `Copy-Item .env.example .env` in PowerShell).
2. Fill `DB_PASSWORD` and `MYSQL_ROOT_PASSWORD` with distinct, random **local-only**
   passwords. Hexadecimal strings avoid Compose quoting/interpolation surprises.
   Leave the other values at their defaults for the first run. `.env` is ignored.
3. From the repository root:

   ```sh
   docker compose up --build
   ```

Open **http://localhost:8080** (registration), **http://localhost:8080/home.php**
(public gallery) and **http://localhost:8025** (Mailpit email inbox).
MySQL and SMTP have no host ports; HTTP ports bind only to `127.0.0.1`.
Keep the legacy app local; do not tunnel it or expose it publicly.

Activation and recovery messages go through PHP `mail()` → msmtp → Mailpit:1025.
Mailpit captures mail locally; no relay or external email delivery is configured.
Use synthetic addresses such as `tester@example.test`. Follow the links in the
inbox to exercise activation/recovery. If changing `WEB_PORT`, also change
`APP_URL` to the matching browser address (no trailing slash). Camera requests
use a relative same-origin URL. The supported installation is at the URL root.

## Runtime and initialization

The build uses `php:8.2.35-apache-bookworm` with GD (PNG/JPEG) and PDO MySQL;
Apache serves the existing PHP routes directly without rewriting or a framework.
This avoids adding a separate proxy/FPM service to the initial baseline.
MySQL is `mysql:8.0.45-oraclelinux9`; the catcher is `axllent/mailpit:v1.30.7`.
Image versions are explicit; Debian build packages follow Bookworm repositories
(builds are not bit-for-bit locked). See the upstream [PHP image](https://hub.docker.com/_/php),
[MySQL image](https://hub.docker.com/_/mysql), and [Mailpit Docker documentation](https://mailpit.axllent.org/docs/install/docker/).
Runtime compatibility still requires the checks below.

Compose waits for an authenticated MySQL query before starting the web service.
Every web start runs the existing four-table schema through `config/setup.php`.
It creates missing tables only; it does not drop, truncate, migrate or seed data.
The original column names (including `passowrd`) and table definitions are kept.
Initialization is CLI-only; repeat it explicitly with:

```sh
docker compose exec web php config/setup.php
```

Existing incompatible schemas are not repaired automatically. A failed startup
must be investigated, not resolved by silently deleting volumes.

## Operation and persistence

```sh
docker compose up --build -d --wait
docker compose ps
docker compose logs --tail=100 web db mailpit
docker compose stop
docker compose down
```

`stop` and `down` preserve the named `database` and `photos` volumes. Uploaded
photos are written to `/uploads/` in URLs and `/var/www/html/uploads` in the web
container, writable by Apache's `www-data`. They are separate from committed
historical `images/` samples, which are excluded from the build and never seeded.
Mailpit's inbox is temporary and clears on container recreation.
Source is copied into the image: rebuild after code changes.

**Destructive reset — deletes all local database records and saved photos:**

```sh
docker compose down --volumes
docker compose up --build
```

Use this only for disposable local data. Do not change the Compose project name
or directory when checking persistence; that selects different named volumes.
Changing `.env` passwords does not change users in an existing MySQL volume;
restore the original local values or deliberately reset disposable volumes.

## Smoke and parity checks

Run from a disposable clean checkout with newly created development volumes:

```sh
docker compose up --build -d --wait
docker compose exec web php -m
docker compose exec web sh -c "find . -name '*.php' -print0 | xargs -0 -n1 php -l"
docker compose exec web php tests/smoke.php
docker compose up -d --force-recreate --wait
docker compose exec web php tests/smoke.php --verify-persistence
```

The smoke script creates a synthetic account/photo and retains them. It checks
GD/PDO, gallery HTTP response, captcha PNG, registration and captured activation
mail, activation state, captured recovery mail and reset form, active login,
camera page, a synthetic PNG upload with an overlay, and served image bytes.
It repeats initialization twice and checks record counts. Persistence mode checks
the saved database record and photo hash after recreation. Run the smoke script
again on a populated volume to check initialization with existing records.
It does not print passwords, tokens or media. The captcha is read from this
synthetic client's local PHP session to avoid adding OCR or a test-only route.

Manual parity checklist (record actual results in [validation notes](docs/local-validation.md)):

- Public gallery and pagination; register, read activation mail, activate and log in.
- Request recovery mail, follow the link and try changing the password.
- Browser camera permissions/capture, local image selection and all three overlays.
- Photo display/deletion, likes/comments and notification mail with two synthetic accounts.
- Profile/account settings and logout.
- Repeat initialization; recreate containers and verify DB/photo persistence.

The HTTP smoke check directly posts a synthetic canvas PNG. It does not prove
the browser capture/upload flow, which has an existing form/XHR race.

## Troubleshooting and limitations

- Blank page/503: inspect `docker compose logs web db`; verify `.env` and MySQL
  health. PHP errors go to container logs, not browser responses.
- DB unhealthy on first start: allow initialization time; inspect DB logs and
  available disk space. Do not delete volumes as an automatic repair.
- No email: inspect web logs and Mailpit UI; ensure the `mailpit` service is running.
- Port conflict: change `WEB_PORT`/`APP_URL` together, or `MAIL_PORT`, then recreate.
- Photo errors: verify `docker compose exec --user www-data web test -w /var/www/html/uploads`.
  Only the volume root ownership is changed; contents are never reset on startup.
- Camera access requires browser permission; localhost supports secure-context
  camera APIs. Check local-file input separately if no webcam is available.

Known source-level failures and the limits of validation are documented in
[local validation notes](docs/local-validation.md). This setup does not deliver
the later authentication, tokens, CSRF, image validation or integrity fixes.
For rollback, stop with `docker compose down` and revert this branch's code;
volumes remain intact. Do not feed this local database/media to an older setup
without a deliberate backup and path/configuration review.
