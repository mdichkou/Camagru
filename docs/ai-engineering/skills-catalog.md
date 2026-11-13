# Approved skill catalog

Only these three repository skills are approved by #16. Each links its inputs,
procedure, output template, rubric, permissions and validation from its entrypoint.

| Skill / entrypoint | Trigger / example | Exclusion | Output |
| --- | --- | --- | --- |
| [review-engineering-change](../../.agents/skills/review-engineering-change/SKILL.md) | Review this issue's base/head diff with check evidence | Implementation; architecture options | Criterion map, ranked file/line findings, unverified checks |
| [implement-reviewed-slice](../../.agents/skills/implement-reviewed-slice/SKILL.md) | Implement one approved issue after checking dependencies | Review only; unapproved feature idea | Bounded diff, checks, docs and PR evidence |
| [assess-architecture](../../.agents/skills/assess-architecture/SKILL.md) | Compare options for a specified architecture decision/ref | Routine bug fix or small diff review | Proposed ADR, diagrams, migration and validation conditions |

If a request combines workflows, use only the skills needed for the authorized
stages. Do not turn every implementation into a separate architecture assessment.
Unsupported requests receive ordinary assistance, not a forced catalog match.

Tools: local read/search/Git and connected GitHub reads; relevant checks and local
edits only within authorization. External PR creation requires the task to authorize
it. Review comments, issue writes, merges and deployments are not implied by skill
selection. Quality is judged against the [rubric](evaluations/rubric.md), not prose
length or a claim that a skill was invoked.
