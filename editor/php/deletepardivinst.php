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

//echo $pardivinststring . "<br>\n";

$newpardivinststring = "";
$mainline = "";

$tablestring =  "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr>".
                "<td width=\"25%\" align=\"left\">Source</td><td width=\"25%\" align=\"left\">Encoded</td><td width=\"25%\" align=\"left\">Target</td>".
                "<td width=\"25%\" align=\"left\">Encoded</td><td>&nbsp;</td></tr>\n";

foreach($lines as $line)
{		
	if (strlen(trim($line)) > 0)
	{
		$parts = split("#", $line);
        //echo $parts[0] . "<br>\n";
		if ($parts[0] != $pardivinstnotation)
		{
			$tablestring .= "<tr><td width=\"25%\" bgcolor=\"white\">" . $parts[0] . "</td><td width=\"25%\" bgcolor=\"white\">" . $parts[1] . "</td><td width=\"25%\" bgcolor=\"white\">" . 
                            $parts[2] . "</td><td width=\"20%\" bgcolor=\"white\">" . $parts[3] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deletepardivinst('". 
                            $parts[0] . "');return true\">Delete</a></td></tr>\n";
			
			$newpardivinststring .= $line . ";";		
		}
	}
}

$tablestring .= "</table>" .
			         "<div class=\"inputsection\">" .
        			 "<a name=\"pardiveentry\"></a><div class=\"inputrow\"><div class=\"inputlabel\">Source </div> <div class=\"inputvalue\">".
                     "<input class=\"edittextarea inputfield\" id=\"pardivinstsrcnotation\" type=\"text\"></div>" .
        			 "<a href=\"#pardiventry\" onMouseDown=\"javascript:addParDivInst();\">Add</a></div>" .
        			 "<div class=\"inputrow\"><div class=\"inputlabel\">Target </div> <div class=\"inputvalue\"><input class=\"edittextarea inputfield\" id=\"pardivinsttgtnotation\" type=\"text\"></div>" .
        			 "</div>" .
        			 "<input id=\"pardivinststring\" name=\"pardivinststring\" type=\"hidden\" value=\"". $newpardivinststring . "\"></div>\n";

echo $tablestring;

?>