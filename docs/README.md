# Documentation

## Current product references

- [SCI Design Blocks 1.5.0 architecture, hardening, QA and release disposition](architecture/M9-FINAL-HARDENING-REPORT.md)
- [1.5.0 pattern preview and inventory](patterns/1.5.0-preview.md)
- [Contributor workflow and checks](../CONTRIBUTING.md)

The remaining M0–M8 and earlier M9 reports below are historical evidence. Do not rewrite them to reflect later decisions; use the consolidated M9 report for the current engineering status.

This index organizes the project’s engineering history without removing it. Historical documents describe the scope, evidence, and decisions for their respective releases; they are not all current implementation requirements.

## Architecture

The latest stable feature architecture is [SDD 1.4](architecture/SDD-1.4.md). Earlier specifications remain as versioned history: [SDD 1.2](architecture/SDD-1.2.md) and [SDD 1.1](architecture/SDD-1.1.md). The [1.1 icon inventory](architecture/ICON-INVENTORY-1.1.md) records the curated icon set.

The approved architecture, accepted M5 evidence, and M6 QA/RC status are recorded in [SDD — Astra/Spectra migration](architecture/SDD-ASTRA-MIGRATION.md), the [M5 implementation report](architecture/M5-IMPLEMENTATION-REPORT.md), the [M6 certification report](architecture/M6-CERTIFICATION-REPORT.md), the [M6-FINAL readiness report](architecture/M6-FINAL-REPORT.md), the [M6-FIX-01 Gutenberg repair report](architecture/M6-FIX-01-REPORT.md), the [M6-CLOSE certification report](architecture/M6-CLOSE-REPORT.md), the [M6-CERT-FINAL gate assessment](architecture/M6-CERT-FINAL-REPORT.md), the [M6 responsive/accessibility report](architecture/M6-QA-FINAL-REPORT.md), and the current [M6 Master Close assessment](architecture/M6-MASTER-CLOSE-REPORT.md). The Product Owner's 2026-10-13 decision to raise the 1.5.0 PHP minimum to 8.3 is recorded in [M6-PHP83-PO-DECISION.md](architecture/M6-PHP83-PO-DECISION.md); it supersedes only the PHP 7.4 gate for 1.5.0 and leaves the stable 1.4.0 artifact and historical reports unchanged. The [RC.2 Product Owner QA checklist](architecture/PO-QA-CHECKLIST.md) remains historical; use the [RC.3 checklist](architecture/PO-QA-CHECKLIST-rc.3.md) for the new candidate.

The latest RC.5 QA outcome, open M6 limitations, source-versus-distribution version reconciliation, and final exit checklist are in [M6-FINAL-EXIT-REPORT.md](architecture/M6-FINAL-EXIT-REPORT.md). Candidate reports retain historical results; host-specific paths are represented with local environment placeholders and no credentials.

The M7/M8 branch's M0 equivalence analysis and pattern contracts are in [M7-PATTERN-EXPANSION-DECISIONS.md](architecture/M7-PATTERN-EXPANSION-DECISIONS.md), with implementation, M8-FIX-01 serialization repair, full-width/responsive visual evidence, and QA limitations in [M8-IMPLEMENTATION-REPORT.md](architecture/M8-IMPLEMENTATION-REPORT.md), [M8-FIX-01-REPORT.md](architecture/M8-FIX-01-REPORT.md), and [M8-FULL-WIDTH-REPORT.md](architecture/M8-FULL-WIDTH-REPORT.md); the unreleased additions are described in the [1.5.0 development pattern preview](patterns/1.5.0-preview.md). They do not alter the published 1.4.0 ZIP.

The M9 final QA report consolidates candidate identity, automated and runtime evidence, responsive results, installation/upgrade and no-lock-in checks, accessibility/security limitations, and remaining Product Owner gates: [M9-FINAL-QA-REPORT.md](architecture/M9-FINAL-QA-REPORT.md). RC ZIPs and checksum manifests in `release-candidates/` remain local QA artifacts and are ignored by Git.

## Release engineering

Release gates, test matrices, RC fixes, and closeout records are grouped by release:

- [1.1](releases/1.1/): Tabs foundation and release test matrix.
- [1.2](releases/1.2/): Tabs responsive/theme work, Core regression QA, RC fixes, and final test matrix.
- [1.3](releases/1.3/): Editorial Query RC and final release certification.
- [1.4](releases/1.4/): Pricing/Comparison RC and final stable-release certification.

The 1.4.0 stable download and checksum are on the [GitHub Release](https://github.com/nettixperu/SCI-Design-Blocks/releases/tag/v1.4.0) and in [its release report](releases/1.4/M5-RELEASE-1.4.0.md). Published release assets are immutable; these repository reports provide engineering evidence.

## Spikes and research

- [1.4 Pricing/Comparison pre-SDD spike](spikes/PRE-SDD-1.4-PRICING-COMPARISON-SPIKE.md).
- [1.4 Product Owner visual gate](spikes/PRE-SDD-1.4-MANUAL-VISUAL-GATE.md).

## Migration and cleanup

- [M5 CSS migration matrix](migration/M5-CSS-MIGRATION-MATRIX.md), documenting the site-level CSS migration evidence associated with 1.3.

## Visual assets

- [`assets/`](assets/README.md) records planned product screenshots. No approved screenshots are currently published.
- [`social-preview/`](social-preview/README.md) records the proposed GitHub Social Preview. No image is currently available or configured.

For installation, current features, support, and contributing guidance, start at the [project README](../README.md), [CONTRIBUTING.md](../CONTRIBUTING.md), or [SECURITY.md](../SECURITY.md).
