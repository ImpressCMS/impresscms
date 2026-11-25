#!/usr/bin/env bash
# Set up a local ImpressCMS site for testing: database, PHP dev server, and a
# fully scripted run of the web installer (no browser needed).
#
# Usage: setup_local_site.sh [--mode copy|live] [--site-dir DIR] [--port N]
#                            [--modules a,b] [--db-name NAME] [--debug]
#                            [--remove-install-dir]
#
# Every option can also be given as an environment variable (see defaults below).
# Run with --help for the full list.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# ---- defaults (override with env vars or flags) ---------------------------
REPO="${REPO:-$(git -C "$SCRIPT_DIR" rev-parse --show-toplevel 2>/dev/null || pwd)}"
MODE="${MODE:-copy}"                       # copy = throwaway copy of htdocs, live = serve the checkout itself
SITE_DIR="${SITE_DIR:-$HOME/icms-local/site}"
HOST="${HOST:-127.0.0.1}"
PORT="${PORT:-8080}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"
DB_USER="${DB_USER:-icms}"
DB_PASS="${DB_PASS:-icms}"
DB_NAME="${DB_NAME:-icms_test}"
DB_PREFIX="${DB_PREFIX:-icms}"
DB_ROOT_CMD="${DB_ROOT_CMD:-}"             # e.g. "mysql -uroot" or "sudo mysql"; auto-detected if empty
ADMIN_NAME="${ADMIN_NAME:-Administrator}"  # display name
ADMIN_LOGIN="${ADMIN_LOGIN:-admin}"        # what you type on the login form
ADMIN_PASS="${ADMIN_PASS:-admin123}"
ADMIN_MAIL="${ADMIN_MAIL:-admin@example.test}"
MODULES="${MODULES:-}"                     # comma-separated module dirnames to install during setup
KEEP_INSTALL_DIR="${KEEP_INSTALL_DIR:-1}"  # 1 = leave htdocs/install in place (needed to re-run in live mode)
DEBUG="${DEBUG:-0}"                        # 1 = turn on the Developer Dashboard (inline debug) after install

usage() { awk 'NR>1 && /^#/{sub(/^# ?/,"");print;next} NR>1{exit}' "$0"; echo; echo 'Environment variables / defaults:'; grep -E '^[A-Z_]+="\$\{' "$0" | sed -E 's/="\$\{[A-Z_]+:-(.*)\}"\s*(#.*)?/  default: \1  \2/'; exit 0; }

while [ $# -gt 0 ]; do
  case "$1" in
    --mode) MODE="$2"; shift 2;;
    --site-dir) SITE_DIR="$2"; shift 2;;
    --port) PORT="$2"; shift 2;;
    --modules) MODULES="$2"; shift 2;;
    --db-name) DB_NAME="$2"; shift 2;;
    --keep-install-dir) KEEP_INSTALL_DIR=1; shift;;
    --debug) DEBUG=1; shift;;
    --remove-install-dir) KEEP_INSTALL_DIR=0; shift;;
    -h|--help) usage;;
    *) echo "Unknown option: $1" >&2; exit 2;;
  esac
done

say()  { printf '\033[1;34m==>\033[0m %s\n' "$*"; }
warn() { printf '\033[1;33m!!\033[0m %s\n' "$*" >&2; }
die()  { printf '\033[1;31mxx\033[0m %s\n' "$*" >&2; exit 1; }

[ -f "$REPO/htdocs/include/version.php" ] || die "No ImpressCMS checkout at $REPO (expected htdocs/include/version.php). Set REPO=..."

# Fail fast on live-mode conflicts, before anything is created.
if [ "$MODE" = "live" ]; then
  if ! git -C "$REPO" diff --quiet -- htdocs/mainfile.php; then
    die "htdocs/mainfile.php is already modified in the checkout, so a live install is probably active. Tear it down first (teardown_local_site.sh --site-dir <its dir>), or use --mode copy."
  fi
  [ -d "$REPO/htdocs/install" ] || die "$REPO/htdocs/install is missing (deleted by a previous install?). Restore it with: git -C $REPO checkout -- htdocs/install"
