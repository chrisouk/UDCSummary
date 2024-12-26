<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

	include_once("DBConnectInfo.php");
	$dsn = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dsn);

	$formstring = file_get_contents('rightpane_rtl.inc');

	# Query the database for the notation specified and fill in the result fields into
	# the string above
	include_once("udcform.php");
	$udcform = new UDCForm();
	$udcform->queryformvars($dsn);
	$udcform->setformvars($dsn, "", $formstring, false);
	
	echo $formstring;
?>