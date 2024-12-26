<?php

include_once 'specialchars.php';

class RevisionFields
{
	var $revisiondate = "";
	var $revisionfields = "";
	var $revisionsource = "";
	var $revisioncomment = "";
}

class FormDiffs
{
    var $oldvalue = "";
    var $newvalue = "";   
    var $fieldname = "";
    
    function FormDiffs($in_newvalue, $in_oldvalue, $in_fieldname)
    {
        $this->newvalue = $in_newvalue;        
        $this->oldvalue = $in_oldvalue;
        $this->fieldname = $in_fieldname;
    }
}

class UDCForm
{
	var $searchterm = "";
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
	var $language_id = 1;
	var $edition = "";
	var $auxgroup = "";
	var $verbal_examples = "";
	var $informationnote= "";
	var $validation_errors = array();
	var $editorialnote = "";
	var $introdate = "";
	var $introsource = "";
	var $introcomment = "";
	var $lastrevdate = "";
	var $lastrevsource = "";
	var $lastrevfields = "";
	var $lastrevcomment = "";
    var $derivedfrom = "";
	var $revisionhistory = array();
	var $keywords = array();
	var $alphabeticalindex = array();
    var $othereditcomments = "";
    var $editcomments = "";
    var $nextrecordid = "";
    var $scrollvalue = 0;
    var $usespecialchars = "";
    var $changedfields = array();

    function GetRecordDiffs($oldrecord, &$sqlarray)
    {
        $validation = "";
        $diffs = array();
        
    	if ($this->headingtype != $oldrecord->headingtype)
        {
            $diff = new FormDiffs($this->headingtype, $oldrecord->headingtype, "HeadingType");
            array_push($diffs, $diff);
        }

        $newcaptiontext = explode("~", $this->caption);
        $oldcaptiontext = explode("~", $oldrecord->caption);
        if ($newcaptiontext != $oldcaptiontext[1])
        {
            //echo "Caption differs<br>\n";            
        	$diff = new FormDiffs($this->caption, $oldcaptiontext[1], "Caption");
        	array_push($diffs, $diff);
        }
        
        $scopenotetext = explode("~", $this->scopenote);
        if ($scopenotetext[1] != $oldrecord->scopenote)
        {
            //echo "Scope note differs<br>\n";
        	$diff = new FormDiffs($scopenotetext[1], $oldrecord->scopenote, "Scope Note");
        	array_push($diffs, $diff);
        }

        $appnotetext = explode("~", $this->appnote);       
        if ($appnotetext[1] != $oldrecord->appnote)
        {
            //echo "App note differs<br>\n";
        	$diff = new FormDiffs($appnotetext[1], $oldrecord->appnote, "Application Note");
        	array_push($diffs, $diff);
        }
        
        foreach($this->refs as $ref => $description)
        {
            if (!isset($oldrecord->refs[$ref]))
            {
            	$diff = new FormDiffs($ref, "", "Reference");
            	array_push($diffs, $diff);                    
            }
        }

        foreach($oldrecord->refs as $ref => $description)
        {
            if (!isset($this->refs[$ref]))
            {
            	$diff = new FormDiffs("", $ref, "Reference");
            	array_push($diffs, $diff);                    
            }
        }
        
        /*
        if ($this->examples != $oldrecord->examples)
        {
        	$diff = new FormDiffs($this->examples, $oldrecord->examples);
        	array_push($diffs, $diff);
        }
        if ($this->specialauxtype != $oldrecord->specialauxtype)
        {
        	$diff = new FormDiffs($this->specialauxtype, $oldrecord->specialauxtype);
        	array_push($diffs, $diff);
        }
        if ($this->broadercategory != $oldrecord->broadercategory)
        {
        	$diff = new FormDiffs($this->broadercategory, $oldrecord->broadercategory);
        	array_push($diffs, $diff);
        }
        if ($this->pardivinst != $oldrecord->pardivinst)
        {
        	$diff = new FormDiffs($this->pardivinst, $oldrecord->pardivinst);
        	array_push($diffs, $diff);
        }
        if ($this->language_id != $oldrecord->language_id)
        {
        	$diff = new FormDiffs($this->language_id, $oldrecord->language_id);
        	array_push($diffs, $diff);
        }
        if ($this->edition != $oldrecord->edition)
        {
        	$diff = new FormDiffs($this->edition, $oldrecord->edition);
        	array_push($diffs, $diff);
        }
        if ($this->auxgroup != $oldrecord->auxgroup)
        {
        	$diff = new FormDiffs($this->auxgroup, $oldrecord->auxgroup);
        	array_push($diffs, $diff);
        }
        if ($this->verbal_examples != $oldrecord->verbal_examples)
        {
        	$diff = new FormDiffs($this->verbal_examples, $oldrecord->verbal_examples);
        	array_push($diffs, $diff);
        }
        if ($this->informationnot != $oldrecord->informationnot)
        {
        	$diff = new FormDiffs($this->informationnot, $oldrecord->informationnot);
        	array_push($diffs, $diff);
        }
        if ($this->validation_errors != $oldrecord->validation_errors)
        {
        	$diff = new FormDiffs($this->validation_errors, $oldrecord->validation_errors);
        	array_push($diffs, $diff);
        }
        if ($this->editorialnote != $oldrecord->editorialnote)
        {
        	$diff = new FormDiffs($this->editorialnote, $oldrecord->editorialnote);
        	array_push($diffs, $diff);
        }
        if ($this->introdate != $oldrecord->introdate)
        {
        	$diff = new FormDiffs($this->introdate, $oldrecord->introdate);
        	array_push($diffs, $diff);
        }
        if ($this->introsource != $oldrecord->introsource)
        {
        	$diff = new FormDiffs($this->introsource, $oldrecord->introsource);
        	array_push($diffs, $diff);
        }
        if ($this->introcomment != $oldrecord->introcomment)
        {
        	$diff = new FormDiffs($this->introcomment, $oldrecord->introcomment);
        	array_push($diffs, $diff);
        }
        if ($this->lastrevdate != $oldrecord->lastrevdate)
        {
        	$diff = new FormDiffs($this->lastrevdate, $oldrecord->lastrevdate);
        	array_push($diffs, $diff);
        }
        if ($this->lastrevsource != $oldrecord->lastrevsource)
        {
        	$diff = new FormDiffs($this->lastrevsource, $oldrecord->lastrevsource);
        	array_push($diffs, $diff);
        }
        if ($this->lastrevfields != $oldrecord->lastrevfields)
        {
        	$diff = new FormDiffs($this->lastrevfields, $oldrecord->lastrevfields);
        	array_push($diffs, $diff);
        }
        if ($this->lastrevcomment != $oldrecord->lastrevcomment)
        {
        	$diff = new FormDiffs($this->lastrevcomment, $oldrecord->lastrevcomment);
        	array_push($diffs, $diff);
        }
        if ($this->derivedfrom != $oldrecord->derivedfrom)
        {
        	$diff = new FormDiffs($this->derivedfrom, $oldrecord->derivedfrom);
        	array_push($diffs, $diff);
        }
        if ($this->revisionhistory != $oldrecord->revisionhistory)
        {
        	$diff = new FormDiffs($this->revisionhistory, $oldrecord->revisionhistory);
        	array_push($diffs, $diff);
        }
        if ($this->keywords != $oldrecord->keywords)
        {
        	$diff = new FormDiffs($this->keywords, $oldrecord->keywords);
        	array_push($diffs, $diff);
        }
        if ($this->alphabeticalindex != $oldrecord->alphabeticalindex)
        {
        	$diff = new FormDiffs($this->alphabeticalindex, $oldrecord->alphabeticalindex);
        	array_push($diffs, $diff);
        }
        if ($this->othereditcomments != $oldrecord->othereditcomments)
        {
        	$diff = new FormDiffs($this->othereditcomments, $oldrecord->othereditcomments);
        	array_push($diffs, $diff);
        }
        if ($this->editcomments != $oldrecord->editcomments)
        {
        	$diff = new FormDiffs($this->editcomments, $oldrecord->editcomments);
        	array_push($diffs, $diff);
        }
        if ($this->nextrecordid != $oldrecord->nextrecordid)
        {
        	$diff = new FormDiffs($this->nextrecordid, $oldrecord->nextrecordid);
        	array_push($diffs, $diff);
        }
        */
        
        if (count($diffs) > 0)
        {
            //var_dump($oldrecord);
            $validation = "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bgcolor=\"#ababab\"><tr><td width=\"20%\">Field</td><td width=\"40%\">Old Value</td><td width=\"40%\">New Value</td></tr>";
            
            foreach($diffs as $diff)
            {
                $validation .= "<tr><td bgcolor=\"white\">" . $diff->fieldname . "</td><td bgcolor=\"white\">" . $diff->oldvalue. "</td><td bgcolor=\"white\">" . $diff->newvalue  . "</td></tr>"; 
            }
            
            $validation .= "</table>";
        }
         
        return $validation;       
    }
    
	function executequery($sql, $dsn)
	{
//		if (!@mysql_query("SET NAMES latin1", $dsn))
//		{
//			echo "Failed to set international character set<br>\n";
//		}		
//		
		//echo $sql . "<br>\n"; 
		//$time_start = microtime(true);
		//$res = mysql_query($sql, $dsn) or die mysql_error($dsn);
		//$time_end = microtime(true);
		//$time = $time_end - $time_start;
		//echo $time . " seconds<br>\n";	}
		
		return $res;
	}

	function dumpvarvalues()
	{
		echo "SearchTerm  = " . $this->searchterm;
		echo "mfn = " . $this->mfn . "<br>\n";
		echo "headingtype = " . $this->headingtype . "<br>\n";
		echo "notation = " . $this->notation. "<br>\n";
		echo "EUN = " . $this->EUN. "<br>\n";
		echo "caption = " . $this->caption. "<br>\n";
		echo "scopenote = " . $this->scopnote. "<br>\n";
		echo "appnote = " . $this->appnote . "<br>\n";
		$this->DumpVars("Refs", $this->refs);
		$this->DumpVars("Examples", $this->examples);
		echo "broadercategory = " . $this->broadercategory. "<br>\n";
		$this->DumpVars("ParDiv", $this->pardivinst);
		echo "language_id = " . $this->language_id . "<br>\n";
		echo "auxgroup = " . $this->auxgroup. "<br>\n";
		echo "verbal_examples = " . $this->verbal_examples . "<br>\n";
		$this->DumpVars("Errors", $this->validation_errors);
		echo "informationnote = " . $this->informationnote . "<br>\n";
		echo "editorialnote = " . $this->editiorialnote . "<br>\n";
		echo "introdate = " . $this->introdate. "<br>\n";
		echo "introsource = " . $this->introsource. "<br>\n";
		echo "introcomment = " . $this->introcomment. "<br>\n";
		echo "lastrevdate = " . $this->lastrevdate. "<br>\n";
		echo "lastrevfields = " . $this->lastrevfields. "<br>\n";
		echo "lastrevsource = " . $this->lastrevsource. "<br>\n";
		echo "lastrevcomment = " . $this->lastrecomment. "<br>\n";
        echo "derivedfrom = " . $this->derivedfrom . "<br>\n";
		$this->DumpVars("RevHist", $this->revisionhistory);
		$this->DumpVars("Keywords", $this->keywords);
		$this->DumpVars("AlphaIndex", $this->alphabeticalindex);
        echo "EditComments = " . $this->editcomments . "<br>\n";
	}
		
	function clearvars()
	{
		$this->searchterm = "";
		$this->mfn = 0;
		$this->headingtype = 0;
		$this->notation = "";
		$this->EUN = "";
		$this->caption = "";
		$this->scopenote = "";
		$this->appnote = "";
		$this->ClearArray($this->refs);
		$this->ClearArray($this->examples);//
		$this->broadercategory = "";
		$this->ClearArray($this->pardivinst);
		$this->language_id = 1;	// defaults to English
		$this->edition = 'M'; // defaults to MRF
		$this->auxgroup = "";
		$this->verbal_examples = "";
		//$this->ClearArray($this->validation_errors);
		$this->informationnote = "";
		$this->editorialnote = "";
		$this->introdate = "";
		$this->introsource = "";
		$this->introcomment = "";
		$this->lastrevdate = "";
		$this->lastrevfields = "";
		$this->lastrevsource = "";
		$this->lastrevcomment = "";
        $this->derivedfrom = "";
		$this->ClearArray($this->revisionhistory);
		$this->ClearArray($this->keywords);
		$this->ClearArray($this->alphabeticalindex);
        $this->editcomments = "";
        $this->othereditcomments = "";
        $this->nextrecordid = "";
        $this->usespecialchars = "";
	$this->ClearArray($this->changedfields);
	}

