#!/bin/bash

# create table log_internet_speedtest(
#    begin timestamp not null,
#    ping decimal(6,3) not null,
#    down decimal(12,3) not null,
#    up decimal(12,3) not null
#);

LOG=/tmp/log-internet-speedtest.log

BASEDIR=/home/pi/mars-admin

source $BASEDIR/config.txt

# allow some time after restart to recover network connection

sleep 90
while true
do
	rm -f $LOG
	START=`date +%s`
	/usr/bin/speedtest-cli --csv >> $LOG
	PING=`cat $LOG | awk -F',' '{print $6}'`
	if [ -z "$PING" ]; then
		PING=-1
	fi
	DOWN=`cat $LOG | awk -F',' '{print $7}'`
	if [ -z "$DOWN" ]; then
		DOWN=-1
	fi
	UP=`cat $LOG | awk -F',' '{print $8}'`
	if [ -z "$UP" ]; then
		UP=-1
	fi
	#echo $START,$PING,$DOWN,$UP
	/usr/bin/mysql -u `echo $MYSQL_USER` -p`echo $MYSQL_PASSWD` mars <<EOF
INSERT INTO log_internet_speedtest (begin,ping,down,up) VALUES
(FROM_UNIXTIME($START),$PING,ROUND($DOWN,3),ROUND($UP,3));
EOF
	sleep 600
done

