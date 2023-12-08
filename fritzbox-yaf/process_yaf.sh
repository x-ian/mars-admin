#!/bin/bash

PATH=$PATH:/usr/local/bin:/usr/bin

BASEDIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]:-$0}"; )" &> /dev/null && pwd 2> /dev/null; )";
source $BASEDIR/../config.txt

cd $BASEDIR/yaf
find . -name 'yaf-*' ! -empty -type f -exec mv {} $BASEDIR/yaf_work/ \;
yafscii --in '/home/pi/mars-admin/fritzbox-yaf/yaf_work/*' --out - --mac --tabular --print-header >$BASEDIR/traffic_details_yaf.tsv
$BASEDIR/ipfix-parse.py $BASEDIR/traffic_details_yaf.tsv /var/lib/mysql/traffic_details.csv
mv $BASEDIR/yaf_work/* $BASEDIR/yaf_done/
mysql -u $MYSQL_USER -p$MYSQL_PASSWD $MYSQL_DB --show-warnings --execute="LOAD DATA INFILE '/var/lib/mysql/traffic_details.csv' IGNORE INTO TABLE traffic_details FIELDS TERMINATED BY '\t' (day,mac,remote_ip, outgoing,incoming);"
#rm -f /tmp/traffic_details_yaf.tsv /var/db/mysql_secure/traffic_details.csv

# cleanup unrelevant messy data (no MAC address or less than 1 MB of up AND down traffic)
# propably shouldn't have been imported in the first place

mysql -u $MYSQL_USER -p$MYSQL_PASSWD $MYSQL_DB --execute="delete from traffic_details where mac ='';

mysql -u $MYSQL_USER -p$MYSQL_PASSWD $MYSQL_DB -H < $BASEDIR/traffic-statistics.sql > /tmp/traffic-statistics.html

SUBJECT="fritzbox traffic stats"
BODY="`cat /tmp/traffic-statistics.html`"
$BASEDIR/../send-html-mail.sh "$SUBJECT" "$BODY"