	function ClearArray(&$arr)
	{
		while(count($arr) > 0) array_shift($arr);
		reset($arr);
	}

	function setformvar($varname, &$varvalue, $decode = false)
	{
		if (isset($_POST[$varname]))
		{
            if ($decode)
            {
                $varvalue = trim(html_entity_decode($_POST[$varname], ENT_COMPAT));
                $varvalue = str_replace("\\\"", "\"", $varvalue);
            }
            else
            {
                $varvalue = trim($_POST[$varname]);    
            }
			
			//echo $varname . " = [" . $varvalue . "]<br>\n";
		}
	}

	# Set array values from a form textarea (i.e. line separated)
	function settextarrvals($fieldname, &$arrvals)
	{
		if (!isset($_POST[$fieldname]))
			return;
			
		$temparray = split("\r\n", $_POST[$fieldname]);
		foreach($temparray as $i => $value)
		{
			if (trim($value) != "")
			{
				$arrvals[$value] = $value;
			}
		}
	}

	function setarrvals($fieldname, &$arrvals)
	{
		
		if (isset($_POST[$fieldname]))
		{
			// First split the string into lines
			//echo $fieldname . " arr: " . $_POST[$fieldname] . "<br>\n";
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
			//echo $fieldname . " is not set<br>\n";
		}
	}

	// -----------------------------------------------------------------
	// DumpVars
	// A quick display of all values in an array. Mostly used for
	// debugging
	// -----------------------------------------------------------------

	function DumpVars($arr, $title)
	{
		// return;
		echo "<strong>$title</strong><br>\n";
		
		if (!$arr || count($arr) == 0)
		{
			echo "No elements<br>\n";
			return;
		}

		echo "There are " . count($arr) . " elements<br>\n";
		echo "<strong>" . $title . "</strong><br>\n";
		foreach($arr as $i => $value)
		{
			echo "[" . $i . "] = " . $value . "<br>\n";
		}
	}
    
	function setrevisions($fieldname)
	{
		if (isset($_POST[$fieldname]))
		{
			// First split the string into lines
			$temparray = split(";", $_POST[$fieldname]);
			foreach($temparray as $i => $value)
			{
				if (trim($value) != "")
				{
					//echo "Setting revision history [" . $value . "]<br>\n";
					
					$revisionvalues = explode("#", $value);
					if (count($revisionvalues) == 4)
					{
						//echo "Set!<br>\n";
						
						$revision = new RevisionFields();
						$revision->revisiondate = $revisionvalues[0];
						$revision->revisionsource = $revisionvalues[2];
						$revision->revisionfields = $revisionvalues[1];
						$revision->revisioncomment = $revisionvalues[3];
						$this->revisionhistory[$revision->revisiondate] = $revision;
					}
				}
				else
				{
					//echo "Invalid revision history record [" . $value . "]<br>\n";
				}
			}
		}
		else
		{
			//echo $fieldname . " is not set<br>\n";
		}
	}

	function setexamples()
	{
        $field_id = 1;

		while (isset($_POST["example_notation_" . $field_id]))
		{
            $notation = $_POST["example_notation_" . $field_id];
            if (trim($notation) != "")
            {
                $example = $this->language_id . "~" . $_POST["example_caption_" . $field_id] . "#";// . encodeExample($notation);
    			$this->examples[$notation] = $example;
            }
            $field_id++;
		}
	}

	function setpostvars()
	{   
		//$this->DumpVars($_POST, "SetPostVars");

		//echo "Setting post vars<br>\n";

        if (!isset($_POST['SubmitNotationSearch']) && !isset($_POST['SubmitCaptionSearch']))
        {
            if (isset($_GET['scroll']))
            {
                $this->scrollvalue = $_GET['scroll'];
            }
    
            if (isset($_POST['scrollvalue']))
            {
                $this->scrollvalue = $_POST['scrollvalue'];
            }
        }
                
        $lastnotation = $this->notation;

//        echo "Notation=" . $this->notation . "<br>\n";
//        echo "LastNotation=" . $lastnotation. "<br>\n";         
		//$this->setformvar('searchterm', $this->searchterm);
				
		if ($lastnotation == "" && $this->searchterm != "")
		{
			//echo "Searchterm = " . $this->searchterm . "<br>\n";
			$lastnotation = $this->searchterm;
		}
		
		//$edition = $this->edition;
		//echo "Last notation = " . $lastnotation . "<br>\n";
		
		//$this->setformvar('notation', $this->notation);
		
//		if ($this->notation != $lastnotation && trim($lastnotation) != "")
//		{
//			echo "Different variables<br>\n";
//
//			$this->clearvars();
//			$this->setformvar('notation', $this->notation);
//			//$this->edition = $edition;
//			return;
//		}
//
		//echo "Notation = " . $this->notation . "<br>\n";

		// First, check the form then replace all the inner variables with POST vars
		$this->setformvar('MFN', 				$this->mfn);
		$this->setformvar('mfnvalue', 			$this->mfn);	
		$this->setformvar('searchterm', 		$this->searchterm, true);
		$this->setformvar('notation', 			$this->notation, true);
		$this->setformvar('notationedit', 		$this->notation, true);
//        echo "#Notation=" . $this->notation. "<br>\n";         
//        echo "#Searchterm=" . $this->searchterm. "<br>\n";
		$this->setformvar('EUN', 				$this->EUN);
		$this->setformvar('caption', 			$this->caption);
		$this->setformvar('headingtype', 		$this->headingtype);
		$this->setformvar('scopenote', 			$this->scopenote);
		$this->setformvar('appnote', 			$this->appnote);
		$this->setarrvals('refstring', 			$this->refs);	// This requires pulling data from a string and inserting into an array
		$this->setformvar('specialauxtype', 	$this->specialauxtype);
		$this->setformvar('broader', 			$this->broadercategory);
		$this->setarrvals('pardivinst', 		$this->pardivinst);
		$this->setformvar('language', 			$this->language_id);
		$this->setformvar('edition', 			$this->edition);
		$this->setformvar('auxgroup', 			$this->auxgroup);
		$this->setformvar('verbalexamples', 	$this->verbal_examples);
		$this->setformvar('informationnote', 	$this->informationnote);
		$this->setformvar('editorialnote', 		$this->editorialnote);
		$this->setformvar('introdate', 			$this->introdate);
		$this->setformvar('introsource', 		$this->introsource);
		$this->setformvar('introcomment', 		$this->introcomment);
		$this->setformvar('lastrevdate', 		$this->lastrevdate);
		$this->setformvar('lastrevsource', 		$this->lastrevsource);
		$this->setformvar('lastrevfields', 		$this->lastrevfields);
		$this->setformvar('lastrevcomment', 	$this->lastrevcomment);
   		$this->setformvar('derivedfrom', 	    $this->derivedfrom);
		$this->settextarrvals('approvedkeywords', 		$this->keywords);
		$this->settextarrvals('alphabeticalindex', $this->alphabeticalindex);
		$this->setrevisions('revisionstring', 	$this->revisionhistory);
		$this->setexamples();
        $this->setformvar('othereditcomments',       $this->othereditcomments);
        $this->setformvar('editcomments',       $this->editcomments);
        $this->setformvar('nextrecordid',       $this->nextrecordid);
        $this->setchangedfields();
        //$this->setformvar('scrollvalue',        $this->scrollvalue);
        $this->setformvar('usespecialchars', $this->usespecialchars);
	}
    
    function setchangedfields()
    {
        $this->ClearArray($this->changedfields);
         
        if (isset($_POST['changed_including']))
        {
            array_push($this->changedfields, "105");
        }
        if (isset($_POST['changed_scopenote']))
        {
            array_push($this->changedfields, "110");
        }
        if (isset($_POST['changed_appnote']))
        {
            array_push($this->changedfields, "111");
        }
        if (isset($_POST['changed_examples']))
        {
            array_push($this->changedfields, "115");
        }
        if (isset($_POST['changed_references']))
        {
            array_push($this->changedfields, "125");
        }
        if (isset($_POST['changed_editorial_note']))
        {
            array_push($this->changedfields, "957");
        }
    }
	
	function SetLanguageField($colorlabel, $fieldlabel, &$formstring, &$varfield)
	{
		//echo "LF: " . $varfield . "<br>\n";
		$iSep = strpos($varfield, "~");
		if ($iSep > 0)
		{
			$language = substr($varfield, 0, $iSep);
			$varvalue = substr($varfield, $iSep+1, strlen($varfield) - $iSep);
			//$hexstring = "";
			//for ($i = 0; $i < strlen($varvalue); $i++)
			//{
			//	$hexstring .= ord($varvalue[$i]) . " ";
			//}
			//echo "<br>[" . $hexstring . "]";
			//$varvalue = utf8_decode($varvalue);
			//$hexstring = "";
			//for ($i = 0; $i < strlen($varvalue); $i++)
			//{
			//	$hexstring .= ord($varvalue[$i]) . " ";
			//}
			//echo "<br>[" . $hexstring . "]";
			
			//echo $varvalue . "<br>\n";
			//echo "Language = " . $language . "<br>\n";
			//echo "Field = " . $varvalue . "<br>\n";
			
			$formstring = str_replace($fieldlabel, $varvalue, $formstring);
			if ($language != $this->language_id)
			{
				$formstring = str_replace($colorlabel, "greytextarea", $formstring);
			}
			else
			{
				$formstring = str_replace($colorlabel, "blacktextarea", $formstring);
			}
		}
		else
		{
			//echo $fieldlabel . " has no language<br>\n";
			//$varfield = utf8_decode($varfield);
			//echo $varfield . "<br>\n";
			$formstring = str_replace($fieldlabel, $varfield, $formstring);
		}
	}

    function GetReferenceLine($i, $value, &$reference_no, $addfield)
    {
        $referencess = "";
        
		$langvalue = split("~", $value);
		$splitvalues = split("#", $langvalue[1]);
        
        $notationinput = "reference_notation_" . $reference_no;
        $captioninput = "reference_caption_" . $reference_no;
        
		$referencess .= "<tr><td width=\"15%\" bgcolor=\"white\" valign=\"top\"><textarea rows=\"1\" class=\"examplenotationinput\" name=\"" . $notationinput . "\" id=\"" . 
                        $notationinput . "\">" . $i . "</textarea></td><td width=\"75%\" bgcolor=\"white\" valign=\"top\"";
                     
		if ($langvalue[0] != $this->language_id)
		{
			$referencess .= "class=\"greytextarea\">";				
		}
		else
		{
			$referencess .= "class=\"blacktextarea\">";
		}
		
        $referencess .= "<textarea class=\"examplecaptioninput\" rows=\"1\" name=\"" . $captioninput . "\" id=\"" . $captioninput . "\">" . $splitvalues[0] . "</textarea>";            
		$referencess .= "</td><td width=\"5%\" bgcolor=\"white\">";
        
        if ($addfield)
        {
            $referencess .= "<a href=\"#referencesentry\" onMouseDown=\"javascript:addReference();\">Add</a></td></tr>\n";
		}
        else
        {
            $referencess .= "<a href=\"#referencesentry\" onMouseDown=\"javascript:deleteReference('" . $reference_no . "');\">Del</a></td></tr>\n";
        }
        
        $references_no++;
        
        return $referencess;
    }	

