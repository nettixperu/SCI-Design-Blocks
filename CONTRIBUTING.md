# Contributing

Thanks for helping improve SCI Design Blocks. Before proposing a change, check the [open issues](https://github.com/nettixperu/SCI-Design-Blocks/issues) and describe the user need the change addresses.

## Project principles

- Prefer WordPress Core blocks, APIs, patterns, and styles.
- Prefer patterns before introducing a custom block; custom blocks require a clear user need.
- Avoid frontend JavaScript, dependencies, and CSS that Core or the active theme already provides.
- Preserve the supported baseline: WordPress 7.1+ and PHP 7.4+.
- Keep inserted content editable and portable as Core blocks.

## Changes and checks

- Keep pull requests focused and explain behavior, compatibility, and any user-facing changes.
- Update README or CHANGELOG when behavior or release-facing information changes.
- Run the relevant existing PHP and Python checks from `tests/`; state which checks you ran and any environment limitations.
- Do not commit generated release ZIPs, credentials, temporary files, or unrelated internal audit documents.
- For visual/editor changes, include genuine before/after screenshots when available and identify the WordPress/theme environment. Do not present static checks as browser QA.

## Pull requests

Open a pull request against `main`, summarize the change and validation, and link related issues. Maintainers review scope, Core compatibility, accessibility, and content portability before merging.

By contributing, you submit your contributions under the project license, GPL-2.0-or-later. No separate Contributor License Agreement is required.
