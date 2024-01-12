#!/bin/bash

if [ "$#" -ne 1 ]; then
    echo "Illegal number of parameters"
    exit
fi

# load site specific parameters
FULLNAME=$(realpath $0)
BASEDIR=$(dirname $FULLNAME)
source $BASEDIR/config.txt

TIMESTAMP=$(date +"%Y.%m.%d-%H.%M.%S")
BACKUP_DIR=$1

rm -rf $BACKUP_DIR
mkdir -p $BACKUP_DIR

influxd backup -portable $BACKUP_DIR

tar czf influx.tgz $BACKUP_DIR
scp -i /home/pi/.ssh/id_rsa_pi@raspberries influx.tgz pi@raspberrypi4:/media/exchange/zigbee2mqtt/influx
#rm -rf $BACKUP_DIR
#rm influx.tgz

# create tar archive with timestamp
#tar cvfz $BACKUP_DIR/backup.influx-$TIMESTAMP.tgz $BACKUP_DIR/*
# clean up old backups if you want
# rm -r $BACKUP_DIR/*

# now push it to the github repo
#cd $BASEDIR
#git add influx-backup
#git commit -m "new version" influx-backup
#git push