    function GetExampleLine($i, $value, &$example_no, $addfield)
    {
        $examples = "";
        
		$langvalue = split("~", $value);
		$splitvalues = split("#", $langvalue[1]);
        
        $notationinput = "example_notation_" . $example_no;
        $captioninput = "example_caption_" . $example_no;
        $deletestate = "example_deleted_" . $example_no;
        
		$examples .= "<tr><td width=\"15%\" bgcolor=\"white\" valign=\"top\"><input type=\"hidden\" name=\"" . $deletestate . "\" id=\"" . $deletestate . 
                     "\" value=\"N\"><textarea rows=\"1\" class=\"examplenotationinput\" name=\"" . $notationinput . "\" id=\"" . $notationinput . "\">" . $i . 
                     "</textarea></td><td width=\"75%\" bgcolor=\"white\" valign=\"top\"";
                     
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
		$examples .= "</td><td width=\"5%\" bgcolor=\"white\">";
        
        if ($addfield)
        {
            $examples .= "<a href=\"#exampleentry\" onMouseDown=\"javascript:addExample();\">Add</a></td></tr>\n";
		}
        else
        {
            $examples .= "<a href=\"#exampleentry\" onMouseDown=\"javascript:deleteExample('" . $example_no . "');\">Del</a></td></tr>\n";
        }
        
        $example_no++;
        
        return $examples;
    }	

    function GetPrevNextNotations($search_notation, &$prev_notation, &$next_notation)
    {
        $prev_notation = "";
        $next_notation = "";
        
        if ($search_notation != "")
        {
            if (isset($_SESSION['list_results']))
            {
                $list_results = explode("#", $_SESSION['list_results']);
                $found_notation = false; 
                
                foreach($list_results as $list_item)
                {
                    if ($found_notation == true)
                    {
                        $next_notation = $list_item;
                        break;
                    }
                    
                    if ($list_item == $search_notation)
                    {
                        $found_notation = true;
                    }
                    else
                    {
                        $prev_notation = $list_item;   
                    }
                }
            }
        }        
    }
    
