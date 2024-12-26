<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Chris Overfield" />

	<title>UDC Summary Export</title>
</head>

<body>

    <div style="float: left; width: 700px; margin-left: auto; margin-right: auto; margin-top: 10px;">
        <img src="images/udcsummary.jpg" border="0" /><br />
        <form action="export.php" method="GET">
        <select id="lang" name="lang">
        <?php
        # Establish database connection
    	require_once("DBConnectInfo.php");
    
    	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
    	mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        
        $sql = "select language_id, description from language = " . $lang;
       	$res = @mysql_query($sql, $dbc);
        if ($res)
        {
            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
                echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>\n";
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
        ?>
        </select> 
        </form>
    </div>

</body>
</html>