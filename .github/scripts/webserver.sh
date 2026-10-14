#!/usr/bin/env bash
# Start a web server for the Http suite with the example configuration from
# docs/server/ (or htdocs/htaccess.txt), on http://127.0.0.1:8080.
#
# Usage: .github/scripts/webserver.sh <apache|nginx|frankenphp> <2|3>
#
# Only what a test run needs is changed: addresses and paths, the test site's
# mainfile (ICMS_MAINFILE) and APP_ENV=test, passed as server variables.
set -euo pipefail

server=$1
level=$2
repo=$(pwd)
webroot="$repo/htdocs"
mainfile="$repo/var/test/mainfile.php"
php_version=$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')
socket="/run/php/php${php_version}-fpm.sock"
work=$(mktemp -d)

# Select a routing level in a configuration file: level 2 keeps "# level 2"
# lines; level 3 drops them and uncomments "# level 3" lines.
select_level() {
    if [ "$level" = 3 ]; then
        sed -E '/# level 2$/d; s/^([[:space:]]*)#(.*# level 3)$/\1\2/'
    else
        cat
    fi
}

# The web server users must reach the checkout.
sudo chmod o+rx "$HOME" "$(dirname "$repo")" "$repo"

start_fpm() {
    sudo apt-get install -y -qq "php${php_version}-fpm" > /dev/null
    # run as the checkout's owner: the host writes var/, legacy templates_c/ and cache/
    sudo sed -i -E "s/^(user|group) = .*/\1 = $(id -un)/; s/^listen\.owner = .*/listen.owner = www-data/; s/^listen\.group = .*/listen.group = www-data/" \
        "/etc/php/${php_version}/fpm/pool.d/www.conf"
    sudo systemctl restart "php${php_version}-fpm"
}

case "$server" in
    nginx)
        start_fpm
        select_level < docs/server/nginx.conf \
            | sed -E "s#server unix:/run/php/php-fpm.sock;#server unix:${socket};#; s#listen 80;#listen 8080;#; s#root /var/www/impresscms;#root ${webroot};#" \
            | sed -E "s#^([[:space:]]*)include fastcgi_params;#\1include fastcgi_params;\n\1fastcgi_param ICMS_MAINFILE ${mainfile};\n\1fastcgi_param APP_ENV test;#" \
            | sudo tee /etc/nginx/sites-available/impresscms > /dev/null
        sudo rm -f /etc/nginx/sites-enabled/default
        sudo ln -sf /etc/nginx/sites-available/impresscms /etc/nginx/sites-enabled/impresscms
        sudo nginx -t
        sudo systemctl restart nginx
        ;;
    apache)
        start_fpm
        select_level_htaccess() {
            if [ "$level" = 3 ]; then
                sed -E 's/^([[:space:]]*)#(RewriteRule \\\.php\$ index\.php \[L\])$/\1\2/'
            else
                cat
            fi
        }
        select_level_htaccess < htdocs/htaccess.txt > htdocs/.htaccess
        grep -q '^[[:space:]]*RewriteEngine On' htdocs/.htaccess
        sed -E "s#<VirtualHost \*:80>#<VirtualHost *:8080>#; s#/var/www/impresscms#${webroot}#g; s#unix:/run/php/php-fpm.sock#unix:${socket}#" docs/server/apache-vhost.conf \
            | sed -E "s#^([[:space:]]*)DocumentRoot (.*)#\1DocumentRoot \2\n\1SetEnv ICMS_MAINFILE ${mainfile}\n\1SetEnv APP_ENV test#" \
            | sudo tee /etc/apache2/sites-available/impresscms.conf > /dev/null
        echo 'Listen 8080' | sudo tee /etc/apache2/conf-available/listen-8080.conf > /dev/null
        sudo a2enconf -q listen-8080
        sudo a2enmod -q rewrite proxy proxy_fcgi setenvif
        sudo a2dissite -q 000-default
        sudo a2ensite -q impresscms
        sudo apache2ctl configtest
        sudo systemctl restart apache2
        ;;
    frankenphp)
        curl -fsSL -o "$work/frankenphp" https://github.com/php/frankenphp/releases/latest/download/frankenphp-linux-x86_64
        chmod +x "$work/frankenphp"
        "$work/frankenphp" version
        {
            printf '{\n\tauto_https off\n\tadmin off\n}\n\n'
            select_level < docs/server/Caddyfile \
                | sed -E "s#^example\.test \{#:8080 {#; s#root \* /var/www/impresscms#root * ${webroot}#" \
                | sed -E "s#^([[:space:]]*)php_server\$#\1php_server {\n\1\tenv ICMS_MAINFILE ${mainfile}\n\1\tenv APP_ENV test\n\1}#"
        } > "$work/Caddyfile"
        cat "$work/Caddyfile"
        "$work/frankenphp" validate --config "$work/Caddyfile" --adapter caddyfile
        nohup "$work/frankenphp" run --config "$work/Caddyfile" --adapter caddyfile > "$work/frankenphp.log" 2>&1 &
        ;;
    *)
        echo "Unknown server $server" >&2
        exit 2
        ;;
esac

for _ in $(seq 1 30); do
    if curl -fsS -o /dev/null http://127.0.0.1:8080/up; then
        echo "$server (routing level $level) is up on http://127.0.0.1:8080"
        exit 0
    fi
    sleep 1
done
echo "$server did not answer /up" >&2
curl -sS -i http://127.0.0.1:8080/up | head -40 || true
[ -f "$work/frankenphp.log" ] && tail -50 "$work/frankenphp.log"
exit 1
