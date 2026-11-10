# Changelog

User-facing highlights for stable releases. For full release details, see [GitHub Releases](https://github.com/nettixperu/SCI-Design-Blocks/releases).

## 1.8.0 (ZIP de prueba)

- Lets a Market Pricing selector update linked Core pricing blocks elsewhere in the same nearest shared container, including sibling SCI pricing patterns.
- Updates editor guidance: keep the selector and pricing pattern in the same Group; no manual nesting of the pricing pattern inside the selector is required.

## 1.7.9 (ZIP de prueba)

- Improves the Market Pricing editor guide with short, step-by-step instructions and high-contrast styling.
- Explains exactly how to nest the pricing table under SCI Market Pricing in List View; clarifies that the editor canvas does not update dynamic prices.

## 1.7.8 (ZIP de prueba)

- Makes plan IDs editable, validates uniqueness and allowed characters, and updates linked Core blocks when an ID changes.
- Warns in the Core block inspector when a linked price or button is outside SCI Market Pricing, where the market selector cannot reach it.
- Clarifies that dynamic button prices update in preview and on the published page, and clarifies the annual note field to avoid duplicating its automatic label.

## 1.7.7 (ZIP de prueba)

- Lets a linked Core Button optionally display the selected market's monthly price and period while preserving its plan-specific Fluent Forms link.
- Keeps button pricing opt-in; Core Buttons retain their original text and behavior unless the new control is enabled.
- Documents both supported price placements: a linked Core Paragraph or the linked price button itself.

## 1.7.6 (ZIP de prueba)

- Derives a market flag from the country/region in its locale when no manual emoji override is set; the existing hide-flags option still applies.
- Adds clear in-editor steps for configuring the plan price and linking a Core Paragraph and Button with the same plan ID.
- Leaves the manual flag field optional and clears explicit flags from the integrated pattern so Peru and Mexico use locale-based flags by default.

## 1.7.5 (ZIP de prueba)

- Adds an editable plan name connected to its Core Heading; the plan ID is stable and read-only to prevent accidental unlinking.
- Makes **Eliminar plan y tarjeta** remove the matching Core card when its price and button are linked to the plan; prevents deleting the last plan.
- Adds annual billing per market. By default it is monthly price × 12; an optional annual override and market-specific tax note can be set for PEN, USD, MXN, or another configured currency.
- Updates the integrated pricing pattern with linked plan headings and annual billing rows that follow the selected market.

## 1.7.4 (ZIP de prueba)

- Makes the “Moneda o mercado” label optional and hides it by default in the integrated pricing pattern.
- Adds Gutenberg palette controls for selector text, border, background, selected option fill, and selected option text colors.
- Accepts safe hex colors and Twenty Twenty-Five palette color variables; invalid CSS color values are discarded.
- Leaves styling empty by default so the selector inherits the active theme palette unless an editor chooses explicit colors.

## 1.7.3 (ZIP de prueba)

- Adds native segmented, pill, and dropdown market selectors, with configurable flag emoji per market.
- Uses the segmented selector with Peru and Mexico flags in the integrated pricing pattern by default.
- Keeps the selector accessible through native radio/select controls and adds no external UI library.

## 1.7.2 (ZIP de prueba)

- Fixes a fatal render-time PHP type mismatch in the `render_block` filter callback. WordPress passes the parsed block array as the second argument.
- Keeps the SCI Market Pricing wrapper, Core block price/button bindings, and market/plan configuration introduced in 1.7.1.
- This remains an untagged test build; it does not update a WordPress site.

## 1.7.1 (ZIP de prueba)

- Reworks the 1.7.0 pricing-card proposal into SCI Market Pricing: a small wrapper around editable Core pricing patterns.
- Adds a market/currency selector with configurable markets (up to 30), plan records (up to 50), market-specific displayed prices, and a Fluent Forms URL per market.
- Extends Core Paragraph and Button blocks with an optional SCI plan ID so price text and CTA links can follow the selected market while keeping card content and styling in Gutenberg.
- Sends only stable `plan` and `market` identifiers to Fluent Forms; displayed amounts are not sent as payment authority. Payment Item amounts and supported currency remain configured in Fluent Forms.
- Adds an integrated Core pricing pattern and documentation for setup and staging validation. This remains an untagged staging build and does not change a WordPress site.

## 1.6.4 (ZIP de prueba)

- Adds the Core-only SCI Process — 3 Steps pattern with three responsive columns centered in the available wide area.
- Sets explicit column counts on responsive Core Grid patterns, preventing unused auto-fill tracks from leaving cards clustered on the left.
- Existing page content is not migrated: replace the saved section with the new pattern to receive the corrected layout.

## 1.6.3 (source carried forward)

- Carries forward the sitewide page-title control and lightweight Core Button hover styles from the local plugin package.

## 1.5.1

- Adds the responsive SCI Features Grid and Firefox-inspired SCI Product Story patterns.
- Adds a sitewide page-title setting that removes generated page titles in the editor and on the front end.
- Gives standalone editorial, hero, pricing, comparison, and product-story sections full-width roots with centered wide content.
- Vertically centers the image/icon feature columns and centers the SCI Stats metrics.
- Fixes malformed block JSON in six product-story cards that could trigger Gutenberg's invalid-content warning.

## 1.5.0

- Adds seven Core-only patterns: Hero Cover, Hero Split, Section Intro, Benefits Grid, Services Image Cards, Pricing Split Offer, and CTA Horizontal; total inventory is 27 patterns, 83 curated icons, seven Core Block Styles, and three CSS files.
- Improves full-width commercial sections, responsive Services cards, pricing CTA alignment, and Hero Cover text contrast using WordPress Core layout and block controls.
- Corrects Gutenberg block serialization in Hero Split, Pricing Split, CTA Horizontal, and other affected patterns; regression checks cover Core parser/serializer compatibility.
- Replaces broken example CTA fragments with editable Core Button placeholders that instruct editors to configure a real destination before publishing.
- Makes block-style CSS cache versions consistent with local asset modification times and adds related regressions.
- Raises the minimum PHP requirement to 8.3; WordPress 7.1 remains the minimum. Stable 1.4.0 and its PHP 7.4 requirement are unchanged.
- No SCI custom blocks, SCI frontend JavaScript, new runtime dependencies, or additional CSS were introduced.
- Automated and Product Owner manual Gutenberg checks are documented in the final release report. This release does not claim formal WCAG certification.

## 1.4.0

- Added Pricing Cards, Pricing Featured, Pricing Compact, Comparison — 2 Options, and Comparison — Feature Table patterns.
- Pricing uses editable Core blocks and responsive Core Grid; the comparison table uses Core Table.

## 1.3.0

- Added six editorial Query patterns and the optional Featured Image Hover Zoom style.

## 1.2.0

- Added Underline, Pills, Connected, and Filled styles for the Core Tabs block.

## 1.1.0

- Added the SCI icon collection and Minimal/Bordered Core Accordion styles.

## 1.0.0

- First stable release of the Gutenberg-native pattern library.
