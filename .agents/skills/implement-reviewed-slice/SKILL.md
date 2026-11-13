---
name: implement-reviewed-slice
description: Implement one approved Camagru issue with dependency checks, a bounded diff and PR evidence. Use for authorized implementation, not review-only requests or an unapproved feature idea.
---

# Implement one reviewed slice

Input: one approved issue with acceptance criteria and dependency state. Read
[repository boundaries](../../../AGENTS.md), the issue, roadmap and applicable
ADRs; inspect source, status and existing PRs to avoid duplicate work.

Give a brief plan, risks and validation approach before editing. If a prerequisite
is missing or a significant decision exceeds approval, report the specific blocker.
Otherwise execute the authorized scope on a dedicated branch, preserving unrelated
work. Reuse current tooling; do not silently implement linked issues.

Make the smallest coherent diff; add focused regression coverage where useful.
Execute applicable checks, inspect their actual outputs, inspect the final diff
and update required docs. Track unavailable checks and pre-existing failures.
Use the [handoff template](references/handoff.md) to prepare a PR when authorized.
Stop before merge/deployment or unapproved scope changes. Do not invent results.

Output: bounded implementation, criterion evidence, checks, risks and rollback.
Quality: [shared rubric](../../../docs/ai-engineering/evaluations/rubric.md),
with no unsupported completion claim and no unrelated changes.

Example: `Use $implement-reviewed-slice for approved issue #16; keep human scoring
pending until the owner supplies it.`

Validation: [routing cases and bounded real trial](../../../docs/ai-engineering/evaluations/README.md).
Tools: local edits/checks and requested PR creation within session authorization;
tool instructions do not grant permissions. Use synthetic data only.
