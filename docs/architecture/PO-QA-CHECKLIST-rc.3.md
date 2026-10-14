# SCI Design Blocks 1.5.0-rc.3 — Product Owner QA handoff

**Purpose:** validate the prepared RC.3 candidate on disposable staging only. This checklist is not a release approval.

## Candidate and prerequisites

- Candidate: `SCI Design Blocks 1.5.0-rc.3`.
- ZIP: `sci-design-blocks-1.5.0-rc.3-m6.zip`.
- SHA-256: see [M6 PHP 8.3 decision](M6-PHP83-PO-DECISION.md); verify the actual ZIP before install.
- Stable 1.4.0 remains unchanged and requires PHP 7.4+. RC.3's approved minimum is WordPress 7.1+ and **PHP 8.3+**.
- Record exact WordPress, PHP, database, operating system, theme, browser and viewport. Run the minimum-version certification on actual PHP 8.3; PHP 8.5 is not a substitute.
- Keep the 1.4.0 ZIP available for rollback. Never use production.

## Required certification

- [ ] Recompute and match the RC.3 ZIP SHA-256; verify archive integrity and manifests.
- [ ] Install and activate in a disposable site running PHP 8.3; confirm WordPress recognizes the declared minimum and no fatal errors occur.
- [ ] Run the minimum-requirements test, PHP lint, full PHP/Python suite, parser/serializer checks and inventory assertions: 22 patterns, 83 icons, seven Block Styles, three CSS assets; zero custom SCI blocks, frontend JS and additional runtime dependencies.
- [ ] Confirm the version is 1.5.0-rc.3 and package metadata requires PHP 8.3; RC.2 remains byte-for-byte unchanged.
- [ ] Reuse the relevant insertion/edit/save/reopen and responsive/accessibility checks from [the RC.2 QA checklist](PO-QA-CHECKLIST.md) only where the installed runtime or candidate change makes them relevant. Preserve its PO-reported Hero evidence without relabeling it as automated.
- [ ] Recheck known open items from [M6-QA-FINAL](M6-QA-FINAL-REPORT.md), including Hero Cover contrast, full accessibility, exact responsive gaps, and other M6 release gates.
- [ ] Record results as PASS, PASS WITH LIMITATIONS, FAIL or NOT VERIFIED. A missing PHP 8.3 runtime test keeps the PHP gate open.

Publication remains prohibited unless the Product Owner separately authorizes it. No commit, push, merge or tag is implied by this handoff.
