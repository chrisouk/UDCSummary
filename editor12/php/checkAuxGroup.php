<?php

require_once("DBConnectInfo.php");
include_once("specialchars.php");
	
$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
mysql_select_db (DBDATABASE);

$auxgroup="";
if (isset($_GET['auxgroup']))
	$auxgroup=urldecode($_GET['auxgroup']);

$errorstring = "";
$returnstring = $auxgroup;

$sql = "select x.special_aux_type, c.special_aux_group_id, f.description from language_fields f, classmarks c, classmark_aux_types x where c.classmark_id = f.classmark_id and c.classmark_id = x.classmark_id and field_id = 1 and f.language_id = 1 and c.classmark_tag = '" . mysql_real_escape_string($auxgroup, $dbc) . "'";
$res = @mysql_query($sql, $dbc);

$rowcount=0;
$resultcount = mysql_num_rows($res);
if ($resultcount > 0)
{
	$row = mysql_fetch_array($res, MYSQL_NUM);
	$auxtype= $row[0];
	$groupid = $row[1];
	if ($auxtype == 0)
	{
		$returnstring = "*[" . $auxgroup . "] is not a special auxiliary*";	
	}
	else if ($groupid != 0)
	{
		$returnstring = "*Auxiliary [" . $auxgroup . "] already belongs to auxiliary group " . $groupid . "*";
	}
	else
	{
		$returnstring = $auxgroup . " " . $row[2];		
	}
}
else
{
	$errorstring = "*[" . $auxgroup . "] is not a valid notation*";
}

mysql_free_result($res);				

echo $errorstring . $returnstring;

?>