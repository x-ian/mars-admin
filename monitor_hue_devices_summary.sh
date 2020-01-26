#!/bin/bash

# ./monitor_hue_devices_summary.sh > /var/www/html/hue

BASEDIR=$(dirname "$(realpath -s "$0")")

FALSE_COUNT=0

cut -d';' -f2 $BASEDIR/monitor_hue_devices_history.log | sort | uniq | while read -r i; do
  FALSE_COUNT=`grep "$i" $BASEDIR/monitor_hue_devices_history.log | grep "false" | wc -l`
  TRUE_COUNT=$(grep "$i" $BASEDIR/monitor_hue_devices_history.log | grep "true" | wc -l)
  RATE=`echo "scale=2; $TRUE_COUNT / ( $TRUE_COUNT + $FALSE_COUNT ) * 100" | bc`
  echo "$i ; availability ; $RATE ; true ; $TRUE_COUNT ; false ; $FALSE_COUNT"
done

