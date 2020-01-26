# mars-admin


### example crontabs

@reboot /home/pi/mars-admin/mail-after-startup.sh
@reboot /home/pi/mars-admin/start-ssh-tunnel.sh
@reboot /home/pi/mars-admin/start-ssh-http-tunnel.sh
0 7 * * * /home/pi/mars-admin/start-ssh-tunnel.sh
0 * * * * /home/pi/mars-admin/monitor_network_devices.sh
*/15 * * * * /home/pi/mars-admin/monitor_hue_devices.sh
1 * * * * /home/pi/mars-admin/monitor_hue_devices_summary.sh > /var/www/html/hue
*/30 * * * * /home/pi/mars-admin/process-mail-backlog.sh
*/5 * * * * /home/pi/mars-admin/fritzbox-query.sh

