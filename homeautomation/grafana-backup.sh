#!/bin/bash
# shamelessly taken from https://www.bachmann-lan.de/grafana-backup-dashboards-data-sources/

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

HOST="http://localhost:3000"
# backup grafana data sources

if [ ! -d $BACKUP_DIR/datasources ] ; then
    mkdir -p $BACKUP_DIR/datasources
fi
curl -s "$HOST/api/datasources" -u admin:$GRAFANA_ADMIN_PASSWORD | jq -c -M '.[]'|split -l 1 - $BACKUP_DIR/datasources/
# backup grafana dashboards
if [ ! -d $BACKUP_SIR/dashboards ] ; then
    mkdir -p $BACKUP_DIR/dashboards
fi
for dash in $(curl -s $HOST/api/search\?query\=\&  -u admin:$GRAFANA_ADMIN_PASSWORD | jq -r '.[] | .uid'); do
  curl -s $HOST/api/dashboards/uid/$dash -u admin:$GRAFANA_ADMIN_PASSWORD | sed 's/"id":[0-9]\+,/"id":null,/' | sed 's/\(.*\)}/\1,"overwrite": true}/' | jq . > $BACKUP_DIR/dashboards/$dash.json
done
#for dash in $(curl -k -H "Authorization: Bearer $GRAFANA_API_KEY" $HOST/api/search\?query\=\& | jq -r '.[] | .uri'); do
#  curl -k -H "Authorization: Bearer $GRAFANA_API_KEY" $HOST/api/dashboards/$dash | sed 's/"id":[0-9]\+,/"id":null,/' | sed 's/\(.*\)}/\1,"overwrite": true}/' | jq . > $BACKUP_DIR/dashboards/$(echo ${dash} |cut -d\" -f 4 |cut -d\/ -f2).json
#done
# create tar archive with timestamp
tar cvfz $BACKUP_DIR/backup.grafana-$TIMESTAMP.tgz $BACKUP_DIR/da*
# clean up old backups if you want
# rm -r $BACKUP_DIR/da*

# now push it to the github repo
#cd $BASEDIR
#git add grafana-backup
#git commit -m "new version" grafana-backup
#git push

scp -i /home/pi/.ssh/id_rsa_pi@raspberries $BACKUP_DIR/backup.grafana-$TIMESTAMP.tgz pi@raspberrypi4:/media/exchange/zigbee2mqtt/grafana

