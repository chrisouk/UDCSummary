<?php
	if (!isset($_SESSION))
    	session_start();
    
    require_once("checksession.php");
    checksession();

    #header("Content-type: text/html; charset=utf-8");
    
    function getEncode($field)
    {
        $field = str_replace("+","$$2$$", $field);
        $field = str_replace("'","$$4$$", $field);
        
        return $field;        
    }   
               
    function setformvars(UDCForm &$udcform, $menuchoice, &$formstring, $savesuccess, $resetID)
    {
        include_once('DBConnectInfo.php');
    	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
    	mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        mysql_query("SET CHARACTER SET utf8");
        mysql_query("SET NAMES utf8");
        
		if ($resetID)
		{
			$udcform->mfn = 0;
		}

		// Get the language options for the menu
		$sql = "select language_id, description from language order by language_id";

		$res = mysql_query($sql, $dbc);	
        if ($res)
        {
			while($row = @mysql_fetch_array($res, MYSQL_NUM))
			{
				$udcform->languages[$row[0]] = $row[1];
			}
    		@mysql_free_result($res);
        }
        else
        {
            echo "ERROR: " . mysql_error($res);
        }

		asort($udcform->languages, SORT_STRING);
			              
		$sourcelanguages = "";			
		foreach($udcform->languages as $id => $description)
		{
			$option = "<option value=\"" . $id . "\"";
			if ($udcform->source_language_id == $id)
			{
				$option .= " selected";
			} 
			$option .= ">" . $description . "</option>\n";
			
			if ($id == 1)
			{
				$sourcelanguages .= $option;
			}
		}
		
        $target_language = 1;
		if (isset($_SESSION['deflang']))
		{
            $target_language = $_SESSION['deflang'];
		}
        else
        {
            $target_language = 1;
        }
		
        $description = $udcform->languages[$target_language];
		$udcform->target_language_id = $target_language;
		$targetoption = "<option value=\"" . $target_language . "\" selected>" . $description . "</option>\n";
		$formstring = str_replace("#target_language#", $targetoption, $formstring);
		$formstring = str_replace("#source_languages#", $sourcelanguages, $formstring);

		foreach($udcform->language_values as $langvalid => $langvalcode)
		{
			$formstring = str_replace("#lang-" . $langvalcode . "#", ($udcform->source_language_id==$langvalid) ? " selected" : "", $formstring);
		}

		if ($udcform->notation == "")
			$formstring = str_replace('#notation#', "&nbsp;", $formstring);
		else
			$formstring = str_replace('#notation#', htmlentities($udcform->notation, ENT_COMPAT), $formstring);

		$formstring = str_replace('#searchterm#', htmlentities($udcform->notation, ENT_COMPAT), $formstring);
		if ($udcform->notation == "")
		{
			$formstring = str_replace('#showeditbutton#', "style=\"display:none\"", $formstring);
		}
		else
		{
			$formstring = str_replace('#showeditbutton#', "", $formstring);
		}
        
		$formstring = str_replace("#scrollvalue#", $udcform->scrollvalue, $formstring);
        
		$formstring = str_replace('#mfn#', $udcform->mfn, $formstring);
		$udcform->SetLanguageField('#captioncolor#', '#caption#', $formstring, $udcform->caption);
		$udcform->SetLanguageField('#transcaptioncolor#', '#transcaption#', $formstring, $udcform->transcaption);
		$formstring = str_replace("#heading-a#", ($udcform->headingtype=='a') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-b#", ($udcform->headingtype=='b') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-c#", ($udcform->headingtype=='c') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-d#", ($udcform->headingtype=='d') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-e#", ($udcform->headingtype=='e') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-f#", ($udcform->headingtype=='f') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-g#", ($udcform->headingtype=='g') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-h#", ($udcform->headingtype=='h') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-i#", ($udcform->headingtype=='i') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-k#", ($udcform->headingtype=='k') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-l#", ($udcform->headingtype=='l') ? " selected" : "", $formstring);
		$formstring = str_replace("#heading-M#", ($udcform->headingtype=='M') ? " selected" : "", $formstring);

		$udcform->SetLanguageField('#scopenotecolor#', '#scopenote#', $formstring, $udcform->scopenote);
		$udcform->SetLanguageField('#scopenotecolor#', '#transscopenote#', $formstring, $udcform->transscopenote);
        if ($udcform->scopenote == "")
        {
            $formstring = str_replace("#displayscopenote#","style=\"display:none;\"", $formstring);
        }
        else
        {
            $formstring = str_replace("#displayscopenote#","", $formstring);
        }

		$udcform->SetLanguageField('#appnotecolor#', '#appnote#', $formstring, $udcform->appnote);
		$udcform->SetLanguageField('#appnotecolor#', '#transappnote#', $formstring, $udcform->transappnote);

		$otherComments = '';
		$otherEditorComments = '';

		if (isset($_SESSION['othercomments']))
			$otherComments = trim($_SESSION['othercomments']);
		if (isset($_SESSION['othereditorcomments']))
			$otherEditorComments = trim($_SESSION['othereditorcomments']);

		if(!empty($otherComments))
		{
			$formstring = str_replace("#comments#", $otherComments, $formstring);
		}
		else
		{
			$formstring = str_replace("#comments#", "", $formstring);
		}

		if(trim($udcform->mycomments) != "")
		{
			$formstring = str_replace("#mycomments#", $udcform->mycomments, $formstring);
		}
		else
		{
			$formstring = str_replace("#mycomments#", "", $formstring);
		}

		if(!empty($otherEditorComments))
		{
			$formstring = str_replace("#editorcomments#", $otherEditorComments, $formstring);
		}
		else
		{
			$formstring = str_replace("#editorcomments#", "", $formstring);
		}

		if (trim($udcform->editor_comments) != "")
		{
			$formstring = str_replace("#editormycomments#", $udcform->editor_comments, $formstring);
		}
		else
		{
			$formstring = str_replace("#editormycomments#", "", $formstring);
		}
        
        if ($udcform->appnote == "")
        {
            $formstring = str_replace("#displayappnote#","style=\"display:none;\"", $formstring);
        }
        else
        {
            $formstring = str_replace("#displayappnote#","", $formstring);
        }
		
        # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		# Examples of combination
        # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		$examples = "<table id=\"extable\" class=\"exampletable\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
				    "<tr><td align=\"left\" class=\"examplenotation\">Notation</td><td class=\"exampledescription\" align=\"left\">Description</td>" .
					"</tr>\n";

        $exampleid = 1;
        $lastenglish = "";
        $lastnotation = "";
        $lastsequence = 0;
        $lastfieldtype = "";
        $lasttrans = "";
        $lastclasstag = "";
        
        /* 
        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>\n";
        echo "Examples count = " . count($udcform->examples) . "<br>\n";
        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>\n";
        */
        
		foreach ($udcform->examples as $example)
		{
            # Don't allow changes on the English line
            if ($example->language_id == $udcform->source_language_id)
            {
                # Did we get a translated entry for the last English line?
                if ($lastenglish != $lasttrans)
                {
                    # Put a blank line in for data entry                    
                    $examples .= "<tr><td class=\"examplenotation\" bgcolor=\"#efdede\">&nbsp;</td><td class=\"exampledescription\" bgcolor=\"#efdede\">";
                    $exnotname = "example_" . $exampleid;            
                    $notname   = "notation_" . $exampleid;            
                    $exseq     = "seq_" . $exampleid;
                    $ftype     = "type_" . $exampleid;
                    $classtag  = "classtag_" . $exampleid;
                    $examples .= "<input type=\"hidden\" name=\"" . $classtag . "\" id=\"" . $classtag . "\" value=\"" . $lastclasstag . "\">";
                    $examples .= "<input type=\"hidden\" name=\"" . $ftype . "\" id=\"" . $ftype . "\" value=\"" . $lastfieldtype . "\">";
                    $examples .= "<input type=\"hidden\" name=\"" . $notname . "\" id=\"" . $notname . "\" value=\"" . $lastnotation . "\">";
                    $examples .= "<input type=\"hidden\" name=\"" . $exseq . "\" id=\"" . $exseq . "\" value=\"" . $lastsequence . "\">";
                    $examples .= "<textarea class=\"exampleentry\" id=\"" . $exnotname . "\" name=\"" . $exnotname . "\" rows=\"2\" readonly></textarea>";
                    $examples .= "</td></tr>\n";
                    $exampleid++;
                }

                $lastenglish = $example->example_notation;
                $examples .= "<tr><td class=\"greytextarea examplenotation bidiltr\">" . $example->display_notation . "</td><td class=\"greytextarea exampledescription bidiltr\">" . $example->example_description;
                $examples .= "</td></tr>\n";            
            }
            else
            {
                # Should always be equal to the last English
                if ($example->example_notation == $lastenglish)
                {
                    $lasttrans = $example->example_notation;
                    $examples .= "<tr><td class=\"examplenotation\" bgcolor=\"#deefde\">&nbsp;</td><td class=\"exampledescription\" bgcolor=\"#deefde\">";
                    $exnotname = "example_" . $exampleid;            
                    $notname   = "notation_" . $exampleid;            
                    $exseq     = "seq_" . $exampleid;
                    $ftype     = "type_" . $exampleid;
                    $classtag  = "classtag_" . $exampleid;
                    $examples .= "<input type=\"hidden\" name=\"" . $classtag . "\" id=\"" . $classtag . "\" value=\"" . $example->classmark_tag . "\">";
                    $examples .= "<input type=\"hidden\" name=\"" . $ftype . "\" id=\"" . $ftype . "\" value=\"" . $example->field_type . "\">";                   
                    $examples .= "<input type=\"hidden\" name=\"" . $notname . "\" id=\"" . $notname . "\" value=\"" . $example->example_notation . "\">";
                    $examples .= "<input type=\"hidden\" name=\"" . $exseq . "\" id=\"" . $exseq . "\" value=\"" . $example->sequence_no . "\">";
                    $examples .= "<textarea class=\"exampleentry\" id=\"" . $exnotname . 
                                 "\" name=\"" . $exnotname . "\" rows=\"2\" readonly>"  . $example->example_description . "</textarea>";
                    $examples .= "</td></tr>\n";        
                    $exampleid++;            
                }
            }
            
            $lastnotation = $example->example_notation;
            $lastsequence = $example->sequence_no;
            $lastfieldtype = $example->field_type;
            $lastclasstag = $example->classmark_notation;
		}

        if ($lasttrans != $lastenglish)
        {
            $examples .= "<tr><td class=\"examplenotation\" bgcolor=\"#efdede\">&nbsp;</td><td class=\"exampledescription\" bgcolor=\"#efdede\">";
            $exnotname = "example_" . $exampleid;            
            $notname   = "notation_" . $exampleid;            
            $exseq     = "seq_" . $exampleid;
            $ftype     = "type_" . $exampleid;
            $classtag  = "classtag_" . $exampleid;
            $examples .= "<input type=\"hidden\" name=\"" . $classtag . "\" id=\"" . $classtag . "\" value=\"" . $lastclasstag . "\">";
            $examples .= "<input type=\"hidden\" name=\"" . $ftype . "\" id=\"" . $ftype . "\" value=\"" . $lastfieldtype . "\">";
            $examples .= "<input type=\"hidden\" name=\"" . $notname . "\" id=\"" . $notname . "\" value=\"" . $lastnotation . "\">";
            $examples .= "<input type=\"hidden\" name=\"" . $exseq . "\" id=\"" . $exseq . "\" value=\"" . $lastsequence . "\">";
            $examples .= "<textarea class=\"exampleentry\" id=\"" . $exnotname . "\" name=\"" . $exnotname . "\" rows=\"2\" readonly></textarea>";
            $examples .= "</td></tr>\n";
        }

		$examples .= "</table>";
        
		$formstring = str_replace("#examples#", $examples, $formstring);
        
        if (count($udcform->examples) == 0)
        {
            $formstring = str_replace("#displayexamples#","style=\"display:none;\"", $formstring);
        }
        else
        {
            $formstring = str_replace("#displayexamples#","", $formstring);
        }
        
		$formstring = str_replace("#notspecialaux#", ($udcform->specialauxtype==0) ? " selected" : "", $formstring);
		$formstring = str_replace("#hyphenaux#", ($udcform->specialauxtype==1) ? " selected" : "", $formstring);
		$formstring = str_replace("#pointaux#", ($udcform->specialauxtype==2) ? " selected" : "", $formstring);
		$formstring = str_replace("#apostropheaux#", ($udcform->specialauxtype==3) ? " selected" : "", $formstring);
		$formstring = str_replace("#otheraux#", ($udcform->specialauxtype==4) ? " selected" : "", $formstring);

		$udcform->SetLanguageField('#broadercategorycolor#', '#broadercategory#', $formstring, $udcform->broadercategory);
		$udcform->SetLanguageField('#derivedfromcolor#', '#derivedfrom#', $formstring, $udcform->derivedfrom);

		$udcform->SetLanguageField('#verbalexamplescolor#', '#verbalexamples#', $formstring, $udcform->verbal_examples);
		$udcform->SetLanguageField('#verbalexamplescolor#', '#transverbalexamples#', $formstring, $udcform->transverbal_examples);
        if ($udcform->verbal_examples == "")
        {
            $formstring = str_replace("#displayverbalexamples#","style=\"display:none;\"", $formstring);
        }
        else
        {
            $formstring = str_replace("#displayverbalexamples#","", $formstring);
        }
        
		if (count($udcform->validation_errors) > 0)
		{
			$formstring = str_replace("#errorreasons#", $udcform->GetErrorString(), $formstring);
			$formstring = str_replace("#errorshow#", "block", $formstring);
			$formstring = str_replace("#successshow#", "none", $formstring);
		}
		else
		{
			$formstring = str_replace("#errorreasons#", "", $formstring);
			$formstring = str_replace("#errorshow#", "none", $formstring);
			if ($savesuccess)
				$formstring = str_replace("#successshow#", "block", $formstring);
			else
				$formstring = str_replace("#successshow#", "none", $formstring);
		}

		$udcform->SetLanguageField('#informationnotecolor#', '#infonote#', $formstring, $udcform->informationnote);
		$formstring = str_replace("#editorialnote#", $udcform->editorialnote, $formstring);
		$formstring = str_replace("#introdate#", $udcform->introdate, $formstring);
		$formstring = str_replace("#introsource#", $udcform->introsource, $formstring);
		$formstring = str_replace("#introcomment#", $udcform->introcomment, $formstring);

		$formstring = str_replace("#lastrevdate#", $udcform->lastrevdate, $formstring);
		$formstring = str_replace("#lastrevsource#", $udcform->lastrevsource, $formstring);
		$formstring = str_replace("#lastrevfields#", $udcform->lastrevfields, $formstring);
		$formstring = str_replace("#lastrevcomment#", $udcform->lastrevcomment, $formstring);
				
        @mysql_close($dbc);
        	
	}

    function ShowRightPane($scrollpos)
    {  
        $target_language = $_SESSION['deflang'];
		require_once('udcform.php');
		$udcform = new UDCForm();

		$udcform->scrollvalue = $scrollpos;
        $udcform->source_language_id = 1;  
        $udcform->target_language_id = $target_language;

		$direction = "textleft";
		if ($_SESSION['rtl'] == true)
		{
			$direction = "textright";
			$formstring = file_get_contents('rightpane_rtl.inc');
		}
		else
		{
			$formstring = file_get_contents('rightpane.inc');
		}

		$formstring = str_replace("#savelanguage#", $target_language, $formstring);

		include_once('DBConnectInfo.php');
    	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
    	mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        mysql_query("SET CHARACTER SET utf8");
        mysql_query("SET NAMES utf8");
		
        $notation = "";
        $encode = false;
        
        if (isset($_GET['notation']))
        {
            $notation = $_GET['notation'];
        }
        
        $notation = str_replace("\\\"", "\"", $notation);
        
        if (isset($_POST['searchterm']))
        {
            $notation = $_POST['searchterm'];
        }      

        if (isset($_GET['encode']))
        {
            $encode = true;
        }      
        
        if ($notation != "")
        {
			//echo 'Querying form vars<br>' . PHP_EOL;
            if (isset($_SESSION['deflang']))
            {
                $udcform->target_language_id = $_SESSION['deflang'];
                $udcform->source_language_id = 1;
            }

            $udcform->notation = $notation;
            $udcform->searchterm = $notation;
            $udcform->dsn = $dbc;
            $udcform->queryformvars($dbc);
			//echo 'Queried form vars<br>' . PHP_EOL;
        }
		else
		{
			//echo "No search term<br>\n";
		}
              
        $savesuccess = false;
        if (isset($_SESSION['savesuccess']))
        {
            $savesuccess = $_SESSION['savesuccess'];
        }

		//echo "Setting form vars<br>\n";

      	setformvars($udcform, "", $formstring, $savesuccess, false);

		//echo "Set form vars<br>\n";

        if ($encode == true)
        {
            $formstring = str_replace("+", "%2b", $formstring);
            $formstring = str_replace("'", "%27", $formstring);
        }
        
        echo $formstring;

        $_SESSION['savesuccess'] = false;

		//echo 'Closing database<br>' . PHP_EOL;
        @mysql_close($dbc);
    }
    
    $scrollpos = 0;
    if(isset($_POST['scrollvalue']))
    {
        $scrollpos = $_POST['scrollvalue'];
    }
    else
    {
        if(isset($_GET['scrollvalue']))
        {
            $scrollpos = $_GET['scrollvalue'];
        }
    }

	#echo "Showing right pane<br>\n";
    ShowRightPane($scrollpos);
    #echo 'Finished rightpane<br>' . PHP_EOL;
?>