#!/bin/bash

# pings all network devices in the local network and sends out emails if some 
# are not responding

# best done with a daily cronjob like 0 7 * * * /home/marsPortal/misc/monitor_network_devices.sh

BASEDIR=/home/pi/mars-admin

source $BASEDIR/config.txt

LOG=/tmp/monitor_hue_devices.log
HISTORYLOG=$BASEDIR/monitor_hue_devices_history.log

TIMESTAMP=`date +%Y%m%d-%H%M%S`

rm $LOG
rm $LOG.tmp

curl http://philips-hue/api/5OT3CdgYuuE8K9ltcbNvhuwgQtVNoHtWRq8WxN6y/lights | jq '.[] | "'`echo $TIMESTAMP`' ; \(.name) ; \(.state.reachable)"' >> $HISTORYLOG

