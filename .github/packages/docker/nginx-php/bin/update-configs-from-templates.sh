#!/usr/bin/env sh

set -e

echo Parsing templates...
for filename in $(find /etc/templates -name \*.tpl -print); do
	RESULT_TEMPLATE_FILE=$(echo "$filename" | sed 's/\/templates\//\//g' | sed 's/\.tpl$//g')
    echo " [*] $filename -> $RESULT_TEMPLATE_FILE"
    DOLLAR='$' envsubst < $filename > $RESULT_TEMPLATE_FILE
done