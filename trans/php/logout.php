<?php

    session_cache_expire("120");
	session_start();

	include_once("checksession.php");
	checksession();

	require_once("DBConnectInfo.php");

	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);

    if (isset($_SESSION['userid']))
    {
    	$userid = $_SESSION['userid'];
    	if (!empty($userid))
    	{
			$sql = "insert into user_activity (userid, activity, activity_time) values ('" . $userid . "', 'LOGGED OUT', NOW())";
			$res = @mysql_query($sql, $dbc);
			if ($res)
			{
				@mysql_query("COMMIT", $dbc);
			}
		}
	}
    @mysql_close($dbc);

    session_destroy();

    header("Location: ../login.htm");
?>