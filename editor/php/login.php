<?php

	session_start();

	//echo "Hello<br>\n";	
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
	
	//echo "UserID=" . $userid . "<br>\n";
	//echo "Pwd=" . $password . "<br>\n";
	
	require_once("DBConnectInfo.php");

	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);

	if(strlen($userid) > MAX_USERID_LEN)
	{
		$userid = substr($userid, 0, MAX_USERID_LEN);
	}
	
	$pwd = "";
	$access_page1 = "N";
	$access_page1 = "N";	
    $access_page1 = "N";
   	$show_reviewer_comment = "N";	
    $revision_name = "";
    $revision_date = "";
    $updates_allowed = "N";
    
	$sql = "select pwd, access_page1, access_page2, access_page3, show_reviewer_comment, revision_name, revision_date, updates_allowed from udc_user where login_name = '" . addslashes($userid) . "'";
	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
		$row = mysql_fetch_array($res, MYSQL_NUM);
		if ($row)
		{
			$pwd = $row[0];
			$access_page1 = strtoupper($row[1]);
        	$access_page2 = strtoupper($row[2]);
            $access_page3 = strtoupper($row[3]);
            $show_reviewer_comment = strtoupper($row[4]);
            $revision_name = $row[5];
            $revision_date = $row[6];
            $updates_allowed = strtoupper($row[7]);
		}
		mysql_free_result($res);
	}

	$encpwd = md5($password);
	//echo "Pwd=" . $encpwd . "<br>\n";
	if ($encpwd == $pwd)
	{
		$_SESSION['userid'] = $userid;
        $_SESSION['access_page1'] = $access_page1;
        $_SESSION['access_page2'] = $access_page2;
        $_SESSION['access_page3'] = $access_page3;
        $_SESSION['show_reviewer_comment'] = $show_reviewer_comment;
        $_SESSION['revision_name'] = $revision_name;
        $_SESSION['revision_date'] = $revision_date;
        $_SESSION['updates_allowed'] = $updates_allowed;
        
        $sql = "insert into user_activity (userid, activity, activity_time) values ('" . $userid . "', 'LOGGED IN', NOW())";
    	$res = @mysql_query($sql, $dbc);
    	if ($res)
    	{
            @mysql_query("COMMIT", $dbc);
        }

    	$sql = "select db_name, site_name from mgmt_db_current";
    	$res = @mysql_query($sql, $dbc);
    	if ($res)
    	{
    		$row = mysql_fetch_array($res, MYSQL_NUM);
    		if ($row)
    		{
                $_SESSION['mgmt_db_name'] = $row[0];
                $_SESSION['mgmt_site_name'] = $row[1];
    		}
    		mysql_free_result($res);
    	}

        @mysql_close($dbc);
        
		header("Location: edittag.php");
	}	
	else
	{
		$_SESSION['userid'] = "";
        $_SESSION['access_page1'] = "N";
        $_SESSION['access_page2'] = "N";
        $_SESSION['access_page3'] = "N";
        $_SESSION['show_reviewer_comment'] = "N";
        $_SESSION['revision_name'] = "";
        $_SESSION['revision_date'] = "";
        $_SESSION['updates_allowed'] = "N";

        session_destroy();
        @mysql_close($dbc);

		header("Location: ../login.htm");
	}
?>