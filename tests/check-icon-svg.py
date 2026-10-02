#!/usr/bin/env python3
"""Check fixture SVGs against the WordPress 7.1 Core icon sanitizer allowlist."""
import json
import sys
import xml.etree.ElementTree as ET
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
MANIFEST = ROOT / "icons" / "manifest.php"
SVG_DIR = ROOT / "icons" / "svg"

# WordPress 7.1.2: WP_Icons_Registry::sanitize_icon_content(). Root fill is
# intentionally stripped by Core; core/icon's own stylesheet sets currentColor.
ALLOWED = {
    "svg": {"xmlns", "width", "height", "viewbox", "class", "aria-hidden", "role", "focusable", "fill"},
    "path": {"fill", "fill-rule", "d", "transform"},
    "polygon": {"fill", "fill-rule", "points", "transform", "focusable"},
}

# Load the PHP manifest through the PHP interpreter to validate the same source
# PHP uses for registration; __() is stubbed only for this static check.
php = r'''function __( $text, $domain = null ) { return $text; }
$items = require $argv[1];
echo json_encode( $items, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR );'''
import subprocess
result = subprocess.run(["php", "-r", php, str(MANIFEST)], check=True, capture_output=True, text=True)
manifest = json.loads(result.stdout)
if len(manifest) != 7:
    sys.exit(f"Expected 7 manifest entries; got {len(manifest)}")

assets = {p.stem for p in SVG_DIR.rglob("*.svg")}
entries = {entry["local_name"] for entry in manifest.values()}
if assets != entries:
    sys.exit(f"Manifest/source mismatch. Manifest-only={entries - assets}; source-only={assets - entries}")

for name, entry in manifest.items():
    if name != entry["local_name"] or entry["source_name"] != name:
        sys.exit(f"Name/provenance mismatch for {name}")
    path = SVG_DIR / f"{name}.svg"
    root = ET.parse(path).getroot()
    if root.tag != "{http://www.w3.org/2000/svg}svg":
        sys.exit(f"Root is not an SVG-namespaced element: {path}")
    text = path.read_text(encoding="utf-8").lower()
    if any(token in text for token in ("<script", "<foreignobject", "href=", "url(", "stroke=")):
        sys.exit(f"Unsafe or Core-incompatible SVG feature in {path}")
    for element in root.iter():
        tag = element.tag.rsplit("}", 1)[-1].lower()
        attrs = {key.rsplit("}", 1)[-1].lower() for key in element.attrib}
        if tag not in ALLOWED or attrs - ALLOWED[tag]:
            sys.exit(f"Outside Core 7.1 allowlist: {path}: <{tag}> {sorted(attrs - ALLOWED.get(tag, set()))}")
    print(f"{name}.svg PASS")

runtime_css_js = [p for p in ROOT.rglob("*") if p.is_file() and p.suffix.lower() in {".css", ".js"} and not ({".git", "audit-input"} & set(p.relative_to(ROOT).parts))]
if runtime_css_js:
    sys.exit("Unexpected CSS or JavaScript file in plugin source: " + ", ".join(str(p.relative_to(ROOT)) for p in runtime_css_js))
if (ROOT / "package.json").exists() or (ROOT / "composer.json").exists() or (ROOT / "vendor").exists():
    sys.exit("Unexpected runtime/development dependency manifest or vendor tree")
print("SVG/source checks PASS: 7 entries match 7 safe, allowlist-compatible sources; no plugin CSS/JS/dependency manifest.")
