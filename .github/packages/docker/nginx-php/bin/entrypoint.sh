#!/usr/bin/env sh

if [ -f /var/local/bin/do-custom-vars-tasks.sh ]; then
  /var/local/bin/do-custom-vars-tasks.sh
fi;

random-file-env-var.sh "$DB_SALT" DB_SALT 32
random-file-env-var.sh "$DB_PREFIX" DB_PREFIX 4

validate-if-not-empty-env-var.sh DB_TYPE "$DB_TYPE" pdo.mysql 0
validate-if-not-empty-env-var.sh DB_USER "$DB_USER" root 0
validate-if-not-empty-env-var.sh DB_PCONNECT "$DB_PCONNECT" 0 0
validate-if-not-empty-env-var.sh DB_CHARSET "$DB_CHARSET" utf8 0
validate-if-not-empty-env-var.sh DB_COLLATION "$DB_COLLATION" utf8_general_ci 0
validate-if-not-empty-env-var.sh DB_PORT "$DB_PORT" 3306 0
validate-if-not-empty-env-var.sh WEB_PORT "$WEB_PORT" 80 0
validate-if-not-empty-env-var.sh SERVER_NAME "$SERVER_NAME" ICMSServer 0
validate-if-not-empty-env-var.sh INSTALL_LANGUAGE "$INSTALL_LANGUAGE" english 0
validate-if-not-empty-env-var.sh DB_NAME "$DB_NAME" icms 0
validate-if-not-empty-env-var.sh DB_HOST "$DB_HOST" '' 101
validate-if-not-empty-env-var.sh DB_PREFIX "$DB_PREFIX" '' 102
validate-if-not-empty-env-var.sh DB_SALT "$DB_SALT" '' 103
validate-if-not-empty-env-var.sh INSTALL_ADMIN_PASS "$INSTALL_ADMIN_PASS" '' 104
validate-if-not-empty-env-var.sh INSTALL_ADMIN_LOGIN "$INSTALL_ADMIN_LOGIN" '' 105
validate-if-not-empty-env-var.sh INSTALL_ADMIN_NAME "$INSTALL_ADMIN_NAME" '' 106
validate-if-not-empty-env-var.sh INSTALL_ADMIN_EMAIL "$INSTALL_ADMIN_EMAIL" '' 107

if [ -z "$DB_PASS" ]; then
  echo "WARNING: DB_PASS is empty. We do not recommend to use such value in production."
fi;

echo Parsing templates...
pushd /etc/templates/
    DOLLAR='$'
    for filename in *.tpl; do
        RESULT_TEMPLATE_FILE=$(echo "$filename" | sed 's/\/templates\//\//g' | sed 's/\.tpl$//g')
        echo " [*] $filename -> $RESULT_TEMPLATE_FILE"
        envsubst < $filename > $RESULT_TEMPLATE_FILE
    done
popd

pm2-runtime /etc/pm2/ecosystem.yml