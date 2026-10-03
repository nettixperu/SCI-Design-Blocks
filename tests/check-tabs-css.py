#!/usr/bin/env python3
"""Static checks for the scoped SCI Core Tabs styles."""
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
CSS_PATH = ROOT / "assets" / "css" / "tabs-styles.css"
css = CSS_PATH.read_text(encoding="utf-8")
clean = re.sub(r"/\*.*?\*/", "", css, flags=re.S)
neutral_shadow = "box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.08)"
color_scan = clean.replace(neutral_shadow, "")

if clean.count("{") != clean.count("}"):
    sys.exit("Tabs CSS braces are unbalanced.")
if "!important" in clean.lower():
    sys.exit("Tabs CSS must not use !important.")
if re.search(r"#[0-9a-f]{3,8}\b|\bwhite\b(?!-space)|\bblack\b|rgba?\(|hsla?\(", color_scan, re.I):
    sys.exit("Hardcoded color found outside the documented neutral Connected shadow.")
if re.search(r"font-family|font-size|width\s*:|min-width\s*:|max-width\s*:|height\s*:|min-height\s*:|max-height\s*:|flex\s*:", clean, re.I):
    sys.exit("SCI Tabs must preserve Core typography and auto-width.")
if re.search(r"text-overflow\s*:|overflow\s*:\s*hidden|text-overflow\s*:\s*ellipsis", clean, re.I):
    sys.exit("SCI Tabs must not truncate or hide long tab labels.")
if "@media" in clean.lower():
    sys.exit("No responsive breakpoint is required for horizontal overflow.")

rules = re.findall(r"([^{}]+)\{([^{}]*)\}", clean)
if len(rules) != 12:
    sys.exit(f"Expected twelve compact Tabs CSS rules; found {len(rules)}.")
if sum(len(selector_text.split(",")) for selector_text, _ in rules) != 18:
    sys.exit("Expected eighteen compact scoped selectors.")

allowed_properties = {
    "flex-wrap", "overflow-x", "overscroll-behavior-inline", "border", "border-color",
    "border-radius", "box-shadow", "content", "position", "z-index",
    "margin-bottom", "border-bottom-color", "background-color", "background-image",
    "flex-shrink", "white-space",
}
selectors = []
styles_found = set()
for selector_text, declarations in rules:
    for selector in selector_text.split(","):
        selector = selector.strip()
        if not selector.startswith(".wp-block-tabs.is-style-sci-"):
            sys.exit(f"Unscoped selector: {selector}")
        styles = {style for style in ("sci-underline", "sci-pills", "sci-connected", "sci-filled") if style in selector}
        if not styles:
            sys.exit(f"Unexpected SCI Tabs style scope: {selector}")
        styles_found.update(styles)
        selectors.append(selector)

    for declaration in declarations.split(";"):
        declaration = declaration.strip()
        if not declaration:
            continue
        if ":" not in declaration:
            sys.exit(f"Malformed declaration: {declaration}")
        prop, value = (part.strip() for part in declaration.split(":", 1))
        if prop not in allowed_properties or not value:
            sys.exit(f"Unexpected/empty declaration: {prop}: {value}")

if "flex-wrap: nowrap" not in clean or "overflow-x: auto" not in clean:
    sys.exit("All SCI styles must keep the tab list horizontal and scrollable.")
responsive_selector_text = rules[0][0]
if any(style not in responsive_selector_text for style in ("sci-underline", "sci-pills", "sci-connected", "sci-filled")):
    sys.exit("Every opt-in style must receive the responsive tab-list rules.")
if styles_found != {"sci-underline", "sci-pills", "sci-connected", "sci-filled"}:
    sys.exit(f"Unexpected or missing style scopes: {sorted(styles_found)}")
if "currentColor" not in clean or "border-radius: 999px" not in clean:
    sys.exit("Pills must use contextual currentColor and its approved rounded treatment.")
if "[aria-selected=\"true\"]" not in clean:
    sys.exit("Pills active state selector is missing.")
if not any(".wp-block-tabs.is-style-sci-connected" in selector for selector in selectors):
    sys.exit("Connected selectors are missing or unscoped.")
if "border-bottom-color: transparent" not in clean or neutral_shadow not in clean:
    sys.exit("Connected must visually join its active tab to the panel with the documented subtle shadow.")
if ".wp-block-tabs.is-style-sci-connected .wp-block-tab-list button[aria-selected=\"true\"]::before" not in clean:
    sys.exit("Connected must suppress the Core underline pseudo-element only for its active tab.")
if ".wp-block-tabs.is-style-sci-connected .wp-block-tab-panels" not in clean:
    sys.exit("Connected panel border selector is missing.")
if ".wp-block-tabs.is-style-sci-filled > .wp-block-tab-list" not in clean:
    sys.exit("Filled shared navigation surface selector is missing.")
if "background-color: color-mix(in srgb, currentColor 5%, transparent)" not in clean:
    sys.exit("Filled surface must derive its tint from the contextual Core text color.")
if "background-image: linear-gradient(" not in clean or "currentColor 14%, transparent" not in clean:
    sys.exit("Filled selected state must add a currentColor-derived fill layer.")
if "border-color: currentColor" not in clean or "border-radius: 0.25rem" not in clean:
    sys.exit("Filled selected state needs a visible theme-aware segment boundary and modest radius.")
filled_active = next(
    (declarations for selector_text, declarations in rules if ".wp-block-tabs.is-style-sci-filled .wp-block-tab-list button[aria-selected=\"true\"]" in selector_text),
    None,
)
if filled_active is None or "background-image:" not in filled_active or "background-color:" in filled_active:
    sys.exit("Filled must overlay its active tint without replacing the Core background-color.")

nowrap_rule = next(
    ((selector_text, declarations) for selector_text, declarations in rules if ".wp-block-tabs.is-style-sci-underline .wp-block-tab-list button" in selector_text),
    None,
)
if nowrap_rule is None or "white-space: nowrap" not in nowrap_rule[1] or "flex-shrink: 0" not in nowrap_rule[1]:
    sys.exit("Every SCI tab button must preserve its full label on one line without flex shrink.")
if not all(f".wp-block-tabs.is-style-{style} .wp-block-tab-list button" in nowrap_rule[0] for style in ("sci-underline", "sci-pills", "sci-connected", "sci-filled")):
    sys.exit("The single-line label fix must be shared and scoped to all four SCI styles.")

print(
    f"Tabs CSS checks PASS: {CSS_PATH.stat().st_size} bytes, {len(rules)} scoped rules, "
    f"{len(selectors)} selectors, no hardcoded brand colors/dimensions, truncation, !important, or media queries."
)
