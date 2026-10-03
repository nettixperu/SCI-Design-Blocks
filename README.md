# SCI Design Blocks 1.3.0

Lightweight Gutenberg-native design patterns and optional styles for WordPress.

## Requirements

- WordPress 7.1 or later
- PHP 7.4 or later
- WordPress Block Editor

## Included

- 15 patterns total: 9 established patterns and 6 editorial Core Query patterns
- 83 curated icons
- Core Accordion styles: Minimal and Bordered
- Core Tabs styles: Underline, Pills, Connected and Filled
- Core Post Featured Image style: optional SCI — Hover Zoom

Editorial Query patterns:

- Featured Hero
- Editorial Lead
- Compact List
- Editorial Grid
- Editorial Stack
- Editorial Sections

## Architecture

SCI Design Blocks is Core-first: its patterns are insertion templates composed from WordPress Core blocks, including Core Query, layout and post content blocks. Inserted patterns are unsynced and remain editable Core content. The plugin registers no custom SCI blocks, adds no SCI frontend JavaScript, and has no external runtime dependencies.

The six editorial patterns use Core Query compositions. Editorial Lead and each Editorial Sections pair use independent Query blocks. Keep their filters and ordering equivalent when you want coordinated results; SCI does not synchronize queries, and separate sections can show overlapping posts.

Hover Zoom is opt-in and off by default. It respects `prefers-reduced-motion`. Accordion and Tabs styles are optional Core Block Styles that inherit Core/theme colors and content behavior.

## When the plugin is inactive

Editorial patterns remain ordinary Core blocks and their content/query remains editable. SCI-specific registered icons and optional visual styles require the plugin to be active; see [Third-Party Notices](THIRD-PARTY-NOTICES.md) for icon licensing details.

## License

SCI Design Blocks is licensed under [GPL-2.0-or-later](LICENSE).
