<?php

	/**
	 * UDCForm
	 * Form containing UDC record data for storage and display
	 * @author Chris Overfield
	 * @copyright 2010
	 */

	if (!isset($_SESSION))
		session_start();

	include_once 'specialchars.php';

	class RevisionFields
	{
		var $revisiondate = "";
		var $revisionfields = "";
		var $revisionsource = "";
		var $revisioncomment = "";
	}

	class ExampleClassmark
	{
		public $language_id = 0;
		public $example_notation = "";
		public $display_notation = "";
		public $example_description = "";
		public $classmark_notation = "";
		public $field_type = "";
		public $sequence_no = 0;

		function Dump()
		{
			echo "Lang=" . $this->language_id . "<br>\n";
			echo "Notation=" . $this->example_notation . "<br>\n";
			echo "Display=" . $this->display_notation. "<br>\n";
			echo "Description=" . $this->example_description . "<br>\n";
			echo "Classtag=" . $this->classmark_notation . "<br>\n";
			echo "Type=" . $this->field_type . "<br>\n";
			echo "SeqNo=" . $this->sequence_no . "<br>\n";
		}
	};

	class UDCForm
	{
		public $searchterm = "";
		public $mfn = 0;
		public $headingtype = 0;
		public $notation = "";
		public $EUN = "";
		public $caption = "";
		public $oldtranscaption = "";
		public $transcaption = "";
		public $scopenote = "";
		public $oldtransscopenote = "";
		public $transscopenote = "";
		public $appnote = "";
		public $oldtransappnote = "";
		public $transappnote = "";
		public $refs = array();
		public $examples = array();
		public $specialauxtype = 0;
		public $broadercategory = "";
		public $pardivinst = array();
		public $source_language_id = 0;
		public $target_language_id = 0;
		public $edition = "";
		public $auxgroup = "";
		public $verbal_examples = "";
		public $transverbal_examples = "";
		public $informationnote= "";
		public $validation_errors = array();
		public $editorialnote = "";
		public $introdate = "";
		public $introsource = "";
		public $introcomment = "";
		public $lastrevdate = "";
		public $lastrevsource = "";
		public $lastrevfields = "";
		public $lastrevcomment = "";
		public $derivedfrom = "";
		public $languages = array();
		public $max_language = 0;
		public $language_values = array();
		public $revisionhistory = array();
		public $keywords = array();
		public $alphabeticalindex = array();
		public $client_encoding = "";
		public $othercomments = "";
		public $myoldcomment = "";
		public $mycomments = "";
		public $commentdate = "";
		public $editor_othercomments = "";
		public $editor_oldcomment = "";
		public $editor_comments = "";
		public $editor_commentdate = "";
		public $dsn;
		public $scrollvalue = 0;
		public $arr_editor_comments = array();
		public $arr_user_comments = array();
		public $parallel_div_instructions;

		public function setdsn($dsn)
		{
			include_once('DBConnectInfo.php');
			$this->dsn = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
			mysql_select_db (DBDATABASE);
			mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $this->dsn);
			mysql_query("SET CHARACTER SET utf8");
			mysql_query("SET NAMES utf8");
		}

		public function dumpvarvalues()
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
			echo "language_id = " . $this->source_language_id . "<br>\n";
			echo "target_language_id = " . $this->target_language_id . "<br>\n";
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
			$this->DumpVars("EditorComments", $this->arr_editor_comments);
			$this->DumpVars("UserComments", $this->arr_user_comments);
		}

		/**
		 * clearvars
		 * Clear (reset) all member variables in the class
		 */

		public function clearvars()
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
			$this->source_language_id = 1;	// defaults to English
			$this->target_language_id = 1;	// defaults to English
			$this->edition = 'M'; // defaults to MRF
			$this->auxgroup = "";
			$this->verbal_examples = "";
			$this->ClearArray($this->validation_errors);
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
			$this->ClearArray($this->languages);
			$this->transcaption = "";
			$this->transscopenote = "";
			$this->transappnote = "";
			$this->transverbal_examples = "";
			$this->max_language = 0;
			$this->mycomments = "";
			$this->commentdate = "";
			$this->editor_comments = "";
			$this->editor_commentdate = "";
			$this->ClearArray($this->revisionhistory);
			$this->ClearArray($this->keywords);
			$this->ClearArray($this->alphabeticalindex);
			$this->ClearArray($this->language_values);
			$this->ClearArray($this->arr_editor_comments);
			$this->ClearArray($this->arr_user_comments);
			$this->parallel_div_instructions = "";
		}

		/**
		 * ClearArray
		 * Clears (empties) an array of any contents
		 * @param array $arr
		 */

		public function ClearArray(&$arr)
		{
			while(count($arr) > 0) array_shift($arr);
			reset($arr);
		}

		/**
		 * setformvar
		 * Takes the value of a POSTed form variable and sets it on the corresponding
		 * class variable
		 * @param string $varname
		 * @param string $varvalue
		 */

		public function setformvar($varname, &$varvalue)
		{
			if (isset($_POST[$varname]))
			{
				$varvalue = trim(stripslashes($_POST[$varname]));
			}
		}

		/**
		 * setsessionvar
		 * Sets a member variable to the values of a named session variable
		 * @param string $varname
		 * @param variant $varvalue
		 */

		public function setsessionvar($session_variable_name, &$member_variable)
		{
			if (isset($_SESSION[$session_variable_name]))
			{
				$member_variable = trim($_SESSION[$session_variable_name]);
			}
		}

			#

		/**
		 * settextarrvals
		 * Set array values from a form textarea (i.e. line separated)
		 * @param string $form_field_name
		 * @param array $array_values
		 */

		public function settextarrvals($form_field_name, &$array_values)
		{
			if (!isset($_POST[$form_field_name]))
				return;

			$temparray = explode("\r\n", $_POST[$form_field_name]);
			foreach($temparray as $i => $value)
			{
				if (trim($value) != "")
				{
					$array_values[$value] = stripslashes($value);
				}
			}
		}

		/**
		 * setarrvals
		 * Set array values from a form hidden text field
		 * (items separated by @ and fields within items separated by #)
		 * @param string $form_field_name
		 * @param array $arrvals
		 */

		public function setarrvals($form_field_name, &$array_values)
		{
			if (isset($_POST[$form_field_name]))
			{
				$field = stripslashes($_POST[$form_field_name]);

				# First split the string into lines
				$temparray = explode("@", $field);
				foreach($temparray as $dummy => $value)
				{
					# Split each line into fields (separated by a #)
					if (trim($value) != "")
					{
						$keypos = strpos($value, "#");
						$key = mb_substr($value, 0, $keypos, "UTF-8");
						$keyval = mb_substr($value, $keypos+1, strlen($value) - $keypos - 1, "UTF-8");
						$array_values[$key] = $keyval;
					}
				}
			}
		}

		/**
		 * DumpVars
		 * A quick display of all values in an array. Mostly used for debugging
		 * @param array $array_in
		 * @param string $title
		 */

		public function DumpVars($array_in, $title)
		{
			// return;
			echo "<strong>$title</strong><br>\n";

			if ($array_in == null || count($array_in) == 0)
			{
				echo "No " . $title . "<br>\n";
				return;
			}

			echo "There are " . count($array_in) . " elements<br>\n";
			echo "<strong>" . $title . "</strong><br>\n";
			foreach($array_in as $key => $value)
			{
				echo "[" . $key . "] = " . $value . "<br>\n";
			}
		}

		/**
		 * setexamples
		 * Create an example from the example variables in a form
		 * and add to the class example array
		 */

		public function setexamples()
		{
			$this->ClearArray($this->examples);

			$example_no = 1;

			while(true)
			{
				if (DUMMYINSERT)
				{
					echo "Example " . $example_no . "<br>\n";
				}

				if (!isset($_POST['example_' . $example_no]))
				{
					if (DUMMYINSERT)
					{
						echo "End of examples<br>\n";
					}
					break;
				}

				$new_example = new ExampleClassmark();

				$new_example->field_type = $_POST['type_' . $example_no];
				$new_example->classmark_notation = $_POST['classtag_' . $example_no];
				$new_example->example_notation = $_POST['notation_' . $example_no];
				$new_example->sequence_no = $_POST['seq_' . $example_no];
				$new_example->language_id = $this->target_language_id;
				$new_example->example_description = $_POST['example_' . $example_no];

				array_push($this->examples, $new_example);

				$example_no++;
			}
		}

		/**
		 * setrevisions
		 * Create a revision histroy record the revision history variables in a form
		 * and add to the class revision history array
		 * @param string $revision_history_fieldname
		 */

		public function setrevisions($revision_history_fieldname)
		{
			if (isset($_POST[$revision_history_fieldname]))
			{
				# First split the string into lines
				$temparray = explode("@", $_POST[$revision_history_fieldname]);

				foreach($temparray as $dummy => $value)
				{
					if (trim($value) != "")
					{
						# Split the line into 4 fields (separated by #)
						$revisionvalues = explode("#", $value);
						if (count($revisionvalues) == 4)
						{
							$revision = new RevisionFields();
							$revision->revisiondate = $revisionvalues[0];
							$revision->revisionsource = $revisionvalues[2];
							$revision->revisionfields = $revisionvalues[1];
							$revision->revisioncomment = $revisionvalues[3];
							$this->revisionhistory[$revision->revisiondate] = $revision;
						}
					}
				}
			}
		}

		/**
		 * setpostvars
		 * Takes all POSTed form input and sets form input field values into
		 * class member variables
		 */

		public function setpostvars()
		{
			$this->setformvar('scrollvalue', $this->scrollvalue);

			$lastnotation = $this->notation;

			// First, check the form then replace all the inner variables with POST vars
			$this->setformvar('MFN', 				$this->mfn);
			$this->setformvar('mfnvalue', 			$this->mfn);
			$this->setformvar('searchterm', 		$this->searchterm);
			$this->setformvar('notation', 			$this->notation);
			$this->setformvar('notationedit', 		$this->notation);
			$this->setformvar('EUN', 				$this->EUN);
			$this->setformvar('caption', 			$this->caption);
			$this->setformvar('transcaption', 		$this->transcaption);
			$this->setformvar('headingtype', 		$this->headingtype);
			$this->setformvar('scopenote', 			$this->scopenote);
			$this->setformvar('appnote', 			$this->appnote);
			$this->setformvar('transscopenote', 	$this->transscopenote);
			$this->setformvar('transappnote', 		$this->transappnote);
			$this->setarrvals('refstring', 			$this->refs);	// This requires pulling data from a string and inserting into an array
			$this->setarrvals('examplestring', 		$this->examples);
			$this->setformvar('specialauxtype', 	$this->specialauxtype);
			$this->setformvar('broader', 			$this->broadercategory);
			$this->setarrvals('pardivinst', 		$this->pardivinst);
			$this->setformvar('language', 			$this->source_language_id);
			$this->setsessionvar('deflang',			$this->target_language_id);
			$this->setformvar('edition', 			$this->edition);
			$this->setformvar('auxgroup', 			$this->auxgroup);
			$this->setformvar('verbalexamples', 	$this->verbal_examples);
			$this->setformvar('transverbalexamples', $this->transverbal_examples);
			$this->setformvar('informationnote', 	$this->informationnote);
			$this->setformvar('editorialnote', 		$this->editorialnote);
			$this->setformvar('introdate', 			$this->introdate);
			$this->setformvar('introsource', 		$this->introsource);
			$this->setformvar('introcomment', 		$this->introcomment);
			$this->setformvar('lastrevdate', 		$this->lastrevdate);
			$this->setformvar('lastrevsource', 		$this->lastrevsource);
			$this->setformvar('lastrevfields', 		$this->lastrevfields);
			$this->setformvar('lastrevcomment', 	$this->lastrevcomment);
			$this->setformvar('derivedfrom', 		$this->derivedfrom);
			$this->setformvar('mycomments', 		$this->mycomments);
			$this->setformvar('editormycomments', 	$this->editor_comments);
			$this->settextarrvals('keywords', 		$this->keywords);
			$this->settextarrvals('alphabeticalindex', $this->alphabeticalindex);
			$this->setrevisions('revisionstring', 	$this->revisionhistory);
			$this->setexamples();
		}

		/**
		 * SetLanguageField
		 * Set coloured text on a language field.  Text for the specified language is coloured
		 * black. If there is no field value in the current language, English is displayed in
		 * a kind of brown text colour
		 * @param string $colorlabel
		 * @param string $fieldlabel
		 * @param string $formstring
		 * @param string $varfield
		 */

		public function SetLanguageField($colorlabel, $fieldlabel, &$formstring, &$varfield)
		{
			//echo "LF: " . $varfield . "<br>\n";
			$iSep = strpos($varfield, "~");
			if ($iSep > 0)
			{
				$language = substr($varfield, 0, $iSep);
				$varvalue = mb_substr($varfield, $iSep+1, strlen($varfield) - $iSep, "UTF-8");

				$formstring = str_replace($fieldlabel, $varvalue, $formstring);
				if ($language != $this->source_language_id)
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
				$formstring = str_replace($fieldlabel, $varfield, $formstring);
			}
		}

		/**
		 * setformvars
		 * Take the form to display in text format and substitute # delimited field markers with member variable values
		 * in the class
		 * e.g. replace #encodedtag# with $this->EUN
		 * @param string $dbc
		 * @param string $menuchoice
		 * @param string $formstring
		 * @param bool $savesuccess
		 * @param long $resetID
		 */

		public function setformvars($dbc, $menuchoice, &$formstring, $savesuccess, $resetID = false)
		{
			if ($resetID)
			{
				$this->mfn = 0;
			}

			# Get the classmark tree
			include_once("displaybranch.php");
			$formstring = str_replace("#displaytree#", GetHierarchyBranch($menuchoice, "", "", $this->target_language_id), $formstring);

			# Get the language options for the menu
			$sql = "select language_id, code, description from language order by language_id";
			$res = mysql_query($sql, $this->dsn);
			if ($res)
			{
				while($row = @mysql_fetch_array($res, MYSQL_NUM))
				{
					$option = $row[2] . ":" . $row[0] . ":" . $row[1];
					//echo $option . "<br>\n";
					$this->languages[$row[0]] = $option;
				}
				@mysql_free_result($res);
			}

			# Sort language strings
			asort($this->languages, SORT_STRING);

			$sourcelanguages = "";
			foreach($this->languages as $option)
			{
				$row = explode(":", $option);
				$option = "<option value=\"" . $row[1] . "\"";
				$langcode = $row[2];
				if ($this->source_language_id==$row[1])
				{
					$option .= " selected";
					$lang = $row[1];
				}
				$option .= ">" . $row[0] . "</option>\n";

				// for the time being, English is the only source language option
				if ($row[1] == 1)
				{
					$sourcelanguages .= $option;
				}
			}

			if (isset($_SESSION['deflang']))
			{
				$this->target_language_id = $_SESSION['deflang'];
			}

			$target_language = $this->languages[$this->target_language_id];
			$langvals = explode(":", $target_language);
			$langvalsname = $langvals[0];
			$targetoption = "<option id=\"0\" selected>" . $langvalsname ."</option>\n";
			$formstring = str_replace("#target_language#", $targetoption, $formstring);
			$formstring = str_replace("#source_languages#", $sourcelanguages, $formstring);
			foreach($this->language_values as $langvalid => $langvalcode)
			{
				$formstring = str_replace("#lang-" . $langvalcode . "#", ($this->source_language_id==$langvalid) ? " selected" : "", $formstring);
			}

			if ($this->notation == "")
				$formstring = str_replace('#notation#', "&nbsp;", $formstring);
			else
				$formstring = str_replace('#notation#', htmlentities($this->notation, ENT_COMPAT), $formstring);

			$formstring = str_replace('#searchterm#', htmlentities($this->notation, ENT_COMPAT), $formstring);
			$showeditbutton = true;
			if ((isset($_SESSION['userrole']) && $_SESSION['userrole'] == 0) || $this->notation == "")
			{
				$showeditbutton = false;
			}

			if ($showeditbutton == false)
			{
				$formstring = str_replace('#showeditbutton#', "style=\"display:none\"", $formstring);
			}
			else
			{
				$formstring = str_replace('#showeditbutton#', "", $formstring);
			}

			$formstring = str_replace("#scrollvalue#", $this->scrollvalue, $formstring);

			$formstring = str_replace('#mfn#', $this->mfn, $formstring);
			$this->SetLanguageField('#captioncolor#', '#caption#', $formstring, $this->caption);
			$this->SetLanguageField('#transcaptioncolor#', '#transcaption#', $formstring, $this->transcaption);
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
			$this->SetLanguageField('#scopenotecolor#', '#transscopenote#', $formstring, $this->transscopenote);
			if ($this->scopenote == "")
			{
				$formstring = str_replace("#displayscopenote#","style=\"display:none;\"", $formstring);
			}
			else
			{
				$formstring = str_replace("#displayscopenote#","", $formstring);
			}


			$this->SetLanguageField('#appnotecolor#', '#appnote#', $formstring, $this->appnote);
			$this->SetLanguageField('#appnotecolor#', '#transappnote#', $formstring, $this->transappnote);

			if(isset($_SESSION['othercomments']) && trim($_SESSION['othercomments']) != "")
			{
				$formstring = str_replace("#comments#", $_SESSION['othercomments'], $formstring);
			}
			else
			{
				$formstring = str_replace("#comments#", "", $formstring);
			}

			if(trim($this->mycomments) != "")
			{
				$formstring = str_replace("#mycomments#", $this->mycomments, $formstring);
			}
			else
			{
				$formstring = str_replace("#mycomments#", "", $formstring);
			}

			if(trim($this->editor_comments) != "")
			{
				$formstring = str_replace("#editormycomments#", $this->editor_comments, $formstring);
			}
			else
			{
				$formstring = str_replace("#editormycomments#", "", $formstring);
			}

			if ($this->appnote == "")
			{
				$formstring = str_replace("#displayappnote#","style=\"display:none;\"", $formstring);
			}
			else
			{
				$formstring = str_replace("#displayappnote#","", $formstring);
			}
			$formstring = str_replace("#inputlang#", $langcode, $formstring);

			/*
			// Examples of combination
			$examples = "<table id=\"extable\" class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
						"<tr><td align=\"left\" width=\"15%\">Notation</td><td width=\"75%\" align=\"left\">Description</td><td width=\"10%\">&nbsp;</td>" .
						"</tr>\n";

			$examplestring = "";
			$linkid = 1;
			foreach ($this->examples as $i => $value)
			{
				$langvalue = split("~", $value);
				$splitvalues = split("#", $langvalue[1]);

				$editlinkname = "editlink_" . $linkid++;

				$examples .= "<tr><td width=\"15%\" bgcolor=\"white\"><div id=\"" . $exnotname . "\" name=\"" . $exnotname . "\">" . $i . "</div></td><td width=\"75%\" bgcolor=\"white\"";
				$is_target_language = "true";
				if ($langvalue[0] != $this->target_language_id)
				{
					$is_target_language = "false";
					$examples .= "class=\"greytextarea\">";
				}
				else
				{
					$examples .= "class=\"blacktextarea\">";
				}

				$examples .= "<div id=\"" . $exnotdesc . "\" name=\"" . $exnotdesc . "\">";
				$examples .= $splitvalues[0];

				$htmlnotation = htmlentities($i, ENT_COMPAT, "UTF-8");
				$htmlnotation = str_replace("'", "%27", $htmlnotation);
				$htmlnotation = str_replace("+", "%2B", $htmlnotation);

				$examples .= "</div></td>";
				$examples .= "<td width=\"10%\" bgcolor=\"white\"><div id=\"" . $editlinkname . "\" name=\"" .
							 $editlinkname . "\" style=\"display:none\"><a href=\"#exampleentry\" onMouseDown=\"javascript:editExample('" . $htmlnotation .
							 "');return true;\">Edit</a></span></td></tr>\n";

				$examplestring .= $htmlnotation . "#" . $splitvalues[0] . '#' . $splitvalues[1] . '#' . $splitvalues[2] . '#' . $is_target_language . "@";
			}

			$examples .= "</table>" .
						 "<div class=\"inputsection\">" .
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
						 "<input id=\"examplestring\" name=\"examplestring\" type=\"hidden\" value=\"". $examplestring . "\">\n" .
						 "</div>";

			$formstring = str_replace("#examples#", $examples, $formstring);
			if (count($this->examples) == 0)
			{
				$formstring = str_replace("#displayexamples#","style=\"display:none;\"", $formstring);
			}
			else
			{
				$formstring = str_replace("#displayexamples#","", $formstring);
			}

			*/

			$formstring = str_replace("#notspecialaux#", ($this->specialauxtype==0) ? " selected" : "", $formstring);
			$formstring = str_replace("#hyphenaux#", ($this->specialauxtype==1) ? " selected" : "", $formstring);
			$formstring = str_replace("#pointaux#", ($this->specialauxtype==2) ? " selected" : "", $formstring);
			$formstring = str_replace("#apostropheaux#", ($this->specialauxtype==3) ? " selected" : "", $formstring);
			$formstring = str_replace("#otheraux#", ($this->specialauxtype==4) ? " selected" : "", $formstring);

			$this->SetLanguageField('#broadercategorycolor#', '#broadercategory#', $formstring, $this->broadercategory);
			$this->SetLanguageField('#derivedfromcolor#', '#derivedfrom#', $formstring, $this->derivedfrom);

			$formstring = str_replace("#EUN#", $this->EUN, $formstring);

			$pardivinst = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\"><tr><td width=\"25%\" align=\"left\">Source</td><td width=\"25%\" align=\"left\">Encoded</td><td width=\"25%\" align=\"left\">Target</td><td width=\"25%\" align=\"left\">Encoded</td><td>&nbsp;</td></tr>\n";
			$newpardivinststring = "";
			foreach ($this->pardivinst as $i => $value)
			{
				$splitpardivinst = split("#", $value);
				$pardivinst .= "<tr><td width=\"25%\" bgcolor=\"white\">" . $i . "</td><td width=\"25%\" bgcolor=\"white\">" . $splitpardivinst[0] . "</td><td width=\"25%\" bgcolor=\"white\">" . $splitpardivinst[1] . "</td><td width=\"20%\" bgcolor=\"white\">" . $splitpardivinst[2] . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#\" onMouseDown=\"javascript:deletepardivinst('". $i . "');return true\">Delete</a></td></tr>\n";
				$newpardivinststring .= $i . "#" . $value . "#@";
			}
			$pardivinst .= "</table>" .
						  "<div class=\"inputsection\">" .
						 "<a name=\"pardiveentry\"></a><div class=\"inputrow\"><div class=\"inputlabel\">Source </div> <div class=\"inputvalue\"><input class=\"edittextarea\" id=\"pardivinstsrcnotation\" type=\"text\" size=\"30\"></div>" .
						 "<div class=\"inputlabel\">Src Encoded</div><div class=\"inputvalue\"><input class=\"editttextarea\" id=\"pardivinstsrcencoded\" type=\"text\" size=\"30\"></div>" .
						 "<a href=\"#pardiventry\" onMouseDown=\"javascript:addParDivInst();\">Add</a></div>" .
						 "<div class=\"inputrow\"><div class=\"inputlabel\">Target </div> <div class=\"inputvalue\"><input class=\"edittextarea\" id=\"pardivinsttgtnotation\" type=\"text\" size=\"30\"></div>" .
						 "<div class=\"inputlabel\">Tgt Encoded</div><div class=\"inputvalue\"><input class=\"editttextarea\" id=\"pardivinsttgtencoded\" type=\"text\" size=\"30\"></div>" .
						 "</div>" .
						 "<input id=\"pardivinststring\" name=\"pardivinststring\" type=\"hidden\" value=\"". $newpardivinststring . "\"></div>\n";

			$formstring = str_replace("#pardivinst#", $pardivinst, $formstring);
			$formstring = str_replace("#auxgroupid#", $this->auxgroup, $formstring);
			$this->SetLanguageField('#verbalexamplescolor#', '#verbalexamples#', $formstring, $this->verbal_examples);
			$this->SetLanguageField('#verbalexamplescolor#', '#transverbalexamples#', $formstring, $this->transverbal_examples);
			if ($this->verbal_examples == "")
			{
				$formstring = str_replace("#displayverbalexamples#","style=\"display:none;\"", $formstring);
			}
			else
			{
				$formstring = str_replace("#displayverbalexamples#","", $formstring);
			}

			if (count($this->validation_errors) > 0)
			{
				$formstring = str_replace("#errorreasons#", $this->GetErrorString(), $formstring);
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

			$this->SetLanguageField('#informationnotecolor#', '#infonote#', $formstring, $this->informationnote);
			$formstring = str_replace("#editorialnote#", $this->editorialnote, $formstring);
			$formstring = str_replace("#introdate#", $this->introdate, $formstring);
			$formstring = str_replace("#introsource#", $this->introsource, $formstring);
			$formstring = str_replace("#introcomment#", $this->introcomment, $formstring);

			$formstring = str_replace("#lastrevdate#", $this->lastrevdate, $formstring);
			$formstring = str_replace("#lastrevsource#", $this->lastrevsource, $formstring);
			$formstring = str_replace("#lastrevfields#", $this->lastrevfields, $formstring);
			$formstring = str_replace("#lastrevcomment#", $this->lastrevcomment, $formstring);

			$revisionhistory = "<table class=\"reftable\" width=\"100%\" bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">" .
						"<tr><td align=\"left\" width=\"15%\">Date</td><td width=\"20%\" align=\"left\">Fields</td><td width=\"15%\" align=\"left\">Source</td><td width=\"40%\" align=\"left\">Comment</td><td width=\"5%\">&nbsp;</td><td width=\"5%\">&nbsp;</td></tr>\n";

			$revisionstring = "";
			foreach ($this->revisionhistory as $i => $value)
			{
				$revisionhistory .= "<tr><td width=\"15%\" bgcolor=\"white\">" . $value->revisiondate . "</td><td width=\"20%\" bgcolor=\"white\">" . $value->revisionfields .
							 "</td><td width=\"15%\" bgcolor=\"white\">" . $value->revisionsource . "</td><td width=\"40%\" bgcolor=\"white\">" . $value->revisioncomment . "</td><td width=\"5%\" bgcolor=\"white\"><a href=\"#revhistentry\" onMouseDown=\"javascript:editRevision('" . $value->revisiondate .
							 "');return true;\">Edit</a></td><td width=\"5%\" bgcolor=\"white\"><a href=\"#revhistentry\" onMouseDown=\"javascript:deleteRevision('" . $value->revisiondate. "');return true;\">Delete</a></td></tr>\n";

				$revisionstring .= $value->revisiondate . "#" . $value->revisionfields . "#" . $value->revisionsource. "#" . $value->revisioncomment."@";
			}

			$revisionhistory .= "</table><a name=\"revhistentry\"></a> Date <input class=\"edittextarea\" id=\"revisiondate\" type=\"text\" size=\"10\"> Fields <input class=\"edittextarea\" id=\"revisionfields\" " .
								"type=\"text\" size=\"19\">" .
								" Source <input class=\"editttextarea\" id=\"revisionsource\" type=\"text\" size=\"10\"> Comments <input class=\"editttextarea\" id=\"revisioncomments\" type=\"text\" size=\"19\">" .
								 "<input id=\"revisionstring\" name=\"revisionstring\" type=\"hidden\" value=\"". $revisionstring . "\">\n";

			$formstring = str_replace("#revisions#", $revisionhistory, $formstring);

			$formstring = str_replace("#keywords#", implode("\r\n", $this->keywords), $formstring);
			$formstring = str_replace("#alphaindex#", implode("\r\n", $this->alphabeticalindex), $formstring);
		}

		function GetErrorString()
		{
			$errorstring = "<ul>";
			foreach($this->validation_errors as $i => $error)
			{
				$errorstring .= "<li>" . $error . "</li>";
			}
			$errorstring .= "</ul>\n";

			return $errorstring;
		}

		function queryformvars($dsn)
		{
			#echo "Querying form vars<br>\n";

			$this->ClearArray($this->validation_errors);

			$notation = trim($this->notation);
			$notation = str_replace("\xa0", " ", $this->notation);
			$notation = trim($notation);

			if (strlen($notation) == 0)
			{
				$notation = trim($this->searchterm);
			}

			if ($this->searchterm != $notation && $this->searchterm != "")
			{
				$notation = $this->searchterm;
			}

			$language = $this->source_language_id;
			$targetlanguage = $this->target_language_id;
			$edition = $this->edition;
			$this->clearvars();

			$this->searchterm = $notation;
			$this->notation = $notation;
			$this->source_language_id = $language;
			$this->target_language_id = $targetlanguage;
			$this->edition = $edition;

			if ($this->notation == "")
			{
				$_SESSION['othercomments'] = "";
				$_SESSION['oldcomments'] = "";
				$_SESSION['oldeditorcomments'] = "";
				return;
			}

			$sql = "select c.classmark_id, h.heading_type, c.special_aux_type, c.classmark_enc_tag from classmarks c, headingtypes h where c.classmark_tag = '" . $this->notation . "' and c.heading_type = h.heading_type_id and c.active = 'Y'";

			#echo $sql . "<br>\n";

			$res = @mysql_query($sql, $this->dsn);

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
				}
				else
				{
					array_push($this->validation_errors, "'" . $this->notation . "' is an invalid notation");
				}

				@mysql_free_result($res);
			}

			// Retrieve the broader category details
			$sql = "select c2.classmark_tag, f.language_id, f.description from classmarks c join classmarks c2 on c.broader_category = c2.classmark_id, language_fields f " .
				   "where c2.classmark_id = f.classmark_id and f.field_id = 1 and c.classmark_id = " . $this->mfn . " and f.language_id in (" . $this->source_language_id . ", " . $this->target_language_id .
				   ") and c.active = 'Y' order by f.language_id"; // . " and f.edition = '" . $this->edition . "'";

			#echo $sql . "<br>\n";

			$res = @mysql_query($sql, $this->dsn);

			$rowcount=0;
			$resultcount = @mysql_num_rows($res);
			if ($resultcount > 0)
			{
				while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
				{
					if ($row[1] == $this->target_language_id || $row[1] == $this->source_language_id)
						$this->broadercategory = $row[1] . '~' . specialchars($row[0]) . " " . specialchars($row[2]);
				}
			}

			mysql_free_result($res);

			// Retrieve the derivedfrom details
			$sql = "select c2.classmark_tag, f.language_id, f.description from classmarks c join classmarks c2 on c.derived_from = c2.classmark_tag join language_fields f " .
				   "on c2.classmark_id = f.classmark_id and f.field_id = 1 and c.classmark_id = " . $this->mfn . " and f.language_id in (" . $this->target_language_id . ", " . $this->source_language_id .
				   ") and c.active = 'Y' order by f.language_id"; // . " and f.edition = '" . $this->edition . "'";

			#echo $sql . "<br>\n";

			$res = @mysql_query($sql, $this->dsn);

			$rowcount=0;
			$resultcount = @mysql_num_rows($res);
			if ($resultcount > 0)
			{
				while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
				{
					if ($row[1] == $this->target_language_id || $row[1] == $this->source_language_id)
						$this->derivedfrom = $row[1] . '~' . specialchars($row[0]) . " " . specialchars($row[2]);
				}
			}

			mysql_free_result($res);

			// Now retrieve all language field entries.  Language fields include any field that can be expressed in more than one language
			// such as caption, verbal example, scope note etc
			$sql = 	"select f.field_id, f.language_id, description from language_fields f join classmarks c on c.classmark_id = f.classmark_id and f.language_id in (" . $this->target_language_id . ", " .
					$this->source_language_id . ") where c.classmark_id = " . $this->mfn . " and c.active = 'Y' order by f.field_id, f.language_id"; // and f.edition = '" . $this->edition . "'

			#echo $sql . "<br>\n";

			$res = @mysql_query($sql, $this->dsn);

			$rowcount=0;
			$resultcount = @mysql_num_rows($res);
			if ($resultcount > 0)
			{
				while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
				{
					if ($row[1] == $this->target_language_id || $row[1] == $this->source_language_id)
					{
						switch($row[0])
						{
							case 1:
								// Caption
								if ($row[1] == $this->source_language_id)
								{
									$this->caption = specialchars($row[2]);
								}
								if ($row[1] == $this->target_language_id)
								{
									$this->oldtranscaption = $row[2];
									$this->transcaption = specialchars($row[2]);
								}
								break;
							case 4:
								// Verbal_examples
								if ($row[1] == $this->source_language_id)
								{
									$this->verbal_examples = specialchars($row[2]);
								}
								else if ($row[1] == $this->target_language_id)
								{
									$this->transverbal_examples = specialchars($row[2]);
								}
								break;
							case 5:
								// Scope note
								if ($row[1] == $this->source_language_id)
								{
									$this->scopenote = specialchars($row[2]);
								}
								else if ($row[1] == $this->target_language_id)
								{
									$this->oldtransscopenote = $row[2];
									$this->transscopenote = specialchars($row[2]);
								}
								break;
							case 6:
								// Application note
								if ($row[1] == $this->source_language_id)
								{
									$this->appnote = specialchars($row[2]);
								}
								else if ($row[1] == $this->target_language_id)
								{
									$this->oldtransappnote = $row[2];
									//echo "Transappnote = " . $row[2] . "<br>\n";
									$this->transappnote = specialchars($row[2]);
									//echo "Transappnote = " . $this->transappnote . "<br>\n";
								}
								break;
							case 10:
								// Information note
								$this->informationnote = $row[1] . '~' . specialchars($row[2]);
								break;
						}
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

			// Examples of combination
			$sql = "select e.field_type, e.tag, c.classmark_tag, f.description, f.language_id, e.seq_no " .
				   "from example_classmarks e join classmarks c on c.classmark_id = e.classmark_id " .
				   "left outer join language_fields f on f.classmark_id = e.classmark_id and e.seq_no = f.seq_no and f.field_id = 2 and f.language_id in (" .
				   $this->target_language_id . ", " . $this->source_language_id . ") where c.classmark_id = " . $this->mfn . " and c.active = 'Y' " .
				   "order by f.seq_no, f.language_id";

			#echo $sql . "<br>\n";

			$res = @mysql_query($sql, $this->dsn);

			$this->ClearArray($this->examples);

			$exarray = array();

			$rowcount=0;
			$resultcount = @mysql_num_rows($res);
			if ($resultcount > 0)
			{

				while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
				{
					$example = new ExampleClassmark();
					$example->field_type = $row[0];
					$example->example_notation = $row[1];
					$example->classmark_notation = $row[2];
					$example->example_description = $row[3];
					$example->language_id = $row[4];
					$example->sequence_no = $row[5];

					switch($example->field_type)
					{
						case "a":
							// Direct addition
							$example->display_notation = $example->classmark_notation . $example->example_notation;
							break;
						case "b":
							// Colon (:) combination
							$example->display_notation = $example->classmark_notation . ":" . $example->example_notation;
							break;
						case "c":
						case "r":
							// Full notation - used if the first component differs from the notation in field 001.
							$example->display_notation = $example->example_notation;
							break;
						default:
							break;
					}

					array_push($this->examples, $example);
				}
			}

			@mysql_free_result($res);

			// Translator's notes
			$sql = "select reviewer, comments, date_changed from udct_comments where classmark_id = " . $this->mfn . " order by date_changed desc";

			#echo $sql . "<br>\n";

			$res = @mysql_query($sql, $this->dsn);

			$_SESSION['oldcomments'] = "";
			$_SESSION['othercomments'] = "";
			$_SESSION['oldeditorcomments'] = "";
			$_SESSION['othereditorcomments'] = "";

			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				$reviewer = $row[0];

				if ($reviewer == $_SESSION['userid'])
				{
					$this->mycomments = $row[1];
					$this->commentdate = $row[2];
					$_SESSION['oldcomments'] = $row[1];
				}
				else
				{
					if (trim($row[1]) != "")
						$_SESSION['othercomments'] .= "<strong>" . ucfirst($row[0]) . "</strong> [" . $row[2] . "]<br>" . $row[1] . "<br><br>";
				}
			}

			@mysql_free_result($res);

			// Editor Comments
			$sql = "select reviewer, comments, date_changed from udct_editor_comments where classmark_id = " . $this->mfn . " order by date_changed asc";

			#echo $sql . "<br>\n";

			$res = @mysql_query($sql, $this->dsn);

			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				$reviewer = $row[0];

				if ($reviewer == $_SESSION['userid'])
				{
					$this->editor_comments = $row[1];
					$_SESSION['oldeditorcomments'] = $row[1];
				}
				else
				{
					if (trim($row[1]) != "")
						$_SESSION['othereditorcomments'] .= "<strong>" . ucfirst($row[0]) . "</strong> [" . $row[2] . "]<br>" . $row[1] . "<br><br>";
				}
			}

			@mysql_free_result($res);

			@mysql_close($dsn);
		}

		function LoadClassmark(&$dsn)
		{
			$notation = trim($this->notation);
			$notation = str_replace("\xa0", " ", $notation);
			$notation = trim($notation);

			if (strlen($notation) == 0)
			{
				return "";
			}

			$this->clearvars();

			$this->notation = $notation;

			$sql = "select c.classmark_id, h.heading_type, c.special_aux_type, c.classmark_enc_tag, c.broader_category, c.derived_from from classmarks c, headingtypes h where c.classmark_tag = '" . $this->notation . "' and c.heading_type = h.heading_type_id and c.active = 'Y'";
			##echo $sql . "<br>\n";

			$res = @mysql_query($sql, $dsn);
			if ($res)
			{
				while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
				{
					$this->mfn = $row[0];
					$this->headingtype = $row[1];
					$this->specialauxtype = $row[2];
					$this->EUN = $row[3];
					$this->broadercategory = $row[4];
					$this->derivedfrom = $row[5];
				}
				@mysql_free_result($res);
			}

			if ($this->mfn == 0)
			{
				# Invalid notation
				return;
			}

			# Load references
			$sql = "select r.notation from classmark_refs r join classmarks c on r.notation = c.classmark_tag " .
				   " where c.active = 'Y' and r.classmark_id = " . $this->mfn;
			##echo $sql . "<br>\n";

			$res = @mysql_query($sql, $dsn);

			$this->ClearArray($this->refs);

			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				$refkey = specialchars($row[0]);
				array_push($this->refs, $refkey);
			}

			@mysql_free_result($res);

			// Now retrieve all language field entries.  Language fields include any field that can be expressed in more than one language
			// such as caption, verbal example, scope note etc
			$sql = 	"select f.field_id, f.language_id, description from language_fields f join classmarks c on c.classmark_id = f.classmark_id " .
					"where c.classmark_id = " . $this->mfn . " and c.active = 'Y' order by f.field_id, f.language_id";
			##echo $sql . "<br>\n";

			$res = @mysql_query($sql, $dsn);

			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				switch($row[0])
				{
					case 1:
						// Caption
						$this->caption .= $row[1] . '~' . $row[2] . '#';
						break;
					case 4:
						// Verbal_examples
						$this->verbal_examples .= $row[1] . '~' . $row[2] . '#';
						break;
					case 5:
						// Scope note
						$this->scopenote .= $row[1] . '~' . $row[2] . '#';
						break;
					case 6:
						// Application note
						$this->appnote .= $row[1] . '~' . $row[2] . '#';
						break;
					case 10:
						// Information note
						$this->informationnote .= $row[1] . '~' . $row[2] . '#';
						break;
				}
			}

			@mysql_free_result($res);

			// Examples of combination
			$sql = "select e.field_type, e.seq_no, e.tag " .
				   "from example_classmarks e " .
				   "where c.classmark_id = " . $this->mfn . " " .
				   "order by e.seq_no, e.language_id";
			##echo $sql . "<br>\n";

			$res = @mysql_query($sql, $dsn);

			$this->ClearArray($this->examples);

			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				array_push($this->examples, $row[0] . "~" . $row[1] . "~" . $row[2] . "#");
			}

			@mysql_free_result($res);

			// Translator's notes
			$sql = "select reviewer, comments, date_changed from udct_comments where classmark_id = " . $this->mfn . " order by date_changed desc";

			$res = @mysql_query($sql, $dsn);

			$user_comments = array();
			$editor_comments = array();
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				array_push($user_comments, $row[0] . "~" . $row[1] . "~" . $row[2] . "#");
			}
			@mysql_free_result($res);

			// Editor Comments
			$sql = "select reviewer, comments, date_changed from udct_editor_comments where classmark_id = " . $this->mfn . " order by date_changed asc";
			##echo $sql . "<br>\n";

			$res = @mysql_query($sql, $this->dsn);
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
				array_push($editor_comments, $row[0] . "~" . $row[1] . "~" . $row[2] . "#");
			}

			@mysql_free_result($res);

			#echo "Serializing<br>\n";

			$buffer = "";
			$buffer .= $this->mfn . "|";
			$buffer .= $this->headingtype . "|";
			$buffer .= $this->notation . "|";
			$buffer .= $this->EUN . "|";
			$buffer .= $this->caption . "|";
			$buffer .= $this->scopenote . "|";
			$buffer .= $this->appnote . "|";
			$buffer .= $this->serialize_array($this->refs);
			$buffer .= $this->serialize_array($this->examples);
			$buffer .= $this->specialauxtype . "|";
			$buffer .= $this->broadercategory . "|";
			#$buffer .= $this->pardivinst . "|";
			$buffer .= $this->auxgroup . "|";
			$buffer .= $this->verbal_examples . "|";
			$buffer .= $this->informationnote . "|";
			$buffer .= $this->editorialnote . "|";
			#$buffer .= $this->introdate . "|";
			#$buffer .= $this->introsource . "|";
			#$buffer .= $this->introcomment . "|";
			#$buffer .= $this->lastrevdate . "|";
			#$buffer .= $this->lastrevsource . "|";
			#$buffer .= serialize_array($this->lastrevfields . "|";
			#$buffer .= $this->lastrevcomment . "|";
			$buffer .= $this->derivedfrom . "|";
			#$buffer .= serialize_array($this->revisionhistory . "|";
			#$buffer .= $this->keywords . "|";
			$buffer .= $this->serialize_array($user_comments);
			#$buffer .= serialize_array($this->alphabeticalindex . "|";
			#$buffer .= $this->commentdate . "|";
			#$buffer .= $this->editor_othercomments . "|";
			$buffer .= $this->serialize_array($editor_comments);
			#$buffer .= $this->editor_commentdate . "|";

			#echo "Complete<br>\n";

			return $buffer;
		}

		function serialize_array($arr)
		{

			$buffer = count($arr) . "|";

			foreach($arr as $value)
			{
				$buffer .= $value . "|";
			}

			return $buffer;

		}

		function udc_unpack(&$fields, &$field_id, &$item, $desc, $lang=0)
		{

			#echo "Unpacking " . $desc . "<br>\n";

			if ($field_id >= count($fields))
			{
				echo "Fields boundary exceeded " . $field_id . " vs " . count($fields) . "<br>\n";
				return;
			}

			$item = $fields[$field_id];

			if ($lang > 0)
			{
				$field_values = explode("#", $item);
				foreach($field_values as $value)
				{
					$value_items = explode("~", $value, 2);
					if (count($value_items) > 1)
					{
						if ($value_items[0] == $lang)
						{
							echo $value_items[1] . "<br>\n";
						}
					}
				}
			}
			else
			{
				echo $item. "<br>\n";
			}

			$field_id++;

		}

		function udc_unpack_array(&$fields, &$field_id, &$item, $desc)
		{

			#echo "Unpacking " . $desc . "<br>\n";

			$field_count = count($fields);

			if ($field_id >= $field_count)
			{
				echo "Fields boundary exceeded " . $field_id . " vs " . count($fields) . "<br>\n";
				return;
			}

			# First get the count of array items
			$arr_size = $fields[$field_id++];

			#echo "Found " . $arr_size . " fields<br>\n";

			for($i=0; ($i<$arr_size) && ($field_id < $field_count); $i++)
			{
				echo $fields[$field_id] . "<br>\n";
				array_push($item, $fields[$field_id]);
				$field_id++;
			}

		}

		function UnpackClassmark($buffer, $lang)
		{

			echo "Unpacking classmark<br>\n";

			# Split up the buffer into fields
			$fields = explode("|", $buffer);
			$field_id = 0;

			#echo "Unpack retrieved" . count($fields) . " fields<br>\n";

			$this->udc_unpack($fields, $field_id, $this->mfn, "MFN");
			$this->udc_unpack($fields, $field_id, $this->headingtype, "HeadingType");
			$this->udc_unpack($fields, $field_id, $this->notation, "Notation");
			$this->udc_unpack($fields, $field_id, $this->EUN, "EUN");
			$this->udc_unpack($fields, $field_id, $this->caption, "Caption", $lang);
			$this->udc_unpack($fields, $field_id, $this->scopenote, "ScopeNote", $lang);
			$this->udc_unpack($fields, $field_id, $this->appnote, "AppNote", $lang);
			$this->udc_unpack_array($fields, $field_id, $this->refs, "Refs");
			$this->udc_unpack_array($fields, $field_id, $this->examples, "Examples");
			$this->udc_unpack($fields, $field_id, $this->specialauxtype, "SpecialAuxType");
			$this->udc_unpack($fields, $field_id, $this->broadercategory, "BroaderCategory");
			$this->udc_unpack($fields, $field_id, $this->auxgroup, "AuxGroup");
			$this->udc_unpack($fields, $field_id, $this->verbal_examples, "VerbalExamples", $lang);
			$this->udc_unpack($fields, $field_id, $this->informationnote, "InformationNote", $lang);
			$this->udc_unpack($fields, $field_id, $this->editorialnote, "EditorialNote");
			#$buffer .= $this->introdate . "|";
			#$buffer .= $this->introsource . "|";
			#$buffer .= $this->introcomment . "|";
			#$buffer .= $this->lastrevdate . "|";
			#$buffer .= $this->lastrevsource . "|";
			#Unpack_array($this->lastrevfields . "|";
			#$buffer .= $this->lastrevcomment . "|";
			$this->udc_unpack($fields, $field_id, $this->derivedfrom, "DerivedFrom");
			#Unpack_array($this->revisionhistory . "|";
			#$buffer .= $this->keywords . "|";
			$this->udc_unpack_array($fields, $field_id, $this->arr_user_comments, "UserComments");
			#Unpack_array($this->alphabeticalindex . "|";
			#$buffer .= $this->commentdate . "|";
			#$buffer .= $this->editor_othercomments . "|";
			$this->udc_unpack_array($fields, $field_id, $this->arr_editor_comments, "EditorComments");

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

			$this->caption = str_replace("\n", " ", $this->caption);
			$this->scopenote = str_replace("\n", " ", $this->scopenote);
			$this->appnote= str_replace("\n", " ", $this->appnote);
			$this->verbal_examples= str_replace("\n", " ", $this->verbal_examples);

			return true;
		}

		function GetNotationNumber($notation)
		{
			$notation_number = $notation;
			/*
			$ispacepos = strpos($notation, " ");
			if ($ispacepos > 0)
			{
				$notation_number = substr($notation, 0, $ispacepos);
			}
			*/
			return $notation_number;
		}

		function GetClassmarkIDFromNotation($notation, &$dbc)
		{
			$classmark_id = 0;

			$notation_number = $this->GetNotationNumber($notation);

			if (trim($notation_number) != "")
			{
				$sql = "select classmark_id from classmarks where classmark_tag = '" . mysql_real_escape_string($notation_number) . "'";
				$res = @mysql_query($sql, $this->dsn);

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

		/*
		function GetInsertSQL(&$sqlarray, &$errors, &$this->dsn)
		{
			if ($this->CheckFormVars($errors))
			{
				$specialauxgroupid = $this->GetClassmarkIDFromNotation($this->auxgroup, $this->dsn);
				$broadercategoryid = $this->GetClassmarkIDFromNotation($this->broadercategory, $this->dsn);

				$sql = "insert into classmarks (classmark_id, heading_type, classmark_tag, classmark_enc_tag, broader_category, special_aux_group_id, derived_from) select " .
				$this->mfn . ", h.heading_type_id, '" . mysql_real_escape_string($this->notation) . "', '" . mysql_real_escape_string($this->EUN) . "', " . $broadercategoryid . ", " . $specialauxgroupid .
					", '" . mysql_real_escape_string($this->GetNotationNumber($this->derivedfrom)) . "' from headingtypes h where h.heading_type = '" . $this->headingtype . "'";
				array_push($sqlarray, $sql);

				// Now the language fields - first caption
				$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
				$this->mfn . ", 1, " . $this->source_language_id . ", 1, '" .mysql_real_escape_string($this->caption) . "')";
				array_push($sqlarray, $sql);

				// Verbal Examples
				if (strlen($this->verbal_examples) > 0)
				{
					$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
					$this->mfn . ", 4, " . $this->source_language_id . ", 1, '" . mysql_real_escape_string($this->verbal_examples) . "')";
					array_push($sqlarray, $sql);
				}

				// Scope note
				if (strlen($this->scopenote) > 0)
				{
					$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
					$this->mfn . ", 5, " . $this->source_language_id . ", 1, '" . mysql_real_escape_string($this->scopenote) . "')";
					array_push($sqlarray, $sql);
				}

				// Application note
				if (strlen($this->appnote) > 0)
				{
					$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no,  description) values (" .
					$this->mfn . ", 6, " . $this->source_language_id . ", 1, '" . mysql_real_escape_string($this->appnote) . "')";
					array_push($sqlarray, $sql);
				}

				// Information note
				if (strlen($this->informationnote) > 0)
				{
					$sql = "insert into language_fields (classmark_id, field_id, language_id, seq_no,  description) values (" .
					$this->mfn . ", 10, " . $this->source_language_id . ", 1, '" . mysql_real_escape_string($this->informationnote) . "')";
					//#echo $sql . "<br>\n";
					array_push($sqlarray, $sql);
				}

				// Refs and examples
				$this->GetRefsInsertSQL($sqlarray);
				$this->GetExamplesInsertSQL($sqlarray, $this->dsn);
				$this->GetAnnotationsInsertSQL($sqlarray);
				include_once 'inserthier.php';
				InsertIntoHierarchy($this->dsn, $this->mfn, $this->EUN, $broadercategoryid, $sqlarray, 0);
			}
		}
		*/

		/**
		 * RemoveIllegalCharacters
		 * Remove all line feeds (currently) from the field and replace with spaces
		 * @param $field
		 * @return mixed
		 */

		function RemoveIllegalCharacters($field)
		{
			$parts = explode("\n", $field);
			if (count($parts) > 1)
			{
				$output = "";

				foreach($parts as $part)
				{
					$part = trim($part);
					if (!empty($output))
					{
						$output .= " ";
					}
					$output .= $part;
				}

				$field = $output;
			}

			return $field;
		}

		function ReplaceLanguageField(&$sqlarray, $field_id, $field, $lang)
		{
			$sql = "";
			if ($field != "")
			{
				$field = RemoveIllegalCharacters($field);
				$sql = "replace into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" .
				$this->mfn . ", " . $field_id . ", " . $this->target_language_id . ", 1, convert('" . mysql_real_escape_string($field) . "' using utf8))";
				array_push($sqlarray, $sql);
			}
			else
			{
				$sql =  "delete from language_fields " .
						"where classmark_id = " . $this->mfn . " " .
						"and field_id = " . $field_id . " " .
						"and language_id = " . $lang;
				array_push($sqlarray, $sql);
			}
		}

		function UpdateFieldStats(&$statsarray)
		{
			$statsarray[] = "delete from translation_status_fields where classmark_id = " . $this->mfn . " and language_id = " . $this->target_language_id;
			$statsarray[] = "insert into translation_status_fields (classmark_id, language_id, field_id, lang_field_count) ".
							"select f.classmark_id, f.language_id, f.field_id, count(*) ".
							"from language_fields f join classmarks c on c.classmark_id = f.classmark_id ".
							"where c.classmark_id = " . $this->mfn . " " .
							"and f.language_id = " . $this->target_language_id . " ".
							"and f.field_id in (select field_id from language_fields f2 where f2.classmark_id = f.classmark_id and f2.seq_no = f.seq_no and f2.field_id = f.field_id and f2.language_id = 1) ".
							"group by f.classmark_id, f.language_id, f.field_id";
//			$sql = "delete from translation_status_fields where classmark_id = " . $this->mfn . " and language_id = " . $this->target_language_id;
//			array_push($statsarray, $sql);
//			$sql = "replace into translation_status_fields (classmark_id, language_id, field_id, lang_field_count) select " .
//			$this->mfn . ", " . $this->target_language_id . ", f.field_id, count(*) from language_fields f where f.classmark_id = " . $this->mfn . " and f.language_id = " . $this->target_language_id .
//			" group by f.classmark_id, f.language_id, f.field_id";
//			array_push($statsarray, $sql);
		}

		function GetUpdateSQL(&$sqlarray, &$errors, &$dbc)
		{
			if ($this->CheckFormVars($errors))
			{
				// Caption is a mandatory field and therefore always present (unless not English)
				$this->ReplaceLanguageField($sqlarray, 1, $this->transcaption, $this->target_language_id);

				if ($_SESSION['oldcomments'] != $this->mycomments)
				{

					$sql = "delete from udct_comments where classmark_id = " . $this->mfn . " and reviewer = '" . $_SESSION['userid'] . "'";
					array_push($sqlarray, $sql);

					if ($this->mycomments != "")
					{
						$sql = "insert into udct_comments (classmark_id, reviewer, comments, date_changed) values (" . $this->mfn . ", '" . $_SESSION['userid']. "', convert('" .
								@mysql_real_escape_string($this->mycomments) . "' using utf8), now())";
						array_push($sqlarray, $sql);
					}
				}

				if ($_SESSION['oldeditorcomments'] != $this->editor_comments)
				{
					$sql = "delete from udct_editor_comments where classmark_id = " . $this->mfn . " and reviewer = '" . $_SESSION['userid'] . "'";
					array_push($sqlarray, $sql);

					if ($this->editor_comments != "")
					{
						$sql = "insert into udct_editor_comments (classmark_id, reviewer, comments, date_changed) values (" . $this->mfn . ", '" . $_SESSION['userid']. "', convert('" .
								@mysql_real_escape_string($this->editor_comments) . "' using utf8), now())";
						array_push($sqlarray, $sql);
					}
				}

				$this->ReplaceLanguageField($sqlarray, 4, $this->transverbal_examples, $this->target_language_id);
				$this->ReplaceLanguageField($sqlarray, 5, $this->transscopenote, $this->target_language_id);
				$this->ReplaceLanguageField($sqlarray, 6, $this->transappnote, $this->target_language_id);
				$this->GetExamplesInsertSQL($sqlarray, $this->dsn, $this->target_language_id);
				$this->UpdateFieldStats($sqlarray);
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
					if (!array_key_exists($keyword, $termarray))
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

		function GetAdminSQL(&$sqlarray)
		{
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
			//array_push($sqlarray, $sql);
			$sql = "delete from revision_history_fields where classmark_id = " . $this->mfn;
			//array_push($sqlarray, $sql);

			//echo "There are " . count($this->revisionhistory) . " revision history items<br>\n";

			$iSeqNo = 1;
			foreach($this->revisionhistory as $i => $revision)
			{
				$sql = "insert into revision_history (classmark_id, sequence_no, revision_date, revision_source, revision_comment) values (" . $this->mfn .
					   ", " . $iSeqNo++ . ", '" . $revision->revisiondate . "', '" . $revision->revisionsource . "', '" . @mysql_real_escape_string($revision->revisioncomment) . "')";
				array_push($sqlarray, $sql);

				$revisionfields = explode(",", $revision->revisionfields);
				if ($revisionfields)
				{
					$iHistSeq = 1;
					foreach($revisionfields as $i => $field)
					{
						// Latest Revision
						$field = trim($field);
						$sql = "insert into revision_history_fields (classmark_id, revision_date, sequence_no, revision_field) values (" . $this->mfn .
							   ", '" . $revision->revisiondate . "', " . $iHistSeq++  . ", '" . $field . "')";
						array_push($sqlarray, $sql);
					}
				}
			}
		}

		function GetAnnotationsInsertSQL(&$sqlarray)
		{
			// Editorial Note
			$sql = "delete from other_annotations where classmark_id = " . $this->mfn . " and revision_field = '955'";
			array_push($sqlarray, $sql);
			if ($this->editorialnote != "")
			{
				$sql = "insert into other_annotations (classmark_id, revision_field, annotation) values (" . $this->mfn . ", '955', '" . mysql_real_escape_string($this->editorialnote) . "')";
				array_push($sqlarray, $sql);
			}
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
				//#echo $sql . "<br>\n";
				array_push($sqlarray, $sql);
				$iSeqNo++;
			}
		}

		function GetExamplesInsertSQL(&$sqlarray, &$dbc)
		{
			$sql = "delete from language_fields where classmark_id = " . $this->mfn . " and field_id = 2 and language_id = " . $this->target_language_id;
			array_push($sqlarray, $sql);

			// Refresh the examples from the class examplestring
			// It may have been updated if someone has added an example
			$iSeqNo = 1;
			foreach ($this->examples as $example)
			{
				if (trim($example->example_description) != "")
				{
					$sql =  "insert into language_fields (field_id, language_id, seq_no, description, classmark_id) values (2, " . $this->target_language_id. ", " . $example->sequence_no .
							", convert('" . @mysql_real_escape_string($example->example_description) . "' using utf8), " . $this->mfn . ")";
					array_push($sqlarray, $sql);

					if (DUMMYINSERT == true)
					{
						$example->Dump();
						#echo $sql . "<br>\n";
					}
				}
			}
		}

	};

?>
