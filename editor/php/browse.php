<html>
<head>
<title>Test Page</title>
	<link rel="StyleSheet" href="../dtree.css" type="text/css" />
	<script type="text/javascript" src="../dtree2.js"></script>
</head>

<body>
<?php

	session_start();
	
	require_once("DBConnectInfo.php");
		
	if (isset($_GET['tag']))
		$searchtag = $_GET['tag'];
	else if (isset($_POST['tag']))
		$searchtag = $_POST['tag'];
	
	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
	
	echo "Database opened<br>\n";

	include_once("browsetag.php");
	$resultcount = BrowseTag($searchtag, $dbc);
	
	mysql_close($dbc);
?>

</body>
</html>
