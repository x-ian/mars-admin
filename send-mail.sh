#!/bin/bash

BASEDIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]:-$0}"; )" &> /dev/null && pwd 2> /dev/null; )";

source $BASEDIR/config.txt

TIMESTAMP=`date +%Y%m%d-%H%M%S`

SUBJECT="$1 ($DEVICE_NAME,$SSH_TUNNEL_PORT)"
BODY=$2
SENDER=$AuthUser
#RECEIVER=$RECEIVER already part of config.txt

mkdir $BASEDIR/mail-backlog
TEMP_MAIL=`mktemp $BASEDIR/mail-backlog/$TIMESTAMP-XXXXXX`.sh
echo "From: $SENDER
To: $RECEIVER
Subject: $SUBJECT

$BODY" > $TEMP_MAIL.mail

# place mail job in backlog of mails
echo "#!/bin/bash
/usr/bin/msmtp -C $MSMTP_CONFIG $RECEIVER < $TEMP_MAIL.mail > $TEMP_MAIL.exit 2>&1
# if [ $? -eq 0 ]; then # used to work, doesnt anymore...
if [ ! -s "$TEMP_MAIL.exit" ]; then
	rm -f $TEMP_MAIL*
fi
" > $TEMP_MAIL

# try to send it once right away
/bin/bash -x $TEMP_MAIL

