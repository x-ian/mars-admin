#!/bin/bash

# try to send all net yet send mails from backlog dir in case 1st send wasn't successful
# keep it in backlog if still not successful

BASEDIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]:-$0}"; )" &> /dev/null && pwd 2> /dev/null; )";

for i in `ls -t $BASEDIR/mail-backlog/*.sh`; do
	/bin/bash -x $i
done

