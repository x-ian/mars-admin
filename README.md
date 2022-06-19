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

### mars-backup

Time Machine on SMB share

* https://ubuntu.com/tutorials/install-and-configure-samba#4-setting-up-user-accounts-and-connecting-to-share
* https://wiki.samba.org/index.php/Configure_Samba_to_Work_Better_with_Mac_OS_X
* https://ingenieur-hb.de/wp/apple-timemachine-auf-samba-share/
* https://support.apple.com/en-gb/HT205926
* https://www.truenas.com/community/threads/timemachine-backups-extremely-slow-to-smb-share-12-0u6.96106/
* https://eclecticlight.co/consolation-t2m2-and-log-utilities/

Syncthing

* limited by many small files, ideally excluding most of them. 
* simple check for # of files ```for d in .*/; do echo "$d" >> file-count && find "$d" | wc -l >> file-count; done```
* .stignore file for full home dir: 
```
/.gem
/.m2
/.npm
/Dropbox
/OneDrive - Department of HIV and AIDS, MOH Malawi
/VirtualBox VMs
(?d).DS_Store
/Library/Application Support/Syncthing
/Library/Application Support/Google/Chrome
/Library/Application Support/Firefox
/Department of HIV and AIDS, MOH Malawi
node_modules/
```

Alternatives?

* https://github.com/Sentaroh/SMBSync2
* rsnapshot
* rdiff-backup
* https://codeberg.org/izzy/Adebar