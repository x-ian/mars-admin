<? 
$username = $_GET['mac'];

include('common.php'); 

$mysqli = mysqli_connect('localhost', $MYSQL_USER, $MYSQL_PASSWD, $MYSQL_DB);

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past to bypass browser caching
?>

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
  	    <h1>Traffic details at <?php echo date('Y-m-d H:i:s'); ?></h1>
		<?= dropdown_link_to_device($username) ?>
	  </div>

<? 
$today = date('Y-m-d', strtotime('-0 day'));
$yesterday = date('Y-m-d', strtotime('-1 day'));
$daysago7 = date('Y-m-d', strtotime('-6 days'));
$daysago30 = date('Y-m-d', strtotime('-29 days'));	

function deviceinfo($row, $upordown) {
//	$number = "<a href='/mars/device_with_volume.php?username={$row[username]}'> {$row[$upordown]} </a>";
//	$link = dropdown_link_to_device($row['username']);	
//    echo "{$number}: {$link}";
	echo $row[$upordown] . ": " . $row["remote_ip"] . " (" . $row["reverse_dns"] . ")";
}

// include("./device-traffic-details-2.php");

require dirname(__FILE__)."/device-traffic-details-2.php";


echo "<div class=\"page-header\"><h1>Top downloads</h1></div>";
generatedailytraffic($mysqli, $username, 'Download', $today, $yesterday, $daysago7, $daysago30);

echo "<div class=\"page-header\"><h1>Top uploads</h1></div>";
generatedailytraffic($mysqli, $username, 'Upload', $today, $yesterday, $daysago7, $daysago30);

?>

<br/>

<p>Updated every 5 minutes; up to first 5 minutes may not be counted.</p>

<br/>

</div>
</body>
