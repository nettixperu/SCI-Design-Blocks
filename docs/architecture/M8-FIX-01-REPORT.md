# M8-FIX-01 — Gutenberg invalid block repair

Status: structural repair implemented; JavaScript editor validation remains pending. Branch: `feature/sci-pattern-library-expansion`. Stable `v1.4.0` and RC.6 are preserved.

## Root cause

Pricing Split and CTA Horizontal each opened two `core/column` blocks but omitted the closing `<!-- /wp:column -->` comment after the first one. WordPress 7.1.3's PHP block parser therefore interpreted the second column as a child of the first. The browser reports identified those compositions; the Core parser tree independently confirmed the malformed hierarchy before correction (`core/columns` had one direct `core/column`, with the second under it). This is a block-boundary/parent-child error, not an Image serialization issue.

## Minimal correction and preventive review

Added only the missing `<!-- /wp:column -->` in `patterns/sci-pricing-split.html` and `patterns/sci-cta-horizontal.html`. The parser now yields exactly two sibling Core Columns in each pattern. No CSS, JavaScript, custom blocks, dependencies, or remote media were added.

Reviewed all five M8 patterns for block-comment balance and suspicious self-closing static blocks. Section Intro and Benefits Grid have balanced Group/Heading/Paragraph boundaries; Service Cards retains three full editable Core Image serializations (`figure` containing `img alt=""`), not self-closing Image blocks. The static regression now checks balanced block comment nesting for all five M8 patterns and exactly two sibling Core Column blocks for the two affected patterns. Existing PHP Core parser/serializer regression still covers all 27 patterns.

The PHP regression passes against WordPress 7.1.3. It is structural evidence only, not equivalent to JavaScript `validateBlock`. The Gutenberg editor could not be used interactively in this run: Chrome reached the local QA login page, but no authenticated QA editor session was available. No password was retrieved or entered. Insert/save/reopen, interactive edits, media selection, and actual browser `validateBlock` remain explicitly **NOT VERIFIED** for the requested single interactive validation round.

## Verification

- WordPress Core PHP parser/serializer: all 27 registered patterns pass stable round-trip; five M8 patterns pass strict comment nesting.
- Pricing Split and CTA Horizontal: two sibling `core/column` blocks each, with mobile stacking retained.
- Service Cards: three explicit editable Core Image serializations; no remote media.
- Other M8 regression gates: PHP lint, PHP/Python suites, inventory and `git diff --check` are reported with RC.7 handoff.
- Expected inventory remains 27 patterns, 83 icons, 7 Block Styles, 3 CSS files.

## Candidate and restrictions

Because production pattern markup changed, local [RC.7](../../release-candidates/sci-design-blocks-1.5.0-rc.7.zip) was generated from a staging copy. SHA-256: `892f997b0b299b2193281da1d0a51fcd7fdd4d3869f950c8df2ba2a001b30d2a`. The [source manifest](../../release-candidates/SOURCE-FILE-MANIFEST-rc.7.sha256) and [package manifest](../../release-candidates/RC-FILE-MANIFEST-rc.7.sha256) each cover 111 files; both checksum validations and ZIP integrity passed. The source plugin header remains `1.5.0-rc.3` (PHP 8.3+, WordPress 7.1+); RC.7 distribution metadata is isolated to the package copy. RC.6 SHA remains `c30ec6e7405053341eb26eefce408db708bf8b46375680e29f613bad19513f63`. RC.7 is not a published release. No commit, push, merge, tag, or release publication was performed; `main` and `v1.4.0` were not modified.