fi
[ "$MODE" != "copy" ] || [ ! -e "$SITE_DIR/htdocs" ] || die "$SITE_DIR/htdocs already exists. Run teardown_local_site.sh --site-dir $SITE_DIR first."

# ---- 1. PHP prerequisites ---------------------------------------------------
say "Checking PHP"
command -v php >/dev/null || die "php not found on PATH"
php -r 'exit(version_compare(PHP_VERSION, "7.4", ">=") ? 0 : 1);' || die "PHP >= 7.4 required (found $(php -r 'echo PHP_VERSION;'))"
# Capture first: "php -m | grep -q" under pipefail fails at random when grep exits early (SIGPIPE).
php_mods=$(php -m)
missing=""
for ext in pdo_mysql mbstring gd curl session pcre xml; do
  grep -qix "$ext" <<<"$php_mods" || missing="$missing $ext"
done
[ -z "$missing" ] || die "Missing PHP extensions:$missing (e.g. apt-get install php-mysql php-mbstring php-gd php-curl php-xml)"

# ---- 2. Database -----------------------------------------------------------
say "Checking database at $DB_HOST:$DB_PORT"
command -v mysql >/dev/null || die "mysql client not found (install mariadb-client / mysql-client)"
db_ok() { mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USER" -p"$DB_PASS" -e 'SELECT 1' >/dev/null 2>&1; }
m() { mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USER" -p"$DB_PASS" "$@"; }
find_root() {
  [ -n "$DB_ROOT_CMD" ] && return 0
  for cand in "mysql -uroot" "sudo -n mysql"; do
    if $cand -e 'SELECT 1' >/dev/null 2>&1; then DB_ROOT_CMD="$cand"; return 0; fi
  done
  return 1
}
grant_user() { # create the app user (if needed) and give it full rights on DB_NAME
  say "Creating/granting database user $DB_USER on $DB_NAME via '$DB_ROOT_CMD'"
  $DB_ROOT_CMD <<SQL
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
CREATE USER IF NOT EXISTS '$DB_USER'@'127.0.0.1' IDENTIFIED BY '$DB_PASS';
CREATE USER IF NOT EXISTS '$DB_USER'@'%' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'127.0.0.1';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'%';
FLUSH PRIVILEGES;
SQL
}
if ! db_ok; then
  find_root || die "Cannot log in as $DB_USER and no root access to create it. Start MariaDB/MySQL, or set DB_ROOT_CMD / DB_USER / DB_PASS."
  grant_user
  db_ok || die "Still cannot connect as $DB_USER after creating it"
fi
existing=$(m -N -e "SHOW TABLES FROM \`$DB_NAME\` LIKE '${DB_PREFIX}_users'" 2>/dev/null || true)
if [ -n "$existing" ]; then
  die "Database $DB_NAME already contains an ImpressCMS install (${DB_PREFIX}_users). Run teardown_local_site.sh first, or pick another DB_NAME."
fi
create_db() { m -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci" 2>/dev/null; }
if ! create_db; then
  # The user exists but has no rights on this DB name (e.g. a second site with --db-name).
  find_root || die "$DB_USER may not create database $DB_NAME and there is no root access to grant it. Set DB_ROOT_CMD or use an existing DB_NAME."
  grant_user
  create_db || die "Could not create database $DB_NAME as $DB_USER"
fi

# ---- 3. Web root + trust path ---------------------------------------------
mkdir -p "$SITE_DIR"
SITE_DIR="$(cd "$SITE_DIR" && pwd)"
TRUST="$SITE_DIR/trust_path"
case "$MODE" in
  copy)
    ROOT="$SITE_DIR/htdocs"
    [ ! -e "$ROOT" ] || die "$ROOT already exists. Run teardown_local_site.sh --site-dir $SITE_DIR first."
    say "Copying $REPO/htdocs -> $ROOT"
    cp -a "$REPO/htdocs" "$ROOT"
    ;;
  live)
    ROOT="$REPO/htdocs"
    ;;
  *) die "MODE must be copy or live";;
