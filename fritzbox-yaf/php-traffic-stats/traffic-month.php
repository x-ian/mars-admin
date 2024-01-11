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

// For extra protection these are the columns that the user can sort by (in your database table).
$columns = array('mac','ip','hostname','today_down','today_up','yesterday_down','yesterday_up','lastweek_down','lastweek_up','lastmonth_down','lastmonth_up');

// Only get the column if it exists in the above columns array, if it doesn't exist the database table will be sorted by the first item in the columns array.
// $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : 'today_down DESC, yesterday_down DESC, lastweek_down DESC, lastmonth_down DESC';

// Get the sort order for the column, ascending or descending, default is ascending.
// $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : '';

// Get the result...
//if ($result = $mysqli->query('SELECT * FROM traffic_details_full ORDER BY ' .  $column . ' ' . $sort_order)) {
	
	
	




	// select "15 mins ago" as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download
	// from traffic_details_full
	// left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
	// left join local_hosts on traffic_details_full.mac = local_hosts.mac
	// where ts>=(NOW() - INTERVAL 15 MINUTE)
	// group by mac
	//
	// UNION
	//
	// select "30 mins ago" as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download
	// from traffic_details_full
	// left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
	// left join local_hosts on traffic_details_full.mac = local_hosts.mac
	// where ts>=(NOW() - INTERVAL 5 MINUTE)
	// group by mac
	//
	// UNION
	//

//	--	UNION
//
// 	--	select "yesterday" as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download
// 	--	from traffic_details_full
// 	--	left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
// 	--	left join local_hosts on traffic_details_full.mac = local_hosts.mac
// 	--	where DATE(ts)=subdate(curdate(), 1)
// 	--	group by mac
//
// 	--	UNION
//
// 	--	select "last 7 days" as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download
// 	--	from traffic_details_full
// 	--	left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
// 	--	left join local_hosts on traffic_details_full.mac = local_hosts.mac
// 	--	where DATE(ts)>subdate(curdate(), 7)
// 	--	group by mac
//
// 	--	UNION
//
// 	--	select "last 30 days" as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download
// 	--	from traffic_details_full
// 	--	left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
// 	--	left join local_hosts on traffic_details_full.mac = local_hosts.mac
// 	--	where DATE(ts)>subdate(curdate(), 30)
// 	--	group by mac
//
	
