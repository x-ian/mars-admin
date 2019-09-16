#!/bin/bash

HOST=$1

ping -A $HOST | while read pong
do
	echo $pong > /tmp/pingc.log
	grep -v "ms" /tmp/pingc.log
	if [ $? -eq 0 ]; then
		echo "$(date): $HOST: $pong"
	fi
done

