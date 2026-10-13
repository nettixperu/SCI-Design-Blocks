#!/bin/sh
set -eu
repo_root=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
output_dir=${1:-}
if [ -z "$output_dir" ]; then
	printf 'Usage: %s OUTPUT_DIRECTORY\n' "$0" >&2
	exit 2
fi

case "$output_dir" in
	/*) ;;
	*) output_dir="$repo_root/$output_dir" ;;
esac
mkdir -p "$output_dir"
tmp_root=$(mktemp -d "${TMPDIR:-/tmp}/sci-rc6.XXXXXX")
trap 'rm -rf "$tmp_root"' EXIT HUP INT TERM
package_root="$tmp_root/sci-design-blocks"
mkdir -p "$package_root"

for item in sci-design-blocks.php includes patterns assets icons LICENSE COPYING.md THIRD-PARTY-NOTICES.md; do
	cp -R "$repo_root/$item" "$package_root/"
done

perl -0pi -e 's/^ \* Version: 1\.5\.0-rc\.3$/ * Version: 1.5.0-rc.6/m' "$package_root/sci-design-blocks.php"
if ! grep -q '^ \* Version: 1\.5\.0-rc\.6$' "$package_root/sci-design-blocks.php" ||
	! grep -q '^ \* Requires at least: 7\.1$' "$package_root/sci-design-blocks.php" ||
	! grep -q '^ \* Requires PHP: 8\.3$' "$package_root/sci-design-blocks.php"; then
	printf 'Distribution header does not match the approved RC6 metadata.\n' >&2
	exit 1
fi

( cd "$tmp_root" && zip -qr "$output_dir/sci-design-blocks-1.5.0-rc.6.zip" sci-design-blocks )
unzip -t "$output_dir/sci-design-blocks-1.5.0-rc.6.zip"

( cd "$repo_root" && {
	find includes patterns assets icons -type f -print
	printf '%s\n' sci-design-blocks.php LICENSE COPYING.md THIRD-PARTY-NOTICES.md
} | LC_ALL=C sort | while IFS= read -r path; do shasum -a 256 "$path"; done ) > "$output_dir/SOURCE-FILE-MANIFEST-rc.6.sha256"

( cd "$tmp_root" && find sci-design-blocks -type f -print | LC_ALL=C sort | while IFS= read -r path; do shasum -a 256 "$path"; done ) > "$output_dir/RC-FILE-MANIFEST-rc.6.sha256"

source_count=$(wc -l < "$output_dir/SOURCE-FILE-MANIFEST-rc.6.sha256" | tr -d ' ')
package_count=$(wc -l < "$output_dir/RC-FILE-MANIFEST-rc.6.sha256" | tr -d ' ')
if [ "$source_count" -ne "$package_count" ]; then
	printf 'Source/package manifest count mismatch: %s / %s\n' "$source_count" "$package_count" >&2
	exit 1
fi

( cd "$tmp_root" && unzip -q "$output_dir/sci-design-blocks-1.5.0-rc.6.zip" -d extracted )
( cd "$tmp_root/extracted" && shasum -c "$output_dir/RC-FILE-MANIFEST-rc.6.sha256" )
shasum -a 256 "$output_dir/sci-design-blocks-1.5.0-rc.6.zip"
printf 'Source files: %s; package files: %s\n' "$source_count" "$package_count"
printf 'RC6 package and manifests created under %s\n' "$output_dir"
