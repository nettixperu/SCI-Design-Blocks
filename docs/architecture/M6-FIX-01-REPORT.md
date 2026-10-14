# M6-FIX-01 — Gutenberg validation repair

**Baseline:** 1.4.0 remains frozen. **Branch:** `feature/sci-hero-patterns-css-compat`. **HEAD:** `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12` (working tree contains uncommitted M5/M6 work). **Scope:** correction of the confirmed Hero Split Core Image serialization defect and preparation of a local QA candidate only.

## Executive result

The Product Owner's real Gutenberg editor validation confirmed that the self-closing `core/image` in `sci-design-blocks/hero-split` is invalid. Hero Split now stores the empty Image block using the exact wrapper and image markup emitted by Gutenberg's `save` function. A regression assertion checks that serialized form and inventories self-closing blocks across all 22 registered patterns. Hero Cover was reviewed and unchanged.

Source, parser, lint, and regression checks pass. This does **not** constitute a post-fix browser/editor pass: image selection, editing, saving, reopening, responsive behavior, accessibility, PHP 7.4, and package installation/upgrade remain unverified. Candidate status: **PASS WITH LIMITATIONS — QA ONLY**. M6 and stable-release recommendation remain **NO-GO** until the Product Owner completes interactive and remaining release gates.

## Defect, evidence, and correction

The invalid content was:

```html
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} /-->
```

The Product Owner executed Gutenberg's JavaScript validator in the real editor. It returned `Block validation failed for core/image` and reported `Expected StartTag figure, instead saw end of content.` Gutenberg's expected saved markup was:

```html
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img alt=""/></figure>
<!-- /wp:image -->
```

Only `patterns/sci-hero-split.html` was corrected. The empty Image remains a normal editable `core/image` with no URL; the block settings, columns, ordering, CTAs, and public pattern slug are unchanged. Its editor image placeholder remains selectable through Core's standard Image controls. The source plugin header remains `1.4.0`; only the isolated RC packaging copy receives the preview version `1.5.0-rc.2`.

## Pattern audit

All 22 registered patterns were included in the PHP Core parser/serializer regression. Source review found 38 self-closing block comments across the pattern set. They comprise the known no-static-save Core Icon block and server-rendered Query blocks (`core/post-date`, `core/post-excerpt`, `core/post-featured-image`, `core/post-terms`, `core/post-title`). Those preserve comment-only content because they do not save static HTML. The confirmed defect was the sole self-closing static `core/image`; none remain. The test now fails on any self-closing `core/image` or any previously unreviewed self-closing block type.

Hero Cover uses a non-empty Core Cover with nested Core content; it has no empty Image block and was not changed. This source review does not claim interactive validation for all patterns.

## Regression and inventory

`tests/check-pricing-comparison-patterns.php` now asserts that the parsed Hero Split image serializes byte-for-byte to the PO-confirmed Core markup, has no URL (so it remains empty/editable), and is not self-closing. It also inventories self-closing blocks across all 22 registered patterns against the reviewed no-save/dynamic allowlist. This is a static serialization-contract regression, not a replacement for Gutenberg's JavaScript `validateBlock()`.

| Check | Result |
| --- | --- |
| PHP lint | PASS on local PHP 8.5.7 |
| PHP registration/parser/serializer tests | PASS; 22 patterns; WordPress 7.1.3 parser |
| Hero Split Image markup contract | PASS; matches PO-provided Gutenberg save output |
| Self-closing block inventory | PASS; 38 reviewed no-save/dynamic instances, no self-closing Image |
| Python SVG/CSS tests | PASS |
| Icon / Block Style / CSS inventory | 83 icons, 7 Block Styles, 3 CSS files |
| Custom blocks / frontend JavaScript / new runtime dependencies | 0 / 0 / 0 |
| Post-fix Gutenberg editor validation | NOT VERIFIED — PO must insert, select image, edit, save, and reopen |
| PHP 7.4 runtime | NOT VERIFIED |
| Install/activate/upgrade RC.2 | NOT VERIFIED in this pass |
| Responsive/accessibility/full PO QA | NOT VERIFIED / OPEN |

## Release candidate

RC.1 remains preserved unchanged as historical evidence of the reported defect. RC.2 is a separate preview package with its own SHA-256 and file/source manifests. The candidate is only for disposable QA; it is not a stable release and does not modify the 1.4.0 source version.

RC.2 package and manifests are stored beside RC.1 at `${SCI_QA_ROOT}/release-candidates/`:

| Artifact | SHA-256 | Details |
| --- | --- | --- |
| `sci-design-blocks-1.5.0-rc.2-m6.zip` | `f8d5d7c65cb17b1645d407324d73f8c44c8cb1b4b1dbeb8977af23ef007ef477` | 105 files; ZIP integrity passes |
| `RC-FILE-MANIFEST-rc.2.sha256` | `f906b6d125b4217a8263545495ad5eab5e7046bb91d8cfa5f918fcb6beb0de4d` | 105 package files |
| `SOURCE-FILE-MANIFEST-rc.2.sha256` | `a685bb5f75709914b3ff5557084881b52ac4f635f04377a9ed147ad2c9b43368` | 105 source files; source hashes verified |

Compared with RC.1, the only packaged file-content differences are `patterns/sci-hero-split.html` and the staged plugin header version. RC.1 still hashes to `0cd716511c863f0086821128fda592b830ca42614a4e96e0c8244814b4a83ed0`. RC.2 contains no symlinks; the source header remains 1.4.0. Archive and manifest paths were checked, and the copied ZIP is byte-identical to the validated staging package.

No commit, push, merge, tag, GitHub Release, or publication was made. No changes were made to `main`, `v1.4.0`, or other patterns.

## Product Owner retest

On a disposable WordPress 7.1.3+ site, install the RC.2 ZIP and record its SHA-256 before upload. In Gutenberg:

1. Insert **SCI Hero — Split** in a draft and confirm there is no invalid/recovery warning.
2. Select the empty image placeholder in the right column; choose an image from Media Library or upload one, set useful alt text, and verify the normal Image controls remain available.
3. Edit the heading, description, and both CTA labels/targets; save the draft.
4. Leave the editor, reopen the draft, and confirm the image and all edits persist without validation or console errors.
5. Repeat at 1440, 768, 390, and 320 px, checking stacking, reading order, wrapping, CTA visibility, and horizontal overflow.
6. Optionally insert Hero Cover as a separate check; do not treat its previous non-error screenshot as an editor validation.

Report the exact WordPress/PHP/theme/browser versions, viewport, candidate checksum, result of insert/edit/save/reopen, console findings, and screenshots. Keep M6 and release **NO-GO** until this and all other open gates are accepted by the Product Owner.

## Git state

- Branch: `feature/sci-hero-patterns-css-compat`.
- HEAD remains `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`; implementation identity additionally depends on the working-tree/package manifests because there is no commit.
- This task changed only the Hero Split pattern, the pricing/comparison/Hero test, this report, and the documentation index. Existing M5/M6 edits and untracked files were preserved.
- No commits, pushes, tags, merges, or publication actions.
