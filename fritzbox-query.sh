#!/bin/bash

PATH=$PATH:/usr/bin

BASEDIR=$(dirname "$(realpath -s "$0")")
TIMESTAMP=`date +%Y%m%d-%H%M%S`

LOG=$BASEDIR/fritzbox-query.log

touch $LOG

FRITZBOX_IP=fritz.box

TOTAL_BYTES_SENT=$(curl -s "http://$FRITZBOX_IP:49000/igdupnp/control/WANCommonIFC1" -H "Content-Type: text/xml; charset="utf-8"" -H "SoapAction:urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1#GetAddonInfos" -d "@$BASEDIR/fritzbox-query-linkspeed.xml" | grep NewTotalBytesSent | tr -d '</NewTotalBytesSent>')
TOTAL_BYTES_RECEIVED=$(curl -s "http://$FRITZBOX_IP:49000/igdupnp/control/WANCommonIFC1" -H "Content-Type: text/xml; charset="utf-8"" -H "SoapAction:urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1#GetAddonInfos" -d "@$BASEDIR/fritzbox-query-linkspeed.xml" | grep NewTotalBytesReceived | tr -d '</NewTotalBytesReceived>')


echo "$TIMESTAMP ; RECEIVED ; $TOTAL_BYTES_RECEIVED ; SENT ; $TOTAL_BYTES_SENT" >> $LOG
