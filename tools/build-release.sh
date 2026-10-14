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
zip_path="$output_dir/sci-design-blocks-1.5.0.zip"
source_manifest="$output_dir/SOURCE-FILE-MANIFEST-1.5.0.sha256"
package_manifest="$output_dir/PACKAGE-FILE-MANIFEST-1.5.0.sha256"
provenance="$output_dir/BUILD-PROVENANCE-1.5.0.txt"
for artifact in "$zip_path" "$source_manifest" "$package_manifest" "$provenance"; do
	if [ -e "$artifact" ]; then
		printf 'Refusing to overwrite existing release artifact: %s\n' "$artifact" >&2
		exit 1
	fi
done

if [ -n "$(git -C "$repo_root" status --porcelain)" ]; then
	printf 'Refusing to package a dirty working tree.\n' >&2
	exit 1
fi
source_commit=$(git -C "$repo_root" rev-parse HEAD)
source_branch=$(git -C "$repo_root" branch --show-current)
if ! grep -q '^ \* Version: 1\.5\.0$' "$repo_root/sci-design-blocks.php" ||
	! grep -q '^ \* Requires at least: 7\.1$' "$repo_root/sci-design-blocks.php" ||
	! grep -q '^ \* Requires PHP: 8\.3$' "$repo_root/sci-design-blocks.php"; then
	printf 'Source plugin metadata is not the approved stable 1.5.0 configuration.\n' >&2
	exit 1
fi

tmp_root=$(mktemp -d "${TMPDIR:-/tmp}/sci-release-1.5.0.XXXXXX")
trap 'rm -rf "$tmp_root"' EXIT HUP INT TERM
package_root="$tmp_root/sci-design-blocks"
mkdir -p "$package_root"
for item in sci-design-blocks.php includes patterns assets icons LICENSE COPYING.md THIRD-PARTY-NOTICES.md; do
	cp -R "$repo_root/$item" "$package_root/"
done

if grep -Eq 'Version: 1\.5\.0-rc\.|Requires PHP: 7\.4' "$package_root/sci-design-blocks.php"; then
	printf 'Release package contains RC or stale PHP metadata.\n' >&2
	exit 1
fi

( cd "$repo_root" && {
	find includes patterns assets icons -type f -print
	printf '%s\n' sci-design-blocks.php LICENSE COPYING.md THIRD-PARTY-NOTICES.md
} | LC_ALL=C sort | while IFS= read -r path; do shasum -a 256 "$path"; done ) > "$source_manifest"
( cd "$package_root" && {
	find includes patterns assets icons -type f -print
	printf '%s\n' sci-design-blocks.php LICENSE COPYING.md THIRD-PARTY-NOTICES.md
} | LC_ALL=C sort | while IFS= read -r path; do shasum -a 256 "$path"; done ) > "$package_manifest"
if ! cmp -s "$source_manifest" "$package_manifest"; then
	printf 'Runtime source/package manifests differ.\n' >&2
	exit 1
fi

( cd "$tmp_root" && zip -X -q -r "$zip_path" sci-design-blocks )
unzip -t "$zip_path"
mkdir "$tmp_root/verify"
( cd "$tmp_root/verify" && unzip -q "$zip_path" && cd sci-design-blocks && shasum -a 256 -c "$package_manifest" )
zip_sha256=$(shasum -a 256 "$zip_path" | awk '{print $1}')
created_utc=$(date -u '+%Y-%m-%dT%H:%M:%SZ')
{
	printf 'Product: SCI Design Blocks\nVersion: 1.5.0\nSource commit: %s\nSource branch: %s\nBuilt UTC: %s\nZIP: %s\nSHA-256: %s\n' "$source_commit" "$source_branch" "$created_utc" "$(basename "$zip_path")" "$zip_sha256"
} > "$provenance"

printf 'ZIP SHA-256: %s\nSource commit: %s\nSource/package files: %s\nArtifacts: %s\n' "$zip_sha256" "$source_commit" "$(wc -l < "$source_manifest" | tr -d ' ')" "$output_dir"
