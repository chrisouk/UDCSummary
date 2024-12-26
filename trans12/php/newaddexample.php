<?php

	include_once("specialchars.php");
		
	$examplestring = "";
	$notation = "";
	$description = "";
	$exampleencoded = "";
	$seq=1;

	if (isset($_GET['examplestring']))
		$examplestring=html_entity_decode($_GET['examplestring'], ENT_COMPAT, "UTF-8");

	if (isset($_GET['notation']))
		$notation=html_entity_decode($_GET['notation'], ENT_COMPAT, "UTF-8");	
	if (isset($_GET['description']))
		$description=$_GET['description'];
	
	if (isset($_GET['encoded']))
		$exampleencoded=$_GET['encoded'];
	
	//echo "Notation = " . $notation . "<br>\n";
	//echo "Notation = " . urldecode($notation). "<br>\n";
	//echo "Notation = " . html_entity_decode($notation). "<br>\n";
	//echo "Notation = " . html_entity_decode(urldecode($notation)). "<br>\n";
    //echo "Raw ES = " . $_GET['examplestring'] . "<br>\n";
    //echo "ES = " . $examplestring . "<br>\n";
	//echo "Raw=" . $_GET['description'] . "<br>\n";
	//echo "Decoded=" . $description . "<br>\n"; 
	
	$lines = split("@", $examplestring);
	if (count($lines) == 0)
	{
		$lines[0] = $examplestring;
	}
	
	include_once 'encodeexample.php';
	
	// Build the table HTML to return to the client, including all the examples and
	// the example string
	
	$newexamplestring = "";
	$tablestring =  "<table id=\"extable\" class=\"reftable\" width=\"99%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr>" .
					"<td width=\"15%\" align=\"left\">Notation</td><td width=\"75%\" align=\"left\">Description</td>" .
					"<td width=\"10%\">&nbsp;</td></tr>\n";
					
	$linkid = 1;
	foreach($lines as $line)
	{		
		if (strlen(trim($line)) > 0)
		{
			$parts = split("#", $line);
			if ($parts[0] == $notation)
			{
				$parts[1] = $description;
				$parts[4] = "true";
			}
			
			$parts[0] = specialchars($parts[0]);
			$parts[1] = specialchars($parts[1]);
			$parts[2] = specialchars($parts[2]);
	
			$editlinkname = "editlink_" . $linkid++;		
	
	        $htmlnotation = htmlentities($parts[0], ENT_COMPAT, "UTF-8");
	        $htmldescription = urlencode(htmlentities($parts[1], ENT_COMPAT, "UTF-8"));
	        //$htmldescription = $parts[1];
	        
			$newexamplestring .= $htmlnotation . "#" . $htmldescription . "#" . $parts[2] . "#" . $parts[3] . "#" . $parts[4] . "@";		
			$tablestring .= "<tr>" .
							"<td width=\"15%\" bgcolor=\"white\">" . $parts[0] . "</td>" .
							"<td width=\"75%\" bgcolor=\"";
							if ($parts[4] == "true")
							{
								$tablestring .= "white";
							}
							else
							{
								$tablestring .= "#FDFFBB";
							}
			$tablestring .= "\">" . $parts[1] . "</td>" .							
							"<td width=\"10%\" bgcolor=\"white\"> " .
							"<div id=\"" . $editlinkname . "\" <a href=\"#exampleentry\" onMouseDown=\"javascript:editExample('" . $htmlnotation . 
							"');return true;\">Edit</a></div>" .
							"</td>" .
							"</tr>\n";
		}		
	}
	
	$tablestring .= "</table>\n";
	$examplestring = "<div class=\"inputsection\">" .
					 "<a name=\"exampleentry\"></a>" . 
					 "<div class=\"inputrow\">" .
	                    "<div class=\"inputlabel\">Description </div>" .
	                    "<div class=\"inputvalue inputvaluelong\">" .
	                        "<input id=\"examplenotation\" type=\"hidden\">" .
	                        "<input id=\"exampleencoded\" type=\"hidden\">" .
	                        "<input class=\"edittextarea inputexamples\" id=\"exampledescription\" type=\"text\" size=\"80\">" .
	                        "&nbsp;<a href=\"#exampleentry\" onMouseDown=\"javascript:addExample();\">Change</a>&nbsp;<a href=\"#exampleentry\" onMouseDown=\"javascript:addExample('none');\">Cancel</a>" .
	                    "</div>" .
	                 "</div>" .				 
					 "<input id=\"examplestring\" name=\"examplestring\" type=\"hidden\" value=\"". $newexamplestring . "\">\n" .
					 "</div>";                 
					 
	echo $tablestring . $examplestring;

?>