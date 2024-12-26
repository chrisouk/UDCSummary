<?php

require_once("DBConnectInfo.php");
include_once("specialchars.php");
include_once("scanterms.php");

$examplestring = "";
$verbalexamples = "";
$caption = "";

if (isset($_GET['verbalexamples']))
	$verbalexamples=urldecode($_GET['verbalexamples']);
	
if (isset($_GET['caption']))
	$caption=urldecode($_GET['caption']);

//echo "Caption: " . $caption . "\n";
//echo "VE: " . $verbalexamples . "\n";

$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
mysql_select_db (DBDATABASE);

$errorstring = "";
$stopwordarray = array();
$wordarray = array();

$sql = "select stopword from stopwords";
$res = @mysql_query($sql, $dbc);

$rowcount=0;
$resultcount = mysql_num_rows($res);
if ($resultcount > 0)
{
	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
	{
		$term = $row[0];
		$stopwordarray[$term] = trim($row[0]);
	}
}

mysql_free_result($res);				

scanterms($wordarray, $caption, $stopwordarray, 1);
//scanterms($wordarray, $caption, $stopwordarray, 2);
scanterms($wordarray, $verbalexamples, $stopwordarray, 1);
//scanterms($wordarray, $verbalexamples, $stopwordarray, 2);

//echo "Count=" . count($wordarray) . "<br>\n";
//sort($wordarray);

echo implode("\n", $wordarray);

?>