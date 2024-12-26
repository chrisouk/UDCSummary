<?php
    session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Chris Overfield" />

	<title>UDC Summary Export</title>
</head>

<body style="font-family: Calibri,Tahoma,Helvetica,sans-serif; font-size: 1.05em; line-height: 1.4em;">

    <div style="width: 1000px; margin-left: auto; margin-right: auto; margin-top: 10px;">
    <img src="../images/udcsumtitle.jpg" border="0" /><br /><br />
    Please select a language to export and click <strong>Export</strong>.  The file produced should be opened with Microsoft Word, selecting <strong>Unicode (UTF-8)</strong> as the encoding type - see <a href="../images/word_attach.jpg">screenshot</a>.<br /><br />
    </div>
    <div style="width: 300px; margin-left: auto; margin-right: auto; margin-top: 10px;">
        <form action="export.php" method="GET">
        Export Type:<br /><select name="exporttype">
            <option value="caption0">All Captions</option>
            <option value="caption1">Captions - level 1</option>
            <option value="caption2">Captions - level 2</option>
            <option value="caption3">Captions - level 3</option>
            <option value="tagged">Full Tagged</option>
            <option value="full">Full Plain</option>
            </select> <br /><br />
            <?php
            # Establish database connection
        	require_once("DBConnectInfo.php");
        
        	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        	mysql_select_db (DBDATABASE);
            mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
            
            $sql = "select language_id, description from language " . $lang;
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
                while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    echo "<input type=\"radio\" name=\"lang\" value=\"" . $row[0] . "\">" . $row[1] . "<br>\n";
            	}
            	mysql_free_result($res);
            }
            @mysql_close($dbc);
            ?>
            <br />
            <input type="submit" value="Export">
        </form>
    </div>

</body>
</html>