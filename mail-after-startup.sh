#!/bin/bash

BASEDIR=/home/pi/mars-admin

source $BASEDIR/config.txt

sleep 60

# PFSENSE / FreeBSD
# for some reasons it seems as at least in the logs the systems gets signal 15 twice, but at least
# not for normal reboots
# check for the last kernel boot message and see if directly before a signal 15 was invoked
#/usr/local/sbin/clog /var/log/system.log | /bin/grep "kernel boot file is /boot/kernel/kernel" -B 1 | /usr/bin/tail -2 | /bin/grep 'exiting on signal 15'
#CRASH=$?

# RASPBIAN
# search all first messages after startup plus the line before
grep -B 1 'x-info="http://www.rsyslog.com"] start' /var/log/syslog | tail -2 > /tmp/mail-after-startup.log
# check if rsyslogd properly exited on signal 15, if not then assume a crash
grep 'x-info="http://www.rsyslog.com"] exiting on signal 15.' /tmp/mail-after-startup.log
CRASH=$?

if [ $CRASH -eq 0 ]; then
	SUBJECT="`echo "startup at"` `date +%Y%m%d-%H%M`"
else
	CRASH_TIME=`head -1 /tmp/mail-after-startup.log | awk -F' ' '{print $2 $1 $3}'`
	SUBJECT="`echo "startup after crash at"` `date +%Y%m%d-%H%M` `echo " (likely crash time around $CRASH_TIME)"`"
fi
BODY="
SSH tunnel: `echo $SSH_TUNNEL_PORT`
Device name: `echo $DEVICE_NAME`

IP addresses:
`/sbin/ifconfig | grep "inet "`

(mail generated by script /home/pi/mars-admin/mail-after-startup.sh)
"
$BASEDIR/send-mail.sh "$SUBJECT" "$BODY"

