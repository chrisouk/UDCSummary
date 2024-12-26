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
    $access_page1 = "";
    $access_page2 = "";
    $access_page3 = "";
    $show_reviewer_comment = "";
	$revision_name = "";
    $revision_date = "";
    $updates_allowed = "";
        
	if(isset($_POST['userid']))
	{
		$userid = $_POST['userid'];
	}

	if(isset($_POST['password']))
	{
		$password = $_POST['password'];
	}

	if(isset($_POST['access_page1']))
	{
		$access_page1 = $_POST['access_page1'];
	}
	if(isset($_POST['access_page2']))
	{
		$access_page2 = $_POST['access_page2'];
	}
	if(isset($_POST['access_page3']))
	{
		$access_page3 = $_POST['access_page3'];
	}
	if(isset($_POST['show_reviewer_comment']))
	{
		$show_reviewer_comment = $_POST['show_reviewer_comment'];
	}
	if(isset($_POST['revision_name']))
	{
		$revision_name = $_POST['revision_name'];
	}
	if(isset($_POST['revision_date']))
	{
		$revision_date = $_POST['revision_date'];
	}
	if(isset($_POST['updates_allowed']))
	{
		$updates_allowed = $_POST['updates_allowed'];
	}

	echo "UserID=" . $userid . "<br>\n";
	echo "Pwd=" . $password . "<br>\n";
	echo "Page1=" . $access_page1 . "<br>\n";
	echo "Page2=" . $access_page2 . "<br>\n";
	echo "Page3=" . $access_page3 . "<br>\n";
	echo "RevComment=" . $show_reviewer_comment . "<br>\n";
	echo "Revision = " . $revision_name . "<br>\n";
	echo "Revision Date = " . $revision_date . "<br>\n";
    echo "Updates Allowed = " . $updates_allowed . "<br>\n";
    
	require_once("DBConnectInfo.php");

	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);

	echo "Connected to database " . DBDATABASE . "<br>\n";
	
	if(strlen($userid) > MAX_USERID_LEN)
	{
		$userid = substr($userid, 0, MAX_USERID_LEN);
	}
	
	$update = false;
	
	$sql = "select pwd from udc_user where login_name = '" . addslashes($userid) . "'";
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

	$encpwd = md5($password);
    $p1 = ($access_page1 == "") ? "N" : "Y";
    $p2 = ($access_page2 == "") ? "N" : "Y";
    $p3 = ($access_page3 == "") ? "N" : "Y";
    $rc = ($show_reviewer_comment == "") ? "N" : "Y";
    $p4 = ($updates_allowed == "") ? "N" : "Y";

	if ($update)
	{ 
		$sql = "update udc_user set pwd = '" . addslashes($encpwd) . "', access_page1 = '" . $p1 . "', access_page2 = '" . $p2. "', access_page3 = '" . $p3. "', show_reviewer_comment = '" . 
                $rc . "', revision_name = '" . $revision_name . "', revision_date = '" . $revision_date . "', updates_allowed = '" . $p4 . "' where login_name = '" . addslashes($userid) . "'"; 
		echo $sql . "<br>\n"; 
		$res = mysql_query($sql, $dbc);
		if ($res)
		{
			echo "User updated<br>\n";
			mysql_query("COMMIT");
		}
        else
        {
            echo mysql_error($res);
        }        
	}
	else
	{
		$sql = "insert into udc_user (login_name, pwd, access_page1, access_page2, access_page3, show_reviewer_comment, revision_name, revision_date) values ('" . $userid . "', '" . addslashes($encpwd) . "', '" . $p1 .
                "', '" . $p2 . "', '" . $p3 . "', '" . $rc . "', '" . $revision_name . "', '" . $revision_date . "')";
		echo $sql . "<br>\n"; 
		$res = mysql_query($sql, $dbc);
		if ($res)
		{
			echo "User inserted<br>\n";
			mysql_query("COMMIT");
		}		
        else
        {
            echo "Error: " . mysql_error($res);
        }
	}
	
	@mysql_close($dbc);
?>