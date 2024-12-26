<?php

	session_start();

	echo "Hello<br>\n";	

	/**
	 * @author Chris Overfield
	 * @copyright 2009
	 */

	define("MAX_USERID_LEN", 30);
	
	$userid = "";
	$password = "";
	$deflang = 1;	// English
	$usertype = 1;
		
	if(isset($_POST['userid']))
	{
		$userid = $_POST['userid'];
	}

	if(isset($_POST['password']))
	{
		$password = $_POST['password'];
	}

	if(isset($_POST['defaultlanguage']))
	{
		$deflang = $_POST['defaultlanguage'];
	}

	if(isset($_POST['usertype']))
	{
		$usertype = $_POST['usertype'];
	}

	echo "UserID=" . $userid . "<br>\n";
	echo "Pwd=" . $password . "<br>\n";
	
	require_once("DBConnectInfo.php");

	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);

	echo "Connected to database<br>\n";
	
	if(strlen($userid) > MAX_USERID_LEN)
	{
		$userid = substr($userid, 0, MAX_USERID_LEN);
	}
	
	$update = false;
	
	$sql = "select pwd from udct_user_details where userid = '" . addslashes($userid) . "'";
	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
		$row = mysql_fetch_array($res, MYSQL_NUM);
		if ($row)
		{
			echo "Record exists for this user - updating<br>\n";
			$update = true;
		}
		mysql_free_result($res);
	}

	if ($update)
	{
		$encpwd = md5($password);
		$sql = "update udct_user_details set pwd = '" . addslashes($encpwd) . "', default_language_id = " . $deflang . ", usertype = " . $usertype . " where userid = '" . addslashes($userid) . "'"; 
		echo $sql . "<br>\n"; 
		$res = mysql_query($sql, $dbc);
		if ($res)
		{
			echo "User updated<br>\n";
			mysql_query("COMMIT");
		}
	}
	else
	{
		$encpwd = md5($password);
		$sql = "insert into udct_user_details (userid, pwd, usertype, default_language_id) values ('" . $userid . "', '" . addslashes($encpwd) . "', " . $usertype . ", " . $deflang . ")";
		echo $sql . "<br>\n"; 
		$res = mysql_query($sql, $dbc);
		if ($res)
		{
			echo "User inserted<br>\n";
			mysql_query("COMMIT");
		}		
	}
	
	@mysql_close($dbc);
?>