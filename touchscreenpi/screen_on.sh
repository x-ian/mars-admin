#!/bin/bash

echo `date` >> /tmp/screen_on.log

echo "0" > /sys/class/backlight/rpi_backlight/bl_power
