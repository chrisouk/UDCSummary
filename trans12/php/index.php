<?php
	session_start();
	require_once 'checksession.php';
	checksession();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="udc1000.css" type="text/css">
</head>

<body>
	<div id="maincontainer">

<?php 
	// Ok, first determine the filter
	$edition = $_SESSION['edition'];

	echo "<form action=\"changeedition\">\n";
	echo "	<select name=\"editiontype\">\n";
	echo "		<option name=\"MRF\"" . ($edition == "MRF") ? "selected" : "" . ">MRF</option>\n";
	echo "		<option name=\"UDC1000\"" . ($edition == "UDC1000") ? "selected" : "" . ">UDC 1000</option>\n";
	echo "		<option name=\"Pocket\"" . ($edition == "Pocket") ? "selected" : "" . ">Pocket</option>\n";
	echo "		<option name=\"Abridged\"" . ($edition == "Abridged") ? "selected" : "" . ">Abridged</option>\n";
	echo "		<option name=\"Full\"" . ($edition == "Full") ? "selected" : "" . ">Full</option>\n";
	echo "	</select>\n";
	echo "</form>\n";
?>

	</div>
</body>
</html>
