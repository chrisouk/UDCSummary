<?php

include_once("specialchars.php");
	
$revisionstring = "";
$revdate = "";

if (isset($_GET['revisionstring']))
	$revisionstring=urldecode($_GET['revisionstring']);
	
if (isset($_GET['revdate']))
	$revdate=urldecode($_GET['revdate']);
	
$lines = split(";", $revisionstring);
//echo $revisionstring . "<br>\n";
if (count($lines) == 0)
{
	$lines[0] = $revisionstring;
}

$newrevisionstring = "";
$tablestring = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
		    "<tr><td align=\"left\" width=\"15%\">Date</td><td width=\"20%\" align=\"left\">Fields</td><td width=\"15%\" align=\"left\">Source</td><td width=\"40%\" align=\"left\">Comment</td><td width=\"5%\">&nbsp;</td><td width=\"5%\">&nbsp;</td></tr>\n";

foreach($lines as $line)
{		
	if (strlen(trim($line)) > 0)
	{
		$parts = split("#", $line);

		if ($parts[0] != $revdate)
		{
			$newrevisionstring .= $line . ";";		
			$tablestring .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $parts[0] . "</td><td width=\"20%\" bgcolor=\"white\">" . $parts[1] .
					 "</td><td width=\"15%\" bgcolor=\"white\">" . $parts[2] . "</td><td width=\"40%\" bgcolor=\"white\">" . $parts[3] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#revhistentry\" onMouseDown=\"javascript:editRevision('" . $parts[0] . 
					 "');return true;\">Edit</a></td><td width=\"5%\" bgcolor=\"white\"><a href=\"#revhistentry\" onMouseDown=\"javascript:deleteRevision('" . $parts[0] . "');return true;\">Delete</a></td></tr>\n";
		}
	}			
}

$tablestring .= "</table><a name=\"revhistentry\"></a> Date <input class=\"edittextarea\" id=\"revisiondate\" type=\"text\" size=\"10\"> Fields <input class=\"edittextarea\" id=\"revisionfields\" type=\"text\" size=\"19\">" .
			 " Source <input class=\"editttextarea\" id=\"revisionsource\" type=\"text\" size=\"10\"> Comments <input class=\"editttextarea\" id=\"revisioncomments\" type=\"text\" size=\"19\"><input id=\"revisionstring\" name=\"revisionstring\" type=\"hidden\" value=\"". $newrevisionstring . "\"><a href=\"#revhistentry\" onMouseDown=\"javascript:addRevision();\">Add</a>\n";

echo $tablestring . $newrevisionstring;

?>