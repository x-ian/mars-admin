#!/bin/bash

# @reboot /home/pi/mars-admin/start-ssh-tunnel.sh
# 0 7 * * * /home/pi/mars-admin/start-ssh-tunnel.sh

BASEDIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]:-$0}"; )" &> /dev/null && pwd 2> /dev/null; )";

source $BASEDIR/config.txt

echo "Stopping all tunnels"
/usr/bin/killall ssh
/usr/bin/killall autossh
/bin/sleep 120

echo "Starting new tunnel, abort with CTRL-C"
/usr/bin/autossh -M 0 -o "ExitOnForwardFailure=yes" -o "ConnectTimeout=10" -o "UserKnownHostsFile /dev/null" -o "StrictHostKeyChecking no" -o "ServerAliveInterval 60" -o "ServerAliveCountMax 3" -o "BatchMode=yes" -R $SSH_TUNNEL_PORT:localhost:22 -N ssh-tunnel@marsgeneral.com &


#/usr/bin/autossh -M 0 -o "ServerAliveInterval 60" -o "ServerAliveCountMax 3" -o BatchMode=yes -R 18001:localhost:22 -N ssh-tunnel@marsgeneral.com

