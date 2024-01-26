#!/bin/bash

PATH=$PATH:/usr/local/bin:/usr/bin

###BASEDIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]:-$0}"; )" &> /dev/null && pwd 2> /dev/null; )";
BASEDIR=/home/pi/mars-admin/fritzbox-yaf

source $BASEDIR/../config.txt

mysql -u $MYSQL_USER -p$MYSQL_PASSWD $MYSQL_DB --show-warnings --execute="LOAD DATA INFILE '/var/lib/mysql/ip_registry' IGNORE INTO TABLE ip_registry FIELDS TERMINATED BY '\t' (reverse_dns,ip);"
