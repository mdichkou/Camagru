# Quality and human gates

| Gate | Evidence required | Failure / next action |
| --- | --- | --- |
| Scope | Approved issue, dependency status, current source and decision references | Report missing prerequisite or unapproved decision |
| Boundary | Authorized paths/actions, synthetic fixtures, no secret/private media output | Stop unsafe action; redact evidence |
| Implementation | Small coherent diff and acceptance map | Correct gaps within scope; do not absorb linked issues |
| Verification | Exact executed commands/results, meaningful behavior checks where relevant | Label unavailable checks and pre-existing failures |
| Review | File/line findings, final diff, security and relevant accessibility/performance risks | Correct supported findings and rerun affected checks |
| Human decision | Named reviewer, scores, corrections and approval or rejection | Leave pending; agent scores cannot substitute |
| Handoff | Requested PR, limitations and rollback | No merge, deployment or manual issue closure |

For documentation/skills, validate frontmatter, resource links, discovery and
positive/negative routing; trial realistic tasks and check action boundaries.
For runtime changes use the actual commands in AGENTS.md and relevant focused
regressions. Do not run data-mutating smoke checks against a real user's volumes.

Human scoring uses the frozen [rubric](evaluations/rubric.md) and reviews raw
outputs against the answer key. Record identity/date, review minutes, rework
minutes, scores, missed defects, false positives, unsupported assertions and
accepted/rejected corrections. Missing measurements stay `not measured`.
No productivity claim may be drawn from this small exploratory sample.
