#!/bin/bash

BASEDIR=/home/pi/mars-admin/fritzbox-yaf

/home/pi/.local/bin/fritzhosts > /tmp/fritzhosts

cat /tmp/fritzhosts | tail -n +11 | head -n -2 | tr -s ' ' |cut -d ' ' -f3,4,5 | awk '{print "INSERT INTO local_hosts (mac, hostname, ip, query_time) VALUES (LOWER(\"" $3 "\"),\"" $2 "\",\"" $1 "\", NOW());"}' > /tmp/fritzhosts-insert.sql

mysql -u mars -pGeheimerM@rS! mars < /tmp/fritzhosts-insert.sql

