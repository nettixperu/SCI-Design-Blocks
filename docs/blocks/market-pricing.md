# SCI Market Pricing

SCI Market Pricing connects an editable pricing pattern built with WordPress Core blocks to a market/currency selector. It does not draw or replace the pricing cards. The existing groups, headings, descriptions, feature lists, and buttons remain normal Gutenberg content. The selector and pricing pattern can be sibling blocks inside the same Group; the cards do not need to be nested inside the selector.

## Add the pattern

1. Insert **SCI Pricing — Market Selector** from the SCI Design Blocks pattern category.
2. Edit the cards with Core Group, Heading, Paragraph, List, and Button blocks. Duplicate or remove card groups as needed.
3. Select the outer **SCI Market Pricing** block. Choose the selector style, whether to show its label, and optional colors from the theme palette. Then add or edit markets, currencies, and flag emoji; configure the form URL for each market.
4. Under **Planes y precios**, choose a market and set each plan's name, monthly amount, and period text. Each plan gets an ID automatically; you can edit it, and SCI updates linked Core blocks inside this container.
5. Set the optional annual amount for each market. When it is empty, SCI calculates monthly amount × 12. Add a market-specific billing note such as `+ IGV` or `+ IVA` if needed. The sample pattern's annual line updates with the selected currency and stays hidden when no monthly amount is configured.
6. To link a price manually, select its Core Paragraph, open **Bloque → SCI · selector de mercado**, enter the plan's fixed ID, and choose **Precio del plan**. Then select that card's Core Button and enter the same ID in **ID del plan del botón**. If you want the button itself to display the current market's amount, enable **Mostrar el precio mensual en este botón**; this replaces its text with the formatted price and period while keeping its Fluent Forms link. Leave this option off for a normal CTA button. Link the card's Core Heading to that ID if you want the plan name field to update the heading. The integrated sample pattern is already linked.
7. Set the default market, save, and verify the published page.

For a pricing pattern already in use, place the selector and pricing pattern inside the same **Group**; they can be siblings in List View. SCI searches the nearest shared container for linked Core blocks. Assign a matching plan ID to each price paragraph, plan heading, and button. SCI warns if no shared Group contains both the selector and the linked Core block. On paragraphs, choose **Precio del plan** or **Facturación anual** under the SCI market inspector. A price paragraph is replaced with the formatted amount and billing period; an annual paragraph displays the selected market's annual amount (or monthly × 12) and optional tax note. A linked button can either retain its Core CTA text or display the selected market's monthly price and period when **Mostrar el precio mensual en este botón** is enabled. The price and route update in the site preview or on the published page, not in the editor canvas. Its style and plan-specific Fluent Forms route remain associated with the Core Button. The plan panel's name field updates the first heading in its linked card. **Eliminar plan y tarjeta** removes the corresponding Core card when the card contains a linked price paragraph and button; if a custom card is not recognized, remove that Core Group directly in Gutenberg as well.

## Add markets, currencies, and plans

Markets are not hard-coded to PEN or MXN. Each one has a stable ID, display name, optional manual flag emoji, ISO 4217 currency code, locale, form URL, and active/inactive setting. If **Bandera manual (opcional)** is blank, SCI derives the flag from the country/region in the locale (for example, `es-PE` → 🇵🇪, `es-MX` → 🇲🇽, `en-US` → 🇺🇸). If the locale has no two-letter region, no flag is shown. Enter an emoji only to override the derived flag. Add another currency or another market in the block inspector. `Intl.NumberFormat` formats the displayed amount using the selected market's locale and currency. SCI does not fetch exchange rates or convert amounts.

The selector can use a **segmented** radio group, separate **pill** buttons, or the native **dropdown**. Segmented is the integrated pattern's default. The **Mostrar banderas** control can hide derived or manual flags. The “Moneda o mercado” label is optional and hidden in the integrated pattern by default. In the Selector de mercado panel, the theme palette can set general text, border, selector background, selected option fill, and selected option text; clearing a color returns that part to theme-inherited styling. Emoji appearance depends on the visitor's operating system and font, and native dropdown rendering varies by browser.