if ($result = $mysqli->query('


	select 
	mac,
	ip,
	hostname,
	mac_vendor,
	SUM(CASE WHEN period = "today" THEN Download ELSE 0 END) as today_down,
	SUM(CASE WHEN period = "today" THEN Upload ELSE 0 END) as today_up,
	SUM(CASE WHEN period = "yesterday" THEN Download ELSE 0 END) as yesterday_down,
	SUM(CASE WHEN period = "yesterday" THEN Upload ELSE 0 END) as yesterday_up,
	SUM(CASE WHEN period = "last 7 days" THEN Download ELSE 0 END) as lastweek_down,
	SUM(CASE WHEN period = "last 7 days" THEN Upload ELSE 0 END) as lastweek_up,
	SUM(CASE WHEN period = "last 30 days" THEN Download ELSE 0 END) as lastmonth_down,
	SUM(CASE WHEN period = "last 30 days" THEN Upload ELSE 0 END) as lastmonth_up,
	last_active,
	ip

	from 

	(
	
	select "today" as period, 
		traffic_details_full.mac, hostname, local_hosts.mac_vendor, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download, local_hosts.last_active, local_hosts.ip
	from traffic_details_full   
	left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip   
	left join local_hosts on traffic_details_full.mac = local_hosts.mac 
	where DATE(ts)=CURDATE()
	group by mac  

	UNION

	select "yesterday" as period, 
		traffic_details_full.mac, hostname, local_hosts.mac_vendor, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download, local_hosts.last_active, local_hosts.ip
	from traffic_details_full
	left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
	left join local_hosts on traffic_details_full.mac = local_hosts.mac
	where DATE(ts)=subdate(curdate(), 1)
	group by mac

	UNION

	select "last 7 days" as period, 
		traffic_details_full.mac, hostname, local_hosts.mac_vendor, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download, local_hosts.last_active, local_hosts.ip
	from traffic_details_full
	left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
	left join local_hosts on traffic_details_full.mac = local_hosts.mac
	where DATE(ts)>subdate(curdate(), 7)
	group by mac

	UNION

	select "last 30 days" as period, 
		traffic_details_full.mac, hostname, local_hosts.mac_vendor, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download, local_hosts.last_active, local_hosts.ip
	from traffic_details_full
	left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
	left join local_hosts on traffic_details_full.mac = local_hosts.mac
	where DATE(ts)>subdate(curdate(), 30)
	group by mac
	
	) a

	where download > 0 and upload > 0
	group by  mac
	
	 ORDER BY 


' .  $column . ' ' . $sort_order . ' LIMIT 40')) {
	// Some variables we need for the table.
	$up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order); 
	$asc_or_desc = $sort_order == 'ASC' ? 'DESC' : 'ASC';
	$add_class = ' class="highlight"';
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>mars:portal traffic summary</title>
			<meta charset="utf-8">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
			<style>
			html {
				font-family: Tahoma, Geneva, sans-serif;
				padding: 10px;
			}
			table {
				border-collapse: collapse;
				width: 500px;
			}
			th {
				background-color: #54585d;
				border: 1px solid #54585d;
				white-space: nowrap;
			}
			th:hover {
				background-color: #64686e;
			}
			th a {
				display: block;
				text-decoration:none;
				padding: 10px;
				color: #ffffff;
				font-weight: bold;
				font-size: 13px;
			}
			th a i {
				margin-left: 5px;
				color: rgba(255,255,255,0.4);
			}
			td {
				padding: 5px;
				color: #636363;
				border: 1px solid #dddfe1;
				white-space: nowrap;
			}
			tr {
				background-color: #ffffff;
			}
			tr .highlight {
				background-color: #f9fafb;
			}
			</style>
		</head>
		<body>
			<table>
				<tr>
					<th><a href="index.php?column=hostname&order=<?php echo $asc_or_desc; ?>">Hostname<i class="fas fa-sort<?php echo $column == 'hostname' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=mac_vendor&order=<?php echo $asc_or_desc; ?>">MAC&nbsp;vendor<i class="fas fa-sort<?php echo $column == 'mac_vendor' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=today_down&order=<?php echo $asc_or_desc; ?>">Today&nbsp;&darr;<i class="fas fa-sort<?php echo $column == 'today_down' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=today_up&order=<?php echo $asc_or_desc; ?>">Today&nbsp;&uarr;<i class="fas fa-sort<?php echo $column == 'today_up' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=yesterday_down&order=<?php echo $asc_or_desc; ?>">Yesterday down<i class="fas fa-sort<?php echo $column == 'yesterday_down' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=yesterday_up&order=<?php echo $asc_or_desc; ?>">Yesterday up<i class="fas fa-sort<?php echo $column == 'yesterday_up' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=lastweek_down&order=<?php echo $asc_or_desc; ?>">Last week down<i class="fas fa-sort<?php echo $column == 'lastweek_down' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=lastweek_up&order=<?php echo $asc_or_desc; ?>">Last week up<i class="fas fa-sort<?php echo $column == 'lastweek_up' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=lastmonth_down&order=<?php echo $asc_or_desc; ?>">Last month down<i class="fas fa-sort<?php echo $column == 'lastmonth_down' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=lastmonth_up&order=<?php echo $asc_or_desc; ?>">Last month up<i class="fas fa-sort<?php echo $column == 'lastmonth_up' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=last_active&order=<?php echo $asc_or_desc; ?>">Last&nbsp;active<i class="fas fa-sort<?php echo $column == 'last_active' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=ip&order=<?php echo $asc_or_desc; ?>">IP<i class="fas fa-sort<?php echo $column == 'ip' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="index.php?column=mac&order=<?php echo $asc_or_desc; ?>">MAC<i class="fas fa-sort<?php echo $column == 'mac' ? '-' . $up_or_down : ''; ?>"></i></a></th>

				</tr>
				<?php while ($row = $result->fetch_assoc()): ?>
				<tr>
					<td<?php echo $column == 'hostname' ? $add_class : ''; ?>><?php echo $row['hostname']; ?></td>
					<td<?php echo $column == 'mac_vendor' ? $add_class : ''; ?>><?php echo $row['mac_vendor']; ?></td>
					<td<?php echo $column == 'today_down' ? $add_class : ''; ?>><?php echo $row['today_down']; ?></td>
					<td<?php echo $column == 'today_up' ? $add_class : ''; ?>><?php echo $row['today_up']; ?></td>
					<td<?php echo $column == 'yesterday_down' ? $add_class : ''; ?>><?php echo $row['yesterday_down']; ?></td>
					<td<?php echo $column == 'yesterday_up' ? $add_class : ''; ?>><?php echo $row['yesterday_up']; ?></td>
					<td<?php echo $column == 'lastweek_down' ? $add_class : ''; ?>><?php echo $row['lastweek_down']; ?></td>
					<td<?php echo $column == 'lastweek_up' ? $add_class : ''; ?>><?php echo $row['lastweek_up']; ?></td>
					<td<?php echo $column == 'lastmonth_down' ? $add_class : ''; ?>><?php echo $row['lastmonth_down']; ?></td>
					<td<?php echo $column == 'lastmonth_up' ? $add_class : ''; ?>><?php echo $row['lastmonth_up']; ?></td>
					<td<?php echo $column == 'last_active' ? $add_class : ''; ?>><?php echo $row['last_active']; ?></td>
					<td<?php echo $column == 'ip' ? $add_class : ''; ?>><?php echo $row['ip']; ?></td>
					<td<?php echo $column == 'mac' ? $add_class : ''; ?>><?php echo $row['mac']; ?></td>
				</tr>
				<?php endwhile; ?>
			</table>
		</body>
	</html>
	<?php
	$result->free();
}
?>