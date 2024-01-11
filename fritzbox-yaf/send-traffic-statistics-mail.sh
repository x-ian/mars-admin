#!/bin/bash

PATH=$PATH:/usr/local/bin:/usr/bin

###BASEDIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]:-$0}"; )" &> /dev/null && pwd 2> /dev/null; )";
BASEDIR=/home/pi/mars-admin/fritzbox-yaf

source $BASEDIR/../config.txt

curl 192.168.1.9:8000/traffic-month.php >/tmp/traffic-statistics.html

#mysql -u $MYSQL_USER -p$MYSQL_PASSWD $MYSQL_DB -H < $BASEDIR/traffic-statistics.sql > /tmp/traffic-statistics.html

SUBJECT="fritzbox traffic stats"
BODY="$(cat /tmp/traffic-statistics.html)"
$BASEDIR/../send-html-mail.sh "$SUBJECT" "$BODY"
