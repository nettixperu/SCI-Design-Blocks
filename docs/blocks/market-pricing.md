# SCI Market Pricing

SCI Market Pricing connects an editable pricing pattern built with WordPress Core blocks to a market/currency selector. It does not draw or replace the pricing cards. The existing groups, headings, descriptions, feature lists, and buttons remain normal Gutenberg content.

## Add the pattern

1. Insert **SCI Pricing — Market Selector** from the SCI Design Blocks pattern category.
2. Edit the cards with Core Group, Heading, Paragraph, List, and Button blocks. Duplicate or remove card groups as needed.
3. Select the outer **SCI Market Pricing** block. Add or edit markets and currencies, then configure the form URL for each one.
4. Under **Plans and prices**, choose a market and enter each plan's amount and period text. The starting plan IDs are `starter`, `business`, and `enterprise`; add as many plans as needed.
5. Select each Core price paragraph and each Core Button in a card. In **SCI · selector de mercado**, set the same plan ID as in the container. The sample pattern is already linked.
6. Set the default market, save, and verify the published page.

For a pricing pattern already in use, place its Core groups inside SCI Market Pricing, then assign a matching plan ID to each price paragraph and CTA button. A price paragraph should contain only the price value; its text is replaced with the formatted amount and billing period when the visitor changes market. Button label, style, and layout remain Core-controlled.

## Add markets, currencies, and plans

Markets are not hard-coded to PEN or MXN. Each one has a stable ID, display name, ISO 4217 currency code, locale, form URL, and active/inactive setting. Add another currency or another market in the block inspector. `Intl.NumberFormat` formats the displayed amount using the selected market's locale and currency. SCI does not fetch exchange rates or convert amounts.

Plans are keyed by stable IDs. Each plan can have a different amount and billing-period label for each market. Plan cards are just Core blocks, so their count and content can be changed in Gutenberg; add a matching plan record in the container and link its price paragraph and button using the same ID. The configuration currently accepts up to 50 plans and 30 markets to bound saved input.

## Fluent Forms handoff and payment safety

Set the market's **Fluent Forms URL** to the payment form configured for that currency. A market can point to a distinct form (for example, one form for PEN and one for USD). In Fluent Forms, configure each payment form's currency and fixed-price Payment Items. Add a plan choice whose values match the SCI plan IDs and configure the form's supported dynamic default from the `plan` query parameter; capture the `market` parameter in a hidden field if useful for processing.

When a visitor clicks a linked Core Button, SCI adds only `plan=<stable-id>` and `market=<stable-id>` to the configured form URL. It never adds the displayed amount. The form's preconfigured Payment Item and payment gateway remain the source of truth for the charge. Keep the price shown in SCI aligned with the fixed amount in Fluent Forms, and verify each form/gateway/currency combination in staging. Do not treat a browser query parameter as authorization to set or change a charge.

Fluent Forms documents fixed-price Payment Items and payment form currency in [Payment Item fields](https://fluentforms.com/docs/add-payment-item-field-in-payment-forms/) and [payment settings](https://fluentforms.com/docs/configure-payment-settings-with-fluent-forms/). Dynamic defaults are described in [Dynamic Default Value](https://fluentforms.com/docs/dynamic-default-value-in-fluent-forms/). Confirm behavior with the installed Fluent Forms and gateway versions before launch.

## What SCI stores and renders

Market, currency, plan IDs, amounts, period labels, and form URLs are saved as attributes of the wrapper block. Price paragraphs and buttons stay Core blocks with one small SCI plan-ID attribute. The wrapper server-renders its selector around the saved Core content. A small view script changes marked text and links when the selector changes; no external JavaScript library is loaded. The visitor's market choice is not persisted in local storage.

The wrapper and its dynamic behavior require SCI Design Blocks to remain active. If the plugin is deactivated, WordPress retains the inner Core card content, but the market selector and price switching stop working until SCI is reactivated.

## Staging checks

- Insert the integrated pattern, save and reopen it, and confirm that the original groups and Core blocks remain editable.
- Add a third currency and a fourth plan; configure a distinct amount and Fluent Forms URL per market. Confirm selector labels and `Intl.NumberFormat` output.
- Link each price paragraph and button with the corresponding stable plan ID. Confirm that a market change updates only the intended price and that the button preserves its label/style while routing to the correct form.
- Inspect the resulting URL: it should contain `plan` and `market`, and must not contain an amount or price parameter.
- In Fluent Forms, verify the configured currency, fixed Payment Item, default plan selection from `plan`, and gateway payment flow for every market. Test invalid/blank prices and missing form URLs.
- Check a narrow viewport, keyboard operation of the native select, and browser console. Run a new mobile PageSpeed Insights measurement after deploying to staging if measuring performance.
