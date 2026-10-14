# M7 — Pattern expansion decisions

Baseline: `6a65d35051e6d2623d0970819e18daeb3c61103d`, branch `feature/sci-pattern-library-expansion`. Scope is the 17 M0 compositions evaluated against the 22 patterns present at baseline. Patterns remain static, unsynced compositions of WordPress Core blocks.

## M0 equivalence matrix

| ID | Composition / required structure | Existing SCI or Core equivalent | Treatment | New pattern / rationale | Complexity / maintenance |
| --- | --- | --- | --- | --- | --- |
| SCI-001 | Split hero with background | Hero Split; Core Columns/Group | EXISTING SCI PATTERN | Existing split hero covers editable copy, buttons and image. | Low; none |
| SCI-002 | Centered eyebrow, section heading and intro | Editorial Lead is a Query composition, not a static section intro | NEW PATTERN | Static section-level introduction is a distinct insertion workflow. | Low |
| SCI-003 | Unified section with 3+ benefits | Individual Feature patterns | NEW PATTERN | Grid-level section heading/introduction and multiple benefit cards are not provided by individual features. | Medium |
| SCI-004 | Image-led feature cards in a grid | Feature — Image Vertical; Core Grid | NEW PATTERN (combined with SCI-007) | A coordinated multi-card image grid is distinct from one feature; shared card structure avoids two near-identical patterns. | Medium |
| SCI-005 | Centered benefits | Proposed SCI-003; Core alignment controls | CORE CUSTOMIZATION | Centering changes presentation, not structure or editing workflow. | None |
| SCI-006 | Pricing offer paired with feature explanation | Three-plan Pricing patterns; Comparison patterns | NEW PATTERN | One offer plus adjacent explanatory feature list is a different decision workflow from multi-plan comparison. | Medium |
| SCI-007 | Service cards with image, copy and CTA | Proposed SCI-004 image-card grid | CORE CUSTOMIZATION | SCI-004 cards include editable optional CTA; use them as service cards without duplicating structure. | None |
| SCI-008 | Split hero with image and CTA | Hero Split | EXISTING SCI PATTERN | Same content order, columns and editing behavior. | None |
| SCI-009 | Cover hero with dual CTA | Hero Cover | EXISTING SCI PATTERN | Existing Core Cover supports editable background and Buttons. | None |
| SCI-010 | Rounded-image split hero with dual CTA | Hero Split; Core image style controls | CORE CUSTOMIZATION | Radius and button count are user-configurable decoration/content. | None |
| SCI-011 | Centered CTA banner | SCI CTA; Core Group alignment/background | CORE CUSTOMIZATION | Alignment and background do not justify a second CTA composition. | None |
| SCI-012 | Wide-screen horizontal CTA that stacks naturally | SCI CTA is vertical | NEW PATTERN | Deliberately paired message/action columns create a distinct compact insertion workflow. | Low |
| SCI-013 | Footer with newsletter | Core Site Editor/template parts; subscription needs provider/backend | DEFERRED | A visual fake form would mislead; no Core processing or provider dependency is in scope. | High / external integration |
| SCI-014 | Five-column corporate footer with navigation | Core Site Editor template parts, Navigation, Site Logo/Title, Social Links | SITE EDITOR COMPOSITION | Global footer belongs in theme template parts; insertion as page content risks duplicate navigation and false global behavior. | Medium / theme-context QA |
| SCI-015 | Minimal centered footer | Core Group/Row and template parts | SITE EDITOR COMPOSITION | Trivial to compose in the Site Editor; no distinct plugin insertion value. | Low |
| SCI-016 | Navigation header with CTA | Core Navigation and Site Editor template parts | SITE EDITOR COMPOSITION | Preserve Core's theme-managed menu and responsive navigation behavior; do not present as a page-section substitute. | High / browser gate |
| SCI-017 | Top bar plus navigation/CTA | Core Groups, Navigation, Site Editor template parts | SITE EDITOR COMPOSITION | Global header workflow and responsive menu remain theme-owned; no custom menu engine. | High / browser gate |

## Selected contracts

| Slug | M0 | Purpose / blocks | Editable content and responsive contract | Accessibility, portability and regression |
| --- | --- | --- | --- | --- |
| `sci-design-blocks/section-intro` | SCI-002 | Static centered section heading and supporting paragraphs; Core Group, Paragraph, Heading | Eyebrow and copy editable; constrained width; theme controls alignment/spacing. | H2 default; text order preserved; no external resources; available in existing SCI category. |
| `sci-design-blocks/benefits-grid` | SCI-003, SCI-005 | Intro plus three benefit cards; Core Group/Grid/Heading/Paragraph | All copy/card groups editable; Core auto-fit grid wraps at narrow widths. | Heading defaults H2/H3; no color-only meaning or hover dependency; checks Grid and three card structures. |
| `sci-design-blocks/service-cards` | SCI-004, SCI-007 | Intro plus three image-led service cards; Core Group/Grid/Image/Heading/Paragraph/Buttons | Images use Core media picker and editable alt; buttons and content editable; auto-fit layout, natural card height. | Empty Image save markup matches Core static serialization; CTA text and targets explicitly examples; check image serialization and card structure. |
| `sci-design-blocks/pricing-split` | SCI-006 | One offer and adjacent feature explanation; Core Columns/Group/Heading/Paragraph/List/Buttons | Offer, amount, features and CTA all editable; columns stack on mobile; no fixed heights. | Logical reading order and H2/H3 hierarchy; Core list semantics; check column stacking attribute and no regression to 3-plan pricing. |
| `sci-design-blocks/cta-horizontal` | SCI-012 | Compact message/action pair; Core Columns/Heading/Paragraph/Buttons | Copy and buttons editable; columns stack on mobile. | H2 default, meaningful local example target; check buttons are Core and responsive stacking is enabled. |

All contracts use existing category `sci-design-blocks`, require WordPress 7.1+/PHP 8.3+, and introduce no custom block, frontend JS, CSS, dependency, remote image, provider form, or theme-specific API. WordPress theme styles remain user-controlled. Exact rendered/editor validation is reported separately; static validation does not certify Gutenberg save/reopen, visual responsive behavior or WCAG conformance.