Plans are keyed by stable, read-only IDs. Each plan can have a different monthly amount, annual override, billing-period label, and tax note for each market. If no annual override is entered, annual billing is calculated from that market's monthly amount × 12; SCI does not convert currencies. Plan cards are Core blocks; add a matching plan record and link its price paragraph, heading, and button using the same ID. The configuration currently accepts up to 50 plans and 30 markets to bound saved input.

## Fluent Forms handoff and payment safety

Set the market's **Fluent Forms URL** to the payment form configured for that currency. A market can point to a distinct form (for example, one form for PEN and one for USD). In Fluent Forms, configure each payment form's currency and fixed-price Payment Items. Add a plan choice whose values match the SCI plan IDs and configure the form's supported dynamic default from the `plan` query parameter; capture the `market` parameter in a hidden field if useful for processing.

When a visitor clicks a linked Core Button, SCI adds only `plan=<stable-id>` and `market=<stable-id>` to the configured form URL. It never adds the displayed amount. The form's preconfigured Payment Item and payment gateway remain the source of truth for the charge. Keep the price shown in SCI aligned with the fixed amount in Fluent Forms, and verify each form/gateway/currency combination in staging. Do not treat a browser query parameter as authorization to set or change a charge.

Fluent Forms documents fixed-price Payment Items and payment form currency in [Payment Item fields](https://fluentforms.com/docs/add-payment-item-field-in-payment-forms/) and [payment settings](https://fluentforms.com/docs/configure-payment-settings-with-fluent-forms/). Dynamic defaults are described in [Dynamic Default Value](https://fluentforms.com/docs/dynamic-default-value-in-fluent-forms/). Confirm behavior with the installed Fluent Forms and gateway versions before launch.

## What SCI stores and renders

Market, currency, plan IDs, amounts, period labels, and form URLs are saved as attributes of the SCI Market Pricing block. Price paragraphs and buttons stay Core blocks with one small SCI plan-ID attribute. The block server-renders the selector; a small view script updates marked Core blocks in its own content or the nearest shared container when the selector changes. No external JavaScript library is loaded. The visitor's market choice is not persisted in local storage.

The selector and its dynamic behavior require SCI Design Blocks to remain active. If the plugin is deactivated, WordPress retains the Core card content, but the market selector and price switching stop working until SCI is reactivated.

## Staging checks

- Insert the integrated pattern, save and reopen it, and confirm that the original groups and Core blocks remain editable.
- Leave a market's manual flag empty and verify `es-PE`, `es-MX`, `en-US`, and a locale without a region; enter a manual emoji and verify it overrides the derived one. Toggle **Mostrar banderas** off and on.
- Change a plan name or ID and verify its linked Core Heading/Paragraph/Button update; remove a plan and confirm its linked card is removed, then confirm at least one remaining plan cannot be deleted.
- Configure distinct monthly amounts for PEN, USD, and MXN. Verify annual rows update to each currency and default to monthly × 12; set an annual override and `+ IGV`/`+ IVA` note to confirm those are market-specific.
- Enable **Mostrar el precio mensual en este botón** on a linked Core Button and change markets; verify button text and route update together. Disable it and confirm a regular CTA label is preserved.
- Add a third currency and a fourth plan; configure a distinct amount and Fluent Forms URL per market. Confirm selector labels and `Intl.NumberFormat` output.
- Preview the segmented, pill, and dropdown styles; verify flag labels, the hide-flags toggle, selected state, keyboard focus, and radio/select keyboard interaction on desktop, iPad, and mobile.
- Link each price paragraph and button with the corresponding stable plan ID. Confirm that a market change updates only the intended price and that the button preserves its label/style while routing to the correct form.
- Inspect the resulting URL: it should contain `plan` and `market`, and must not contain an amount or price parameter.
- In Fluent Forms, verify the configured currency, fixed Payment Item, default plan selection from `plan`, and gateway payment flow for every market. Test invalid/blank prices and missing form URLs.
- Check a narrow viewport, keyboard operation of the native select, and browser console. Run a new mobile PageSpeed Insights measurement after deploying to staging if measuring performance.
