#!/bin/bash
set -x #echo on

# Add a new language

# Examples:
# ./addlang.sh en_US
# ./addlang.sh es_ES
# ./addlang.sh ca_ES

LOCALE=$1
LANG=`echo $LOCALE | cut -c1-2`

sudo dnf install langpacks-$LANG
mkdir -p $LOCALE/LC_MESSAGES
msginit --no-translator --input=messages.pot --locale=$LOCALE.utf8 -o $LANG.po 

