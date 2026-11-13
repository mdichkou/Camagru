# Camagru engineering handoff

Read the requested issue, dependencies and [roadmap #1](https://github.com/mdichkou/Camagru/issues/1)
before editing. Keep PHP/MySQL and same-origin sessions; introduce React/TypeScript/Vite
only through approved camera slices. Preserve capture/upload, three overlays,
gallery, social and account functionality. No repository ADRs existed before #16;
new decisions must distinguish approved direction from proposals.

Use a dedicated branch, inspect existing work and preserve unrelated changes.
Implement one approved issue; report missing prerequisites or unapproved decisions.
Instructions and tool availability do not grant permissions. Read-only review and
architecture assessment do not authorize edits, external comments or issue changes.
Prepare a PR when requested; human review is required. Never merge or deploy as
part of these workflows. Do not manually close issues.

Keep `.env`, credentials, tokens, real accounts and private media out of logs,
fixtures and commits. Use synthetic data. Keep the legacy runtime localhost-only.
Do not reset databases or volumes as an automatic repair.

## Existing checks

Run relevant checks, recording exact commands, results and unavailable coverage.
The runtime setup and synthetic-data caveats are in [README.md](README.md).

```sh
docker compose up --build -d --wait
docker compose exec web sh -c "find . -name '*.php' -print0 | xargs -0 -n1 php -l"
docker compose exec web php tests/smoke.php
docker compose up -d --force-recreate --wait
docker compose exec web php tests/smoke.php --verify-persistence
git diff --check
```

The smoke script retains synthetic records/photos. Use disposable isolated volumes;
never assume these checks establish security or browser/device parity. Documentation
changes need link/content and diff checks; runtime changes need relevant runtime
checks. There is no Node build, formatter, CI or general unit suite at this baseline.

Repository skills live in `.agents/skills/`. Read the selected `SKILL.md` and linked
template. See [workflow and evidence](docs/ai-engineering/README.md) for routing,
quality gates and evaluation limits; avoid copying these rules into each skill.
