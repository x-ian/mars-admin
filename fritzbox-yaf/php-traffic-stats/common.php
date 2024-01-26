<?php

// parse marsPortal config file
$fh=fopen("/home/pi/mars-admin/config.txt", "r");
while ($line=fgets($fh, 80)) {
  if (!preg_match('/^#/', $line) && preg_match('/=/', $line)) {
    $line_a=explode("=", $line);
	$param = preg_replace( "/\r|\n/", "", $line_a[0] );
	$value = preg_replace( "/\r|\n/", "", $line_a[1] );
    ${$param}=$value;
  }
}
 
$mysqli = mysqli_connect('localhost', $MYSQL_USER, $MYSQL_PASSWD, $MYSQL_DB);
	
function dropdown_link_to_device($mac) {
			return "<div class='dropdown'>
		  <a class='dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown'>
		    {$name}
		    <span class='caret'></span>
		  </a>
		  <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu1'>
			<li role='presentation'><a role='menuitem' tabindex='-1' href='./device-traffic-details.php?mac={$mac}'>Traffic details (by IP)</a></li>
			<li role='presentation'><a role='menuitem' tabindex='-1' href='/mars/reports/device-traffic-details-2ndleveldomain.php?username={$mac}'>Traffic details (by 2nd level domain)</a></li>
		    <li role='presentation'><a role='menuitem' tabindex='-1' href='/mars/reports/device_with_volume.php?username={$mac}'>Traffic history</a></li>
		    <li role='presentation'><a role='menuitem' tabindex='-1' href='./device-activity.php?mac={$mac}'>Activity history</a></li>
		    <li role='presentation' class='divider'></li>
		    <li role='presentation'><a role='menuitem' tabindex='-1' href='/mars/userinfo/edit.php?username={row[username]}'>Edit device</a></li>
		    <li role='presentation' class='divider'></li>
		    <li role='presentation'>Username: {row[firstname]} {row[lastname]}</li>
		    <li role='presentation'>Hostname: {row[hostname]}</li>
		    <li role='presentation'>Group: {row[groupname]}</li>
		    <li role='presentation'>MAC Address: {mac}</li>
		    <li role='presentation'>MAC Vendor: {row[mac_vendor]}</li>
		  </ul>
		</div>";
}

?>
