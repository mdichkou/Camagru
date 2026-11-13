# Frozen review task v1

Review `fixtures/base.php.txt` against `fixtures/head.php.txt` and
`fixtures/harmless-base.md` against `fixtures/harmless-head.md`.
These are synthetic proposed changes; no application is running.

Acceptance criteria: only an owner can delete a photo; reset tokens are purpose
bound, unexpired and consumed once; uploads are bounded and checked from actual
bytes; the gallery documentation retains its destination. Identify regressions
with severity, file/line, trigger and evidence. Distinguish unchanged weaknesses
and unverified checks. Return a criterion map and findings; do not edit files,
post external comments, run the snippets, or fix linked application issues.

Architecture: retain PHP/MySQL and same-origin deployment. No ADR beyond this
approved direction is supplied. Check evidence: none supplied. Source files are
complete policy sketches, not a complete application.
