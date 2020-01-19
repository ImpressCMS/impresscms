#!/usr/bin/env sh

set -e

until nc -z -v -w30 "$DB_HOST" "$DB_PORT"
do
  sleep 1
done

cd /srv/www
./bin/phoenix migrate