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
    
    $finalstring = "";

	for ($i=0; $i < strlen($copystring); $i++)
	{
		switch($copystring[$i])
		{
			case "\"":
				if ($endquote == true)
				{
					$endquote = false;
				}
				else
				{
				    $finalstring .= ".g";
					$endquote = true;				
				}
				break;
			case "(":
				if ($i < strlen($copystring) - 1)
				{
					if ($copystring[$i+1] == "=")
					{
					   $finalstring .= ".h";
                       $i++;
					}
					else if ($copystring[$i+1] == "0")
					{
					   $finalstring .= ".e";
                       $i++;
					}
					else
					{
						$finalstring .= ".f";
					}
				}
				break;
			case ")":
                // Do nothing
				break;
            default:
                $finalstring .= $copystring[$i];
                break;
		}
	}
    /*     
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
						$copystring[$i] = ".f";
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
	*/
	$finalstring = str_replace("=", ".d", $finalstring);
	$finalstring = str_replace("*", ".j", $finalstring);
	$finalstring = str_replace(".z00", ".m", $finalstring);
	$finalstring = str_replace("-0", ".n", $finalstring);
	$finalstring = str_replace("-", ".p", $finalstring);
	$finalstring = str_replace(".z0", ".q", $finalstring);
	$finalstring = str_replace("\'", ".r", $finalstring);
	$finalstring = str_replace("`", ".r", $finalstring);	
	
	// Now look for alphabetical components
	$bFoundChars = false;
    $finalstring2 = "";
    
	for ($j=0; $j < strlen($finalstring); $j++)
	{
		if ($finalstring[$j] >= 'A' && $finalstring[$j] <= 'Z')
		{
			if ($bFoundChars == false)
			{
				$finalstring2 .= ".k";
				$bFoundChars = true;
			}
		}
        else if ($bFoundChars == true)
		{
			$bFoundChars = false;
		}
        
        $finalstring2 .= $finalstring[$j];
	}	

	$finalstring2 = str_replace("+", ".*", $finalstring2);
	$finalstring2 = str_replace("/", ".,", $finalstring2);
    
	$finalstring2 = trim($finalstring2);
	$finalstring2 .= ".-";
		
	return $finalstring2;
}

?>