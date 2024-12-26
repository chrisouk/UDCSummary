<?php

require_once("DBConnectInfo.php");
include_once("specialchars.php");
	
$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
mysql_select_db (DBDATABASE);
//mysql_set_charset('latin1',$dbc);

$refstring = "";
$refnotation = "";
$encoded = "";

if (isset($_GET['refstring']))
	$refstring=urldecode($_GET['refstring']);
	
if (isset($_GET['refnotation']))
	$refnotation=urldecode($_GET['refnotation']);
	
$lines = split(";", $refstring);
if (count($lines) == 0)
{
	$lines[0] = $refstring;
}

// Add tde notation
if ($refnotation != "")
{
	$lines[count($lines)] = $refnotation . "#";
}

include_once 'encodeexample.php';

$sortkeys = array();
$keyarray = array();
foreach($lines as $line)
{
	$parts = split("#", $line);
	if (trim($parts[0]) != "")
	{
		$encoded = encodeExample($parts[0]);
		//echo "Encoded " . $parts[0] . " as " . $encoded . "<br>\n";
		$keyarray[$encoded] = $line;
		$sortkeys[$encoded] = $encoded;
	}
}	


if (sort($sortkeys, SORT_STRING) == false)
{
	//echo "Failed to sort<br>\n";
}
else
{
	//echo "Sorted<br>\n";
}

$errorstring = "";
$newrefstring = "";
$tablestring = "<table class=\"reftable\" width=\"99%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td width=\"15%\" align=\"left\">Notation</td><td width=\"80%\" align=\"left\">Description</td><td>&nbsp;</td></tr>\n";
foreach($sortkeys as $key => $lookup)
{		
	$line = $keyarray[$lookup];
	
	//echo $key . "|" . $line .  "<br>\n";
	$parts = split("#", $line);
	
	$sql = "select f.description from language_fields f, classmarks c where c.classmark_id = f.classmark_id and field_id = 1 and f.language_id = 1 and c.classmark_tag = '" . mysql_real_escape_string($parts[0], $dbc) . "'";
	$res = @mysql_query($sql, $dbc);
	//echo $sql . "<br>\n";
	$rowcount=0;
	$resultcount = mysql_num_rows($res);
	if ($resultcount > 0)
	{
		$row = mysql_fetch_array($res, MYSQL_NUM);
		$newrefstring .= htmlentities($parts[0], ENT_COMPAT) . "#" . htmlentities($row[0], ENT_COMPAT) . ";";		
		$tablestring .= "<tr><td width=\"15%\" bgcolor=\"white\">" . specialchars($parts[0]) . "</td><td width=\"80%\" bgcolor=\"white\">" . specialchars($row[0]) . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#refentry\" onMouseDown=\"javascript:deleteRef('". htmlentities($parts[0], ENT_COMPAT) . "');return true\">Delete</a></td></tr>\n";
	}
	else
	{
		$errorstring .= "*[" . specialchars($parts[0]) . "] is not a valid class notation*\n";
	}
	
	mysql_free_result($res);				
}

$tablestring .= "</table>\n";
$refstring = "<div class=\"inputsection\"><div class=\"inputrow\"><div class=\"inputlabel\">New Notation</div><div class=\"inputvalue inputvaluelong\">" .
			 "<input class=\"edittextarea\" id=\"refnotation\" type=\"text\">&nbsp;<a href=\"../helprefs.htm\" target=_blank>" .
			 "<img src=\"../img/help.png\" border=\"0\"></a><input id=\"refstring\" name=\"refstring\" type=\"hidden\" value=\"". $newrefstring . "\">&nbsp;<a href=\"#refentry\" onMouseDown=\"javascript:addRef();\">Add</a></div></div></div>\n";

//echo "[" . $refnotation . "]";	
echo $errorstring . $tablestring . $refstring;

?>