<?php

    session_cache_expire("120");
    session_start();
    require_once("checksession.php");
    checksession();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<meta name="author" content="Chris Overfield" />
    <link rel="stylesheet" href="../udcedit.css" type="text/css" />
    <link rel="stylesheet" href="../udc1000.css" type="text/css" />
    <link rel="shortcut icon" href="../img/udc.ico" type="image/x-icon" />

	<title>UDC MRF Editor</title>
</head>

<body>

<div id="pagecontainer">
<div id="titleimagecontainer_thin" style="width: 645px; margin-left: auto; margin-right: auto;">&nbsp;</div>
<div style="width: 645px; margin-left: auto; margin-right: auto; margin-top: 10px; font-family: Tahoma, Helvetica, sans-serif; font-size: 13px;">
<div style="width: 645px; float: right"><a href="edittag.php">Back to Editor</a></div>
<div style="width: 645px; float: right; clear: left; margin: 10px 0px;">
<table width="100%" bgcolor="#dddddd" cellpadding="3" cellspacing="1" border="0">
<tr><td><strong>User</strong></td><td><strong>Activity</strong></td><td><strong>Time</strong></td><tr>
 
<?php
    $dt = getdate();
    $year = $dt[year];
    $month = $dt[month];
    
    
/**
 * @author Chris Overfield
 * @copyright 2010
 */

    define("RECORDS_PER_PAGE", 30);
    
    require_once('DBConnectInfo.php');
    
    $start_record = 0;
    if (isset($_GET['sr']))
    {
        $start_record = $_GET['sr'];
    }
    
	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");	//mysql_set_charset('latin1',$dbc);

    define(RECORDS_PER_PAGE, 20);
    
    $record_total = 0;
    
    $sql = "select count(*) from user_activity";
    $res = @mysql_query($sql);
	if ($res)
	{
		if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
		  $record_total = $row[0];
		}
	}
    
	@mysql_free_result($res);

    $sql = "select userid, activity, CONVERT_TZ(activity_time,'+00:00','+08:00') from user_activity order by activity_time desc LIMIT " . $start_record . ", " . (RECORDS_PER_PAGE);
    //echo $sql . "<br>\n";
    $res = @mysql_query($sql);
	if ($res)
	{
		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
		  echo "<tr>";
          echo "<td width=\"33%\" bgcolor=\"white\">" . $row[0] . "</td>";
          echo "<td width=\"33%\" bgcolor=\"white\">" . $row[1] . "</td>";
          echo "<td width=\"33%\" bgcolor=\"white\">" . $row[2] . "</td>";
          echo "</tr>\n";
		}
	}
    
	@mysql_free_result($res);
    
?>

</table>

<?php
    echo "<br>Page: ";
    
    $pages = $record_total / RECORDS_PER_PAGE;
    $start_page = ($start_record / RECORDS_PER_PAGE);
    $start_block = floor($start_page / 10) * 10;
    $previous_start = max($start_block - 1, 0);
    $next_start = min($record_total-1, $start_block+10);
    
    if ($start_block != 0)
    {
        echo "<a href=\"useractivity.php?sr=" . ($previous_start * RECORDS_PER_PAGE) . "\"><<</a> ";
    }
    for($i=$start_block; $i < ($start_block + 10) && ($i < $pages); $i++)
    {
        
        if (($i * RECORDS_PER_PAGE) != $start_record)
        {
            echo "<a href=\"useractivity.php?sr=" . ($i * RECORDS_PER_PAGE) . "\">";
        } 
        
        echo ($i+1);
        
        if (($i * RECORDS_PER_PAGE) != $start_record)
        {
            echo "</a>";
        } 
        echo "&nbsp;";        
    }
    if (($next_start*RECORDS_PER_PAGE) < $record_total)
    {
        echo "<a href=\"useractivity.php?sr=" . ($next_start * RECORDS_PER_PAGE) . "\">>></a> ";
    }
?>
</div>
</div>
</div>
</body>
</html>