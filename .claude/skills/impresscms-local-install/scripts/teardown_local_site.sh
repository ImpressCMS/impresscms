#!/usr/bin/env bash
# Remove a local ImpressCMS test site created by setup_local_site.sh:
# stops the PHP server, drops the database, deletes the site directory and,
# in live mode, puts the git checkout back the way it was.
#
# Usage: teardown_local_site.sh [--site-dir DIR] [--keep-db]
set -euo pipefail

SITE_DIR="${SITE_DIR:-$HOME/icms-local/site}"
KEEP_DB=0
while [ $# -gt 0 ]; do
  case "$1" in
    --site-dir) SITE_DIR="$2"; shift 2;;
    --keep-db) KEEP_DB=1; shift;;
    -h|--help) sed -n '2,7p' "$0" | sed 's/^# \{0,1\}//'; exit 0;;
    *) echo "Unknown option: $1" >&2; exit 2;;
  esac
done

say() { printf '\033[1;34m==>\033[0m %s\n' "$*"; }
[ -f "$SITE_DIR/site.env" ] || { echo "No site.env in $SITE_DIR — nothing to tear down (or pass --site-dir)." >&2; exit 1; }
# shellcheck disable=SC1091
. "$SITE_DIR/site.env"

if [ -f "$SITE_DIR/php-server.pid" ]; then
  say "Stopping PHP server"
  kill "$(cat "$SITE_DIR/php-server.pid")" 2>/dev/null || true
fi

if [ "$KEEP_DB" = "0" ]; then
  say "Dropping database $DB_NAME"
  mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USER" -p"$DB_PASS" -e "DROP DATABASE IF EXISTS \`$DB_NAME\`"
fi

if [ "$MODE" = "live" ]; then
  say "Restoring the checkout at $REPO"
  chmod u+w "$REPO/htdocs/mainfile.php" 2>/dev/null || true
  git -C "$REPO" checkout -- htdocs/mainfile.php htdocs/install
  # The installer copies protector into modules/ and the site writes caches;
  # remove only untracked files in those runtime folders.
  git -C "$REPO" clean -fdq -- htdocs/modules/protector htdocs/cache htdocs/templates_c htdocs/uploads
fi

say "Removing $SITE_DIR"
chmod -R u+w "$SITE_DIR" 2>/dev/null || true
rm -rf "$SITE_DIR"
[ "$MODE" = "live" ] && git -C "$REPO" status --short -- htdocs
echo "Done."
