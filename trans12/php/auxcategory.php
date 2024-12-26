<?php

include_once 'specialchars.php';

class AuxCategory
{
	var	$mfn = 0;
	var $headingtype = 0;
	var $notation = "";
	var $EUN = "";
	var $caption = "";
	var $scopenote = "";
	var $appnote = "";
	var $refs = array();
	var $examples = array();
	var $specialauxtype = 0;
	var $broadercategory = "";
	var $pardivinst = array();
	var $language_id = 0;
	var $edition = "";
	var $catstring = "";
	
	function clearvars()
	{
		$this->mfn = 0;
		$this->headingtype = 0;
		$this->notation = "";
		$this->EUN = "";
		$this->caption = "";
		$this->scopenote = "";
		$this->appnote = "";
		$this->ClearArray($this->refs);
		$this->ClearArray($this->examples);
		$this->broadercategory = "";
		$this->ClearArray($this->pardivinst);
		$this->language_id = 1;	// defaults to English
		$this->edition = 'M'; // defaults to MRF
	}

	function ClearArray(&$arr)
	{
		while(count($arr) > 0) array_pop($arr);
		reset($arr);
	}
	
	function setformvar($varname, &$varvalue)
	{
		if (isset($_POST[$varname])) 
		{
			$varvalue = trim($_POST[$varname]);
			//echo $varname . " = [" . $varvalue . "]<br>\n";
		}
	}
	
	function setarrvals($fieldname, &$arrvals)
	{
		if (isset($_POST[$fieldname]))
		{
			// First split the string into lines
			//echo "Arr: " . $_POST[$fieldname] . "<br>\n";
			$temparray = split(";", $_POST[$fieldname]);
			foreach($temparray as $i => $value)
			{
				if (trim($value) != "")
				{
					$keypos = strpos($value, "#");
					$key = substr($value, 0, $keypos);
					$keyval = substr($value, $keypos+1, strlen($value) - $keypos - 1);
					//echo "Line: " . $value . ", key: " . $key . ", val: " . $keyval . "<br>\n";
					$arrvals[$key] = $keyval;
				}
			}
		}
		else
		{
			echo $fieldname . " is not set<br>\n";
		}
	}
	
	function DumpVars($arr, $title)
	{
		return;
		
		if (!$arr)
			return;
			
		echo "<strong>" . $title . "</strong><br>\n";
		foreach($arr as $i => $value)
		{
			echo "[" . $i . "] = " . $value . "<br>\n";
		}
	}
	
	function setpostvars()
	{
		//$this->DumpVars($_POST, "SetPostVars");
		
		$lastnotation = $this->notation;
		
		$this->setformvar('notation', $this->notation); 
		if ($this->notation != $lastnotation && trim($lastnotation) != "")
		{
			echo "Different variables<br>\n";
			$this->clearvars();
			$this->setformvar('notation', $this->notation); 
			return;
		}
		
		// First, check the form then replace all the inner variables with POST vars
		$this->setformvar('MFN', $this->mfn); 
		$this->setformvar('notation', $this->notation);
		$this->setformvar('EUN', $this->EUN); 
		$this->setformvar('caption', $this->caption); 
		$this->setformvar('headingtype', $this->headingtype);
		$this->setformvar('scopenote', $this->scopenote);
		$this->setformvar('appnote', $this->appnote);
		$this->setarrvals('refstring', $this->refs);	// This requires pulling data from a string and inserting into an array
		$this->setarrvals('examplestring', $this->examples);
		$this->setformvar('specialauxtype', $this->specialauxtype);
		$this->setformvar('broader', $this->broadercategory);
		$this->setarrvals('pardivinst', $this->pardivinst);
		$this->setformvar('language', $this->language_id);
		$this->setformvar('edition', $this->edition);
	}
	
