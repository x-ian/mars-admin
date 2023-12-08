#!/bin/bash

# create local SVG for Nierstein

# 55 */2 * * * /home/pi/mars-admin/homeautomation/create-nierstein-weather-svg.sh

# requires sudo apt-get install html-xml-utils

PATH=$PATH:/usr/bin

cd /tmp
rm -f screenie.png
rm -f screenie_cropped.png
python3 /home/pi/mars-admin/homeautomation/create-nierstein-weather-kachelmann.py
mv screenie_cropped.png ~/.node-red/node-red-static

