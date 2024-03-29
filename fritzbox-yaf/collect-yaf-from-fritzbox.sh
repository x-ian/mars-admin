#!/bin/bash

BASEDIR=/home/pi/mars-admin/fritzbox-yaf

sleep 60

PATH=$PATH:/usr/local/bin:/usr/bin

# This is the address of the router
FRITZIP=http://192.168.1.1

# This is the WAN interface
IFACE="1-wan"
IFACE="2-1"

# Lan Interface
IFACE="3-0"

IFACE="1-lan"

# If you use password-only authentication use 'dslf-config' as username.
FRITZUSER=$1
FRITZPWD=$2

SIDFILE="/tmp/fritz.sid"

if [ -z "$FRITZPWD" ] || [ -z "$FRITZUSER" ]  ; then echo "Username/Password empty. Usage: $0 <username> <password>" ; exit 1; fi

echo "Trying to login into $FRITZIP as user $FRITZUSER"

if [ ! -f $SIDFILE ]; then
  touch $SIDFILE
fi

SID=$(cat $SIDFILE)

# Request challenge token from Fritz!Box
CHALLENGE=$(curl -k -s $FRITZIP/login_sid.lua |  grep -o "<Challenge>[a-z0-9]\{8\}" | cut -d'>' -f 2)

# Very proprieatry way of AVM: Create a authentication token by hashing challenge token with password
HASH=$(perl -MPOSIX -e '
    use Digest::MD5 "md5_hex";
    my $ch_Pw = "$ARGV[0]-$ARGV[1]";
    $ch_Pw =~ s/(.)/$1 . chr(0)/eg;
    my $md5 = lc(md5_hex($ch_Pw));
    print $md5;
  ' -- "$CHALLENGE" "$FRITZPWD")
  curl -k -s "$FRITZIP/login_sid.lua" -d "response=$CHALLENGE-$HASH" -d 'username='${FRITZUSER} | grep -o "<SID>[a-z0-9]\{16\}" | cut -d'>' -f 2 > $SIDFILE

SID=$(cat $SIDFILE)

# Check for successfull authentification
if [[ $SID =~ ^0+$ ]] ; then echo "Login failed. Did you create & use explicit Fritz!Box users?" ; exit 1 ; fi

echo "Capturing traffic on Fritz!Box interface $IFACE ..." 1>&2

# In case you want to use tshark instead of ntopng
#wget --no-check-certificate -qO- $FRITZIP/cgi-bin/capture_notimeout?ifaceorminor=$IFACE\&snaplen=\&capture=Start\&sid=$SID | /usr/bin/tshark -r -

#wget --no-check-certificate -qO- $FRITZIP/cgi-bin/capture_notimeout?ifaceorminor=$IFACE\&snaplen=\&capture=Start\&sid=$SID | ntopng -m "192.168.1.0/24" -F nindex -i -

#wget --no-check-certificate -qO- $FRITZIP/cgi-bin/capture_notimeout?ifaceorminor=$IFACE\&snaplen=\&capture=Start\&sid=$SID | cat

#curl  $FRITZIP/cgi-bin/capture_notimeout?ifaceorminor=$IFACE\&snaplen=\&capture=Start\&sid=$SID --output ./tmp.pcap

#wget --no-check-certificate  $FRITZIP/cgi-bin/capture_notimeout?ifaceorminor=$IFACE\&snaplen=\&capture=Start\&sid=$SID  | yaf --in - --out ./tmp.yaf --noerror 

curl  $FRITZIP/cgi-bin/capture_notimeout?ifaceorminor=$IFACE\&snaplen=\&capture=Start\&sid=$SID  | yaf --mac --in - --out $BASEDIR/yaf/yaf --log $BASEDIR/yaf.log --rotate 60 

#curl  $FRITZIP/cgi-bin/capture_notimeout?ifaceorminor=$IFACE\&snaplen=\&capture=Start\&sid=$SID  | yaf --mac --in - --out ./yaf/yaf --log ./yaf.log --verbose --rotate 30 --stats 45 --idle-timeout 15 --active-timeout 15 --loglevel  info

#yafscii --in './yaf/*' --out - --mac --tabular --print-header >yaf_traffic_details_yaf.tsv
