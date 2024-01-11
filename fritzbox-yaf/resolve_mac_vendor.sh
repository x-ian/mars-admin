#!/bin/bash

BASEDIR=/home/pi/mars-admin/fritzbox-yaf

MAC=$(echo $1 | tr -d \"\')

MAC_FIRST_DIGITS=$(echo $MAC | sed -e 's/://g' | cut -c 1-6 | awk '{print toupper($0)}')
MAC_VENDOR=$(grep $MAC_FIRST_DIGITS $BASEDIR/ieee-oui.csv | awk -F"," '{ print $3 }'| sed -e 's/ /_/g')

if [ -z "${MAC_VENDOR}" ]; then
	MAC_VENDOR=unknown
fi

echo $(echo $MAC_VENDOR | tr -d \"\')
