<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    session_start();
    
	require_once("DBConnectInfo.php");

	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    
    $userid = $_SESSION['userid'];
    
    $sql = "insert into user_activity (userid, activity, activity_time) values ('" . $userid . "', 'LOGGED OFF', NOW())";
	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
        @mysql_query("COMMIT", $dbc);
    }
    @mysql_close($dbc);

    session_destroy();
            
    header("Location: ../login.htm");

?>