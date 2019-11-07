#!/bin/bash

# try to send all net yet send mails from backlog dir in case 1st send wasn't successful
# keep it in backlog if still not successful

for i in `ls -t /home/pi/mail-backlog/*.sh`; do
	/bin/bash -x $i
done

