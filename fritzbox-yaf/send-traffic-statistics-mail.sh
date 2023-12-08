#!/bin/bash

BASEDIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]:-$0}"; )" &> /dev/null && pwd 2> /dev/null; )";

source $BASEDIR/config.txt

SUBJECT="test"

BODY="`cat /tmp/traffic-statistics.html`"

$BASEDIR/send-html-mail.sh "$SUBJECT" "$BODY"

