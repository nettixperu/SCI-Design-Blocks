#!/usr/bin/env python3
"""Static checks for the scoped SCI Core Tabs styles."""
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
CSS_PATH = ROOT / "assets" / "css" / "tabs-styles.css"
css = CSS_PATH.read_text(encoding="utf-8")
clean = re.sub(r"/\*.*?\*/", "", css, flags=re.S)

if clean.count("{") != clean.count("}"):
    sys.exit("Tabs CSS braces are unbalanced.")
if "!important" in clean.lower():
    sys.exit("Tabs CSS must not use !important.")
if re.search(r"#[0-9a-f]{3,8}\b|\b(?:white|black)\b|rgba?\(|hsla?\(", clean, re.I):
    sys.exit("Hardcoded color found; Tabs colors must remain theme-native.")
if re.search(r"font-family|font-size|width\s*:|min-width\s*:|max-width\s*:", clean, re.I):
    sys.exit("SCI Tabs must preserve Core typography and auto-width.")
if "@media" in clean.lower():
    sys.exit("No responsive breakpoint is required for horizontal overflow.")

rules = re.findall(r"([^{}]+)\{([^{}]*)\}", clean)
if len(rules) != 4:
    sys.exit(f"Expected four compact Tabs CSS rules; found {len(rules)}.")

allowed_properties = {
    "flex-wrap", "overflow-x", "overscroll-behavior-inline", "border",
    "border-radius", "box-shadow", "content",
}
selectors = []
for selector_text, declarations in rules:
    for selector in selector_text.split(","):
        selector = selector.strip()
        if not selector.startswith(".wp-block-tabs.is-style-sci-"):
            sys.exit(f"Unscoped selector: {selector}")
        if not any(style in selector for style in ("sci-underline", "sci-pills")):
            sys.exit(f"Unexpected SCI Tabs style scope: {selector}")
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
    sys.exit("SCI Underline and Pills must keep the tab list horizontal and scrollable.")
responsive_selector_text = rules[0][0]
if "sci-underline" not in responsive_selector_text or "sci-pills" not in responsive_selector_text:
    sys.exit("Both opt-in styles must receive the responsive tab-list rules.")
if "currentColor" not in clean or "border-radius: 999px" not in clean:
    sys.exit("Pills must use contextual currentColor and its approved rounded treatment.")
if "[aria-selected=\"true\"]" not in clean:
    sys.exit("Pills active state selector is missing.")

print(
    f"Tabs CSS checks PASS: {CSS_PATH.stat().st_size} bytes, {len(rules)} scoped rules, "
    f"{len(selectors)} selectors, no hardcoded colors/dimensions, !important, or media queries."
)
