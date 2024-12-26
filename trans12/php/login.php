<?php

    session_cache_expire("120");
	session_start();

	/**
	 * @author Chris Overfield
	 * @copyright 2009
	 */

	define("MAX_USERID_LEN", 30);

	$userid = "";
	$password = "";

	if(isset($_POST['userid']))
	{
		$userid = $_POST['userid'];
	}

	if(isset($_POST['password']))
	{
		$password = $_POST['password'];
	}

	// While translator is being updated ----
	if ($userid != 'aida')
	{
		header("Location: ../login.htm");
		exit();
	}

	// While translator is being updated ----

	#echo "UserID=" . $userid . "<br>\n";
	#echo "Pwd=" . $password . "<br>\n";

	require_once("DBConnectInfo.php");
	require_once("dbconnection.php");

	$dbc = DBConnection::getInstance()->getConnection();

	if(strlen($userid) > MAX_USERID_LEN)
	{
		$userid = substr($userid, 0, MAX_USERID_LEN);
	}

	$pwd = "";
	$deflang = 1;
    $usertype = 1;

	$sql = "select u.pwd, u.usertype, u.default_language_id, l.rtl from udct_user_details u join language l on u.default_language_id = l.language_id where u.userid = " . $dbc->quote($userid);
	foreach($dbc->query($sql) as $row)
	{
		$pwd = $row[0];
        $usertype = $row[1];
		$deflang = $row[2];
		$rtl = $row[3];

		if ($rtl == 'Y')
		{
			$_SESSION['rtl'] = true;
		}
		else
		{
			$_SESSION['rtl'] = false;
		}
	}

	$encpwd = md5($password);
	#echo "Pwd=" . $encpwd . "<br>\n";
	if ($encpwd == $pwd)
	{
        $sql = "insert into user_activity (userid, activity, activity_time) values (" . $dbc->quote($userid) . ", 'LOGGED IN', NOW())";
		$dbc->exec($sql);

		$_SESSION['userid'] = $userid;
        $_SESSION['userrole'] = $usertype;
		$_SESSION['deflang'] = $deflang;

		header("Location: edittag.php");
	}
	else
	{
		$_SESSION['userid'] = "";
		$_SESSION['deflang'] = 0;
        $_SESSION['userrole'] = 0;

		header("Location: ../login.htm");
	}
?>