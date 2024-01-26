<? 
$HEADLINE = 'Network activity'; 

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
?>

<?php $date = $_GET['date']; ?>

	<!DOCTYPE html>
	<html>
		<head>
			
			  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
			  <link href="./application.css" rel="stylesheet" type="text/css" />
			  <script src="./application.js"></script>
			
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
<body class="visualize_text">

<!-- begin page-specific content ########################################### -->
    <div id="main">
      <div class="page-header" align="center">
  	    <h1>Network activity for <?= $date; ?> at <?= date('Y-m-d H:i:s'); ?></h1>
		<!--  dropdown_link_to_device($mac)  -->
	  </div>

<?php
  
  echo "
  <table class='table table-striped table-bordered '>
  	<thead><tr>
	<th>Hostname</th>`
	<th>MAC vendor</th>";
    
for ($i=0; $i<=23; $i++) {
	if ($i < 10) {
		echo "<td colspan='2' align='left'>&nbsp;" . $i . ":00</td>";
	} else {
		echo "<td colspan='2' align='left'>" . $i . ":00</td>";
	}
}

	echo "</tr></thead><tbody>";
	//  bgcolor="#00FF00">
	
  function activity($date) {
	  $a = "
		  select 
		  a.mac, a.day, hostname, mac_vendor,
		  
		  SUM(CASE WHEN thirtyHourInterval = '00:00:00' THEN Download ELSE 0 END) 0029_input,
		  SUM(CASE WHEN thirtyHourInterval = '00:00:00' THEN Upload ELSE 0 END) 0029_output,
		  SUM(CASE WHEN thirtyHourInterval = '00:30:00' THEN Download ELSE 0 END) 0059_input,
		  SUM(CASE WHEN thirtyHourInterval = '00:30:00' THEN Upload ELSE 0 END) 0059_output,

		  SUM(CASE WHEN thirtyHourInterval = '01:00:00' THEN Download ELSE 0 END) 0129_input,
		  SUM(CASE WHEN thirtyHourInterval = '01:00:00' THEN Upload ELSE 0 END) 0129_output,
		  SUM(CASE WHEN thirtyHourInterval = '01:30:00' THEN Download ELSE 0 END) 0159_input,
		  SUM(CASE WHEN thirtyHourInterval = '01:30:00' THEN Upload ELSE 0 END) 0159_output,

		  SUM(CASE WHEN thirtyHourInterval = '02:00:00' THEN Download ELSE 0 END) 0229_input,
		  SUM(CASE WHEN thirtyHourInterval = '02:00:00' THEN Upload ELSE 0 END) 0229_output,
		  SUM(CASE WHEN thirtyHourInterval = '02:30:00' THEN Download ELSE 0 END) 0259_input,
		  SUM(CASE WHEN thirtyHourInterval = '02:30:00' THEN Upload ELSE 0 END) 0259_output,

		  SUM(CASE WHEN thirtyHourInterval = '03:00:00' THEN Download ELSE 0 END) 0329_input,
		  SUM(CASE WHEN thirtyHourInterval = '03:00:00' THEN Upload ELSE 0 END) 0329_output,
		  SUM(CASE WHEN thirtyHourInterval = '03:30:00' THEN Download ELSE 0 END) 0359_input,
		  SUM(CASE WHEN thirtyHourInterval = '03:30:00' THEN Upload ELSE 0 END) 0359_output,

		  SUM(CASE WHEN thirtyHourInterval = '04:00:00' THEN Download ELSE 0 END) 0429_input,
		  SUM(CASE WHEN thirtyHourInterval = '04:00:00' THEN Upload ELSE 0 END) 0429_output,
		  SUM(CASE WHEN thirtyHourInterval = '04:30:00' THEN Download ELSE 0 END) 0459_input,
		  SUM(CASE WHEN thirtyHourInterval = '04:30:00' THEN Upload ELSE 0 END) 0459_output,

		  SUM(CASE WHEN thirtyHourInterval = '05:00:00' THEN Download ELSE 0 END) 0529_input,
		  SUM(CASE WHEN thirtyHourInterval = '05:00:00' THEN Upload ELSE 0 END) 0529_output,
		  SUM(CASE WHEN thirtyHourInterval = '05:30:00' THEN Download ELSE 0 END) 0559_input,
		  SUM(CASE WHEN thirtyHourInterval = '05:30:00' THEN Upload ELSE 0 END) 0559_output,

		  SUM(CASE WHEN thirtyHourInterval = '06:00:00' THEN Download ELSE 0 END) 0629_input,
		  SUM(CASE WHEN thirtyHourInterval = '06:00:00' THEN Upload ELSE 0 END) 0629_output,
		  SUM(CASE WHEN thirtyHourInterval = '06:30:00' THEN Download ELSE 0 END) 0659_input,
		  SUM(CASE WHEN thirtyHourInterval = '06:30:00' THEN Upload ELSE 0 END) 0659_output,

		  SUM(CASE WHEN thirtyHourInterval = '07:00:00' THEN Download ELSE 0 END) 0729_input,
		  SUM(CASE WHEN thirtyHourInterval = '07:00:00' THEN Upload ELSE 0 END) 0729_output,
		  SUM(CASE WHEN thirtyHourInterval = '07:30:00' THEN Download ELSE 0 END) 0759_input,
		  SUM(CASE WHEN thirtyHourInterval = '07:30:00' THEN Upload ELSE 0 END) 0759_output,

		  SUM(CASE WHEN thirtyHourInterval = '08:00:00' THEN Download ELSE 0 END) 0829_input,
		  SUM(CASE WHEN thirtyHourInterval = '08:00:00' THEN Upload ELSE 0 END) 0829_output,
		  SUM(CASE WHEN thirtyHourInterval = '08:30:00' THEN Download ELSE 0 END) 0859_input,
		  SUM(CASE WHEN thirtyHourInterval = '08:30:00' THEN Upload ELSE 0 END) 0859_output,

		  SUM(CASE WHEN thirtyHourInterval = '09:00:00' THEN Download ELSE 0 END) 0929_input,
		  SUM(CASE WHEN thirtyHourInterval = '09:00:00' THEN Upload ELSE 0 END) 0929_output,
		  SUM(CASE WHEN thirtyHourInterval = '09:30:00' THEN Download ELSE 0 END) 0959_input,
		  SUM(CASE WHEN thirtyHourInterval = '09:30:00' THEN Upload ELSE 0 END) 0959_output,

		  SUM(CASE WHEN thirtyHourInterval = '10:00:00' THEN Download ELSE 0 END) 1029_input,
		  SUM(CASE WHEN thirtyHourInterval = '10:00:00' THEN Upload ELSE 0 END) 1029_output,
		  SUM(CASE WHEN thirtyHourInterval = '10:30:00' THEN Download ELSE 0 END) 1059_input,
		  SUM(CASE WHEN thirtyHourInterval = '10:30:00' THEN Upload ELSE 0 END) 1059_output,

		  SUM(CASE WHEN thirtyHourInterval = '11:00:00' THEN Download ELSE 0 END) 1129_input,
		  SUM(CASE WHEN thirtyHourInterval = '11:00:00' THEN Upload ELSE 0 END) 1129_output,
		  SUM(CASE WHEN thirtyHourInterval = '11:30:00' THEN Download ELSE 0 END) 1159_input,
		  SUM(CASE WHEN thirtyHourInterval = '11:30:00' THEN Upload ELSE 0 END) 1159_output,

		  SUM(CASE WHEN thirtyHourInterval = '12:00:00' THEN Download ELSE 0 END) 1229_input,
		  SUM(CASE WHEN thirtyHourInterval = '12:00:00' THEN Upload ELSE 0 END) 1229_output,
		  SUM(CASE WHEN thirtyHourInterval = '12:30:00' THEN Download ELSE 0 END) 1259_input,
		  SUM(CASE WHEN thirtyHourInterval = '12:30:00' THEN Upload ELSE 0 END) 1259_output,

		  SUM(CASE WHEN thirtyHourInterval = '13:00:00' THEN Download ELSE 0 END) 1329_input,
		  SUM(CASE WHEN thirtyHourInterval = '13:00:00' THEN Upload ELSE 0 END) 1329_output,
		  SUM(CASE WHEN thirtyHourInterval = '13:30:00' THEN Download ELSE 0 END) 1359_input,
		  SUM(CASE WHEN thirtyHourInterval = '13:30:00' THEN Upload ELSE 0 END) 1359_output,

		  SUM(CASE WHEN thirtyHourInterval = '14:00:00' THEN Download ELSE 0 END) 1429_input,
		  SUM(CASE WHEN thirtyHourInterval = '14:00:00' THEN Upload ELSE 0 END) 1429_output,
		  SUM(CASE WHEN thirtyHourInterval = '14:30:00' THEN Download ELSE 0 END) 1459_input,
		  SUM(CASE WHEN thirtyHourInterval = '14:30:00' THEN Upload ELSE 0 END) 1459_output,

		  SUM(CASE WHEN thirtyHourInterval = '15:00:00' THEN Download ELSE 0 END) 1529_input,
		  SUM(CASE WHEN thirtyHourInterval = '15:00:00' THEN Upload ELSE 0 END) 1529_output,
		  SUM(CASE WHEN thirtyHourInterval = '15:30:00' THEN Download ELSE 0 END) 1559_input,
		  SUM(CASE WHEN thirtyHourInterval = '15:30:00' THEN Upload ELSE 0 END) 1559_output,

		  SUM(CASE WHEN thirtyHourInterval = '16:00:00' THEN Download ELSE 0 END) 1629_input,
		  SUM(CASE WHEN thirtyHourInterval = '16:00:00' THEN Upload ELSE 0 END) 1629_output,
		  SUM(CASE WHEN thirtyHourInterval = '16:30:00' THEN Download ELSE 0 END) 1659_input,
		  SUM(CASE WHEN thirtyHourInterval = '16:30:00' THEN Upload ELSE 0 END) 1659_output,

		  SUM(CASE WHEN thirtyHourInterval = '17:00:00' THEN Download ELSE 0 END) 1729_input,
		  SUM(CASE WHEN thirtyHourInterval = '17:00:00' THEN Upload ELSE 0 END) 1729_output,
		  SUM(CASE WHEN thirtyHourInterval = '17:30:00' THEN Download ELSE 0 END) 1759_input,
		  SUM(CASE WHEN thirtyHourInterval = '17:30:00' THEN Upload ELSE 0 END) 1759_output,

		  SUM(CASE WHEN thirtyHourInterval = '18:00:00' THEN Download ELSE 0 END) 1829_input,
		  SUM(CASE WHEN thirtyHourInterval = '18:00:00' THEN Upload ELSE 0 END) 1829_output,
		  SUM(CASE WHEN thirtyHourInterval = '18:30:00' THEN Download ELSE 0 END) 1859_input,
		  SUM(CASE WHEN thirtyHourInterval = '18:30:00' THEN Upload ELSE 0 END) 1859_output,

		  SUM(CASE WHEN thirtyHourInterval = '19:00:00' THEN Download ELSE 0 END) 1929_input,
		  SUM(CASE WHEN thirtyHourInterval = '19:00:00' THEN Upload ELSE 0 END) 1929_output,
		  SUM(CASE WHEN thirtyHourInterval = '19:30:00' THEN Download ELSE 0 END) 1959_input,
		  SUM(CASE WHEN thirtyHourInterval = '19:30:00' THEN Upload ELSE 0 END) 1959_output,

		  SUM(CASE WHEN thirtyHourInterval = '20:00:00' THEN Download ELSE 0 END) 2029_input,
		  SUM(CASE WHEN thirtyHourInterval = '20:00:00' THEN Upload ELSE 0 END) 2029_output,
		  SUM(CASE WHEN thirtyHourInterval = '20:30:00' THEN Download ELSE 0 END) 2059_input,
		  SUM(CASE WHEN thirtyHourInterval = '20:30:00' THEN Upload ELSE 0 END) 2059_output,

		  SUM(CASE WHEN thirtyHourInterval = '21:00:00' THEN Download ELSE 0 END) 2129_input,
		  SUM(CASE WHEN thirtyHourInterval = '21:00:00' THEN Upload ELSE 0 END) 2129_output,
		  SUM(CASE WHEN thirtyHourInterval = '21:30:00' THEN Download ELSE 0 END) 2159_input,
		  SUM(CASE WHEN thirtyHourInterval = '21:30:00' THEN Upload ELSE 0 END) 2159_output,

		  SUM(CASE WHEN thirtyHourInterval = '22:00:00' THEN Download ELSE 0 END) 2229_input,
		  SUM(CASE WHEN thirtyHourInterval = '22:00:00' THEN Upload ELSE 0 END) 2229_output,
		  SUM(CASE WHEN thirtyHourInterval = '22:30:00' THEN Download ELSE 0 END) 2259_input,
		  SUM(CASE WHEN thirtyHourInterval = '22:30:00' THEN Upload ELSE 0 END) 2259_output,

		  SUM(CASE WHEN thirtyHourInterval = '23:00:00' THEN Download ELSE 0 END) 2329_input,
		  SUM(CASE WHEN thirtyHourInterval = '23:00:00' THEN Upload ELSE 0 END) 2329_output,
		  SUM(CASE WHEN thirtyHourInterval = '23:30:00' THEN Download ELSE 0 END) 2359_input,
		  SUM(CASE WHEN thirtyHourInterval = '23:30:00' THEN Upload ELSE 0 END) 2359_output

		   from (
		  SELECT 
		  mac,
		  remote_ip,
		  DATE(ts) as day,
		  TIME(FROM_UNIXTIME((UNIX_TIMESTAMP(ts) DIV (30* 60) ) * (30*60))) thirtyHourInterval, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download
		  FROM traffic_details_full
		  WHERE 
		  DATE(ts)= '" . $date . "'
		  GROUP BY UNIX_TIMESTAMP(ts) DIV (30* 60), mac
		  ORDER BY ts, mac
		  ) a
	  	left join ip_registry on a.remote_ip = ip_registry.ip   
	  	left join local_hosts on a.mac = local_hosts.mac 
		  
		  GROUP BY a.day, a.mac 		  ORDER by hostname
;";  
	// echo $a;
	return $a;
  }
  
// $all_activities = query(activity($mac));

// $all_activities = $mysqli->query(activity($mac));

$all_activities = mysqli_query($mysqli, activity($date));

// $previous_day = date('Y-m-d');
// $previous_day_date = date_create_from_format('Y-m-d', $previous_day);

while ($row = (mysqli_fetch_assoc($all_activities))) {
	$day = $row['day'];
	// echo $day . ' + ' . $previous_day;
	// echo "<br/>";
	// date_sub($previous_day_date, date_interval_create_from_date_string('1 day'));
	// $previous_day = date_format($previous_day_date, 'Y-m-d');
	// echo $day. ' _ ' . $previous_day;
	// echo "<br/>";

/*	
	while ($day < $previous_day) {
		
		// last activity in the past, fill out all days between then and now
		echo "<tr><td class='text-nowrap'>" . $previous_day . "</td><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/><td/></tr>";
		date_sub($previous_day_date, date_interval_create_from_date_string('1 day'));
		$previous_day = date_format($previous_day_date, 'Y-m-d');
	}
*/
	
	echo "<tr>";
    echo '<td class="text-nowrap">' . $row['hostname'] . '</td>';
    echo '<td class="text-nowrap">' . $row['mac_vendor'] . '</td>';
	for ($i=0; $i<=23; $i++) {
		$input29 = $row[sprintf('%02d',$i) . "29_input"];
		$output29 = $row[sprintf('%02d', $i) . '29_output'];
		$input59 = $row[sprintf('%02d', $i) . "59_input"];
		$output59 = $row[sprintf('%02d', $i) . '59_output'];
		// if (($input29 > 0 || $output29 > 0) AND ($input29 <= 10 || $output29 <= 10)) {
		// 	echo '<td bgcolor="#C8C8C8" title="Up: ' . $output29 . ' Down: ' . $input29 . '"/>';
		// } elseif (($input29 > 10 || $output29 > 10) AND ($input29 <= 100 || $output29 <= 100)) {
		// 	echo '<td bgcolor="#A8A8A8" title="Up: ' . $output29 . ' Down: ' . $input29 . '"/>';
		// } elseif ($input29 > 100 || $output29 > 100) {
		// 	echo '<td bgcolor="#808080" title="Up: ' . $output29 . ' Down: ' . $input29 . '"/>';
		// } else {
		// 	echo '<td/>';
		// }
		// if (($input59 > 0 || $output59 > 0) AND ($input59 <= 10 || $output59 <= 10)) {
		// 	echo '<td bgcolor="#C8C8C8" title="LOW Up: ' . $output59 . ' Down: ' . $input59 . '"/>';
		// } elseif (($input59 > 10 || $output59 > 10) AND ($input59 <= 100 || $output59 <= 100)) {
		// 	echo '<td bgcolor="#A8A8A8" title="MID Up: ' . $output59 . ' Down: ' . $input59 . '"/>';
		// } elseif ($input59 > 100 || $output59 > 100) {
		// 	echo '<td bgcolor="#808080" title="FULL Up: ' . $output59 . ' Down: ' . $input59 . '"/>';
		// } else {
		// 	echo '<td/>';
		// }
		
		$inout29 = $input29 + $output29;
		$inout59 = $input59 + $output59;
		if ($inout29 > 0 AND $inout29 < 10) {
			echo '<td bgcolor="#C8C8C8" title="l Up: ' . $output29 . ' Down: ' . $input29 . '"/>';
		} elseif ($inout29 >= 10 AND $inout29 < 100) {
			echo '<td bgcolor="#A8A8A8" title="m Up: ' . $output29 . ' Down: ' . $input29 . '"/>';
		} elseif ($inout29 >= 100) {
			echo '<td bgcolor="#808080" title="h Up: ' . $output29 . ' Down: ' . $input29 . '"/>';
		} else {
			echo '<td/>';
		}
		if ($inout59 > 0 AND $inout59 < 10) {
			echo '<td bgcolor="#C8C8C8" title="L Up: ' . $output59 . ' Down: ' . $input59 . '"/>';
		} elseif ($inout59 >= 10 AND $inout59 < 100) {
			echo '<td bgcolor="#A8A8A8" title="M Up: ' . $output59 . ' Down: ' . $input59 . '"/>';
		} elseif ($inout59 >= 100) {
			echo '<td bgcolor="#808080" title="H Up: ' . $output59 . ' Down: ' . $input59 . '"/>';
		} else {
			echo '<td/>';
		}
		
	}
	// $day_date = date_create_from_format('Y-m-d', $day);
	// date_sub($day_date, date_interval_create_from_date_string('1 day'));
	// $previous_day_date = $day_date;
	// $previous_day = date_format($day_date, 'Y-m-d');
	echo "</tr>";
}
?>
</tbody></table>

<br/>

<p>Only 30-minute time blocks with actual data traffic >10 MB are listed; everything below is ignored.</p>

<br/>
