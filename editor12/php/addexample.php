<?php

include_once("specialchars.php");
	
$examplestring = "";
$notation = "";
$description = "";
$exampleencoded = "";


if (isset($_GET['examplestring']))
	$examplestring=html_entity_decode(urldecode($_GET['examplestring']));
	
if (isset($_GET['notation']))
	$notation=html_entity_decode(urldecode($_GET['notation']));	
if (isset($_GET['description']))
	$description=html_entity_decode(urldecode($_GET['description']));

if (isset($_GET['encoded']))
	$exampleencoded=urldecode($_GET['encoded']);

//echo "Notation = " . $notation;
//echo "Notation = " . urldecode($notation);
//echo "Notation = " . html_entity_decode($notation);
//echo "Notation = " . html_entity_decode(urldecode($notation));

$lines = split(";", $examplestring);
//echo $examplestring . "<br>\n";
if (count($lines) == 0)
{
	$lines[0] = $examplestring;
}

// Add the notation
if ($notation != "")
{
	if ($exampleencoded == "")
	{
		include_once 'encodeexample.php';
		$exampleencoded =  encodeExample($notation);
	}
	
	$lines[count($lines)] = $notation . "#" . $description . "#" . $exampleencoded . "#";
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

// Sort examples by encoded notation
if (sort($sortkeys, SORT_STRING) == false)
{
	//echo "Failed to sort<br>\n";
}
else
{
	//echo "Sorted<br>\n";
}

// Build the table HTML to return to the client, including all the examples and
// the example string

		$examples = "<table class=\"reftable\" width=\"99%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
				    "<tr><td align=\"left\" width=\"15%\">Notation</td><td width=\"75%\" align=\"left\">Description</td>" .
					"<td width=\"5%\">&nbsp;</td></tr>\n";

		$examplestring = "";
        $example_no = 1;
		foreach ($this->examples as $i => $value)
		{
			$langvalue = split("~", $value);
			$splitvalues = split("#", $langvalue[1]);
            
            $notationinput = "example_notation_" . $example_no;
            $captioninput = "example_caption_" . $example_no;
            $deletestate = "example_deleted_" . $example_no;
            
			$examples .= "<tr><td width=\"15%\" bgcolor=\"white\" valign=\"top\"><input type=\"hidden\" name=\"" . $deletestate . "\" id=\"" . $deletestate . "\" value=\"N\"><textarea rows=\"1\" class=\"examplenotationinput\" name=\"" . $notationinput . "\" id=\"" . $notationinput . "\">" . $i . "</textarea></td><td width=\"75%\" bgcolor=\"white\" valign=\"top\"";
			if ($langvalue[0] != $this->language_id)
			{
				$examples .= "class=\"greytextarea\">";				
			}
			else
			{
				$examples .= "class=\"blacktextarea\">";
			}
			
            $examples .= "<textarea class=\"examplecaptioninput\" rows=\"1\" name=\"" . $captioninput . "\" id=\"" . $captioninput . "\">" . $splitvalues[0] . "</textarea>";            
			//$examples .= $splitvalues[0];
			$examples .= "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#exampleentry\" onMouseDown=\"javascript:deleteExample('" . $example_no . "');\">Delete</a></td></tr>\n";
			
            $example_no++;
			//$examplestring .= $htmlnotation . "#" . $htmldescription . '#' . $splitvalues[1] . ";";
		}

		//$examples =   "<input id=\"examplestring\" name=\"examplestring\" type=\"hidden\" value=\"1#2#3;\">\n";

		$examples .= "</table><div class=\"inputsection\">" .
					 "<a name=\"exampleentry\"></a> ".
                     "<div class=\"inputrow\"><div class=\"inputlabel\">New Notation </div> <div class=\"inputvalue\"><input class=\"edittextarea inputfield\" " .
					 "id=\"examplenotation\" type=\"text\"></div>" .
					 "<a href=\"#exampleentry\" onMouseDown=\"javascript:addExample();\">Add</a></div>" .
					 "<div class=\"inputrow\"><div class=\"inputlabel\">Description </div>  <div class=\"inputvalue inputvaluelong\"><input class=\"edittextarea inputfieldlong\" id=\"exampledescription\" " .
					 "type=\"text\"></div></div>" .					 
					 "</div>";
                     
# --------------------------------------------------------                     
                     
$newexamplestring = "";
$tablestring =  "<table class=\"reftable\" width=\"99%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
				"<tr><td align=\"left\" width=\"15%\">Notation</td><td width=\"75%\" align=\"left\">Description</td>" .
				"<td width=\"5%\">&nbsp;</td></tr>\n";

$example_no = 1;
				
foreach($sortkeys as $key => $lookup)
{	
	$line = $keyarray[$lookup];
	
	if (strlen(trim($line)) > 0)
	{
			$parts = split("#", $line);
    		$parts[0] = specialchars($parts[0]);
    		$parts[1] = specialchars($parts[1]);
    		$parts[2] = specialchars($parts[2]);

            $htmlnotation = htmlentities($parts[0], ENT_COMPAT, "UTF-8");
            $htmldescription = htmlentities($parts[1], ENT_COMPAT, "UTF-8");
            
            $notationinput = "example_notation_" . $example_no;
            $captioninput = "example_caption_" . $example_no;
            $deletestate = "example_deleted_" . $example_no;
            
			$tablestring .= "<tr>" .
                            "<td width=\"15%\" bgcolor=\"white\" valign=\"top\"><input type=\"hidden\" name=\"" . $deletestate . "\" id=\"" . $deletestate . "\" value=\"N\">" .
                            "<textarea rows=\"1\" class=\"examplenotationinput\" name=\"" . $notationinput . "\" id=\"" . $notationinput . "\">" . $parts[0] . "</textarea></td>" .
                            "<td width=\"75%\" bgcolor=\"white\" valign=\"top\"";
			$tablestring .= "class=\"blacktextarea\">";
			$tablestring .= "<textarea class=\"examplecaptioninput\" rows=\"1\" name=\"" . $captioninput . "\" id=\"" . $captioninput . "\">" . $parts[1] . "</textarea>";            
			$tablestring .= "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#exampleentry\" onMouseDown=\"javascript:deleteExample('" . $example_no . "');\">Delete</a></td></tr>\n";
			
            $example_no++;
    }
    
    /*    
	$line = $keyarray[$lookup];
	
	if (strlen(trim($line)) > 0)
	{
		$parts = split("#", $line);
		$parts[0] = specialchars($parts[0]);
		$parts[1] = specialchars($parts[1]);
		$parts[2] = specialchars($parts[2]);

        $htmlnotation = htmlentities($parts[0], ENT_COMPAT, "UTF-8");
        $htmldescription = htmlentities($parts[1], ENT_COMPAT, "UTF-8");
        
		$newexamplestring .= $htmlnotation . "#" . $htmldescription . "#" . $parts[2] . ";";		
		$tablestring .= "<tr>" .
						"<td width=\"15%\" bgcolor=\"white\">" . $parts[0] . "</td>" .
						"<td width=\"60%\" bgcolor=\"white\">" . $parts[1] . "</td>" .
						"<td width=\"20%\" bgcolor=\"white\">" . $parts[2] . "</td>" .
						"<td width=\"5%\" bgcolor=\"white\"> " .
						"<a href=\"#exampleentry\" onMouseDown=\"javascript:editExample('" . addslashes($htmlnotation) . 
						"');return true;\">Edit</a></td><td width=\"5%\" bgcolor=\"white\"> " .
						"<a href=\"#exampleentry\" onMouseDown=\"javascript:deleteExample('". addslashes($htmlnotation) . 
						"');return true\">Delete</a>" .
						"</td>" .
						"</tr>\n";
	}		
    */
}

$tablestring .= "</table>\n";
$examplestring = "<div class=\"inputsection\">" .
				 "<a name=\"exampleentry\"></a><div class=\"inputrow\"> " .
				 "<div class=\"inputlabel\">New Notation </div> <div class=\"inputvalue\">" .
				 "<input class=\"edittextarea\" id=\"examplenotation\" type=\"text\" size=\"30\"></div>" .
				 "<div class=\"inputlabel\">Encoded</div><div class=\"inputvalue\">" .
				 "<input class=\"editttextarea\" id=\"exampleencoded\" type=\"text\" size=\"30\"></div>" .
				 "<a href=\"#exampleentry\" onMouseDown=\"javascript:addExample();\">Add</a></div>" .
				 "<div class=\"inputrow\"><div class=\"inputlabel\">Description </div>" .
				 "<div class=\"inputvalue inputvaluelong\">" .
				 "<input class=\"edittextarea\" id=\"exampledescription\" type=\"text\" size=\"84\"></div></div>" .
				 "</div>" .
				 "<input id=\"examplestring\" name=\"examplestring\" type=\"hidden\" value=\"". $newexamplestring . "\">\n";
				 
echo $tablestring . $examplestring;

?>