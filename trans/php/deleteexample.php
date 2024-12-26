<?php

$examplestring = "";
$examplenotation = "";

  
if (isset($_GET['examplestring']))
	$examplestring=html_entity_decode(urldecode($_GET['examplestring']));
	
if (isset($_GET['examplenotation']))
	$examplenotation=html_entity_decode(urldecode($_GET['examplenotation']));
	
$lines = split(";", $examplestring);
if (count($lines) == 0)
{
	$lines[0] = $examplestring;
}	

$newexamplestring = "";
$mainline = "";
$tablestring = "<table class=\"reftable\" width=\"99%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><th width=\"15%\" align=\"left\">Notation</th><th width=\"65%\" align=\"left\">Description</th><th width=\"20%\" align=\"left\">Encoded</th><th width=\"5%\">&nbsp;</th><th width=\"5%\">&nbsp;</th></tr></tr>\n";
foreach($lines as $line)
{		
	if (strlen(trim($line)) > 0)
	{
		$parts = split("#", $line);
		if ($parts[0] != $examplenotation)
		{
            $htmlnotation = htmlentities($parts[0], ENT_COMPAT, "UTF-8");
			$newexamplestring .= htmlentities($line, ENT_COMPAT, "UTF-8") . ";";		
			$tablestring .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $parts[0] . "</td><td width=\"60%\" bgcolor=\"white\">" . $parts[1] . "</td><td width=\"20%\" bgcolor=\"white\">" . $parts[2] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#exampleentry\" onMouseDown=\"javascript:editExample('" . $htmlnotation . "');return true;\">Edit</a></td><td width=\"5%\" bgcolor=\"white\"><a href=\"#exampleentry\" onMouseDown=\"javascript:deleteExample('". $htmlnotation . "');return true\">Delete</a></td></tr>\n";
		}
	}
}

$tablestring .= "</table>\n";
$examplestring = "<div class=\"inputsection\">" .
				 "<a name=\"exampleentry\"></a><div class=\"inputrow\"><div class=\"inputlabel\">New Notation </div> <div class=\"inputvalue\"><input class=\"edittextarea\" id=\"examplenotation\" type=\"text\" size=\"30\"></div>" .
				 "<div class=\"inputlabel\">Encoded</div><div class=\"inputvalue\"><input class=\"editttextarea\" id=\"exampleencoded\" type=\"text\" size=\"30\"></div>" .
				 "<a href=\"#exampleentry\" onMouseDown=\"javascript:addExample();\">Add</a></div>" .
				 "<div class=\"inputrow\"><div class=\"inputlabel\">Description </div><div class=\"inputvalue inputvaluelong\"><input class=\"edittextarea\" id=\"exampledescription\" type=\"text\" size=\"84\"></div></div>" .
				 "</div>" .
				 "<input id=\"examplestring\" name=\"examplestring\" type=\"hidden\" value=\"". $newexamplestring . "\">\n";
					 
//echo "[" . $mainline. "]";	
echo $tablestring . $examplestring;

?>