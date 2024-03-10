#!/bin/bash

# create table log_internet_ping(
#    begin timestamp not null,
#    end timestamp not null,
#    transmitted int not null,
#    received int not null,
#    packet_loss varchar(50) not null,
#    rtt_avg decimal(6,3) not null
#);

# -- log_internet_ping packet loss per day
# select date(begin), sum(packet_loss <>'0%'), sum(packet_loss ='0%') from log_internet_ping where  begin>='2024-01-01'  group by date(begin);
 
HOST=8.8.8.8
LOG=/tmp/log-internet-ping.log

BASEDIR=/home/pi/mars-admin

source $BASEDIR/config.txt

# allow some time after restart to recover network connection

sleep 60
while true
do
	rm -f $LOG
	START=`date +%s`
	ping -q -w 60 -i 5 $HOST >> $LOG
	STOP=`date +%s`
	TRANSMITTED=`grep "transmitted" $LOG | awk '{print $1}'`
	if [ -z "$TRANSMITTED" ]; then
		TRANSMITTED=-1
	fi
	RECEIVED=`grep "transmitted" $LOG | awk '{print $4}'`
	if [ -z "$RECEIVED" ]; then
		RECEIVED=-1
	fi
	PACKET_LOSS=`grep "transmitted" $LOG | awk '{print $6}'`
	if [ -z "$PACKET_LOSS" ]; then
		PACKET_LOSS=-1
	fi
	RTT_AVG=`grep "rtt" $LOG | awk '{print $4}' | awk -F'/' '{print $2}'`
	if [ -z "$RTT_AVG" ]; then
		RTT_AVG=-1
	fi

#echo Ping of $START, $STOP, $TRANSMITTED, $RECEIVED, $PACKET_LOSS, $RTT_AVG

	/usr/bin/mysql -u `echo $MYSQL_USER` -p`echo $MYSQL_PASSWD` mars <<EOF
INSERT INTO log_internet_ping (begin,end,transmitted,received,packet_loss,rtt_avg) VALUES
(FROM_UNIXTIME($START),FROM_UNIXTIME($STOP),$TRANSMITTED,$RECEIVED,"$PACKET_LOSS",$RTT_AVG);
EOF
	sleep 60
done

