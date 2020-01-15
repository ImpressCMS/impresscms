#!/usr/bin/env sh

ENV_CURRENT_VALUE=$1
ENV_VAR_NAME=$2
ENV_VAR_LENGTH=$3

ENV_VAR_FILE="/etc/$ENV_VAR_NAME.impresscms.cfg"

if [ -z "$ENV_CURRENT_VALUE" ]; then
  if [ ! -f "$ENV_VAR_FILE" ]; then
  	echo "Generating random $ENV_VAR_NAME..."
    echo $(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w $ENV_VAR_LENGTH | head -n 1) > $ENV_VAR_FILE
    chmod 0444 $ENV_VAR_FILE
  fi;
  echo "Reading $ENV_VAR_NAME from file..."
  export "$ENV_VAR_NAME"=$(cat $ENV_VAR_FILE)
fi;