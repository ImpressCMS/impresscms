#!/usr/bin/env sh

ENV_VAR_NAME=$1
ENV_VAR_CURRENT_VALUE=$2
ENV_VAR_DEFAULT_VALUE=$3
ENV_EXIT_CODE=$4

if [ -z "$ENV_VAR_CURRENT_VALUE" ]; then
  if [ $ENV_EXIT_CODE -gt 0 ]; then
    echo "ERROR: Environnet variable $ENV_VAR_NAME must be not empty."
  	exit $ENV_EXIT_CODE
  else
    export "$ENV_VAR_NAME"="$ENV_VAR_DEFAULT_VALUE"
  	echo "WARNING: $ENV_VAR_NAME environemnt variable was empty that's why was changed to default value '$ENV_VAR_DEFAULT_VALUE'."
  fi;
fi;