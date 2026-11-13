# Exploratory evaluation protocol v1

Frozen source is synthetic, text-only and not executable routes. It deliberately
seeds regressions rather than relying on later application fixes (#3/#4/#6).
The four [fixtures](fixtures/) have SHA-256 hashes in `manifest.json`.
Hash UTF-8 file content with CRLF normalized to LF so Windows checkout settings
do not change the frozen identity. The initial source files already used LF.
Baseline application ref: `236d4dd18ef5648a2af9c6fa952ee706e30a256d`.

1. Freeze [task](task.md), [routing cases](triggers.md), [rubric](rubric.md), fixture
   and skill hashes. Record model, tool version, conditions and source ref.
2. Run review in fresh contexts with the exact same task/fixtures: baseline with
   no repository skill, then treatment with review skill and linked template.
   Neither context receives the rubric answer key or the other output.
3. Save raw outputs before examining them against the rubric. Run routing cases
   using catalog descriptions; separately validate real tool discovery.
4. Trial implementation on a real authorized bounded task. Record actual diff,
   checks and corrections; distinguish AI proposals from human decisions.
5. Human reviewer scores outputs, time/rework, missed defects, false positives and
   unsupported assertions. Keep missing measurements pending, never fill them
   with agent judgments. Rerun affected cases after a meaningful correction.

Results: [run record](results.md); raw responses in `outputs/`; bounded real trial
in [case study](../case-studies/issue-16.md). Skill versions are SHA-256 hashes,
not invented release numbers. No scripts are shipped with these skills.

This small, non-random sample is exploratory. Baseline/treatment differ in skill
instructions and evaluator context as declared in the results. Model identity
not exposed by the environment must be recorded as unknown. Discovery, explicit
invocation and automatic trigger selection are distinct checks. No result proves
productivity, production safety or human approval.
