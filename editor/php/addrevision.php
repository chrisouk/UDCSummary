<?php

include_once("specialchars.php");
	
$revisionstring = "";
$revdate = "";
$revfields = "";
$revsource = "";
$revcomments = "";

if (isset($_GET['revisionstring']))
	$revisionstring=urldecode($_GET['revisionstring']);
	
if (isset($_GET['revdate']))
	$revdate=urldecode($_GET['revdate']);
	
if (isset($_GET['revsource']))
	$revsource=urldecode($_GET['revsource']);
	
if (isset($_GET['revfields']))
	$revfields=urldecode($_GET['revfields']);

if (isset($_GET['revcomments']))
	$revcomments=urldecode($_GET['revcomments']);

$lines = split(";", $revisionstring);
//echo $revisionstring . "<br>\n";
if (count($lines) == 0)
{
	$lines[0] = $revisionstring;
}

$errorstring = "";

// Add the revision date and other fields
if ($revdate != "")
{
	$lines[count($lines)] = $revdate . "#" . $revfields. '#' . $revsource . "#" . $revcomments;
}
else
{
	$errorstring = "*Revision date is required*";
}

if (strlen($revfields) == 0)
{
	if ($errorstring != "")
	{
		$errorstring = substr($errorstring, 0, strlen($errorstring)-1) . "\n";
	}
	else
	{
		$errorstring = "*";
	}
	$errorstring .= "Revision fields are required";
}

if (strlen($errorstring) > 0)
{
	$errorstring .= "*";
}

$sortkeys = array();
$keyarray = array();
foreach($lines as $line)
{
	$parts = split("#", $line);
	if (trim($parts[0]) != "")
	{
		$encoded = $parts[0];
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

$newrevisionstring = "";

$tablestring = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
		        "<tr><td align=\"left\" width=\"15%\">Date</td><td width=\"20%\" align=\"left\">Fields</td><td width=\"15%\" align=\"left\">Source</td><td width=\"40%\" " .
                "align=\"left\">Comment</td><td width=\"5%\">&nbsp;</td><td width=\"5%\">&nbsp;</td></tr>\n";

foreach($sortkeys as $key => $lookup)
{		
	$line = $keyarray[$lookup];
	
	if (strlen(trim($line)) > 0)
	{
		$parts = split("#", $line);
		
		$newrevisionstring .= $parts[0] . "#" . $parts[1] . "#" . $parts[2] . "#" . $parts[3] . ";";		
		$tablestring .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $parts[0] . "</td><td width=\"20%\" bgcolor=\"white\">" . $parts[1] .
                        "</td><td width=\"15%\" bgcolor=\"white\">" . $parts[2] . "</td><td width=\"40%\" bgcolor=\"white\">" . $parts[3] . 
                        "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#revhistentry\" onMouseDown=\"javascript:editRevision('" . $parts[0] . 
				        "');return true;\">Edit</a></td><td width=\"5%\" bgcolor=\"white\"><a href=\"#revhistentry\" onMouseDown=\"javascript:deleteRevision('" . $parts[0] . 
                        "');return true;\">Delete</a></td></tr>\n";
	}			
}

$tablestring .= "</table>" .
                "<a name=\"revhistentry\"></a><div class=\"revisionlabel\">Date</div><div class=\"revisionrow\"><input class=\"edittextarea\" style=\"width: 40px;\" " .
                "id=\"revisiondate\" type=\"text\"> Fields <input class=\"edittextarea inputfield\" id=\"revisionfields\" " .
				"type=\"text\">" .
			 	" Source <input class=\"editttextarea inputfield\" id=\"revisionsource\" type=\"text\"></div><div class=\"revisionlabel\">Comment</div>" .
                "<div class=\"revisionrow\"><input class=\"editttextarea\" style=\"width: 377px;\" id=\"revisioncomments\" type=\"text\"></div>" .
				"<input id=\"revisionstring\" name=\"revisionstring\" type=\"hidden\" value=\"". $newrevisionstring . "\"><a href=\"#revhistentry\" onMouseDown=\"javascript:addRevision();\">Add</a>\n";
                             /*
$tablestring .= "</table><a name=\"revhistentry\"></a> Date <input class=\"edittextarea\" id=\"revisiondate\" type=\"text\" size=\"10\"> Fields <input class=\"edittextarea\" id=\"revisionfields\" type=\"text\" size=\"19\">" .
			 " Source <input class=\"editttextarea\" id=\"revisionsource\" type=\"text\" size=\"10\"> Comments <input class=\"editttextarea\" id=\"revisioncomments\" type=\"text\" size=\"19\"><input id=\"revisionstring\" name=\"revisionstring\" type=\"hidden\" value=\"". $newrevisionstring . "\"><a href=\"#revhistentry\" onMouseDown=\"javascript:addRevision();\">Add</a>\n";
*/

echo $errorstring. $tablestring;

?>