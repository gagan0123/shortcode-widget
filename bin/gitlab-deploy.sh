#!/bin/bash

# Exit on error
set -e

# Configuration
SVN_URL="https://plugins.svn.wordpress.org/shortcode-widget/"
SVN_DIR="/tmp/svn-repo"
SRC_DIR="${CI_PROJECT_DIR:-.}" # Defaults to current dir if not in CI

# Check credentials
if [[ -z "$SVN_USERNAME" ]] || [[ -z "$SVN_PASSWORD" ]]; then
    echo "Error: SVN_USERNAME and SVN_PASSWORD must be set."
    exit 1
fi

echo "🚀 Starting deployment for $SVN_URL..."

# 1. Checkout SVN
echo "🔄 Checking out SVN repository..."
rm -rf "$SVN_DIR"
svn co "$SVN_URL" "$SVN_DIR" --depth immediates --quiet --username "$SVN_USERNAME" --password "$SVN_PASSWORD" --no-auth-cache

# 2. Sync Assets
echo "🎨 Syncing assets..."
if [ -d "$SRC_DIR/assets" ]; then
    mkdir -p "$SVN_DIR/assets"
    rsync -rc --delete "$SRC_DIR/assets/" "$SVN_DIR/assets/"
    # Add new assets to SVN
    svn add --force "$SVN_DIR/assets" 2>/dev/null || true
else
    echo "⚠️  No assets directory found."
fi

# 3. Sync Trunk
echo "📂 Syncing plugin files to trunk..."
mkdir -p "$SVN_DIR/trunk"
# Sync files using excludes
rsync -rc --delete --exclude-from="$SRC_DIR/bin/rsync-excludes.txt" "$SRC_DIR/" "$SVN_DIR/trunk/"

# 4. SVN Operations
echo "⚙️  Processing SVN changes..."
cd "$SVN_DIR"

# Add new files (force is needed for nested unversioned files)
# We look at status, filter for ? (unversioned), remove the status char, and add
if svn status | grep -q '^\?'; then
    svn status | grep '^\?' | sed 's/^? *//' | xargs -d '\n' -I {} svn add --force "{}"
fi

# Remove deleted files
# We look at status, filter for ! (missing), remove the status char, and delete
if svn status | grep -q '^\!'; then
    svn status | grep '^\!' | sed 's/^! *//' | xargs -d '\n' -I {} svn rm "{}"
fi

# 5. Commit Trunk
echo "💾 Committing trunk changes..."
# We use the tag name in the commit message if available, otherwise HEAD
TAG_NAME="${CI_COMMIT_TAG:-HEAD}"
svn ci -m "Deploy from GitLab CI: $TAG_NAME" --username "$SVN_USERNAME" --password "$SVN_PASSWORD" --no-auth-cache

# 6. Create SVN Tag (if running on a tag)
if [[ -n "$CI_COMMIT_TAG" ]]; then
    echo "🏷️  Creating SVN Tag $CI_COMMIT_TAG..."
    # Copy trunk to tags/TAG
    # First update to make sure we have the latest revision number from the commit above
    svn update --quiet

    # Check if tag already exists
    if svn info "$SVN_URL/tags/$CI_COMMIT_TAG" >/dev/null 2>&1; then
        echo "⚠️  Tag $CI_COMMIT_TAG already exists. Skipping tag creation."
    else
        svn cp "$SVN_DIR/trunk" "$SVN_DIR/tags/$CI_COMMIT_TAG"
        svn ci -m "Tagging version $CI_COMMIT_TAG" --username "$SVN_USERNAME" --password "$SVN_PASSWORD" --no-auth-cache
        echo "✅ Tag $CI_COMMIT_TAG created."
    fi
fi

echo "🎉 Deployment complete!"
