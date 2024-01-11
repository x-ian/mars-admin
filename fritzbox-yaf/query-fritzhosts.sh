#!/bin/bash

BASEDIR=/home/pi/mars-admin/fritzbox-yaf

/home/pi/.local/bin/fritzhosts > /tmp/fritzhosts

cat /tmp/fritzhosts | tail -n +11 | head -n -2 | tr -s ' ' |cut -d ' ' -f3,4,5 | awk '{print "REPLACE INTO local_hosts (mac, hostname, ip, query_time, mac_vendor) VALUES (LOWER( \"" $3 "\" ), \"" $2 "\", \"" $1 "\", NOW(), \"<mac_vendor_to_be_replaced>\" );"}' > /tmp/fritzhosts-insert.sql


#echo 'INSERT INTO local_hosts (mac, hostname, ip, query_time, mac_vendor) VALUES (LOWER("B8:27:EB:93:2A:E7"),"zigbee2mqttold","192.168.1.222", NOW(), "<mac_vendor>" );' | awk -F" " '{ print $14 }'

rm /tmp/fritzhosts-insert-2.sql

while IFS= read -r line; do
	
    echo "Text read from file: $line"
	MAC=$(echo $line | awk -F" " '{ print $11 }')
	MAC_VENDOR=$($BASEDIR/resolve_mac_vendor.sh $MAC)
	echo $line | sed "s/<mac_vendor_to_be_replaced>/$MAC_VENDOR/g" >> /tmp/fritzhosts-insert-2.sql
	
done < /tmp/fritzhosts-insert.sql

mysql -u mars -pGeheimerM@rS! mars < /tmp/fritzhosts-insert-2.sql

cat /tmp/fritzhosts | grep active | awk '{ print "UPDATE local_hosts SET last_active = NOW() WHERE mac = LOWER(\"" $4 "\"); "}'  > /tmp/fritzhosts-lastactive.sql

mysql -u mars -pGeheimerM@rS! mars < /tmp/fritzhosts-lastactive.sql 
