# SCI Design Blocks 1.5.0 — RC.10 notes

**Status:** Local release candidate for technical verification only. Not stable, approved, or published.

## Candidate contents

- 27 Gutenberg patterns, 83 curated SVG icons, seven opt-in WordPress Core Block Styles, and three CSS assets.
- Core-only pattern markup; no SCI custom blocks, frontend JavaScript, or new runtime dependencies.
- Minimum requirements: WordPress 7.1 and PHP 8.3. Stable 1.4.0 requirements and artifacts remain unchanged.
- CTA examples have no fabricated destination: their saved Button blocks have no URL attribute or anchor `href`, with visible instructions to configure a real destination in the editor before publishing.
- Block-style CSS cache versions follow the local CSS file modification time.

## Validation and limitations

The repository's PHP 8.3, PHP/Core parser and serializer, Python CSS/SVG, inventory, CTA, and cache-version regression checks are run against source and the extracted RC.10 package. Earlier RC.8 clean-install, upgrade, no-lock-in, and responsive/theme evidence is retained in the M9 QA report; it is evidence for RC.8, not a fresh RC.10 installation. Product Owner reports manual Gutenberg QA on new compositions; no JavaScript validator or new browser editor session is claimed for the later CTA placeholder update. This is not WCAG certification. See the final hardening report for exact evidence and remaining gates.
