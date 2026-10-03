#!/usr/bin/env python3
"""Validate the scoped opt-in Core Post Featured Image Hover Zoom style."""
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
CSS_PATH = ROOT / "assets" / "css" / "editorial-image-styles.css"
css = CSS_PATH.read_text(encoding="utf-8")
clean = re.sub(r"/\*.*?\*/", "", css, flags=re.S)
rules = re.findall(r"([^{}]+)\{([^{}]*)\}", clean, flags=re.S)

if clean.count("{") != clean.count("}") or len(rules) != 4:
    sys.exit(f"Expected four balanced Hover Zoom CSS rules; found {len(rules)}.")
if "!important" in clean.lower():
    sys.exit("Hover Zoom must not use !important.")
if clean.count("@media") != 2 or "(hover: hover) and (prefers-reduced-motion: no-preference)" not in clean:
    sys.exit("Hover Zoom must gate the transform on hover capability and reduced-motion preference.")
if "@media (prefers-reduced-motion: reduce)" not in clean or "transition: none" not in clean or "transform: none" not in clean:
    sys.exit("Hover Zoom must neutralize motion when reduced motion is requested.")
if "transform: scale(1.05)" not in clean or "transition: transform 250ms ease" not in clean:
    sys.exit("Hover Zoom scale/transition differs from its approved subtle behavior.")

selectors = []
for selector_text, _ in rules:
    for selector in selector_text.split(","):
        selector = selector.strip()
        if not selector.startswith(".wp-block-post-featured-image.is-style-sci-hover-zoom"):
            sys.exit(f"Unscoped Hover Zoom selector: {selector}")
        selectors.append(selector)

if len(selectors) != 5 or len(set(selectors)) != 4:
    sys.exit(f"Expected five scoped selector arms across four rules; got {len(selectors)} arms/{len(set(selectors))} unique.")
if "width:" in clean or "height:" in clean or "position:" in clean:
    sys.exit("Hover Zoom must not change layout geometry.")

print(f"Hover Zoom CSS checks PASS: {CSS_PATH.stat().st_size} bytes, 4 rules, 5 selectors, 2 media queries, 0 !important.")