esac
mkdir -p "$TRUST"
chmod -R u+w "$ROOT/mainfile.php" "$ROOT/uploads" "$ROOT/modules" "$ROOT/templates_c" "$ROOT/cache" "$TRUST"

# ---- 4. PHP built-in web server ---------------------------------------------
URL="http://$HOST:$PORT"
if curl -s -o /dev/null --max-time 2 "$URL/"; then
  die "Something is already listening on $URL. Stop it or pass --port."
fi
say "Starting PHP dev server on $URL (log: $SITE_DIR/php-server.log)"
# OPcache off: the installer rewrites mainfile.php and immediately includes it; with
# revalidate_freq=2 the next request still sees the old file. Also makes code edits show up instantly.
nohup php -d opcache.enable=0 -S "$HOST:$PORT" -t "$ROOT" >"$SITE_DIR/php-server.log" 2>&1 &
echo $! >"$SITE_DIR/php-server.pid"
for _ in $(seq 1 20); do curl -s -o /dev/null --max-time 1 "$URL/" && break; sleep 0.25; done

# ---- 5. Drive the installer --------------------------------------------------
JAR="$SITE_DIR/install-cookies.txt"; rm -f "$JAR"
I="$URL/install"
step() { # step <name> <expected redirect target or "-"> curl-args...
  local name="$1" expect="$2"; shift 2
  local out code loc
  out=$(curl -sS -b "$JAR" -c "$JAR" -o "$SITE_DIR/last-step.html" -w '%{http_code} %{redirect_url}' "$@")
  code="${out%% *}"; loc="${out#* }"
  if [ "$expect" != "-" ] && [[ "$loc" != *"page_$expect.php" ]]; then
    die "Installer step '$name' did not advance (HTTP $code, redirect '$loc'). Page saved to $SITE_DIR/last-step.html; server log: $SITE_DIR/php-server.log"
  fi
  printf '   %-15s HTTP %s %s\n' "$name" "$code" "$loc"
}
say "Running installer"
SALT=$(head -c 96 /dev/urandom | base64 | tr -dc 'A-Za-z0-9' | head -c 64)
step langselect   start          -X POST -d lang=english "$I/page_langselect.php"
step start        -              "$I/page_start.php"
step modcheck     -              "$I/page_modcheck.php"
step pathsettings -              "$I/page_pathsettings.php"
step pathsettings dbconnection   -X POST --data-urlencode "ROOT_PATH=$ROOT" --data-urlencode "TRUST_PATH=$TRUST" --data-urlencode "URL=$URL" "$I/page_pathsettings.php"
step dbconnection -              "$I/page_dbconnection.php"
# The installer builds "mysql:host=$DB_HOST" and has no port field, but the runtime
# DSN is also "host=$DB_HOST;dbname=..." — so a non-default port rides along in the host.
ICMS_DB_HOST="$DB_HOST"; [ "$DB_PORT" = "3306" ] || ICMS_DB_HOST="$DB_HOST;port=$DB_PORT"
step dbconnection dbsettings     -X POST -d DB_TYPE=pdo.mysql --data-urlencode "DB_HOST=$ICMS_DB_HOST" --data-urlencode "DB_USER=$DB_USER" --data-urlencode "DB_PASS=$DB_PASS" "$I/page_dbconnection.php"
step dbsettings   -              "$I/page_dbsettings.php"
step dbsettings   configsave     -X POST --data-urlencode "DB_NAME=$DB_NAME" -d DB_CHARSET=utf8mb4 -d DB_COLLATION=utf8mb4_unicode_ci --data-urlencode "DB_PREFIX=$DB_PREFIX" -d "DB_SALT=$SALT" "$I/page_dbsettings.php"
step configsave   -              "$I/page_configsave.php"
step configsave   tablescreate   -X POST "$I/page_configsave.php"
step tablescreate -              "$I/page_tablescreate.php"
step tablescreate -              -X POST "$I/page_tablescreate.php"
step siteinit     -              "$I/page_siteinit.php"
step siteinit     tablesfill     -X POST --data-urlencode "adminname=$ADMIN_NAME" --data-urlencode "adminlogin_name=$ADMIN_LOGIN" --data-urlencode "adminmail=$ADMIN_MAIL" --data-urlencode "adminpass=$ADMIN_PASS" --data-urlencode "adminpass2=$ADMIN_PASS" "$I/page_siteinit.php"
step tablesfill   -              "$I/page_tablesfill.php"
step tablesfill   -              -X POST "$I/page_tablesfill.php"
step modules      -              "$I/page_modulesinstall.php"
mod_args=(-d mod=1)
IFS=',' read -ra _mods <<<"$MODULES"
for mod in "${_mods[@]}"; do [ -n "$mod" ] && mod_args+=(-d "install_mods[]=$mod"); done
step modules      -              -X POST "${mod_args[@]}" "$I/page_modulesinstall.php"
step end          -              "$I/page_end.php"
if [ "$KEEP_INSTALL_DIR" = "0" ]; then
  step cleanup    -              "$I/page_end.php?success=1"
