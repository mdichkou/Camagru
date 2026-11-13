# Real bounded trial: issue #16

Date: 13 November 2026. Base: `236d4dd18ef5648a2af9c6fa952ee706e30a256d`.
Branch: `issue-16-ai-workflows`. This is a real repository documentation task,
not a trial implementation of the linked security or frontend issues.

Problem: no repository-specific handoff, approved skills or inspectable evaluation
evidence. Owner input: implement #16 under #1, check dependencies, preserve unrelated
work, validate, prepare a PR; do not merge/deploy/manually close issues.

AI proposal: root shared rules, exactly three instruction-only skills and linked
templates, workflow/catalog/gates, ADR-007 proposal, synthetic frozen review tasks,
baseline/treatment results and pending human scoring. No personal installation,
application code change, package dependency or test infrastructure was proposed.

## Trial execution and corrections

The primary agent used the new `implement-reviewed-slice` procedure and handoff
template to finish this authorized issue. This is author-assisted self-use, not
an independent implementation benchmark. The issue was inspected before edits;
#2 was confirmed closed and PR #19 merged; the checkout was clean at inspection.
No pre-existing repository instructions, skills or ADRs were found. A brief plan,
risks and validation approach were presented before creating files.

Concrete diff: root AGENTS.md; README link to this evidence; three `.agents/skills`
folders; `docs/ai-engineering` workflow/catalog/gates, proposed ADR, evaluation
inputs/results and this case study. Runtime/source/configuration are untouched.

The first local link check found nine entrypoint references with an extra parent
directory. The agent corrected all nine links, preserved the original hashes in
`manifest-v1.json`, and reran link/discovery checks against v2. This was an **AI
correction from an executed check**, not a human correction. Three documentation
targets were absent while drafting; they were completed before final validation.
No result is labeled as a human decision without owner input.

Independent read-only trials returned all seeded defect categories and no finding
on the harmless punctuation edit. Both baseline and treatment did so; no measured
quality or speed improvement is claimed. The architecture assessment explicitly
states its source and approval limits. Raw outputs and detailed conditions are
linked in the [results](../evaluations/results.md).

## Human oversight

The user authorized the scope and PR preparation. Human scoring was requested
during implementation. No human scores, review minutes, accepted corrections or
final approval have been supplied at recording time. They remain pending in
[human-review.json](../evaluations/human-review.json); do not attribute the agent's
link correction or rubric observations to the owner. The draft PR remains subject
to human review. ADR-007 is proposed, not separately approved.

## Acceptance and checks

| Criterion | Evidence / state |
| --- | --- |
| Supported format/discovery | Actual installed Codex app-server parsing and root/nested discovery |
| Scoped triggers | Eight positive/negative description cases; automatic runtime invocation unverified |
| No unauthorized writes | Read-only trial tool traces; explicit separate artifact-save tasks; no merge/deploy/issue changes |
| Baseline and actual results | Frozen review pair, raw outputs, architecture assessment and this real trial |
| Human scoring and corrections | Pending human input; agent corrections clearly separated |

Executed checks: actual discovery, host link/naming/hash validation and final
Git whitespace/diff review. PyYAML validator unavailable; Docker/PHP runtime checks
not rerun. Full commands/results and limits are in the run record.

Lessons: discovery is cheaper to verify with the actual parser than by guessing
formats; relative links need a real check; a small successful review pair does
not establish productivity; human evidence cannot be manufactured by the agent.

Rollback: revert this bounded documentation/skill commit. No schema, data, volumes
or personal skills need changing. Final diff and PR: use this branch's PR against
`master`; review all added files, including untracked artifacts before commit.
