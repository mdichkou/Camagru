# Run record: 13 November 2026

Status: exploratory trials executed; **human scoring and final approval pending**.
Source ref: `236d4dd18ef5648a2af9c6fa952ee706e30a256d` (merged runtime baseline).
Tool: Codex CLI `0.167.0-alpha.4.1` for local discovery; Python `3.10` for host
hash/link validation; independent fresh Codex agent contexts for behavioral
outputs. Exact serving model identifiers and backend tool versions were not
exposed to those agents: **unknown**, no model override requested.

## Conditions and raw evidence

The [task](task.md), four fixtures, [rubric](rubric.md), routing cases and first
skill versions were frozen in [manifest-v1.json](manifest-v1.json) before outputs
were examined. The current [manifest.json](manifest.json) records v2: only nine
relative skill links changed, removing one excess `../` each. Fixtures, prompts,
rubric and shared boundaries are identical. No executable skill scripts were added.

| Trial | Inputs / condition | Raw output | Observed result (agent assessment) |
| --- | --- | --- | --- |
| Baseline review | Fresh context; task + four fixtures only; explicitly no repository skill/AGENTS/rubric | [Baseline](outputs/baseline-review.md) | All three seed categories identified; size split into a fourth finding; unchanged execute-result weakness labeled separately |
| Skill review | Fresh context; identical task/fixtures + review skill v1, template and shared boundaries; no answer key or baseline output | [Treatment and routing](outputs/skill-review.md) | Same three seed categories identified; harmless change retained; checks explicitly unrun |
| Scoped routing | Eight frozen requests + three catalog descriptions; classification only | Same treatment output | R1/R2/R3 select review/implement/assess; R4/R5/R6 none; R7 review with no merge/deploy; R8 read-only assessment |
| Architecture | Fresh context; assess skill/template, requirements and selected actual source; no drafted ADR or answer key | [Architecture response](outputs/architecture-assessment.md) | Compares options, labels proposal, returns diagram, migration, validation and limitations; no edits |
| Bounded real implementation | Primary agent using implement skill/handoff for this approved #16 documentation work | [Case study](../case-studies/issue-16.md) | Catalog/docs, link correction, actual validation and requested PR preparation; human scoring left pending |

Baseline versus treatment is a comparison of **instruction bundles**: treatment
also gets AGENTS.md and template. It does not isolate the skill text's causal
effect. Independent agents were explicitly restricted to their declared inputs;
the evaluator did not supply the answer key or desired defects. V1's broken
relative links were an actual quality failure even though explicit absolute paths
in the trial setup allowed access to boundaries/template. The v2 follow-up checks
confirm corrected paths; do not describe v1 as independently self-contained.

Provisional source comparison: baseline and treatment each missed **0 of 3** seed
categories, raised **0** false findings on the harmless edit and made **0** claims
of executed runtime checks. These counts are agent observations, awaiting human
confirmation. Routing classification matched **8 of 8** declared cases. This
tests descriptions with explicit classification instructions, not spontaneous
automatic skill invocation in the CLI. No human review/rework time was measured.

## Executed local checks

- `codex --version`: `codex-cli 0.167.0-alpha.4.1`.
- Started `codex app-server --stdio`, sent `initialize`, `initialized`, then
  `skills/list` with repository root and nested `api` CWDs and `forceReload: true`.
  [Normalized response](outputs/discovery.json): all three expected names enabled,
  scope `repo`, exact repository paths; no parser errors. No model turn is needed
  for this protocol check. This validates discovery/format, not model behavior.
- Host Python SHA-256 verification of all 14 manifest entries: see final check
  record below. Markdown local links checked across root handoff/README, skill
  entrypoints/templates and AI docs. First pass found nine incorrect skill links
  and three not-yet-written documentation targets; corrected/completed before
  final check. No runtime application source changed.
- System skill-creator `quick_validate.py` was inspected; its required PyYAML is
  absent (`ModuleNotFoundError`). It was **not run successfully** and no dependency
  was installed. Actual Codex parsing, naming checks and link/hash checks provide
  format evidence instead.
- `docker` and `php` were not found in this session's PATH; the conventional Docker
  Desktop binary path was also absent. No containers, schema, mail, persistence,
  PHP lint or application/browser checks were rerun. This is an instruction/docs
  change, not Docker setup; prior #2 results remain historical evidence only.
- Git's installed `2.36.1.windows.1` rejects this checkout's ownership even with
  command-level `safe.directory`. A temporary global-config file trusts only this
  checkout; no user's global Git config was edited. Branch/index writes require
  the environment's approval mechanism because `.git` is read-only in sandbox.

The treatment evaluator reread v2 links, AGENTS.md and template: all four review
skill links resolve; findings and all eight routing conclusions are unchanged.
The architecture evaluator separately confirmed its three corrected links resolve.
These follow-ups did not execute snippets or claim human approval.
Saved responses preserve the evaluators' wording; baseline Markdown hard-break
trailing spaces were removed after the staged whitespace check flagged them.

Final host check: **passed** all 14 frozen hashes, 58 local Markdown links across
24 files, three required frontmatter names/descriptions, all evidence JSON parsing
and instruction-only assets. Hashes normalize CRLF to LF for checkout portability.
Git whitespace and final staged-diff checks are recorded in the PR handoff.
Human scores, review/rework minutes and acceptance remain in
[human-review.json](human-review.json).

To repeat the frozen-input check from repository root with Python 3:

```python
from pathlib import Path
import hashlib, json
manifest = json.loads(Path('docs/ai-engineering/evaluations/manifest.json').read_text())
for name, expected in manifest['sha256'].items():
    content = Path(name).read_bytes().replace(b'\r\n', b'\n')
    assert hashlib.sha256(content).hexdigest() == expected, name
```

For discovery, start `codex app-server --stdio` and send newline-delimited JSON
requests in order (wait for each response before the next request):

```json
{"id":1,"method":"initialize","params":{"clientInfo":{"name":"camagru-issue16-validation","version":"1.0"},"capabilities":{"experimentalApi":true}}}
{"method":"initialized","params":{}}
{"id":2,"method":"skills/list","params":{"cwds":["C:/Users/lenovo/Camagru","C:/Users/lenovo/Camagru/api"],"forceReload":true}}
```

Replace the two CWD paths for another checkout. The normalized response omits
unrelated personal/plugin skills. V1 skill content can be reconstructed from v2
by changing each `](../../../` prefix back to `](../../../../`; verify against
`manifest-v1.json` before comparing old results.

## Limitations

One synthetic review pair and one documentation implementation cannot prove
productivity or production security. Architecture trial used supplied issue
requirements instead of retrieving GitHub itself; source baseline inspection
limits are disclosed in its output. Real automatic invocation, broader task
coverage and human-scored corrections remain outstanding. No unauthorized
evaluation mutations were observed in tool traces; later explicit artifact-saving
tasks were separately authorized and are not part of the read-only trials.
