# Workflow and discovery

1. Select one approved issue and verify dependencies, existing work and current
   source/ADRs. Explain plan, risks and checks before implementing.
2. Route using the [catalog](skills-catalog.md); read the selected skill and template.
   Shared commands and boundaries live in [AGENTS.md](../../AGENTS.md).
3. Execute within session authorization. Record actual commands and inspect outputs.
   Review and architecture tasks are read-only unless edits are separately requested.
4. Inspect the diff, map criteria to evidence and prepare the requested PR. Record
   human corrections and pending decisions using the [gates](quality-gates.md).

## Supported environment

Target: local Codex CLI / repository workspace. Official documentation checked
13 November 2026: [Build skills](https://learn.chatgpt.com/docs/build-skills) and
[AGENTS.md discovery](https://learn.chatgpt.com/docs/agent-configuration/agents-md).
Codex scans `.agents/skills` from CWD through repository root. Each skill has a
`SKILL.md` with YAML `name` and `description`; body/resources load on selection.
Root `AGENTS.md` supplies repository instructions; deeper instructions can override
within their scope. This catalog uses no executable skill scripts, hooks, tool
grants or optional UI metadata. Local files do not install skills in personal
ChatGPT Work. No personal/global skill settings are modified by this change.

Launch Codex from this repository, use `$review-engineering-change`,
`$implement-reviewed-slice` or `$assess-architecture`, and inspect the selected
path. If new files do not appear, restart Codex. For a non-mutating discovery
check use the installed app-server `skills/list` request with the repository CWD;
verify three repo-scoped entries and no parser errors. Tool APIs vary by release:
record the exact CLI version and method used in the evaluation results.

Trigger examples exercise selection separately from output quality. A successful
file parser or discovery result does not prove reliable automatic routing. Frozen
tasks must be compared using the same source and declared instruction differences;
do not pretend a later, informed reread is an independent baseline.
