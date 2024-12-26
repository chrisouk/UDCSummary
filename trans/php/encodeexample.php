<?php

function encodeExample($notation)
{
	if (strlen($notation) == 0)
		return "";
	
	$copystring = str_replace(".", ".z", $notation);
    $copystring = str_replace("::", ".c", $copystring);
	$copystring = str_replace(":", ".b", $copystring);
	
	// bracket processing:
	// (= should be j
	// (0 should be h
	// ( should be i
	
	$closechar = "";
	$endquote = false;
    
	for ($i=0; $i < strlen($copystring); $i++)
	{
		switch($copystring[$i])
		{
			case "\"":
				if ($endquote == true)
				{
					$copystring = trim(substr($copystring, 0, $i)) . trim(substr($copystring, $i+1, strlen($copystring)-($i+1)));
					$endquote = false;
				}
				else
				{
					$copystring = trim(substr($copystring, 0, $i)) . ".h" . trim(substr($copystring, $i+1, strlen($copystring)-($i+1)));					
					$endquote = true;				
				}
				break;
			case "(":
				if ($i < strlen($copystring) - 1)
				{
					if ($copystring[$i+1] == "=")
					{
						//echo $copystring . " becomes " . substr($copystring, 0, $i) . "|" . "j" . "|" . substr($copystring, $i+2, strlen($copystring)-($i+2));					
						$copystring = trim(substr($copystring, 0, $i)) . ".g" . trim(substr($copystring, $i+2, strlen($copystring)-($i+2)));					
						//$closechar = "j";
					}
					else if ($copystring[$i+1] == "0")
					{
							$copystring = trim(substr($copystring, 0, $i)) . ".e" . trim(substr($copystring, $i+2, strlen($copystring)-($i+2)));	
						//$closechar = "h";
					}
					else
					{
						$copystring = trim(substr($copystring, 0, $i)) . ".f" . trim(substr($copystring, $i+1, strlen($copystring)-($i+1)));
						//$closechar = "i";
					}
				}
				break;
			case ")":
				$copystring[$i] = $closechar;
				$closechar = "";
				break;
		}
	}
	
	$copystring = str_replace("=", ".d", $copystring);
	$copystring = str_replace("*", ".j", $copystring);
	$copystring = str_replace(".z00", ".m", $copystring);
	$copystring = str_replace("-0", ".n", $copystring);
	$copystring = str_replace("-", ".p", $copystring);
	$copystring = str_replace(".z0", ".q", $copystring);
	$copystring = str_replace("\'", ".r", $copystring);
	$copystring = str_replace("`", ".r", $copystring);	
	
	// Now look for alphabetical components
	$bFoundChars = false;
	for ($j=0; $j < strlen($copystring); $j++)
	{
		if ($copystring[$j] >= 'A' && $copystring[$j] <= 'Z')
		{
			if ($bFoundChars == false)
			{
				$copystring = trim(substr($copystring, 0, $j)) . ".k" . trim(substr($copystring, $j+1, strlen($copystring)-($j+1)));
                $j++;		
				$bFoundChars = true;
			}
		}
		else if ($bFoundChars == true)
		{
			$bFoundChars = false;
		}
	}	

	$copystring = str_replace("+", ".*", $copystring);
	$copystring = str_replace("/", ".,", $copystring);
    
	$copystring = trim($copystring);
	$copystring .= ".-";
		
	return $copystring;
}

?>