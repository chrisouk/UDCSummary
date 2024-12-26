<?php

require_once("DBConnectInfo.php");
include_once("specialchars.php");
	
$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
mysql_select_db (DBDATABASE);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
$category = "";
$language = 1;
if (isset($_GET['category']))
	$category=urldecode($_GET['category']);
if (isset($_GET['language']))
	$language=urldecode($_GET['language']);
	
$errorstring = "";
$returnstring = $category;

$sql = "select f.description, f.language_id from language_fields f, classmarks c where c.classmark_id = f.classmark_id and field_id = 1 and c.classmark_tag = '" . mysql_real_escape_string($category, $dbc) . "' order by f.language_id";
$res = @mysql_query($sql, $dbc);

$rowcount=0;
$resultcount = mysql_num_rows($res);
if ($resultcount > 0)
{
	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
	{
		if ($row[1] == 1 || $row[1] == $language)
			$returnstring = $row[1] . '#' . $category . " " . $row[0];				
	}
}
else
{
	$errorstring = "*[" . $category . "] is not a valid notation*";
}

mysql_free_result($res);				

echo $errorstring . $returnstring;

?>