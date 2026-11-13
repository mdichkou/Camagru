---
name: assess-architecture
description: Assess a Camagru architecture decision against current behavior and requirements, comparing options and proposing an ADR. Use for requested architecture assessments, not routine edits or reviews of a small diff.
---

# Assess architecture

Inputs: repository/ref, requirements and decision under review. Read
[repository boundaries](../../../AGENTS.md), roadmap #1 and relevant decisions.
Trace actual behavior and trust boundaries using source locations. Label current
versus proposed/approved states, and record missing runtime evidence.

Compare the smallest viable option with credible alternatives, including costs,
operational burden, security, accessibility/performance implications where relevant,
migration and reversibility. Preserve the approved PHP/MySQL and same-origin
incremental direction; flag any alternative requiring owner approval.

Return a proposed ADR using the [template](references/adr.md), appropriate Mermaid
diagrams, bounded migration slices and observable validation conditions. Avoid
invented traffic, capacity, performance or production outcomes.

An audit authorizes reading and a response only. Do not write code, ADR files,
issues or external comments unless the session separately authorizes those actions.
Tools: source/GitHub reads and authorized non-mutating inspection. No deployment.

Quality: [shared rubric](../../../docs/ai-engineering/evaluations/rubric.md);
recommendations must trace to requirements and concrete evidence.

Example: `Use $assess-architecture at master to assess repository-scoped AI
workflows for #16; return a proposed ADR without writing files.`

Validation: [routing cases](../../../docs/ai-engineering/evaluations/README.md)
and architecture output scored for option trade-offs, state labels and no writes.
