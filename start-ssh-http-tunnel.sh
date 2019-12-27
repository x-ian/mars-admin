#!/bin/bash

# https://www.calazan.com/how-to-share-your-local-web-server-to-the-internet-using-a-reverse-ssh-tunnel/

ssh ssh-tunnel@marsgeneral.com -nNT -o ServerAliveInterval=30 -R 8888:localhost:80
