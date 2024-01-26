<?php
$username = $_GET['mac'];
$mysqli->query(top_upordown($username, $today, $today, 10, $upordown));


function top_upordown($username, $startday, $endday, $topX, $upordown) {
$a = "
	select 
		date(ts) as day, 
		mac, 
		remote_ip, 
		reverse_dns, 
		ROUND(sum(outgoing) / 1000000) as Upload, 
		ROUND(sum(incoming) / 1000000) as Download
	from traffic_details_full
	left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
	where mac = \"{$username}\" and date(ts) >= \"{$startday}\" and date(ts) <= \"{$endday}\"
	group by mac, remote_ip order by {$upordown} desc limit {$topX}";

	// echo $a;
	return $a;
}

function total_upordown($username, $startday, $endday, $upordown) {
  $a = "	select 
		date(ts) as day, 
		mac, 
		remote_ip, 
		ROUND(sum(outgoing) / 1000000) as Upload, 
		ROUND(sum(incoming) / 1000000) as Download
	from traffic_details_full WHERE mac = \"" . $username . "\" and date(ts) >= \"" . $startday . "\" AND date(ts) <= \"" . $endday . "\";";  
	// echo $a;
	return $a;
}

$upordown_total_today = null;

function generatedailytraffic($mysqli, $username, $upordown, $today, $yesterday, $daysago7, $daysago30) {
	//echo "<hr/><p>Top " . $upordown . "s daily</p>";
	
	global $upordown_total_today;
	global $upordown_total_yesterday;
	global $upordown_total_last7days;
	global $upordown_total_last30days;

	global $upordown_today;
	global $upordown_yesterday;
	global $upordown_last7days;
	global $upordown_last30days;
	
	
	$upordown_today = $mysqli->query(top_upordown($username, $today, $today, 10, $upordown)) or die($mysqli->error);
	$upordown_yesterday = $mysqli->query(top_upordown($username, $yesterday, $yesterday, 10, $upordown)) or die($mysqli->error);
	$upordown_last7days = $mysqli->query(top_upordown($username, $daysago7, $today, 10, $upordown)) or die($mysqli->error);
	$upordown_last30days = $mysqli->query(top_upordown($username, $daysago30, $today, 10, $upordown)) or die($mysqli->error);

	$upordown_total_today = $mysqli->query(total_upordown($username, $today, $today, $upordown)) or die($mysqli->error);
	$upordown_total_yesterday = $mysqli->query(total_upordown($username, $yesterday, $yesterday, $upordown)) or die($mysqli->error);
	$upordown_total_last7days = $mysqli->query(total_upordown($username, $daysago7, $today, $upordown)) or die($mysqli->error);
	$upordown_total_last30days = $mysqli->query(total_upordown($username, $daysago30, $today, $upordown)) or die($mysqli->error);
?>

<table class='table table-striped'>
	<?= $MYSQL_USER; ?>
	
	<thead><tr>
		<th><?=$upordown?> (MB)</th>
		<th>Today</th>
		<th>Yesterday</th>
		<th>Last 7 days</th>
		<th>Last 30 days</th>
	</tr></thead>
	<tbody><tr>

		<td>Total</td>
		<td>
			<? if ($row = $upordown_total_today->fetch_assoc()) {
				echo $row[$upordown];
			}?>	
		</td>
		<td>
			<? if ($row = $upordown_total_yesterday->fetch_assoc()) {
				echo $row[$upordown];
			}?>	
		</td>
		<td>
			<? if ($row = $upordown_total_last7days->fetch_assoc()) {
				echo $row[$upordown];
			}?>	
		</td>
		<td>
			<? if ($row = $upordown_total_last30days->fetch_assoc()) {
				echo $row[$upordown];
			}?>	
		</td>
	</tr>

<?
		for ($i=1; $i<=10; $i++) {
			echo "<tr>";
			echo "<td>Top #" . $i . "</td>";
			echo "<td>";
			if ($row = $upordown_today->fetch_assoc()) {
				deviceinfo($row, $upordown);
			}
			echo "</td>";
			echo "<td>";
			if ($row = $upordown_yesterday->fetch_assoc()) {
				deviceinfo($row, $upordown);
			}
			echo "</td>";
			echo "<td>";
			if ($row = $upordown_last7days->fetch_assoc()) {
				deviceinfo($row, $upordown);
			}
			echo "</td>";
			echo "<td>";
			if ($row = $upordown_last30days->fetch_assoc()) {
				deviceinfo($row, $upordown);
			}
			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
		mysqli_free_result($upordown_total_today);
		mysqli_free_result($upordown_total_yesterday);
		mysqli_free_result($upordown_total_last7days);
		mysqli_free_result($upordown_total_last30days);
		mysqli_free_result($upordown_today);
		mysqli_free_result($upordown_yesterday);
		mysqli_free_result($upordown_last7days);
		mysqli_free_result($upordown_last30days);
	}
?>
