# M8 — Full-width sections and responsive pattern review

Status: **PASS WITH LIMITATIONS**. This is a local candidate change, not a release approval. No commit, push, merge, tag, or publication was made.

## Findings and changes

The five M8 sections had only `alignwide` roots, and Twenty Twenty-One constrained their content descendants to its narrow default content width. Services therefore rendered two columns at 1440 px. The correction uses a Core `alignfull` Group as each section root, a constrained `alignwide` inner Group, and Core `alignwide` on the Benefits and Services Grids and the Pricing/CTA Columns. Section copy and card content remain in constrained Groups for readable line lengths.

SCI Services — Image Cards now uses a 16rem Core auto-fit Grid minimum. It produces three balanced cards at 1440 and 1024 px, two at 768 px, and one at 390 and 320 px. Core Image blocks remain empty/editable (no image URL or remote dependency); their 4:3 `aspectRatio` and `cover` `scale` are serialized with the inline `aspect-ratio:4/3;object-fit:cover` style required by the WordPress 7.1.3 Core Image `save()` implementation. Core flex `space-between` keeps each card's CTA at the row bottom.

Only Core block markup and a regression assertion changed. No custom CSS, JavaScript, dependency, fixed height, or custom block was added. Prior patterns, icons, styles, and CSS inventory remain intact.

## Visual checks

The current source patterns were loaded temporarily into the existing WordPress 7.1.3 QA fixture pages 29–33 and then the original page contents and Twenty Twenty-Five active theme were restored. Frontend rendering was measured in Chrome's responsive viewport at 1440, 1024, 768, 390, and 320 CSS pixels in both Twenty Twenty-Five 1.5 and Twenty Twenty-One 2.9. For all five patterns and both themes, the outer `alignfull` root matched the viewport width and `documentElement.scrollWidth` showed no horizontal overflow.

Service Cards column counts (identical in both themes):

| Viewport | Columns | Twenty Twenty-Five card width | Twenty Twenty-One card width |
| ---: | ---: | ---: | ---: |
| 1440 | 3 | 433 px | 407 px |
| 1024 | 3 | 295 px | 268 px |
| 768 | 2 | 336 px | 329 px |
| 390 | 1 | 330 px | 360 px |
| 320 | 1 | 260 px | 290 px |

CTA top coordinates matched between cards on each row at the desktop and tablet grid checks. To check image cropping, a locally installed fixture image was used temporarily on the QA page only; it rendered at 4:3 (for example, 407 × 305 px at the 1440 viewport in Twenty Twenty-One) using Core's aspect ratio/object-fit style. The pattern itself retains empty image placeholders.

These are actual frontend page renders, not Inserter thumbnail screenshots. Pattern-card thumbnails are previews and are not evidence of the saved block markup's layout. The authenticated Gutenberg editor was unavailable: opening the fixture editor redirected to WordPress Login. Therefore Inserter-thumbnail comparison, editor canvas verification, and interactive select/replace/save/reopen of the images remain **NOT VERIFIED**. Browser layout measurements and PHP parsing do not replace Gutenberg's JavaScript `validateBlock()`.

## Regression and inventory

- PHP 8.3 lint: all 13 PHP files passed.
- PHP tests: icon inventory, editorial Query, pricing/comparison/pattern parser, and Tabs round-trip passed against WordPress 7.1.3.
- Python tests: all four icon/SVG/CSS checks passed.
- Parser/serializer: all 27 registered patterns round-tripped; block comments are balanced; no self-closing `core/image` remains.
- New regression checks assert `alignfull` M8 roots, wide Core Grid/Columns, three 16rem-minimum Service Images with Core save-style serialization, and responsive Core Columns.
- `git diff --check`: passed.
- Inventory remains **27 patterns, 83 icons, 7 Block Styles, 3 CSS files**.

The local package is [sci-design-blocks-1.5.0-rc.8.zip](../../release-candidates/sci-design-blocks-1.5.0-rc.8.zip), SHA-256 `d91480a3db2be091f824b42fee9cad81bf8c7f2a041e26035bbc3978767faa81`. The source and distribution manifests each contain 111 file hashes and verified successfully. RC.6 (`c30ec6e7…`) and RC.7 (`892f997b…`) remain byte-identical to their prior checksums.

The 1.5.0 candidate remains unreleased and M8 is still not release-approved pending authenticated editor validation and remaining accessibility/release gates. Stable 1.4.0 and prior RC artifacts remain unchanged. RC.8 is a local candidate artifact only.