fi

# ---- 6. Verify -----------------------------------------------------------------
say "Verifying"
tables=$(m -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME'")
[ "$tables" -gt 20 ] || die "Only $tables tables in $DB_NAME — install did not complete. See $SITE_DIR/last-step.html"
VJAR="$SITE_DIR/verify-cookies.txt"; rm -f "$VJAR"
curl -s -c "$VJAR" -b "$VJAR" -o /dev/null -X POST -d "uname=$ADMIN_LOGIN" --data-urlencode "pass=$ADMIN_PASS" -d op=login -d xoops_redirect=/admin.php "$URL/user.php"
admin_html=$(curl -sL -c "$VJAR" -b "$VJAR" "$URL/admin.php" || true)
if grep -qi 'logout' <<<"$admin_html"; then
  echo "   admin login OK"
else
  warn "Install finished but admin login could not be confirmed — check in a browser."
fi
if [ "$DEBUG" = "1" ]; then
  m "$DB_NAME" -e "UPDATE \`${DB_PREFIX}_config\` SET conf_value='1' WHERE conf_name='debug_mode' AND conf_modid=0"
  echo "   debug mode ON (Developer Dashboard: inline)"
fi
installed=$(m -N "$DB_NAME" -e "SELECT GROUP_CONCAT(dirname) FROM \`${DB_PREFIX}_modules\`")

cat >"$SITE_DIR/site.env" <<EOF
MODE=$MODE
REPO=$REPO
ROOT=$ROOT
TRUST=$TRUST
URL=$URL
HOST=$HOST
PORT=$PORT
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_USER=$DB_USER
DB_PASS=$DB_PASS
DB_NAME=$DB_NAME
DB_PREFIX=$DB_PREFIX
ADMIN_LOGIN=$ADMIN_LOGIN
ADMIN_PASS=$ADMIN_PASS
EOF

cat <<EOF

ImpressCMS is running.
  Site:        $URL/
  Admin panel: $URL/admin.php   (login: $ADMIN_LOGIN / $ADMIN_PASS)
  Mode:        $MODE   (web root: $ROOT)
  Trust path:  $TRUST
  Database:    $DB_NAME on $DB_HOST:$DB_PORT, prefix ${DB_PREFIX}_, $tables tables
  Modules:     $installed
  Server log:  $SITE_DIR/php-server.log   (PHP notices/warnings land here)
  Settings:    $SITE_DIR/site.env
Tear down with: $SCRIPT_DIR/teardown_local_site.sh --site-dir $SITE_DIR
EOF
