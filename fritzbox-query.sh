#!/bin/bash

BASEDIR=$(dirname "$(realpath -s "$0")")
FRITZBOX_IP="192.168.1.1"

TOTAL_BYTES_SENT=$(curl -s "http://$FRITZBOX_IP:49000/igdupnp/control/WANCommonIFC1" -H "Content-Type: text/xml; charset="utf-8"" -H "SoapAction:urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1#GetAddonInfos" -d "@fritzbox-query-linkspeed.xml" | grep NewTotalBytesSent | tr -d '</NewTotalBytesSent>')
TOTAL_BYTES_RECEIVED=$(curl -s "http://$FRITZBOX_IP:49000/igdupnp/control/WANCommonIFC1" -H "Content-Type: text/xml; charset="utf-8"" -H "SoapAction:urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1#GetAddonInfos" -d "@fritzbox-query-linkspeed.xml" | grep NewTotalBytesReceived | tr -d '</NewTotalBytesReceived>')

echo $TOTAL_BYTES_SENT
echo $TOTAL_BYTES_RECEIVED
