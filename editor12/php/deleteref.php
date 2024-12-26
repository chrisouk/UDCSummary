<?php

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
	
	$newrefstring = "";
	$mainline = "";
	$tablestring = "<table class=\"reftable\" width=\"99%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td align=\"left\">Notation</td><td align=\"left\">Description</td><td>&nbsp;</td></tr>\n";
	foreach($lines as $line)
	{		
		if (strlen(trim($line)) > 0)
		{
			$parts = split("#", $line);
			$mainline .= sprintf("%s|%d ", $line, count($parts));
			if ($parts[0] != $refnotation)
			{
				$newrefstring .= $parts[0] . "#" . $parts[1] . "#;";		
				$tablestring .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $parts[0] . "</td><td width=\"80%\" bgcolor=\"white\">" . $parts[1] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deleteRef('". $parts[0] . "');return true\">Delete</a></td></tr>\n";
			}
			else
			{
				//echo "No rows returned for broader<br>\n";
			}
		}
	}
	
	$tablestring .= "</table>\n";
	$refstring = "<div class=\"inputsection\"><div class=\"inputrow\"><div class=\"inputlabel\">New Notation</div><div class=\"inputvalue inputvaluelong\">" .
			 	 "<input class=\"edittextarea\" id=\"refnotation\" type=\"text\">&nbsp;<a href=\"../helprefs.htm\" target=_blank>" .
			 	 "<img src=\"../img/help.png\" border=\"0\"></a><input id=\"refstring\" name=\"refstring\" type=\"hidden\" value=\"". $newrefstring . "\">&nbsp;<a href=\"#\" onMouseDown=\"javascript:addRef();\">Add</a></div></div></div>\n";

	//echo "[" . $mainline. "]";	
	echo $tablestring . $refstring;
?>