# SCI Design Blocks 1.5.0 — RC.9 notes

**Status:** Superseded local artifact. Do not install or distribute. Preserved byte-for-byte; not stable, approved, or published.

RC.9 was built before review caught stale `url` attributes in the serialized Core Button comments after their HTML `href` values were removed. The source correction removes those JSON attributes as well. RC.10 is the current candidate.

## Candidate contents

- 27 Gutenberg patterns, 83 curated SVG icons, seven opt-in WordPress Core Block Styles, and three CSS assets.
- Core-only pattern markup; no SCI custom blocks, frontend JavaScript, or new runtime dependencies.
- Minimum requirements: WordPress 7.1 and PHP 8.3. Stable 1.4.0 requirements and artifacts remain unchanged.
- CTA examples do not assume a contact URL. Configure each button destination in the editor before publishing.
- Block-style CSS cache versions follow the local CSS file modification time.

## Validation and limitations

The repository's PHP 8.3, PHP/Core parser and serializer, Python CSS/SVG, inventory, CTA, and cache-version regression checks are run for this candidate. Earlier RC.8 clean-install, upgrade, no-lock-in, and responsive/theme evidence is retained in the M9 QA report; it is evidence for RC.8, not a fresh RC.9 installation. Product Owner reports manually validating new Gutenberg compositions; no JavaScript validator or new editor session is claimed for the CTA placeholder text update. This is not WCAG certification. See the final hardening report for the candidate-specific gates and exact evidence.
