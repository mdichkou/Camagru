---
name: review-engineering-change
description: Review a Camagru engineering diff against an issue and architecture, producing evidence and ranked findings. Use for requested change reviews, not implementation or architecture option selection.
---

# Review an engineering change

Inputs: issue acceptance criteria, base/head refs or frozen diff, relevant ADRs
(or their absence), and actual check results. If missing, inspect available
repository/issue evidence and label the gaps; do not invent a base or test result.

Read [repository boundaries](../../../AGENTS.md) and use the
[review template](references/review.md). Read changed files and necessary callers,
not just the diff. Trace each criterion to code and executed evidence.

Check relevant trust boundaries: active sessions and photo ownership; activation
versus recovery purpose, expiry and one-time use; upload bytes, limits, format and
storage; CSRF and output escaping. Trace accessibility and performance risks when
the changed behavior touches them. Distinguish existing defects from regressions.
Do not turn a documentation change into a full application audit.

Rank actionable findings by impact with exact file/line, trigger, consequence and
evidence. Separate unsupported suspicions and unexecuted checks. A clean lint or
happy-path smoke run does not prove authorization. Do not edit during review or
submit external review comments without authorization.

Output: criterion map, ranked findings, relevant risks, checks and limitations.
Quality: use the [shared rubric](../../../docs/ai-engineering/evaluations/rubric.md);
every finding must be reproducible or supported by a concrete source trace.

Example: `Use $review-engineering-change to review issue #16 against master and
issue-16-ai-workflows using the recorded checks.`

Validation: run positive/negative routing examples and the frozen seeded-defect
and harmless-change review in [evaluations](../../../docs/ai-engineering/evaluations/README.md).
Tools: repository/GitHub reads and authorized, isolated checks only; availability
does not grant write permission.
