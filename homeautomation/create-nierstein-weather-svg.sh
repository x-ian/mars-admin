#!/bin/bash

# create local SVG for Nierstein

# 55 */2 * * * /home/pi/mars-admin/create-nierstein-weather-svg.sh

# requires sudo apt-get install html-xml-utils

PATH=$PATH:/usr/bin

cd /tmp
rm -f meteogram.svg
wget https://www.yr.no/en/content/2-2862485/meteogram.svg
cat meteogram.svg | hxremove '[x="16"], [transform="translate(612, 22.25)"], [x="624"], [x="675.5"], [transform="translate(30 180)"], [transform="translate(30 252)"], [transform="translate(30, 278)"]' > m.svg
cat m.svg | sed 's/width="782" height="391"/width="782" height="210"/g' | sed 's/translate(16, 53.86)/translate(16, 0)/g' | sed 's/translate(0, 84.86)/translate(0, 31)/g' > m2.svg

mv m2.svg ~/.node-red/node-red-static
