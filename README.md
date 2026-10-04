# SCI Design Blocks 1.4.0

Lightweight Gutenberg-native design patterns and optional Core Block Styles for WordPress.

## Requirements

- WordPress 7.1 or later
- PHP 7.4 or later
- WordPress Block Editor

## Included

- 20 patterns: 9 established, 6 editorial Core Query, and 5 Pricing/Comparison patterns
- 83 curated icons
- Core Accordion styles: Minimal and Bordered
- Core Tabs styles: Underline, Pills, Connected and Filled
- Core Post Featured Image style: optional SCI — Hover Zoom

New Pricing and Comparison patterns:

- SCI Pricing — Cards
- SCI Pricing — Featured
- SCI Pricing — Compact
- SCI Comparison — 2 Options
- SCI Comparison — Feature Table

## Architecture

SCI Design Blocks is Core-first and theme-native. Patterns are unsynced insertion templates composed from WordPress Core blocks. Pricing Cards use Core Group Grid for responsive reflow; three plans are the default, and the Product Owner reports visual validation through six plans. The plan count is not enforced in code. Pricing values and all sample comparison copy are illustrative editable content, not a pricing data model.

Comparison Feature Table uses the semantic Core Table block with a caption and table headers. The plugin adds no custom SCI blocks, frontend JavaScript, Pricing/Comparison CSS, or external runtime dependencies. It adds no pricing entities, REST routes, settings, database schema, checkout behavior, or synchronized monthly/annual toggle.

The six editorial patterns use Core Query compositions. Editorial Lead and each Editorial Sections pair use independent Query blocks. SCI does not synchronize their filters or guarantee that separate sections avoid overlapping posts.

Hover Zoom is opt-in and off by default. It respects `prefers-reduced-motion`. Accordion and Tabs styles are optional Core Block Styles that inherit Core/theme colors and content behavior.

## Validation

The Product Owner reports manual Gutenberg/editor and frontend PASS for the new Pricing/Comparison patterns, Pricing Cards scalability through six plans, Tabs regression, and mobile Tabs horizontal scrolling. Codex static and artifact checks passed. Codex browser QA and database-backed WordPress activation QA were not performed. The repository release record distinguishes Product Owner manual evidence from Codex static checks and any browser QA.

## When the plugin is inactive

Inserted patterns remain ordinary Core blocks and their content remains editable. SCI-specific registered icons and optional visual styles require the plugin to be active; see [Third-Party Notices](THIRD-PARTY-NOTICES.md) for icon licensing details.

## License

SCI Design Blocks is licensed under [GPL-2.0-or-later](LICENSE).
