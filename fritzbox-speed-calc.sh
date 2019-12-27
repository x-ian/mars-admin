#!/bin/bash

# ./fritzbox-speed-calc.sh > fritzbox-speed-rearranged.csv

BASEDIR=$(dirname "$(realpath -s "$0")")

PREVIOUS_SENT=''
PREVIOUS_RECEIVE=''

input="$BASEDIR/fritzbox-query.log"
while IFS= read -r line
do
#  echo "$line"
  TIMESTAMP=`echo $line | cut -d";" -f1`
  REC=`echo $line | cut -d";" -f3`
  SENT=`echo $line | cut -d";" -f5`
#  echo "-$REC-$SENT-"
#  REC_RATE=$(($REC - $PREVIOUS_RECEIVE))
  echo "$TIMESTAMP ; RECEIVED_RATE ; $(expr \($REC - $PREVIOUS_RECEIVE\) / 300 / 1000 '*' 8) ; SENT_RATE ; $(expr \($SENT - $PREVIOUS_SENT \) / 300 / 1000 '*' 8 )"
  PREVIOUS_SENT=$SENT
  PREVIOUS_RECEIVE=$REC
done < "$input"
