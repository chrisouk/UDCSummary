<?php

include_once("specialchars.php");
	
$pardivinststring = "";
$srcnotation = "";
$srcencoded = "";
$tgtnotation = "";
$tgtencoded = "";

if (isset($_GET['pardivinststring']))
	$pardivinststring=urldecode($_GET['pardivinststring']);
	
if (isset($_GET['srcnotation']))
	$srcnotation=urldecode($_GET['srcnotation']);
	
if (isset($_GET['tgtnotation']))
	$tgtnotation=urldecode($_GET['tgtnotation']);

if (isset($_GET['srcencoded']))
	$srcencoded=urldecode($_GET['srcencoded']);

if (isset($_GET['tgtencoded']))
	$tgtencoded=urldecode($_GET['tgtencoded']);

$lines = split(";", $pardivinststring);
//echo $pardivinststring . "<br>\n";
if (count($lines) == 0)
{
	$lines[0] = $pardivinststring;
}

// Add tde notation
if ($srcnotation != "")
{
	if ($srcencoded == "")
	{
		include_once 'encodeexample.php';
		$srcencoded =  encodeExample($srcnotation);
	}

	if ($tgtnotation != "")
	{
		if ($tgtencoded == "")
		{
			$tgtencoded =  encodeExample($tgtnotation);
		}
	}
	
	$lines[count($lines)] = $srcnotation . "#" . $srcencoded . "#" . $tgtnotation . "#" . $tgtencoded . "#";
}

$sortkeys = array();
$keyarray = array();
foreach($lines as $line)
{
	$parts = split("#", $line);
	if (trim($parts[0]) != "")
	{
		$encoded = $parts[1];
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

$newpardivinststring = "";
$tablestring = "<table class=\"reftable\" widtd=\"99%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td widtd=\"25%\" align=\"left\">Source</td><td widtd=\"25%\" align=\"left\">Encoded</td><td widtd=\"25%\" align=\"left\">Target</td><td widtd=\"25%\" align=\"left\">Encoded</td><td>&nbsp;</td></tr>\n";
foreach($sortkeys as $key => $lookup)
{		
	$line = $keyarray[$lookup];
	
	if (strlen(trim($line)) > 0)
	{
		$parts = split("#", $line);
		
		$newpardivinststring .= $parts[0] . "#" . $parts[1] . "#" . $parts[2] . "#" . $parts[3]. "#;";		
		$tablestring .= "<tr><td width=\"25%\" bgcolor=\"white\">" . $parts[0] . "</td><td width=\"25%\" bgcolor=\"white\">" . $parts[1] . "</td><td width=\"25%\" bgcolor=\"white\">" . $parts[2] . "</td><td width=\"25%\" bgcolor=\"white\">" . $parts[3] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deleteParDivInst('". $parts[0] . "');return true\">Delete</a></td></tr>\n";
	}		
}

$tablestring .= "</table>\n";

$pardivinststring =  "<div class=\"inputsection\">" .
					 "<a name=\"pardiveentry\"></a><div class=\"inputrow\"><div class=\"inputlabel\">Source </div> <div class=\"inputvalue\"><input class=\"edittextarea\" id=\"pardivinstsrcnotation\" type=\"text\" size=\"30\"></div>" .
					 "<div class=\"inputlabel\">Src Encoded</div><div class=\"inputvalue\"><input class=\"editttextarea\" id=\"pardivinstsrcencoded\" type=\"text\" size=\"30\"></div>" .
					 "<a href=\"#pardiventry\" onMouseDown=\"javascript:addParDivInst();\">Add</a></div>" .
					 "<div class=\"inputrow\"><div class=\"inputlabel\">Target </div> <div class=\"inputvalue\"><input class=\"edittextarea\" id=\"pardivinsttgtnotation\" type=\"text\" size=\"30\"></div>" .
					 "<div class=\"inputlabel\">Tgt Encoded</div><div class=\"inputvalue\"><input class=\"editttextarea\" id=\"pardivinsttgtencoded\" type=\"text\" size=\"30\"></div>" .
					 "</div>" .
					 "<input id=\"pardivinststring\" name=\"examplestring\" type=\"hidden\" value=\"". $newpardivinststring . "\"></div>\n";

echo $tablestring . $pardivinststring;

?>