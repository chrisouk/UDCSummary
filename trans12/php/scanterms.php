<?php

	// This function extracts words from an input string and constructs
 	// a list of valid words by only selecting those words that are not
	// in a list of 'stop words'
	//
	// The $searchtype argument can be
	// 1 = filter out stop words
	// 2 = only index words within < > delimiters - such as <Coffee Pot>
	//     and treat these as one term
	  
	function scanterms(&$wordarray, $field, &$stopwordarray, $searchtype)
	{
		$searchstring = $field;
		//echo "Field: " . $field . "<br>\n";
		$searchstring = str_replace(".", "", $searchstring);
		$searchstring = str_replace(",", "", $searchstring);
		$searchstring = str_replace("\"", "", $searchstring);
		$searchstring = str_replace("(", "", $searchstring);
		$searchstring = str_replace(")", "", $searchstring);
		$searchstring = str_replace(":", "", $searchstring);
		$searchstring = str_replace(";", "", $searchstring);
		
		if ($searchtype != 2)
		{
			$searchstring = str_replace("<", "", $searchstring);
			$searchstring = str_replace(">", "", $searchstring);	
		}
		
		//echo "Search: " . $searchstring . "<br>\n";

		if ($searchtype == 1)
		{
			filterstopwords($wordarray, $searchstring, $stopwordarray);
		}		
		else
		{
			filterdelimitedwords($wordarray, $searchstring);
		}
	}

	function filterstopwords(&$wordarray, $searchstring, &$stopwordarray)
	{
		$searcharray = explode(" ", $searchstring);
		
		foreach($searcharray as $i => $term)
		{
			$term = trim($term);
			//echo $term . "<br>\n";
			$term = strtoupper($term);
			if (!array_key_exists($term, $stopwordarray))
			{				
				if (!array_key_exists($term, $wordarray))
					$wordarray[$term] = $term;
			}
		}		
	}
	
	function filterdelimitedwords(&$wordarray, $searchstring)
	{
		$iPos = 0;
		while(($iPos = strpos($searchstring, "<", $iPos)) !== false)
		{
			$endPos = strpos($searchstring, ">", $iPos+1);
			if ($endPos !== false)
			{
				$term = substr($searchstring, $iPos+1, $endPos-$iPos-1);
				$term = strtoupper($term);
				//echo $term . "<br>\n";		
				if (!array_key_exists($term, $wordarray))
					$wordarray[$term] = $term;
			}
			$iPos++;
		}
		//echo "iPos = " . $iPos . "\n";
	}

	
?>