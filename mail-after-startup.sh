#!/bin/bash

BASEDIR=/home/pi/mars-admin

source $BASEDIR/config.txt

sleep 60

# checking for system crashes
# for some reasons it seems as at least in the logs the systems gets signal 15 twice, but at least
# not for normal reboots
# check for the last kernel boot message and see if directly before a signal 15 was invoked
/usr/local/sbin/clog /var/log/system.log | /bin/grep "kernel boot file is /boot/kernel/kernel" -B 1 | /usr/bin/tail -2 | /bin/grep 'exiting on signal 15'
CRASH=$?

if [ $CRASH -eq 0 ]; then
	SUBJECT="`echo "startup at"` `date +%Y%m%d-%H%M`"
else
	SUBJECT="`echo "startup after crash at"` `date +%Y%m%d-%H%M`"	
fi
BODY="
SSH tunnel: `echo $SSH_TUNNEL_PORT`
Device name: `echo $DEVICE_NAME`

IP addresses:
`/sbin/ifconfig | grep "inet "`

(mail generated by script /home/pi/mars-admin/mail-after-startup.sh)
"
$BASEDIR/send-mail.sh "$SUBJECT" "$BODY"

