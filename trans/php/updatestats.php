<?php header("Content-type: text/html; charset=UTF-8");

    session_cache_expire("120");
	session_start();

	include_once("checksession.php");
	checksession();

    define("DUMMYINSERT", false);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC PE Translator</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>


</head>


<body>

<?php

	include_once('DBConnectInfo.php');
	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");

	$sqlarray = array();
	$sqlarray[] =   "delete from translation_status_fields";
    $sqlarray[] =   "insert into translation_status_fields (classmark_id, language_id, field_id, lang_field_count) " .
	                "select f.classmark_id, f.language_id, f.field_id, count(*) ".
	                "from language_fields f join classmarks c on c.classmark_id = f.classmark_id ".
	                "where c.active = 'Y' ".
	                "and f.field_id in (select field_id from language_fields f2 where f2.classmark_id = f.classmark_id and f2.seq_no = f.seq_no and f2.field_id = f.field_id and f2.language_id = 1) ".
	                "group by f.classmark_id, f.language_id, f.field_id";

    foreach($sqlarray as $sql)
    {
	    echo $sql . " ";
	    $res = @mysql_query($sql, $dbc);
	    if ($res)
	    {
		    echo "SUCCESS<br>\n";
	    }
	    else
	    {
		    echo mysql_error($dbc) . "<br>\n";
	    }
    }

	$res = @mysql_query("COMMIT", $dbc);

	@mysql_close($dbc);
?>

</body>
</html>
