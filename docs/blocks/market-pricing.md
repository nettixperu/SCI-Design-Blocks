# SCI Market Pricing

SCI Market Pricing connects an editable pricing pattern built with WordPress Core blocks to a market/currency selector. It does not draw or replace the pricing cards. The existing groups, headings, descriptions, feature lists, and buttons remain normal Gutenberg content.

## Add the pattern

1. Insert **SCI Pricing — Market Selector** from the SCI Design Blocks pattern category.
2. Edit the cards with Core Group, Heading, Paragraph, List, and Button blocks. Duplicate or remove card groups as needed.
3. Select the outer **SCI Market Pricing** block. Choose the selector style, whether to show its label, and optional colors from the theme palette. Then add or edit markets, currencies, and flag emoji; configure the form URL for each market.
4. Under **Plans and prices**, choose a market and set each plan's name, monthly amount, and period text. The starting plan IDs are `starter`, `business`, and `enterprise`; IDs remain fixed so existing bindings do not break.
5. Set the optional annual amount for each market. When it is empty, SCI calculates monthly amount × 12. Add a market-specific billing note such as `+ IGV` or `+ IVA` if needed. The sample pattern's annual line updates with the selected currency and stays hidden when no monthly amount is configured.
6. Select each Core price paragraph and Core Button in a card. In **SCI · selector de mercado**, set the same plan ID as in the container. Link the card's Core Heading as the plan name; the integrated sample pattern is already linked.
7. Set the default market, save, and verify the published page.

For a pricing pattern already in use, place its Core groups inside SCI Market Pricing, then assign a matching plan ID to each price paragraph, plan heading, and CTA button. On paragraphs, choose **Precio del plan** or **Facturación anual** under the SCI market inspector. A price paragraph is replaced with the formatted amount and billing period; an annual paragraph displays the selected market's annual amount (or monthly × 12) and optional tax note. Button label, style, and layout remain Core-controlled. The plan panel's name field updates the first heading in its linked card. **Eliminar plan y tarjeta** removes the corresponding Core card when the card contains a linked price paragraph and button; if a custom card is not recognized, remove that Core Group directly in Gutenberg as well.

## Add markets, currencies, and plans

Markets are not hard-coded to PEN or MXN. Each one has a stable ID, display name, optional flag emoji, ISO 4217 currency code, locale, form URL, and active/inactive setting. Add another currency or another market in the block inspector. `Intl.NumberFormat` formats the displayed amount using the selected market's locale and currency. SCI does not fetch exchange rates or convert amounts.

The selector can use a **segmented** radio group, separate **pill** buttons, or the native **dropdown**. Segmented is the integrated pattern's default. The **Mostrar banderas** control can hide flags; each market has a text field for its flag emoji. The “Moneda o mercado” label is optional and hidden in the integrated pattern by default. In the Selector de mercado panel, the theme palette can set general text, border, selector background, selected option fill, and selected option text; clearing a color returns that part to theme-inherited styling. Emoji appearance depends on the visitor's operating system and font, and native dropdown rendering varies by browser.

Plans are keyed by stable, read-only IDs. Each plan can have a different monthly amount, annual override, billing-period label, and tax note for each market. If no annual override is entered, annual billing is calculated from that market's monthly amount × 12; SCI does not convert currencies. Plan cards are Core blocks; add a matching plan record and link its price paragraph, heading, and button using the same ID. The configuration currently accepts up to 50 plans and 30 markets to bound saved input.

## Fluent Forms handoff and payment safety

Set the market's **Fluent Forms URL** to the payment form configured for that currency. A market can point to a distinct form (for example, one form for PEN and one for USD). In Fluent Forms, configure each payment form's currency and fixed-price Payment Items. Add a plan choice whose values match the SCI plan IDs and configure the form's supported dynamic default from the `plan` query parameter; capture the `market` parameter in a hidden field if useful for processing.

When a visitor clicks a linked Core Button, SCI adds only `plan=<stable-id>` and `market=<stable-id>` to the configured form URL. It never adds the displayed amount. The form's preconfigured Payment Item and payment gateway remain the source of truth for the charge. Keep the price shown in SCI aligned with the fixed amount in Fluent Forms, and verify each form/gateway/currency combination in staging. Do not treat a browser query parameter as authorization to set or change a charge.

Fluent Forms documents fixed-price Payment Items and payment form currency in [Payment Item fields](https://fluentforms.com/docs/add-payment-item-field-in-payment-forms/) and [payment settings](https://fluentforms.com/docs/configure-payment-settings-with-fluent-forms/). Dynamic defaults are described in [Dynamic Default Value](https://fluentforms.com/docs/dynamic-default-value-in-fluent-forms/). Confirm behavior with the installed Fluent Forms and gateway versions before launch.

## What SCI stores and renders

Market, currency, plan IDs, amounts, period labels, and form URLs are saved as attributes of the wrapper block. Price paragraphs and buttons stay Core blocks with one small SCI plan-ID attribute. The wrapper server-renders its selector around the saved Core content. A small view script changes marked text and links when the selector changes; no external JavaScript library is loaded. The visitor's market choice is not persisted in local storage.

The wrapper and its dynamic behavior require SCI Design Blocks to remain active. If the plugin is deactivated, WordPress retains the inner Core card content, but the market selector and price switching stop working until SCI is reactivated.

## Staging checks

- Insert the integrated pattern, save and reopen it, and confirm that the original groups and Core blocks remain editable.
- Change a plan name and verify its linked Core Heading changes; remove a plan and confirm its linked card is removed, then confirm at least one remaining plan cannot be deleted.
- Configure distinct monthly amounts for PEN, USD, and MXN. Verify annual rows update to each currency and default to monthly × 12; set an annual override and `+ IGV`/`+ IVA` note to confirm those are market-specific.
- Add a third currency and a fourth plan; configure a distinct amount and Fluent Forms URL per market. Confirm selector labels and `Intl.NumberFormat` output.
- Preview the segmented, pill, and dropdown styles; verify flag labels, the hide-flags toggle, selected state, keyboard focus, and radio/select keyboard interaction on desktop, iPad, and mobile.
- Link each price paragraph and button with the corresponding stable plan ID. Confirm that a market change updates only the intended price and that the button preserves its label/style while routing to the correct form.
- Inspect the resulting URL: it should contain `plan` and `market`, and must not contain an amount or price parameter.
- In Fluent Forms, verify the configured currency, fixed Payment Item, default plan selection from `plan`, and gateway payment flow for every market. Test invalid/blank prices and missing form URLs.
- Check a narrow viewport, keyboard operation of the native select, and browser console. Run a new mobile PageSpeed Insights measurement after deploying to staging if measuring performance.