	function setformvars(&$formstring, $oldvalues, $resetID = false)
	{
        $validation = "";
        /*        
        if ($oldvalues->notation != "")
        {
            $validation = true;
            
            $validation = $this->ValidateForm($oldvalues);
            $formstring = str_replace("#validation#", $validation, $formstring);
            $formstring = str_replace("#validationon#", "block", $formstring);
            //$formstring = str_replace("#validationoff#", "none", $formstring);
        }	
        else
        */
        {
            $formstring = str_replace("", $validation, $formstring);
            $formstring = str_replace("#validationon#", "none", $formstring);
            $formstring = str_replace("#validationoff#", "block ", $formstring);
        }
         
		//echo "Setting form vars<br>\n";
		if ($resetID)
		{
			//echo "Resetting MFN<br>\n";
			$this->mfn = 0;
		}
		
		// If no language set or invalid language specified, default to English
		if ($this->language_id < 1)
			$this->language_id = 1;

        $page1access = false;
        $page2access = false;
        $page3access = false;
        $pageaccess = "";
        
        $pageaccesscount = 0;

        //$formstring = "Scroll=" . $this->scrollvalue . "<br>" . $formstring;
                 
        if (isset($_SESSION['as_results']))
        {
            $formstring = str_replace("#backtosearchresults#", "<span style=\"float: left; width: 150px; text-align: left; height: 21px; vertical-align: middle\">&nbsp;<a href=\"advanced_search.php\">Back to Search Results</a></span>", $formstring);
        }
        else
        {
            $formstring = str_replace("#backtosearchresults#", "", $formstring);                
        }

        $menustring = "<span style=\"float: right; width 500px; text-align: right; height: 21px; vertical-align: middle\">";
        
        if (strstr($_SESSION['userid'], "aida") || strstr($_SESSION['userid'], "chris"))
        {
            $menustring .= "<a href=\"useractivity.php\">Activity</a> | ";
        }        
                
        $menustring .= "<a href=\"showeditorialcomments.php\">Comments</a> | ";        

        $menustring .= "<a href=\"udcexport.php\">Exports</a> | ";        

        $menustring .= "<a href=\"logoff.php\">Logout</a>&nbsp;</span>";   
        
        $formstring = str_replace("#menuitems#", $menustring, $formstring);
                
        $formstring = str_replace("#scrollvalue#", $this->scrollvalue, $formstring);
                
        if (isset($_SESSION['searchresults']))
        {
            $formstring = str_replace("#searchresults#", $_SESSION['searchresults'], $formstring);
        }        
        else
        {
            $formstring = str_replace("#searchresults#", "&nbsp;", $formstring);
        }

        if (isset($_SESSION['notationsearchterm']))
        {
            $nst = str_replace("\"", "&quot;", $_SESSION['notationsearchterm']);
            $formstring = str_replace("#notationsearchterm#", $nst, $formstring);
        }
        else
        {
            $formstring = str_replace("#notationsearchterm#", "", $formstring);
        }                

        if (isset($_SESSION['captionsearchterm']))
        {
            $formstring = str_replace("#captionsearchterm#", $_SESSION['captionsearchterm'], $formstring);
        }
        else
        {
            $formstring = str_replace("#captionsearchterm#", "", $formstring);
        }                

        // Next/prev notations
        $search_notation = $this->notation;
        $next_notation = "";
        $prev_notation = "";
        
        if (isset($_SESSION['first_result']) && $_SESSION['first_result'] != "")
        {
            $search_notation = "";
            $_SESSION['first_result'] = "";
        }

        $this->GetPrevNextNotations($search_notation, $prev_notation, $next_notation);
        
        if ($prev_notation != "")
        {
            $formstring = str_replace("#displayprevnotation#", "", $formstring);
            $prev_notation = str_replace("\"", "%22", $prev_notation);
            $prev_notation = str_replace("+", "%2B", $prev_notation);
            $formstring = str_replace("#prevnotation#", @mysql_real_escape_string($prev_notation), $formstring);
        }
        else
        {
            $formstring = str_replace("#displayprevnotation#", "display:none", $formstring);            
        }

        if ($next_notation != "")
        {
            $formstring = str_replace("#displaynextnotation#", "", $formstring);
            $next_notation = str_replace("\"", "%22", $next_notation);
            $next_notation = str_replace("+", "%2B", $next_notation);
            $formstring = str_replace("#nextnotation#", @mysql_real_escape_string($next_notation), $formstring);
        }
        else
        {
            $formstring = str_replace("#displaynextnotation#", "display:none", $formstring);            
        }
        
        $classcaption = " style=\"display:block\"";
        $classincluding = " style=\"display:block\"";
        if (isset($_SESSION['access_page1']) && $_SESSION['access_page1'] == "Y")
        {
            $page1access = true;
            $pageaccesscount++;
            $pageaccess .= "Page1<input type=\"hidden\" id=\"page1access\" value=\"Y\">&nbsp;";
            $classcaption = "style=\"display:none\"";
            $classincluding = "style=\"display:none\"";
            //echo "Not hiding buttons";
        } 
        else
        {
            //echo "Hiding buttons";
            $formstring = str_replace("#showrestrictedbuttons#", " style=\"display:none\"", $formstring);
        }
        
        if (isset($_SESSION['access_page2']) && $_SESSION['access_page2'] == "Y") 
        {
            $page2access = ($page1access == false) ? true : false;
            //echo "Page2 = " . $page2access . "<br>\n";
            $pageaccesscount++;
            $pageaccess .= "<input type=\"hidden\" id=\"page2access\" value=\"Y\">";
            if ($pageaccesscount == 1)
            {
                $pageaccess .= "Page2&nbsp;";
            }
            else
            {   
                $pageaccess .= "<a href=\"#\" onMouseDown=\"showPage('page2');\">Page2</a>&nbsp;";
            }
        } 
        if (isset($_SESSION['access_page3']) && $_SESSION['access_page3'] == "Y") 
        {
            $page3access = ($page1access == false) ? true : false;
            //echo "Page3 = " . $page3access . "<br>\n";
            $pageaccesscount++;
            $pageaccess .= "<input type=\"hidden\" id=\"page3access\" value=\"Y\">";
            if ($pageaccesscount == 1)
            {
                $pageaccess .= "Page3&nbsp;";
            }
            else
            {   
                $pageaccess .= "<a href=\"#\" onMouseDown=\"showPage('page3');\">Page3</a>&nbsp;";
            }
        } 

        $sitename = "UDC [unknown]";
        if (isset($_SESSION['mgmt_site_name'])) 
        {
            $sitename = $_SESSION['mgmt_site_name'];
        }
    
        $formstring = str_replace("#sitename#", $sitename, $formstring);

        if (isset($_SESSION['show_reviewer_comment']) && $_SESSION['show_reviewer_comment'] == "Y") 
        {
            $formstring = str_replace("#reviewercomment#", " style=display:block", $formstring);
        }
        else
        {
            $formstring = str_replace("#reviewercomment#", " style=display:none", $formstring);
        }
        
        $formstring = str_replace("#helpercaption#", $classcaption, $formstring);
        $formstring = str_replace("#helperincluding#", $classincluding, $formstring);
        
        if ($pageaccesscount > 1)
        {
            $formstring = str_replace("#pageaccess#", $pageaccess, $formstring);
        }
        else
        {
            $formstring = str_replace("#pageaccess#", ""    , $formstring);   
        }
        if ($page1access)
        {
            $formstring = str_replace("#page1access#", "style=\"display:block\"", $formstring);
        }
        else
        {
            $formstring = str_replace("#page1access#", "style=\"display:none\"", $formstring);
        }
        if ($page2access)
        {
            $formstring = str_replace("#page2access#", "style=\"display:block\"", $formstring);
        }
        else
        {
            $formstring = str_replace("#page2access#", "style=\"display:none\"", $formstring);
        }
        if ($page3access)
        {
            $formstring = str_replace("#page3access#", "style=\"display:block\"", $formstring);
        }
        else
        {
            $formstring = str_replace("#page3access#", "style=\"display:none\"", $formstring);
        }

        if ($_SESSION['mgmt_db_name'] != 'MRF')
        {
            $formstring = str_replace("#showmrfdiffs#", "", $formstring);
        }
        else
        {
            $formstring = str_replace("#showmrfdiffs#", "display:none", $formstring);
        }
        
        if ($this->mfn == 0)
        {
            $formstring = str_replace("#shownextrecord#", " display:none;", $formstring);
        }
        else
        {
            $formstring = str_replace("#shownextrecord#", "", $formstring);
        }
        
        $formstring = str_replace("#nextrecordid#", $this->nextrecordid, $formstring);
        //echo "<pre>" . $formstring . "</pre>\n";
               
		$formstring = str_replace("#lang-eng#", ($this->language_id==1) ? " selected" : "", $formstring);
		$formstring = str_replace("#lang-nld#", ($this->language_id==2) ? " selected" : "", $formstring);
		$formstring = str_replace("#lang-spa#", ($this->language_id==3) ? " selected" : "", $formstring);
		$formstring = str_replace("#lang-fra#", ($this->language_id==4) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-swe#", ($this->language_id==5) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-ger#", ($this->language_id==6) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-hrv#", ($this->language_id==7) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-rus#", ($this->language_id==8) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-slv#", ($this->language_id==9) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-fin#", ($this->language_id==10) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-ita#", ($this->language_id==11) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-geo#", ($this->language_id==12) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-pol#", ($this->language_id==13) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-rom#", ($this->language_id==14) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-cze#", ($this->language_id==15) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-hun#", ($this->language_id==16) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-ukr#", ($this->language_id==17) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-hin#", ($this->language_id==18) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-nor#", ($this->language_id==19) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-est#", ($this->language_id==20) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-arm#", ($this->language_id==21) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-ser#", ($this->language_id==22) ? " selected" : "", $formstring);
        $formstring = str_replace("#lang-por#", ($this->language_id==23) ? " selected" : "", $formstring);

		$formstring = str_replace("#ed-abridged#", ($this->edition=='A') ? " selected" : "", $formstring);
		$formstring = str_replace("#ed-pocket#", ($this->edition=='P') ? " selected" : "", $formstring);
		$formstring = str_replace("#ed-outline#", ($this->edition=='O') ? " selected" : "", $formstring);
		$formstring = str_replace("#ed-full#", ($this->edition=='F') ? " selected" : "", $formstring);
		$formstring = str_replace("#ed-mrf#", ($this->edition=='M') ? " selected" : "", $formstring);

		//echo "---------------------------------------------------------------------------------------------<br>\n";
		if ($this->notation == "")
			$formstring = str_replace('#notation#', "", $formstring);
		else
			$formstring = str_replace('#notation#', htmlentities($this->notation, ENT_COMPAT), $formstring);
		
		$formstring = str_replace("#scrollvalue#", $this->scrollvalue, $formstring);
        
		$formstring = str_replace('#searchterm#', htmlentities($this->notation, ENT_COMPAT), $formstring);
		//echo $formstring . "<br>\n";
		//echo "---------------------------------------------------------------------------------------------<br>\n";
		$formstring = str_replace('#mfn#', $this->mfn, $formstring);
		//echo $formstring . "<br>\n";
		//echo "---------------------------------------------------------------------------------------------<br>\n";
		$this->SetLanguageField('#captioncolor#', '#caption#', $formstring, $this->caption);
		//$formstring = str_replace('#caption#', $this->caption, $formstring);
		//echo $formstring . "<br>\n";
		//echo "HT=" . $this->headingtype . "<br>\n";
		$formstring = str_replace("#heading-a#", ($this->headingtype=='a') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-b#", ($this->headingtype=='b') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-c#", ($this->headingtype=='c') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-d#", ($this->headingtype=='d') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-e#", ($this->headingtype=='e') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-f#", ($this->headingtype=='f') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-g#", ($this->headingtype=='g') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-h#", ($this->headingtype=='h') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-i#", ($this->headingtype=='i') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-k#", ($this->headingtype=='k') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-l#", ($this->headingtype=='l') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-M#", ($this->headingtype=='M') ? " selected" : "", $formstring);

		$this->SetLanguageField('#scopenotecolor#', '#scopenote#', $formstring, $this->scopenote);
		$this->SetLanguageField('#appnotecolor#', '#appnote#', $formstring, $this->appnote);

		$references = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
				    "<tr><td align=\"left\" width=\"15%\">Notation</td><td width=\"75%\" align=\"left\">Description</td>" .
					"<td width=\"5%\">&nbsp;</td></tr>\n";


		// At this point we have to find a reference applicable to this language
		// If there is an English description of the reference but no description for the selected language
		// then we'll use the English description
        
        /*		
		$referencestring = "";
        $reference_no = 1;
		foreach ($this->refs as $i => $value)
		{
            $references .= $this->GetReferenceLine($i, $value, $reference_no, false);
			//$referencestring .= $htmlnotation . "#" . $htmldescription . '#' . $splitvalues[1] . ";";
		}

		$references .= $this->GetReferenceLine("", "", $reference_no, true);

		$references .= "</table>";
		
		$formstring = str_replace("#references#", $references, $formstring);
        
        */
        
        # ==========

		$outputrefs = array();
		$refstring = "";
		foreach ($this->refs as $i => $value)
		{
			//echo "Ref=" . $value . "<br>\n";
			$languagevalue = split("~", $value); 		
			$references .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $i . "</td><td width=\"80%\" bgcolor=\"white\"";
			if ($languagevalue[0] != $this->language_id)
			{
				$references .= "class=\"greytextarea\">"; 
			}
			else
			{
				$references .= "class=\"blacktextarea\">";
			}
			$references .= $languagevalue[1];
  			$references .= "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#refentry\" onMouseDown=\"javascript:deleteRef('" . $i . "');return true\">Delete</a></td></tr>\n";
			 
			$refstring .= $i . "#" . $languagevalue[1] . ";";
		}

		$references .= "</table>" .
					   "<a name=\"refentry\"><div class=\"inputsection\"><div class=\"inputrow\"><div class=\"inputlabel\">New Notation</div><div class=\"inputvalue inputvaluelong\">" .
					   "<input class=\"edittextarea inputfield\" id=\"refnotation\" type=\"text\">&nbsp;<a href=\"../helprefs.htm\" target=_blank>" .
						"<img src=\"../img/help.png\" border=\"0\"></a><input id=\"refstring\" name=\"refstring\" type=\"hidden\" value=\"". $refstring . "\">&nbsp;<a href=\"#refentry\" " .
						"onMouseDown=\"javascript:addRef();\">Add</a></div></div></div>\n";

		$formstring = str_replace("#references#", $references, $formstring);
        
		// Examples of combination
		$examples = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
				    "<tr><td align=\"left\" width=\"15%\">Notation</td><td width=\"75%\" align=\"left\">Description</td>" .
					"<td width=\"5%\">&nbsp;</td></tr>\n";

		$examplestring = "";
        $example_no = 1;
		foreach ($this->examples as $i => $value)
		{
            $examples .= $this->GetExampleLine($i, $value, $example_no, false);
			//$examplestring .= $htmlnotation . "#" . $htmldescription . '#' . $splitvalues[1] . ";";
		}

		$examples .= $this->GetExampleLine("", "", $example_no, true);

		$examples .= "</table>";
        /*
        $examples .= "<div class=\"inputsection\">" .
					 "<a name=\"exampleentry\"></a> ".
                     "<div class=\"inputrow\"><div class=\"inputlabel\">New Notation </div> <div class=\"inputvalue\"><input class=\"edittextarea inputfield\" " .
					 "id=\"examplenotation\" type=\"text\"></div>" .
					 "<a href=\"#exampleentry\" onMouseDown=\"javascript:addExample();\">Add</a></div>" .
					 "<div class=\"inputrow\"><div class=\"inputlabel\">Description </div>  <div class=\"inputvalue inputvaluelong\"><input class=\"edittextarea inputfieldlong\" id=\"exampledescription\" " .
					 "type=\"text\"></div></div>" .					 
					 "</div>";
        */
        
		//echo "ExampleString = " . $examplestring . "<br>\n";
		
		$formstring = str_replace("#examples#", $examples, $formstring);
        
        $newexamples =  "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">";

        $notationinput = "example_notation_" . $example_no;
        $captioninput  = "example_caption_" . $example_no;
        $deletestate   = "example_deleted_" . $example_no;
            
		$newexamples .= "<tr><td width=\"15%\" bgcolor=\"white\" valign=\"top\"><input type=\"hidden\" name=\"" . $deletestate . "\" id=\"" . $deletestate . "\" value=\"N\"><textarea rows=\"1\" class=\"examplenotationinput\" name=\"" . $notationinput . "\" id=\"" . $notationinput . "\"></textarea></td><td width=\"75%\" bgcolor=\"white\" valign=\"top\"";
		$newexamples .= "class=\"greytextarea\">";					
        $newexamples .= "<textarea class=\"examplecaptioninput\" rows=\"1\" name=\"" . $captioninput . "\" id=\"" . $captioninput . "\"></textarea>";            
		$newexamples .= "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#exampleentry\" onMouseDown=\"javascript:AddExample();\">Add</a></td></tr>\n";
		$newexamples .= "</table>";        

		$formstring = str_replace("#newexamples#", $newexamples, $formstring);

		$formstring = str_replace("#notspecialaux#", ($this->specialauxtype==0) ? " selected" : "", $formstring);
		$formstring = str_replace("#hyphenaux#", ($this->specialauxtype==1) ? " selected" : "", $formstring);
		$formstring = str_replace("#pointaux#", ($this->specialauxtype==2) ? " selected" : "", $formstring);
		$formstring = str_replace("#apostropheaux#", ($this->specialauxtype==3) ? " selected" : "", $formstring);
		$formstring = str_replace("#otheraux#", ($this->specialauxtype==4) ? " selected" : "", $formstring);

		$this->SetLanguageField('#broadercategorycolor#', '#broadercategory#', $formstring, $this->broadercategory);
		$this->SetLanguageField('#derivedfromcolor#', '#derivedfrom#', $formstring, $this->derivedfrom);
		//$formstring = str_replace("#9#", $this->broadercategory, $formstring);

		$formstring = str_replace("#EUN#", $this->EUN, $formstring);

		$pardivinst = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td width=\"25%\" align=\"left\">Source</td><td width=\"25%\" align=\"left\">Encoded</td><td width=\"25%\" align=\"left\">Target</td><td width=\"25%\" align=\"left\">Encoded</td><td>&nbsp;</td></tr>\n";
		$newpardivinststring = "";
		foreach ($this->pardivinst as $i => $value)
		{
			$splitpardivinst = split("#", $value);
			$pardivinst .= "<tr><td width=\"25%\" bgcolor=\"white\">" . $i . "</td><td width=\"25%\" bgcolor=\"white\">" . $splitpardivinst[0] . "</td><td width=\"25%\" bgcolor=\"white\">" . $splitpardivinst[1] . "</td><td width=\"20%\" bgcolor=\"white\">" . $splitpardivinst[2] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deletepardivinst('". $i . "');return true\">Delete</a></td></tr>\n";
			$newpardivinststring .= $i . "#" . $value . "#;";
		}
		$pardivinst .= "</table>" .
					  "<div class=\"inputsection\">" .
					 "<a name=\"pardiveentry\"></a><div class=\"inputrow\"><div class=\"inputlabel\">Source </div> <div class=\"inputvalue\"><input class=\"edittextarea inputfield\" id=\"pardivinstsrcnotation\" type=\"text\"></div>" .
					 "<a href=\"#pardiventry\" onMouseDown=\"javascript:addParDivInst();\">Add</a></div>" .
					 "<div class=\"inputrow\"><div class=\"inputlabel\">Target </div> <div class=\"inputvalue\"><input class=\"edittextarea inputfield\" id=\"pardivinsttgtnotation\" type=\"text\"></div>" .
					 "</div>" .
					 "<input id=\"pardivinststring\" name=\"pardivinststring\" type=\"hidden\" value=\"". $newpardivinststring . "\"></div>\n";
		
		//$pardivinst .= "</table> Source <input size=\"14\" class=\"edittextarea\" id=\"pardivinstsrcnotation\" type=\"text\"> Encoded <input size=\"14\" class=\"edittextarea\" id=\"pardivinstsrcencoded\" type=\"text\"> Target <input size=\"14\" class=		//"edittextarea\" id=\"pardivinsttgtnotation\" type=\"text\"> Encoded <input size=\"14\" class=\"edittextarea\" id=\"pardivinsttgtencoded\" type=\"text\"><input type=\"hidden\" id=\"pardivinststring\" value=\"" . $newpardivinststring . "\">\n";

		$formstring = str_replace("#pardivinst#", $pardivinst, $formstring);
		$formstring = str_replace("#auxgroupid#", $this->auxgroup, $formstring);
		$this->SetLanguageField('#verbalexamplescolor#', '#verbalexamples#', $formstring, $this->verbal_examples);

		if (count($this->validation_errors) > 0)
		{
            if ($this->validation_errors[0] == "Success")
            {
                if (isset($_SESSION['updates_allowed']) && $_SESSION['updates_allowed'] == "Y")
                {
                    $formstring = str_replace("#successtype#", "Record", $formstring);
                }
                else
                {
                    $formstring = str_replace("#successtype#", "Comments", $formstring);
                }
    			$formstring = str_replace("#successshow#", "block", $formstring);                
    			$formstring = str_replace("#errorshow#", "none", $formstring);
            }	
            else
            {  
    			$formstring = str_replace("#errorreasons#", $this->GetErrorString(), $formstring);
    			$formstring = str_replace("#errorshow#", "block", $formstring);
    			$formstring = str_replace("#successshow#", "none", $formstring);                
            }    
		}
		else
		{
			$formstring = str_replace("#errorreasons#", "", $formstring);
			$formstring = str_replace("#errorshow#", "none", $formstring);
			$formstring = str_replace("#successshow#", "none", $formstring);                
		}
        
        $this->ClearArray($this->validation_errors);

		$this->SetLanguageField('#informationnotecolor#', '#infonote#', $formstring, $this->informationnote);
		$formstring = str_replace("#editorialnote#", $this->editorialnote, $formstring);
		$formstring = str_replace("#usespecialchars#", $this->usespecialchars, $formstring);
		$formstring = str_replace("#introdate#", $this->introdate, $formstring);
		$formstring = str_replace("#introsource#", $this->introsource, $formstring);
		$formstring = str_replace("#introcomment#", $this->introcomment, $formstring);
		$formstring = str_replace("#editcomments#", $this->editcomments, $formstring);
        $formstring = str_replace("#othereditcomments#", $this->othereditcomments, $formstring);
        if ($this->othereditcomments == "")
        {
            $formstring = str_replace("#showothercomments#", "display:none", $formstring);
            $formstring = str_replace("#editcommentslabel1#", "", $formstring);
            $formstring = str_replace("#editcommentslabel2#", "Work Note", $formstring);
        }
        else
        {
            $formstring = str_replace("#showothercomments#", "", $formstring);            
            $formstring = str_replace("#editcommentslabel1#", "Work Note", $formstring);
            $formstring = str_replace("#editcommentslabel2#", "", $formstring);
        }
        
		$formstring = str_replace("#lastrevdate#", $this->lastrevdate, $formstring);
		$formstring = str_replace("#lastrevsource#", $this->lastrevsource, $formstring);
		$formstring = str_replace("#lastrevfields#", $this->lastrevfields, $formstring);
		$formstring = str_replace("#lastrevcomment#", $this->lastrevcomment, $formstring);
        
        $revdate = "";
        $revname = "";
        
        if (isset($_SESSION['revision_date']))
        {
            $revdate = $_SESSION['revision_date'];
        }
        
        if (isset($_SESSION['revision_name']))
        {
            $revname = $_SESSION['revision_name'];            
        }

        
	    $lastrevcopy = "<a style=\"text-align:right;\" href=\"#\" onMouseDown=\"javascript:copyrevisionfields('" . $revdate . "', '" . $revname . "');\"><img src=\"../img/copy.jpg\" border=\"0\"></a>";#
        $formstring = str_replace("#lastrevcopy#", $lastrevcopy, $formstring);
        
		// Revision History -----------------------------------------------------------------------------------------------------------------

		$revisionhistory =  "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
				            "<tr><td align=\"left\" width=\"15%\">Date</td><td width=\"20%\" align=\"left\">Fields</td><td width=\"15%\" align=\"left\">Source</td>" .
                            "<td width=\"40%\" align=\"left\">Comment</td><td width=\"5%\">&nbsp;</td><td width=\"5%\">&nbsp;</td></tr>\n";

		$revisionstring = "";
		foreach ($this->revisionhistory as $i => $value)
		{
			//echo "Revision = " . $i . "<br>\n";
			$revisionhistory .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $value->revisiondate . "</td><td width=\"20%\" bgcolor=\"white\">" . $value->revisionfields .
						        "</td><td width=\"15%\" bgcolor=\"white\">" . $value->revisionsource . "</td><td width=\"40%\" bgcolor=\"white\">" . $value->revisioncomment . 
                                "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#revhistentry\" onMouseDown=\"javascript:editRevision('" . $value->revisiondate . 
						        "');return true;\">Edit</a></td><td width=\"5%\" bgcolor=\"white\"><a href=\"#revhistentry\" onMouseDown=\"javascript:deleteRevision('" . $value->revisiondate. 
                                "');return true;\">Delete</a></td></tr>\n";
				
			$revisionstring .= $value->revisiondate . "#" . $value->revisionfields . "#" . $value->revisionsource. "#" . $value->revisioncomment.";";
		}
        
		$revisionhistory .= "</table>" .
                            "<a name=\"revhistentry\"></a><div class=\"revisionlabel\">Date</div><div class=\"revisionrow\"><input class=\"edittextarea\" style=\"width: 40px;\" " .
                            "id=\"revisiondate\" type=\"text\"> Fields <input class=\"edittextarea inputfield\" id=\"revisionfields\" " .
							"type=\"text\">" .
					 		" Source <input class=\"editttextarea inputfield\" id=\"revisionsource\" type=\"text\"></div><div class=\"revisionlabel\">Comment</div>" .
                             "<div class=\"revisionrow\"><input class=\"editttextarea\" style=\"width: 377px;\" id=\"revisioncomments\" type=\"text\"></div>" .
							 "<input id=\"revisionstring\" name=\"revisionstring\" type=\"hidden\" value=\"". $revisionstring . "\">\n";

		//echo "RevisionString = " . $revisionstring . "<br>\n";
		$formstring = str_replace("#revisions#", $revisionhistory, $formstring);
		
		$formstring = str_replace("#approvedkeywords#", implode("\r\n", $this->keywords), $formstring);
		$formstring = str_replace("#alphaindex#", implode("\r\n", $this->alphabeticalindex), $formstring);
        
		$changed_fields = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">\n";
        
		foreach ($this->changedfields as $field)
		{
            switch($field)
            {
                case "105":
                    $formstring = str_replace("#changed_including#", " checked", $formstring);
                    break;
                case "110":
                    $formstring = str_replace("#changed_scopenote#", " checked", $formstring);
                    break;
                case "111":
                    $formstring = str_replace("#changed_appnote#", " checked", $formstring);
                    break;
                case "115":
                    $formstring = str_replace("#changed_examples#", " checked", $formstring);
                    break;
                case "125":
                    $formstring = str_replace("#changed_references#", " checked", $formstring);
                    break;
                case "957":
                    $formstring = str_replace("#changed_editorial_note#", " checked", $formstring);
                    break;
            }
		}

        $formstring = str_replace("#changed_including#", "", $formstring);
        $formstring = str_replace("#changed_scopenote#", "", $formstring);
        $formstring = str_replace("#changed_appnote#", "", $formstring);
        $formstring = str_replace("#changed_references#", "", $formstring);
        $formstring = str_replace("#changed_examples#", "", $formstring);
        $formstring = str_replace("#changed_editorial_note#", "", $formstring);
        
	}

	function GetErrorString()
	{
		foreach($this->validation_errors as $i => $error)
		{
			$errorstring .= $error . "<br>";
		}

		return $errorstring;
	}

	function queryformvars($dsn)
	{
		
		//echo "QueryFormvars<br>\n";
		
		//echo "Searchterm = " . trim($this->searchterm) . "<br>\n";
		
		$notation = trim($this->notation);
		$notation = str_replace("\xa0", " ", $notation);
		$notation = trim($notation);
        //echo "Post notation = [" . $notation . "]<br>\n";
		
		//$test1 = " Hello";
		//$test2 = "Hello ";

		if (strlen($notation) == 0)
		{
			$notation = trim($this->searchterm);
			//echo "Setting search term value " . $notation . "<br>\n";
		}
		else
		{
			//echo "Length = " . strlen($notation) . "<br>\n";
			//echo "Using notation [" . $notation . "]<br>\n";
		}
		//echo "Notation = " . $notation . "<br>\n";

		if ($this->searchterm != $notation && $this->searchterm != "")
		{
			$notation = $this->searchterm;
		}	
        
		$language = $this->language_id;
		$edition = $this->edition;
        $scrollvalue = $this->scrollvalue;
        
		$this->clearvars();

		$this->searchterm = $notation;
		$this->notation = $notation;
		$this->language_id = $language;
		$this->edition = $edition;
        $this->scrollvalue = $scrollvalue;
        
		//echo "Notation = " . $this->notation . "<br>\n";
		//echo "Searchterm = " . $this->searchterm . "<br>\n";
		
		if ($this->notation == "")
			return;
			
		$sql =  "select c.classmark_id, h.heading_type, c.special_aux_type, c.classmark_enc_tag from classmarks c, headingtypes h where c.classmark_tag = '" . $this->notation . 
                "' and c.heading_type = h.heading_type_id and c.active = 'Y'";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

        $active_record = "";
        $valid_notation = false;
        
		$rowcount=0;
        if ($res)
        {
            $resultcount = @mysql_num_rows($res);
    		if ($resultcount > 0)
    		{
    			$row = @mysql_fetch_array($res, MYSQL_NUM);
    			$this->mfn = $row[0];
    			$this->headingtype = $row[1];
    			$this->specialauxtype = $row[2];
    			$this->EUN = $row[3];
                $valid_notation = true;                
    			//echo "MFN is " . $this->mfn . "<br>\n";
    		}
    		else
    		{
                //echo "Notation does not exist<br>\n";
    		}
    
    		@mysql_free_result($res);
        }
        
        if ($valid_notation == false)
        {
        	$sql = "select c.active, a.audit_date from classmarks c left outer join audit_history a on c.classmark_id = a.classmark_id and a.audit_type = 'C' where c.classmark_tag = '" . $this->notation . "'";      
        	$res = @mysql_query($sql, $dsn);
            if ($res)
            {
    			$row = @mysql_fetch_array($res, MYSQL_NUM);
                if ($row)
                {
                    if ($row[0] == 'N')
                    {
                        array_push($this->validation_errors, "'" . $this->notation . "' is a cancelled notation [" . $row[1] . "]");
                    }                    
                }
                else
                {
                    //echo "Notation is not inactive either<br>\n";
                }
                
                @mysql_free_result($res);                
            }
            
            if (count($this->validation_errors) == 0)
            {
    			array_push($this->validation_errors, "'" . $this->notation . "' is an invalid notation");                
            }

            return;            
        }
        
        $hierarchy_code = "";
        $sql = "select h.hierarchy_code from classmarks c join classmark_hierarchy h on c.classmark_id = h.classmark_id where c.classmark_id = " . $this->mfn;
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
        if ($res)
        {
            $resultcount = @mysql_num_rows($res);
    		if ($resultcount > 0)
    		{
    			$row = @mysql_fetch_array($res, MYSQL_NUM);
    			$hierarchy_code = $row[0];
    				
    			//echo "HCode is " . $hierarchy_code . "<br>\n";
    		}
    
    		@mysql_free_result($res);
        }

        

//        $search_hier_code = $hierarchy_code;
//        $found_class = false;
//        while($found_class == false && strlen($search_hier_code) > 0)
//        {     
//            $search_hier_code = substr($hierarchy_code, 0, strlen($hierarchy_code) - 1);
//            $sql = "select h.hierarchy_code, c.classmark_id, c.classmark_tag from classmarks c join classmark_hierarchy h on c.classmark_id = h.classmark_id where h.hierarchy_code like '" . $search_hier_code . 
//                   "%' and c.active = 'Y' order by h.hierarchy_code";
//    		//echo $sql . "<br>\n";
//    
//    		$res = @mysql_query($sql, $dsn);
//    
//    		$rowcount=0;
//            $h_id = 0;
//            $h_code = "";
//            $h_tag = "";
//            
//            if ($res)
//            {
//                $resultcount = @mysql_num_rows($res);
//        		if ($resultcount > 0)
//        		{
//        			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
//                    {
//   			            $h_code = $row[0];
//                        $h_id = $row[1];
//                        $h_tag = $row[2];
//                         
//                        //echo "HC=" . $h_code . ", ID=" . $h_id . "<br>\n";
//        			
//                        //echo "Comp: " . $h_id . " = " . $this->mfn . "<br>\n";
//                        if ($h_id == $this->mfn)
//                        {
//                            $found_class = true;
//                            //echo "Found class " . $h_id . "<br>\n";
//                            continue;
//                        }	
//                        
//                        if ($found_class == true)
//                        {
//                            $this->nextrecordid = $h_tag;
//                            $lastnav = $_SESSION['nextnav'];
//                            if ($lastnav != "")
//                            {
//                                $lastnav .= "#";
//                            }
//                            $lastnav .= $h_tag;
//                            $_SESSION['nextnav'] = $lastnav;
//                            //echo "Nav String = " . $lastnav . "<br>\n";
//                            break;
//                        }
//                    }
//                    
//                    /*if ($found_class == false)
//                    {
//                        // We didn't find the next class, so go back up a level
//                        int $last_nav_pos = strrchr($lastnav, "#");
//                        if ($last_nav_pos !== FALSE)
//                        {
//                            $lastnav = substr($lastnav, 0, $last_nav_pos);
//                            $last_nav_pos = strrchr($lastnav, "#");
//                            if ($last_nav_pos !== FALSE)
//                            {
//                                $last_tag = substr($lastnav, $last_nav_pos + 1);
//                                //$last 
//                            }
//                        }
//                    }*/
//        		}
//            }
//    
//    		@mysql_free_result($res);
//      }
        
		// Retrieve the broader category details
		$sql = "select c2.classmark_tag, f.language_id, f.description from classmarks c join classmarks c2 on c.broader_category = c2.classmark_id, language_fields f " .
		       "where c2.classmark_id = f.classmark_id and f.field_id = 1 and c.classmark_id = " . $this->mfn; // . " and f.edition = '" . $this->edition . "'";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				if ($row[1] == 1 || $row[1] == $this->language_id)
					$this->broadercategory = $row[1] . '~' . specialchars($row[0]) . " " . specialchars($row[2]);
			}
			//echo "MFN is " . $this->mfn . "<br>\n";
		}
		else
		{
			//echo "No rows returned for broader<br>\n";
		}

		mysql_free_result($res);
		
		// Retrieve the derivedfrom details
		$sql = "select c2.classmark_tag, f.language_id, f.description from classmarks c join classmarks c2 on c.derived_from = c2.classmark_tag join language_fields f " .
		       "on c2.classmark_id = f.classmark_id and f.field_id = 1 and c.classmark_id = " . $this->mfn; // . " and f.edition = '" . $this->edition . "'";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				if ($row[1] == 1 || $row[1] == $this->language_id)
					$this->derivedfrom = $row[1] . '~' . specialchars($row[0]) . " " . specialchars($row[2]);
			}
			//echo "MFN is " . $this->mfn . "<br>\n";
		}
		else
		{
			//echo "No rows returned for broader<br>\n";
		}

		mysql_free_result($res);        
        
		// Now retrieve all language field entries.  Language fields include any field that can be expressed in more than one language
		// such as caption, verbal example, scope note etc
		$sql = "select f.field_id, f.language_id, description from language_fields f, classmarks c where c.classmark_id = " . $this->mfn .
			   " and c.classmark_id = f.classmark_id order by f.field_id, f.language_id"; // and f.edition = '" . $this->edition . "' 
		//echo $sql . "<br>\n";
		
		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				if ($row[1] == 1 || $row[1] == $this->language_id)
				{
					switch($row[0])
					{
						case 1:
							// Caption
							$this->caption = $row[1] . '~' . specialchars($row[2]);
							break;
						case 4:
							// Verbal_examples
							$this->verbal_examples = $row[1] . '~' . specialchars($row[2]);

							break;
						case 5:
							// Scope note
							$this->scopenote = $row[1] . '~' . specialchars($row[2]);
							break;
						case 6:
							// Application note
							$this->appnote = $row[1] . '~' . specialchars($row[2]);
							break;
						case 10:
							// Information note
							$this->informationnote = $row[1] . '~' . specialchars($row[2]);
							break;
					}
					//echo "Caption is " . $this->caption . "<br>\n";
				}
			}
		}
		else
		{
			if (count($this->validation_errors) == 0)
				array_push($this->validation_errors, "'" . $this->notation . "' does not exist as a notation for this edition");
			$this->broadercategory = "";
			return;
		}

		@mysql_free_result($res);

		// References
		//$sql = "select r.notation, f.language_id, f.description from classmark_refs r join classmarks c on r.notation = c.classmark_tag "
		//      "join language_fields f on f.classmark_id = c.classmark_id where r.classmark_id = " . $this->mfn . " and f.field_id = 1 order by r.sequence_no, f.language_id";
		$sql = "select r.notation, f.language_id, f.description from language_fields f, classmark_refs r, classmarks c where r.classmark_id = " . $this->mfn .
			   " and r.notation = c.classmark_tag and c.classmark_id = f.classmark_id and f.field_id = 1 and c.active = 'Y' order by c.classmark_enc_tag";
		//echo $sql . "<br>\n";

		//echo $sql . "<br>\n";
		$res = @mysql_query($sql, $dsn);

		$this->ClearArray($this->refs);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				// Use English if there is no reference for the target langauge
				if ($row[1] == 1 || $row[1] == $this->language_id)
				{
					$refkey = specialchars($row[0]);
					$this->refs[$refkey] = $row[1] . '~' . specialchars($row[2]);
					//echo "Ref: " . $row[1] . '~' . specialchars($row[2]) . "<br>\n";
				}
			}
		}
		else
		{
			//echo "No rows returned<br>\n";
		}

		@mysql_free_result($res);


		// Examples of combination
		$sql = "select e.field_type, e.tag, e.encoded_tag, c.classmark_tag, f.description, f.language_id " .
			   "from example_classmarks e join classmarks c on c.classmark_id = e.classmark_id " .
			   "left outer join language_fields f on f.classmark_id = e.classmark_id and e.seq_no = f.seq_no and f.field_id = 2 where c.classmark_id = " . $this->mfn . " order by e.encoded_tag";  
		//$sql = "select e.field_type, e.tag, e.encoded_tag, c.classmark_tag, f.description, f.language_id from language_fields f, example_classmarks e, classmarks c where c.classmark_id = " . $this->mfn;
		//$sql .= " and c.classmark_id = e.classmark_id and c.classmark_id = f.classmark_id and e.seq_no = f.seq_no and f.field_id = 2";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$this->ClearArray($this->examples);

		$refarray = array();
		
		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				array_push($refarray, $row[0] . '#' . $row[1] . '#' . $row[2] . '#' . $row[3] . '#' . $row[4] . '#' . $row[5] . '#');	
			}	
		}
		
		@mysql_free_result($res);
				
		include_once 'encodeexample.php';		
				
		foreach($refarray as $i => $ref)
		{
			//echo "Example: " . $ref . "<br>\n";
			$row = split("#", $ref);
			if (count($row) > 0)
			{
				//echo "Type=" . $row[0] . "<br>\n";
				if ($row[5] == 1 || $row[5] == $this->language_id || $row[5] == null)
				{
					$encoded = $row[2];
					switch($row[0])
					{
						case "a":
							// Direct addition
							$key = $this->notation . $row[1];
							if ($encoded == "")
							{
								//echo "Encoding " . $key . "<br>\n";
								$encoded = encodeExample($key);
								//echo "Encoded to " . $encoded . "<br>\n";
							}
							$this->examples[$key] = $row[5] . '~' . specialchars($row[4]) . '#'. specialchars($encoded) . '#';
							break;
						case "b":
							// Colon (:) combination
							$key = specialchars($row[3]) . ":" . specialchars($row[1]);
							if ($encoded == "")
							{
								//echo "Encoding " . $key . "<br>\n";
								$encoded = encodeExample($key);
								//echo "Encoded to " . $encoded . "<br>\n";
							}
							$this->examples[$key] = $row[5] . '~' . specialchars($row[4]) . '#'. specialchars($encoded) . '#';
							break;
						case "c":
						case "r":
							// Full notation - used if the first component differs from the notation in field 001.
							$key = specialchars($row[1]);
							if ($encoded == "")
							{
								//echo "Encoding " . $key . "<br>\n";
								$encoded = encodeExample($key);
								//echo "Encoded to " . $encoded . "<br>\n";
							}
							$this->examples[$key] = $row[5] . '~' . specialchars($row[4]) . '#'. specialchars($encoded) . '#';
							break;
						default:
							echo "Unknown field type " . $row[0] . "<br>\n";
							break;
					}
					
					//echo $this->examples[$key] . "<br>\n";
				}
				else
				{
					//echo "Did not pass<br>\n";
				}
			}
		}
	
		
		//$this->DumpVars($this->examples, "Examples");
		
		// Parallel Div Instructions
		$sql = "select p.src_notation, p.target_notation from parallel_div_instructions p, classmarks c where c.classmark_id = " . $this->mfn;
		$sql .= " and c.classmark_id = p.classmark_id order by p.sequence_no";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$this->ClearArray($this->pardivinst);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				$key = specialchars($row[0]);

				if (trim($key) == "")
				continue;

				$value = specialchars($row[1]);
				$keyenc = encodeExample($key);
				$valueenc = "";
				if ($value != "")
				{
					$valueenc = encodeExample($value);
				}
				$this->pardivinst[$key] = $keyenc . '#' . $value . '#' . $valueenc . '#';
			}
		}
		else
		{
			//echo "No rows returned<br>\n";
		}

		@mysql_free_result($res);

		// Special auxiliary group
		$sql = "select c2.classmark_tag, f.description, f.language_id from language_fields f, classmarks c, classmarks c2 where c.classmark_id = " .
		$this->mfn . " and c.special_aux_group_id = f.classmark_id and c.special_aux_group_id = c2.classmark_id " .
			   "and c.special_aux_group_id != 0 and f.field_id = 1"; // and f.edition = '" . $this->edition . "'";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				if ($row[2] == 1 || $row[2] == $this->language_id)
					$this->auxgroup = $row[2] . '~' . $row[0] . " " . $row[1];
			}
		}
		else
		{
			//echo "No rows returned<br>\n";
		}

		@mysql_free_result($res);

		// Other annotations (editorial note)
		$sql = "select revision_field, annotation from other_annotations where classmark_id = " . $this->mfn;
		//echo $sql . "<br>\n";

		$this->editorialnote = "";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				switch($row[0])
				{
				    case "952":
						if ($this->usespecialchars != "")
							$this->usespecialchars .= "\n\n";
						$this->usespecialchars .= specialchars($row[1]);
                        break;
					case "955":
						// Editorial Note
						if ($this->editorialnote != "")
							$this->editorialnote .= "\n\n";
						$this->editorialnote .= specialchars($row[1]);
						break;
					default:
						break;
				}
			}
		}
		else
		{
			//echo "No rows returned<br>\n";
		}

		@mysql_free_result($res);
		
		// Other editor comments
		$sql = "select revision_field, annotation from other_annotations where classmark_id = " . $this->mfn;
		//echo $sql . "<br>\n";

		$this->editorcomments = "";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				switch($row[0])
				{
					case "955":
						// Editorial Note
						if ($this->editorialnote != "")
							$this->editorialnote .= "\n\n";
						$this->editorialnote .= specialchars($row[1]);
						break;
					default:
						break;
				}
			}
		}
		else
		{
			//echo "No rows returned<br>\n";
		}

		@mysql_free_result($res);        
		// Audit history
		$sql = "select audit_type, audit_date, audit_source, audit_comment from audit_history where classmark_id = " . $this->mfn;
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				switch($row[0])
				{
					case "I":
						$this->introdate = $row[1];
						$this->introsource = specialchars($row[2]);
						$this->introcomment = specialchars($row[3]);
						break;
					case "R":
						$this->lastrevdate = $row[1];
						$this->lastrevsource = $row[2];
						$this->lastrevcomment = $row[3];
						break;
					default:
						break;
				}
			}
		}
		else
		{
			//echo "No rows returned<br>\n";
		}

		@mysql_free_result($res);

		// Last revision
		$sql = "select revision_date, revision_field from revision_fields where classmark_id = " . $this->mfn;
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				//echo "[" . $row[0] . "][" . $row[1] . "]";
				$this->lastrevdate = $row[0];
				if (strlen($this->lastrevfields) > 0)
					$this->lastrevfields .= ", ";
				$this->lastrevfields .= $row[1];
			}
		}
		else
		{
			//echo "No rows returned<br>\n";
		}

		@mysql_free_result($res);

		// Revision history
		$sql = "select revision_date, revision_source, revision_comment from revision_history where classmark_id = " . $this->mfn;
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{		
				$revdate = $row[0];
				$revsource = $row[1];
				$revcomment = $row[2];

				if (isset($this->revisionhistory[$revdate]))
				{
					//echo "Revision found for date [" . $revdate . "]<br>\n";					
					$revision = $this->revisionhistory[$revdate];
					$revision->revisiondate = $revdate;
					$revision->revisionsource = $revsource;
					$revision->revisioncomment = $revcomment;
                    $this->revisionhistory[$revdate] = $revision;
				}
				else
				{
					//echo "Revision created for date [" . $revdate . "]<br>\n";					
					$revision = new RevisionFields();
					$revision->revisiondate = $revdate;
					$revision->revisionsource = $revsource;
					$revision->revisioncomment = $revcomment;
					$this->revisionhistory[$revdate] = $revision;
				}
			}
		}
		
		@mysql_free_result($res);
		
		// Revision history fields
		$sql = "select revision_date, revision_field from revision_history_fields where classmark_id = " . $this->mfn;
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{		
				$revdate = $row[0];
				$revfield = $row[1];
				
				//echo "Found history " . $revdate . ", " . $revfield . "<br>\n";
				
				if (isset($this->revisionhistory[$revdate]))
				{
					//echo "Revision history found for date [" . $revdate . "]<br>\n";
						
					$revision = $this->revisionhistory[$revdate];
					if ($revision)
					{
						//echo "Added revision<br>\n";
						if (strlen($revision->revisionfields) > 0)
							$revision->revisionfields .= ", ";
							
						$revision->revisionfields .=  $revfield;
					}
                    $this->revisionhistory[$revdate] = $revision;
				}
				else
				{
					//echo "No revision for date [" . $revdate . "]<br>\n";
				}
			}
		}
		
		@mysql_free_result($res);
		
		// Editor comments
		$sql = "select reviewer, comments, CONVERT_TZ(date_changed,'+00:00','+08:00') from udc_comments where classmark_id = " . $this->mfn . " order by date_changed asc";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
                $reviewer = $row[0];
				$comment = $row[1];
                $date_changed = $row[2];	
                if ($this->othereditcomments != "")
                {
                    $this->othereditcomments .= "<br><br>\n";
                }
                $this->othereditcomments .= "<strong>" . $reviewer . " [" . $date_changed . "]</strong><br>" . $comment . "\n";
			}
		}
		
		@mysql_free_result($res);
		        
		//echo "There are " . count($this->revisionhistory) . " revision histories<br>\n";

		// Keywords
		$sql = "select t.description from search_terms s, terms t where t.term_id = s.term_id and s.term_type = 1 and s.classmark_id = " . $this->mfn . " order by s.sequence_no";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{		
				$term = $row[0];
				
				if (!isset($this->keywords[$term]))
				{
					array_push($this->keywords, $term);
				}
			}
		}
		
		@mysql_free_result($res);
		
		//echo "There are " . count($this->keywords) . " keywords<br>\n";

		// Alphabetical Index
		$sql = "select t.description from search_terms s, terms t where t.term_id = s.term_id and s.term_type = 2 and s.classmark_id = " . $this->mfn . " order by s.sequence_no";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{		
				$term = $row[0];
				
				if (!isset($this->alphabeticalindex[$term]))
				{
					array_push($this->alphabeticalindex, $term);
				}
			}
		}
		
		@mysql_free_result($res);

		// Changed fields
		$sql = "select field_id from changed_fields where classmark_id = " . $this->mfn . " order by seq_no";
		//echo $sql . "<br>\n";

		$res = @mysql_query($sql, $dsn);

		$rowcount=0;
		$resultcount = @mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
                array_push($this->changedfields, $row[0]);
			}
		}
		
		@mysql_free_result($res);
		
		//$this->dumpvarvalues();
		//echo "There are " . count($this->alphabeticalindex) . " alpha index entries<br>\n";
	}
    
    function GenerateDiffs(&$udcform)
    {
        $diffs = array();
        
        // Compare values
        $mfn = 0;
        
        if ($udcform->headingtype != $this->headingtype)
        {
            $sql = "";
        }
        /*    
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
    	var $auxgroup = "";
    	var $verbal_examples = "";
    	var $informationnote= "";
    	var $validation_errors = array();
    	var $editorialnote = "";
    	var $introdate = "";
    	var $introsource = "";
    	var $introcomment = "";
    	var $lastrevdate = "";
    	var $lastrevsource = "";
    	var $lastrevfields = "";
    	var $lastrevcomment = "";
        var $derivedfrom = "";
    	var $revisionhistory = array();
    	var $keywords = array();
    	var $alphabeticalindex = array();
        var $othereditcomments = "";
        var $editcomments = "";
        var $nextrecordid = "";
        var $scrollvalue = 0;
            
        */
    }
	
	function CheckFormVars(&$errors)
	{
		include_once 'encodeexample.php';
		if (trim($this->notation) == "")
		{
			array_push($errors, "Notation is required");
		}
		else if (trim($this->EUN) == "")
		{
			$this->EUN = encodeExample($this->notation);
		}

		if (trim($this->caption) == "")
		{
			array_push($errors, "Caption is required");
		}

		return true;
	}

    function GetNotationNumber($notation)
    {
		$notation_number = $notation;
		$ispacepos = strpos($notation, " ");
		if ($ispacepos > 0)
		{
			$notation_number = substr($notation, 0, $ispacepos);
		}        
        
        return $notation_number;
    }
    
	function GetClassmarkIDFromNotation($notation, &$dbc)
	{
		$classmark_id = 0;

        $notation_number = $this->GetNotationNumber($notation);

		if (trim($notation_number) != "")
		{
			$sql = "select classmark_id from classmarks where classmark_tag = '" . mysql_real_escape_string($notation_number) . "'";
			$res = @mysql_query($sql, $dbc);
				
			if ($res)
			{
				if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
				{
					$classmark_id = $row[0];
				}
			}

			@mysql_free_result($res);
		}

		return $classmark_id;
	}

	function GetInsertSQL(&$sqlarray, &$errors, &$dbc)
	{
		//echo "MFN for this class = " . $this->mfn . "<br>\n";

		if ($this->CheckFormVars($errors))
		{
            //echo "Checked<br>\n";
            
			$specialauxgroupid = $this->GetClassmarkIDFromNotation($this->auxgroup, $dbc);
			$broadercategoryid = $this->GetClassmarkIDFromNotation($this->broadercategory, $dbc);
            
            //echo "GetIDFromNotation<br>\n";
            
			$sql = "insert into classmarks (classmark_id, heading_type, classmark_tag, classmark_enc_tag, broader_category, special_aux_group_id, derived_from) select " .
			$this->mfn . ", h.heading_type_id, '" . mysql_real_escape_string($this->notation) . "', '" . mysql_real_escape_string($this->EUN) . "', " . $broadercategoryid . ", " . $specialauxgroupid .
                ", '" . mysql_real_escape_string($this->GetNotationNumber($this->derivedfrom)) . "' from headingtypes h where h.heading_type = '" . $this->headingtype . "'";
			array_push($sqlarray, $sql);
            //echo $sql . "<br>\n";
            				
			// Now the language fields - first caption
			$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
			$this->mfn . ", 1, " . $this->language_id . ", 1, '" .mysql_real_escape_string($this->caption) . "')";
			echo $sql . "<br>\n";
			array_push($sqlarray, $sql);

			// Verbal Examples
            if (strlen($this->verbal_examples) > 0)
            {
    			$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
    			$this->mfn . ", 4, " . $this->language_id . ", 1, '" . mysql_real_escape_string($this->verbal_examples) . "')";
    			//echo $sql . "<br>\n";
    			array_push($sqlarray, $sql);
            }
    
			// Scope note
            if (strlen($this->scopenote) > 0)
            {
    			$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
    			$this->mfn . ", 5, " . $this->language_id . ", 1, '" . mysql_real_escape_string($this->scopenote) . "')";
    			//echo $sql . "<br>\n";
    			array_push($sqlarray, $sql);
            }
            				
			// Application note
            if (strlen($this->appnote) > 0)
            {
    			$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no,  description) values (" .
    			$this->mfn . ", 6, " . $this->language_id . ", 1, '" . mysql_real_escape_string($this->appnote) . "')";
    			//echo $sql . "<br>\n";
    			array_push($sqlarray, $sql);
            }
            				
			// Information note
            if (strlen($this->informationnote) > 0)
            {
    			$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no,  description) values (" .
    			$this->mfn . ", 10, " . $this->language_id . ", 1, '" . mysql_real_escape_string($this->informationnote) . "')";
    			//echo $sql . "<br>\n";
    			array_push($sqlarray, $sql);
			}
            
			// Refs and examples
			$this->GetRefsInsertSQL($sqlarray);
			$this->GetExamplesInsertSQL($sqlarray, $dbc);
			$this->GetAnnotationsInsertSQL($sqlarray);
			$this->GetEditCommentsSQL($sqlarray, $dbc);
			//$this->GetAdminSQL($sqlarray);
			//$this->GetKeywordSQL($sqlarray);
            //echo "Checking hierarchy<br>\n";	
			include_once 'inserthier.php';
            InsertIntoHierarchy($dsn, $this->mfn, $this->EUN, $broadercategoryid, $sqlarray, 0);			
		}
		else
		{
			//echo "Form vars don't check out<br>\n";
		}
	}

	function ReplaceLanguageField(&$sqlarray, $field_id, $field, $lang)
	{
		$sql = "";
		if ($field != "")
		{
			// Scope note
			$sql = "replace into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
			$this->mfn . ", " . $field_id . ", " . $this->language_id . ", 1, '" . mysql_real_escape_string($field) . "')";
			//echo $sql . "<br>\n";
			array_push($sqlarray, $sql);
		}
		else
		{
			$sql =  "delete from language_fields " .
                    "where classmark_id = " . $this->mfn . " " . 
				    "and field_id = " . $field_id . " " .
                    "and language_id = " . $lang;
			//echo $sql . "<br>\n";
			array_push($sqlarray, $sql);
		}
	}

	function GetUpdateSQL(&$sqlarray, $oldrecord, &$errors, &$dbc)
	{
	   //echo "Checking form vars<br>\n";
       
		if ($this->CheckFormVars($errors))
		{
            if (isset($_SESSION['updates_allowed']) && $_SESSION['updates_allowed'] == "Y")
            {
                //echo "Checked form vars<br>\n";
                
    			$broadercategoryid = $this->GetClassmarkIDFromNotation($this->broadercategory, $dbc);
    			$sql = "update classmarks c, headingtypes h " .
    			  	   "set c.heading_type = h.heading_type_id, " .
    				   "c.classmark_tag = '" . mysql_real_escape_string($this->notation) . "', " .
    				   "c.classmark_enc_tag = '" . mysql_real_escape_string($this->EUN) . "', " .
    				   "c.broader_category = " . $broadercategoryid . ", " .
                       "c.derived_from = '" . mysql_real_escape_string($this->GetNotationNumber($this->derivedfrom)) . "' " .
    				   "where c.classmark_id = " . $this->mfn . " " .
    				   "and h.heading_type = '" . $this->headingtype . "'";
    			//echo $sql . "<br>\n";
    			array_push($sqlarray, $sql);
    				
    			// Caption is a mandatory field and therefore always present
    			$sql = "replace into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
    			$this->mfn . ", 1, " . $this->language_id . ", 1, '" .mysql_real_escape_string($this->caption) . "')";
    			//echo $sql . "<br>\n";
    			array_push($sqlarray, $sql);
    
                //echo "Verbal Examples<br>\n";
    			$this->ReplaceLanguageField($sqlarray, 4, $this->verbal_examples, $this->language_id);
                //echo "Scope Note<br>\n";
    			$this->ReplaceLanguageField($sqlarray, 5, $this->scopenote, $this->language_id);
                //echo "App Note<br>\n";
    			$this->ReplaceLanguageField($sqlarray, 6, $this->appnote, $this->language_id);
                //echo "Info Note<br>\n";
    			$this->ReplaceLanguageField($sqlarray, 10, $this->informationnote, $this->language_id);
    
    			// Refs
                //echo "Refs<br>\n";
    			$this->GetRefsInsertSQL($sqlarray);
                //echo "Examples<br>\n";
    			$this->GetExamplesInsertSQL($sqlarray, $dbc, $this->language_id);
                //echo "Annotations<br>\n";
    			$this->GetAnnotationsInsertSQL($sqlarray);
                //echo "Admin SQL<br>\n";
    			$this->GetAdminSQL($sqlarray);
                //echo "Keywords<br>\n";
    			$this->GetKeywordSQL($sqlarray);
                //echo "Edit Comments<br>\n";
                //echo "fin<br>\n";
                //$this->GetRecordDiffs($oldrecord, $sqlarray);
                $this->GetChangedFields($sqlarray);
            }
            $this->GetEditCommentsSQL($sqlarray, $dbc);
		}
	}

	# This function is here to collect a list of all unique search terms before the search term SQL is generated
	# and insert any new terms into the terms table
	# This means that when we come to generate the search term SQL, all search terms can be linked to an existing
	# term in the terms table. This works across individual search term sets such as keywords and the alphabetical 
	# index
	
	function GetTermDefsSQL(&$sqlarray, &$alltermsarray)
	{
		if (count($alltermsarray) > 0)
		{
			$sql = "select term_id, description from terms where description in (";
			$termsql = "";
			foreach($alltermsarray as $i => $keyword)
			{
				if (strlen($termsql) > 0)
					$termsql .= ",";
				$termsql .= "'" . mysql_real_escape_string($keyword) . "'";
			}
			
			$sql .= $termsql . ")";
			
			//echo $sql . "<br>\n";
			
			$res = @mysql_query($sql);
	
			$termarray = array();
	
			$resultcount = @mysql_num_rows($res);
			if ($resultcount > 0)
			{
				while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
				{
					$desc = $row[1];
					$id = $row[0];
					$termarray[$desc] = $id;
				}
			}
	
			@mysql_free_result($res);
	
			# Now search the input terms against database terms
			$newterms = array();
			foreach($alltermsarray as $i => $keyword)
			{
				if (!isset($termarray[$keyword]))
				{
					$newterms[$keyword] = $keyword;
				}
			}
			
		 	# Add new terms to the terms table
			foreach($newterms as $i => $desc)
			{
				$sql = "insert into terms (term_id, description) select ifnull(max(term_id)+1, 1), '" . mysql_real_escape_string(strtoupper($desc)) . "' from terms";
				array_push($sqlarray, $sql);
			}
		}	
	}
	
	function GetSearchTermSQL(&$sqlarray, $wordtype, &$wordarray)
	{
		array_push($sqlarray, "delete from search_terms where classmark_id = " . $this->mfn . " and term_type = " . $wordtype);
	 
		if (count($wordarray) > 0)
		{
		 	$iSeqNo = 1;
			foreach($wordarray as $i => $desc)
			{
				$sql = "insert into search_terms (classmark_id, term_type, sequence_no, term_id, stem_term_id) select " . $this->mfn . ", " . $wordtype . 
				   	   ", " . $iSeqNo++ . ", t.term_id, 0 from terms t where t.description = '" . mysql_real_escape_string(strtoupper($desc)) . "'";
				array_push($sqlarray, $sql);
			}
	 	}				
	}
	
	function GetKeywordSQL(&$sqlarray)
	{
		$alltermarray = array();
		$alltermarray = array_merge($this->keywords, $this->alphabeticalindex);
		$this->GetTermDefsSQL($sqlarray, $alltermarray);
		
		$this->GetSearchTermSQL($sqlarray, 1, $this->keywords);
		$this->GetSearchTermSQL($sqlarray, 2, $this->alphabeticalindex);
	}
    
    function CheckLastRevision($revision_date)
    {
        // If the current revision date != last revision date, save the last revision date
        // to the revision_history and then set the 
    }

	function GetAdminSQL(&$sqlarray)
	{
		//echo "There are " . count($this->revisionhistory) . " revision history items<br>\n";
		// First set the revision data if not already set

        /*        
        if (isset($_SESSION['revision_date']) && $_SESSION['revision_date'] != "")
        {
            $this->CheckLastRevision($_SESSION['revision_date']);            
        }

        if (isset($_SESSION['revision_date']) && $_SESSION['revision_date'] != "")
        {
            $this->CheckLastRevision($_SESSION['revision_date']);            
        }
        
        if ($this->lastrevdate != $_SESSION['revision_date'])
        {
            $this->lastrevdate = $_SESSION['revision_date'];
        }

        if ($this->lastrevsource != $_SESSION['revision_name'])
        {
            $this->lastrevsource = $_SESSION['revision_name'];
        }
        */
        
		// Introduction fields
		// Note this also deletes latest revision field data
		$sql = "delete from audit_history where classmark_id = " . $this->mfn;
		array_push($sqlarray, $sql);
		
		$sql = "insert into audit_history (classmark_id, audit_type, audit_date, audit_source, audit_comment) values (" . $this->mfn .
		       ", 'I', '" . $this->introdate . "', '" . $this->introsource . "', '" . @mysql_real_escape_string($this->introcomment) . "')";
		array_push($sqlarray, $sql);
		
		// Latest Revision 
		$sql = "insert into audit_history (classmark_id, audit_type, audit_date, audit_source, audit_comment) values (" . $this->mfn .
		       ", 'R', '" . $this->lastrevdate . "', '" . $this->lastrevsource . "', '" . @mysql_real_escape_string($this->lastrevcomment) . "')";
		array_push($sqlarray, $sql);
		$sql = "delete from revision_fields where classmark_id = " . $this->mfn;
		array_push($sqlarray, $sql);
		$fieldarray = explode(",", $this->lastrevfields);
		if ($fieldarray)
		{
			foreach($fieldarray as $i => $field)
			{
				$field = trim($field);
				$sql = "insert into revision_fields (classmark_id, revision_date, revision_field) values (" . $this->mfn . ", '" . $this->lastrevdate . "', '" . $field . "')";
				array_push($sqlarray, $sql);						
			}
		}
		
		// Now revision history
		$sql = "delete from revision_history where classmark_id = " . $this->mfn;
		array_push($sqlarray, $sql);
		$sql = "delete from revision_history_fields where classmark_id = " . $this->mfn;
		array_push($sqlarray, $sql);
		
		//echo "There are " . count($this->revisionhistory) . " revision history items<br>\n";
		
		foreach($this->revisionhistory as $i => $revision)
		{
			$sql = "insert into revision_history (classmark_id, revision_date, revision_source, revision_comment) values (" . $this->mfn .
			       ", '" . $revision->revisiondate . "', '" . $revision->revisionsource . "', '" . @mysql_real_escape_string($revision->revisioncomment) . "')";
			array_push($sqlarray, $sql);
			
			$revisionfields = explode(",", $revision->revisionfields);
			if ($revisionfields)
			{
                $hist_seq_no = 1; 
				foreach($revisionfields as $i => $field)
				{
					// Latest Revision 
					$field = trim($field);
					$sql = "insert into revision_history_fields (classmark_id, revision_date, sequence_no, revision_field) values (" . $this->mfn .
					       ", '" . $revision->revisiondate . "', " . $hist_seq_no++  . ", '" . $field . "')";
					array_push($sqlarray, $sql);
				}
			}
		}
	}

	function GetEditCommentsSQL(&$sqlarray, &$dsn)
	{
	   /*
		// Editorial Note
		$sql = "select comments from udc_comments where classmark_id = " . $this->mfn;
		//echo $sql . "<br>\n";

		$comment = "";
        
		$res = @mysql_query($sql, $dsn);
		if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
			$comment = $row[0];	
		}

        @mysql_free_result($res);
                
        if ($comment != "")
        {
    		if ($this->editcomments != "")
    		{
                $this->editcomments = str_replace("~", "-", $this->editcomments);
        		$sql = "update udc_comments set comments = '" . @mysql_real_escape_string($this->editcomments) . "', acknowledged = 'N', reviewer = '" . $_SESSION['userid'] . 
                       "', date_changed = NOW() where classmark_id = " . $this->mfn; 
        		array_push($sqlarray, $sql);
            }
            else
            {            
                $sql = "delete from udc_comments where classmark_id = " . $this->mfn;
        		array_push($sqlarray, $sql);
            }
        }
        else
        {*/
    		if ($this->editcomments != "")
    		{
                $this->editcomments = str_replace("~", "-", $this->editcomments);
    			$sql = "insert into udc_comments (classmark_id, comments, reviewer, date_changed) values (" . $this->mfn . ", '" . @mysql_real_escape_string($this->editcomments) . "', '" . $_SESSION['userid'] . 
                        "', NOW())";
    			array_push($sqlarray, $sql);			
    		}
        //}
	}

	function GetRefsInsertSQL(&$sqlarray)
	{
		$sql = "delete from classmark_refs where classmark_id = " . $this->mfn;
		array_push($sqlarray, $sql);

		$iSeqNo = 1;
		foreach ($this->refs as $i => $value)
		{
			$splitref = split("#", $i);
			$sql = "insert into classmark_refs (classmark_id, sequence_no, notation) values (" . $this->mfn . ", " . $iSeqNo . ", '" . mysql_real_escape_string($splitref[0]) . "')";
            //echo $sql . "<br>\n";
			array_push($sqlarray, $sql);
			$iSeqNo++;
		}
	}

	function GetAnnotationsInsertSQL(&$sqlarray)
	{
		// Editorial Note
		$sql = "delete from other_annotations where classmark_id = " . $this->mfn . " and revision_field in ('955','952')";
		array_push($sqlarray, $sql);
		if ($this->editorialnote != "")
		{
			$sql = "insert into other_annotations (classmark_id, revision_field, annotation) values (" . $this->mfn . ", '955', '" . mysql_real_escape_string($this->editorialnote) . "')";
			array_push($sqlarray, $sql);			
		}
		if ($this->usespecialchars != "")
		{
			$sql = "insert into other_annotations (classmark_id, revision_field, annotation) values (" . $this->mfn . ", '952', '" . mysql_real_escape_string($this->usespecialchars) . "')";
			array_push($sqlarray, $sql);			
		}
	}

	function GetExamplesInsertSQL(&$sqlarray, &$dbc)
	{
		$sql = "delete from example_classmarks where classmark_id = " . $this->mfn;
		array_push($sqlarray, $sql);
		$sql = "delete from language_fields where classmark_id = " . $this->mfn .  " and language_id = " . $this->language_id . " and field_id = 2";
		array_push($sqlarray, $sql);
	
		// Refresh the examples from the class examplestring
		// It may have been updated if someone has added an example
		$iSeqNo = 1;
		foreach ($this->examples as $i => $value)
		{
			if (trim($value) == "")
			{
				continue;
			}
				
			//echo "EX: " . $i . " == " . $value . "<br>\n";
            $splitlanguage = explode("~", $value, 2);
			$splitvalues = split("#", $splitlanguage[1]);
			$examplenotation = $i;
			$combinationtype = "c";
			$examplenotationencoded = $splitvalues[1];
				
			if (trim($examplenotationencoded) == "")
			{
				$examplenotationencoded = encodeExample($examplenotation);
			}

			// Determine the combination type
			// a = direct addition
			// b = colon combination
			// c = full (replacement) notation
			// r = Reference to UDC number
				
			// First see if the combination example exists as a UDC number
//			if ($this->GetClassmarkIDFromNotation($examplenotation, $dbc) != 0)
//			{
//				$combinationtype = "r";
//			}
//			else
			{
				$notationlength = strlen($this->notation);
				if (strlen($examplenotation) > $notationlength )
				{
					// See if the first few characters of the example notation match the class notation
					if (substr($examplenotation, 0, $notationlength) == $this->notation)
					{
						// See if this is a colon combination or direct addition
						if (substr($examplenotation, $notationlength, 1) == ":")
						{
							$combinationtype = "b";
							// Remove the class notation from the example notation
							$removenotation = $this->notation . ":";
							$examplenotation = str_replace($removenotation, "", $examplenotation);
						}
						else
						{
							// If the next character is a number, this is not an addition match
							// e.g.  811+239 is not a direction addition for 81 but it is for 811
							$nextchar = substr($examplenotation, $notationlength, 1);
							if (!is_numeric($nextchar) || $nextchar == '.')
							{
								$combinationtype = "a";
    							// Remove the class notation from the example notation
    							$notationstart = strlen($this->notation);
    							$examplenotation = substr($examplenotation, $notationstart);
							}
						}
					}
				}
			}
				
			$sql = "insert into example_classmarks (classmark_id, field_type, seq_no, tag, encoded_tag) values (" .
			$this->mfn . ", '" . $combinationtype . "', " . $iSeqNo . ", '" . @mysql_real_escape_string($examplenotation) . "', '" . @mysql_real_escape_string($examplenotationencoded) . "')";
			array_push($sqlarray, $sql);
				
			if ($combinationtype != "r")
			{
				$sql = "insert into language_fields (classmark_id, field_id, language_id,  seq_no, description) values (" .
				$this->mfn . ", 2, " . $this->language_id . ", " . $iSeqNo . ", '" . @mysql_real_escape_string($splitvalues[0]) . "')";
				array_push($sqlarray, $sql);
			}
				
			$iSeqNo++;
		}
	}
		
	function GetChangedFields(&$sqlarray)
	{
		// Editorial Note
		$sql = "delete from changed_fields where classmark_id = " . $this->mfn;
		array_push($sqlarray, $sql);
		if (count($this->changedfields) > 0)
		{
            $sql = "update classmarks set changed_fields = 'Y' where classmark_id = " . $this->mfn;
            array_push($sqlarray, $sql);

            $seq_no = 1;
            foreach($this->changedfields as $field)
            {
                $sql = "insert into changed_fields (classmark_id, seq_no, field_id) values (" . $this->mfn . ", " . $seq_no++ . ", '" . $field . "')";
                array_push($sqlarray, $sql);
            }			
		}
        else
        {
            $sql = "update classmarks set changed_fields = 'N' where classmark_id = " . $this->mfn;
            array_push($sqlarray, $sql);
        }
	}
	//var $pardivinst = array();

};

?>