	function setformvars(&$formstring)
	{
		$formstring = str_replace("#1#", $this->catstring, $formstring);
	/*
		$formstring = str_replace("#-a#", ($this->language_id==1) ? " selected" : "", $formstring);
		$formstring = str_replace("#-b#", ($this->language_id==2) ? " selected" : "", $formstring);
		$formstring = str_replace("#-c#", ($this->language_id==3) ? " selected" : "", $formstring);
		$formstring = str_replace("#-d#", ($this->language_id==4) ? " selected" : "", $formstring);

		$formstring = str_replace("#-e#", ($this->edition=='A') ? " selected" : "", $formstring);
		$formstring = str_replace("#-f#", ($this->edition=='P') ? " selected" : "", $formstring);
		$formstring = str_replace("#-g#", ($this->edition=='O') ? " selected" : "", $formstring);
		$formstring = str_replace("#-h#", ($this->edition=='F') ? " selected" : "", $formstring);
		$formstring = str_replace("#-i#", ($this->edition=='M') ? " selected" : "", $formstring);
	
		//echo "---------------------------------------------------------------------------------------------<br>\n";
		$formstring = str_replace('#0#', $this->notation, $formstring);
		//echo $formstring . "<br>\n";
		//echo "---------------------------------------------------------------------------------------------<br>\n";
		$formstring = str_replace('#1#', $this->mfn, $formstring);
		//echo $formstring . "<br>\n";
		//echo "---------------------------------------------------------------------------------------------<br>\n";
		$formstring = str_replace('#3#', $this->caption, $formstring);
		//echo $formstring . "<br>\n";
		$formstring = str_replace("#2a#", ($this->headingtype==1) ? " selected" : "", $formstring);
		$formstring = str_replace("#2b#", ($this->headingtype==2) ? " selected" : "", $formstring);
		$formstring = str_replace("#2c#", ($this->headingtype==3) ? " selected" : "", $formstring);
		$formstring = str_replace("#2d#", ($this->headingtype==4) ? " selected" : "", $formstring);
		$formstring = str_replace("#2e#", ($this->headingtype==5) ? " selected" : "", $formstring);
		$formstring = str_replace("#2f#", ($this->headingtype==6) ? " selected" : "", $formstring);
		$formstring = str_replace("#2g#", ($this->headingtype==7) ? " selected" : "", $formstring);
		$formstring = str_replace("#2h#", ($this->headingtype==8) ? " selected" : "", $formstring);
		$formstring = str_replace("#2i#", ($this->headingtype==9) ? " selected" : "", $formstring);
		$formstring = str_replace("#2k#", ($this->headingtype==10) ? " selected" : "", $formstring);
		$formstring = str_replace("#2l#", ($this->headingtype==11) ? " selected" : "", $formstring);
		$formstring = str_replace("#2M#", ($this->headingtype==12) ? " selected" : "", $formstring);
		
		$formstring = str_replace("#4#", $this->scopenote, $formstring);

		$formstring = str_replace("#5#", $this->appnote, $formstring);
		
		$references = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td width=\"15%\" align=\"left\">Notation</td><td width=\"80%\" align=\"left\">Description</td><td>&nbsp;</td></tr>\n";
		$refstring = "";
		foreach ($this->refs as $i => $value) 
		{
			$splitref = split("#", $i);
			$references .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $splitref[0] . "</td><td width=\"80%\" bgcolor=\"white\">" . $value . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deleteRef(" . $splitref[0] . ");return true\">Delete</a></td></tr>\n";
			$refstring .= $splitref[0] . "#" . $value . ";";
		}
		$references .= "</table>New Notation <input class=\"edittextarea\" id=\"refnotation\" type=\"text\">&nbsp;<a href=\"../helprefs.htm\" target=_blank><img src=\"../img/help.png\" border=\"0\"></a><input id=\"refstring\" name=\"refstring\" type=\"hidden\" value=\"". $refstring . "\">\n";
		
		$formstring = str_replace("#6#", $references, $formstring);

		$examples = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td align=\"left\" width=\"15%\">Notation</td><td width=\"60%\" align=\"left\">Description</td><td width=\"15%\" align=\"left\">Encoded</td><td width=\"5%\">&nbsp;</td><td width=\"5%\">&nbsp;</td></tr>\n";
		$examplestring = "";
		foreach ($this->examples as $i => $value) 
		{
			echo "Example key = " . $i . "<br>\n";
			$splitvalues = split("#", $value);
			$examples .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $i . "</td><td width=\"60%\" bgcolor=\"white\">" . $splitvalues[0] . "</td><td width=\"15%\" bgcolor=\"white\">" . $splitvalues[1] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:editExample('" . $i. "');return true;\">Edit</a></td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deleteExample('" . $i. "');return true;\">Delete</a></td></tr>\n";
			$examplestring .= $i . "#" . $value . ";";			
		}
		$examples .= "</table> New Notation <input class=\"edittextarea\" id=\"examplenotation\" type=\"text\"> Description <input class=\"edittextarea\" id=\"exampledescription\" type=\"text\"> Encoded <input class=\"editttextarea\" id=\"exampleencoded\" type=\"text\"><input id=\"examplestring\" name=\"examplestring\" type=\"hidden\" value=\"". $examplestring . "\">\n";
		echo "ExampleString = " . $examplestring . "<br>\n";
		$formstring = str_replace("#7#", $examples, $formstring);

		$formstring = str_replace("#8x#", ($this->specialauxtype==0) ? " selected" : "", $formstring);
		$formstring = str_replace("#8a#", ($this->specialauxtype==1) ? " selected" : "", $formstring);
		$formstring = str_replace("#8b#", ($this->specialauxtype==2) ? " selected" : "", $formstring);
		$formstring = str_replace("#8c#", ($this->specialauxtype==3) ? " selected" : "", $formstring);
		$formstring = str_replace("#8d#", ($this->specialauxtype==4) ? " selected" : "", $formstring);
		
		$formstring = str_replace("#9#", $this->broadercategory, $formstring);
		
		$formstring = str_replace("#10#", $this->EUN, $formstring);

		$pardivinst = "<table class=\"reftable\" width=\"99%\" bgcolor=\"#dddddd\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td width=\"25%\" align=\"left\">Source</td><td width=\"25%\" align=\"left\">Encoded</td><td width=\"25%\" align=\"left\">Target</td><td width=\"25%\" align=\"left\">Encoded</td><td>&nbsp;</td></tr>\n";
		$newpardivinststring = "";
		foreach ($this->pardivinst as $i => $value) 
		{
			$splitpardivinst = split("#", $value);
			$pardivinst .= "<tr><td width=\"25%\" bgcolor=\"white\">" . $i . "</td><td width=\"25%\" bgcolor=\"white\">" . $splitpardivinst[0] . "</td><td width=\"25%\" bgcolor=\"white\">" . $splitpardivinst[1] . "</td><td width=\"20%\" bgcolor=\"white\">" . $splitpardivinst[2] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deletepardivinst('". $i . "');return true\">Delete</a></td></tr>\n";
			$newpardivinststring .= $i . "#" . $value . "#;";			
		}
		$pardivinst .= "</table> Source <input size=\"14\" class=\"edittextarea\" id=\"pardivinstsrcnotation\" type=\"text\"> Encoded <input size=\"14\" class=\"edittextarea\" id=\"pardivinstsrcencoded\" type=\"text\"> Target <input size=\"14\" class=\"edittextarea\" id=\"pardivinsttgtnotation\" type=\"text\"> Encoded <input size=\"14\" class=\"edittextarea\" id=\"pardivinsttgtencoded\" type=\"text\"><input type=\"hidden\" id=\"pardivinststring\" value=\"" . $newpardivinststring . "\">\n";
		
		$formstring = str_replace("#11#", $pardivinst, $formstring);
		*/
	}
	
	function queryauxcategories($dsn)
	{		
		$sql = "select aux_cat_id, aux_cat_name from aux_categories order by aux_cat_name";
		$this->catstring = "";
		$res = @mysql_query($sql, $dsn);
		
		$rowcount=0;
		$resultcount = mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				$this->catstring .= "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>\n";
			}
		}
		else
		{
			//echo "No rows returned<br>\n";
		}
	
		mysql_free_result($res);		
	}

};

?>