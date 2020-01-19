#!/usr/bin/env sh

set -e

until nc -z -v -w30 "$DB_HOST" "$DB_PORT" >/dev/null 2>&1; do
  sleep 1
done

cd /srv/www
./bin/phoenix migrate