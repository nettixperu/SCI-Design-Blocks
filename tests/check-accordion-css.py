#!/usr/bin/env python3
"""Small dependency-free checks for SCI's opt-in Core Accordion styles."""
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
CSS_PATH = ROOT / "assets" / "css" / "accordion-styles.css"
css = CSS_PATH.read_text(encoding="utf-8")
clean = re.sub(r"/\*.*?\*/", "", css, flags=re.S)

if clean.count("{") != clean.count("}") or clean.count("{") != 6:
    sys.exit("Expected six balanced CSS rule blocks.")
if "!important" in clean.lower():
    sys.exit("Accordion CSS must not use !important.")
if "@" in clean:
    sys.exit("No at-rules or responsive overrides are needed for this stylesheet.")
if re.search(r"#[0-9a-f]{3,8}\b|\b(?:white|black)\b|rgba?\(|hsla?\(", clean, re.I):
    sys.exit("Hardcoded color found; Accordion colors must inherit from the theme.")
if re.search(r"font-family|font-size|box-shadow|background(?:-color)?\s*:", clean, re.I):
    sys.exit("Typography, shadows, or hardcoded backgrounds are outside the style contract.")

rules = re.findall(r"([^{}]+)\{([^{}]*)\}", clean)
if len(rules) != 6:
    sys.exit(f"Could not parse all CSS rule blocks: {len(rules)}")

allowed_properties = {
    "border-block-start", "border-block-start-color", "border-block-end",
    "border-block-end-color", "border", "border-color", "margin-block-start",
    "padding-inline", "padding",
}
selectors = []
for selector_text, declarations in rules:
    for selector in selector_text.split(","):
        selector = selector.strip()
        if not selector.startswith((
            ".wp-block-accordion.is-style-sci-minimal",
            ".wp-block-accordion.is-style-sci-bordered",
        )):
            sys.exit(f"Unscoped selector: {selector}")
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

if not any("is-style-sci-minimal" in selector for selector in selectors):
    sys.exit("Minimal style selectors are missing.")
if not any("is-style-sci-bordered" in selector for selector in selectors):
    sys.exit("Bordered style selectors are missing.")
if "color-mix(in srgb, currentColor 24%, transparent)" not in clean:
    sys.exit("Borders must inherit the contextual theme color with a fallback.")
if "var(--wp--preset--spacing--20, 1rem)" not in clean:
    sys.exit("Bordered spacing must use a WordPress spacing preset with a fallback.")

print(f"Accordion CSS checks PASS: {CSS_PATH.stat().st_size} bytes, {len(rules)} scoped rules, 0 !important, no CSS assets outside styles.")
