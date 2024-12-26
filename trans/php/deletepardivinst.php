<?php

$pardivinststring = "";
$pardivinstnotation = "";

if (isset($_GET['pardivinststring']))
	$pardivinststring=urldecode($_GET['pardivinststring']);
	
if (isset($_GET['srcnotation']))
	$pardivinstnotation=urldecode($_GET['srcnotation']);
	
$lines = split(";", $pardivinststring);
if (count($lines) == 0)
{
	$lines[0] = $pardivinststring;
}	

$newpardivinststring = "";
$mainline = "";
$tablestring = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td width=\"25%\" align=\"left\">Source Notation</td><td width=\"25%\" align=\"left\">Source Notation</td><td width=\"25%\" align=\"left\">Encoded</td><td width=\"25%\" align=\"left\">Target Notation</td><td width=\"25%\" align=\"left\">Encoded</td><td>&nbsp;</td></tr>\n";
foreach($lines as $line)
{		
	if (strlen(trim($line)) > 0)
	{
		$parts = split("#", $line);
		if ($parts[0] != $pardivinstnotation)
		{
			$newpardivinststring .= $line . ";";		
			$tablestring .= "<tr><td width=\"25%\" bgcolor=\"white\">" . $parts[0] . "</td><td width=\"25%\" bgcolor=\"white\">" . $parts[1] . "</td><td width=\"25%\" bgcolor=\"white\">" . $parts[2] . "</td><td width=\"25%\" bgcolor=\"white\">" . 
			                 $parts[3] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deleteParDivInst('". $parts[0] . "');return true\">Delete</a></td></tr>\n";
		}
	}
}

$tablestring .= "</table>\n";
$pardivinststring = "<div class=\"inputsection\">" .
					 "<a name=\"pardiveentry\"></a><div class=\"inputrow\"><div class=\"inputlabel\">Source </div> <div class=\"inputvalue\"><input class=\"edittextarea\" id=\"pardivinstsrcnotation\" type=\"text\" size=\"30\"></div>" .
					 "<div class=\"inputlabel\">Src Encoded</div><div class=\"inputvalue\"><input class=\"editttextarea\" id=\"pardivinstsrcencoded\" type=\"text\" size=\"30\"></div>" .
					 "<a href=\"#pardiventry\" onMouseDown=\"javascript:addParDivInst();\">Add</a></div>" .
					 "<div class=\"inputrow\"><div class=\"inputlabel\">Target </div> <div class=\"inputvalue\"><input class=\"edittextarea\" id=\"pardivinsttgtnotation\" type=\"text\" size=\"30\"></div>" .
					 "<div class=\"inputlabel\">Tgt Encoded</div><div class=\"inputvalue\"><input class=\"editttextarea\" id=\"pardivinsttgtencoded\" type=\"text\" size=\"30\"></div>" .
					 "</div>" .
					 "<input id=\"pardivinststring\" name=\"examplestring\" type=\"hidden\" value=\"". $newpardivinststring . "\"></div>\n";

//echo "[" . $mainline. "]";	
echo $tablestring . $pardivinststring;

?>