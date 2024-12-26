<?php
	session_start();

    require_once("checksession.php");
    checksession();
    
/*    echo "[" . $_SESSION['userid'] . "]<br>\n";
    echo "[" . $_SESSION['access_page1'] . "]<br>\n";
    echo "[" . $_SESSION['access_page2'] . "]<br>\n";
    echo "[" . $_SESSION['access_page3'] . "]<br>\n";
    echo "[" . $_SESSION['show_reviewer_comment'] . "]<br>\n";
  */  
	require_once("DBConnectInfo.php");
	include_once("specialchars.php");

    define("DUMMYINSERT", false);
    
	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");
    //mysql_set_charset('latin1',$dbc);

	// Read the web page file and substitute form variables

	ob_start();
	include('form_edittag.php');
	$formfile = ob_get_contents();
	ob_end_clean();

	// Store post variables
	include('udcform.php');
	$formob = new udcform();
    
    //echo "Before postvars: " . $_POST['scrollvalue'] . "<br>\n";
    
	$formob->setpostvars();

	$loaderror = "";

    $control_id = 1;
    if (isset($_POST['SubmitNotationSearch']))
    {
        $_SESSION['list_results'] = "";
        $_SESSION['first_result'] = "";
        
        $notation = "";
        if (isset($_POST['notationsearchterm']))
        {
            $notation = $_POST['notationsearchterm'];
        }
        
        if ($notation != "")
        {
            $searchresults = "<table id=\"searchresultstable\" width=\"100%\" border=\"0\">";
            $sql =  "select c.classmark_tag, f.description, c.changed_fields, a.audit_date from classmarks c join language_fields f on c.classmark_id = f.classmark_id " .
                    "left outer join audit_history a on c.classmark_id = a.classmark_id and a.audit_type = 'I' " .
            		"left outer join classmark_hierarchy h on c.classmark_id = h.classmark_id " .
                    "where f.field_id = 1 and f.language_id = 1 and c.classmark_tag like '" . 
                    @mysql_real_escape_string($notation)  . "%' and c.active = 'Y' order by c.classmark_enc_tag"; 
            //echo $sql . "<br>\n";
			$res = @mysql_query($sql, $dbc);
			if ($res)
			{
				while(($row = mysql_fetch_array($res, MYSQL_NUM)))
				{
				    if (count($row) > 3 && $row[3] == "0912")
                    {
                        $color = "green";
                    }
                    else if ($row[2] == "Y")
                    {
                        $color = "magenta";
                    }
                    else
                    {
                        $color = "black";
                    }
                    $value = str_replace("+", "%2B", $row[0]);
                    $value = str_replace('"', '%22', $value);
                    $controlname = "searchresult_" . ($control_id++);
				    $searchresults .= "<tr><td class=\"searchresultscell\" valign=\"top\"><a id=\"" . $controlname . "\" class=\"searchresultscell\" href=\"#\" onclick=\"GotoNotation('". $value 
                    . "')\"; return false" . "\">" . $row[0] . 
                                      "</a></td><td class=\"searchresultscell\" valign=\"top\" style=\"color: " . $color . "\">" . $row[1] . "</td></tr>";  
//				    $searchresults .= "<tr><td class=\"searchresultscell\" valign=\"top\"><a id=\"" . $controlname . "\" class=\"searchresultscell\" href=\"edittag.php?tag=" . urlencode($value) . 
//                                      "&scroll=\"" . $_POST['scrollvalue'] . "\" onclick=\"notationchange(" . $controlname . ")\">" . $row[0] . 
//                                      "</a></td><td class=\"searchresultscell\" valign=\"top\" style=\"color: " . $color . "\">" . $row[1] . "</td></tr>";  
					$formob->mfn = ($row[0]) + 1;
                    
                    $_SESSION['list_results'] .= $row[0] . "#";
                    if ($_SESSION['first_result'] == "")
                    {
                        $_SESSION['first_result'] = $row[0];
                    }                    
				}
				mysql_free_result($res);
			}
            
            $searchresults .= "</table>";
            
            $_SESSION['searchresults'] = $searchresults;
            $notation = str_replace('\"', '"', $notation);
            $_SESSION['notationsearchterm'] = $notation;
            $_SESSION['captionsearchterm'] = "";
        }
    }

    if (isset($_POST['SubmitCaptionSearch']))
    {
        $_SESSION['list_results'] .= $row[0] . "#";
        $_SESSION['first_result'] = "";
        
        $caption = "";
        if (isset($_POST['captionsearchterm']))
        {
            $caption = $_POST['captionsearchterm'];
        }
                
        if ($caption != "")
        {
            $searchresults = "<table width=\"100%\" border=\"0\">";
            $sql =  "select c.classmark_tag, f.description, a.audit_date from classmarks c join language_fields f on c.classmark_id = f.classmark_id " .
                    "left outer join audit_history a on c.classmark_id = a.classmark_id and a.audit_type = 'I' ". 
                    "left outer join classmark_hierarchy h on c.classmark_id = h.classmark_id " .
                    "where f.field_id = 1 and f.language_id = 1 and f.description like '%" . 
                    @mysql_real_escape_string($caption) . "%' and c.active = 'Y' order by c.classmark_enc_tag"; 
			$res = @mysql_query($sql, $dbc);
			if ($res)
			{
				while(($row = mysql_fetch_array($res, MYSQL_NUM)))
				{
				    if (count($row) > 2 && $row[2] == "0912")
                    {
                        $color = "green";
                    }
                    else
                    {
                        $color = "black";
                    }
                    $value = str_replace("+", "%2B", $row[0]);
                    $searchresults .= "<tr><td valign=\"top\"><a id=\"searchresult_" . ($control_id++) . "\" class=\"searchresultscell\" href=\"#\" onclick=\"GotoNotation('". urlencode($value) . "')\"; return false" . 
                                      "\">" . $row[0] . "</a></td><td valign=\"top\" style=\"color: " . $color . "\">" . $row[1] . "</td></tr>";  
					$formob->mfn = ($row[0]) + 1;
                    
                    $_SESSION['list_results'] .= $row[0] . "#";
                    if ($_SESSION['first_result'] == "")
                    {
                        $_SESSION['first_result'] = $row[0];
                    }
				}
				mysql_free_result($res);
			}
            $searchresults .= "</table>";
            
            $_SESSION['searchresults'] = $searchresults;
            $_SESSION['captionsearchterm'] = $caption;
            $_SESSION['notationsearchterm'] = "";
        }
    }
    
	if (isset($_POST['SubmitSave']))
	{
		// This is a save operation
		$sql = "";

		if ($formob->mfn == 0)
		{
			// New record being saved - get the next classmark ID
            //echo "NEW RECORD<br>\n";
            
			$sql = "select max(classmark_id) from classmarks";
			$res = @mysql_query($sql, $dbc);
			if ($res)
			{
				$row = mysql_fetch_array($res, MYSQL_NUM);
				if ($row)
				{
					$formob->mfn = ($row[0]) + 1;
				}
				mysql_free_result($res);
			}

			$sqlarray = array();
			$errors = array();

			// Generate the insert SQL
			$sql = $formob->GetInsertSQL($sqlarray, $formob->validation_errors, $dbc);

			if (count($formob->validation_errors) == 0)
			{
				// Save the SQL
				include_once 'savesql.php';
				SaveSQL($sqlarray, $formob, $dbc, DUMMYINSERT);
				$formob->queryformvars($dbc);
			}
			else
			{
				$formob->mfn = 0;
			}
		}
        else 
        {
            # Load the old record values to generate the diffs
            $validated = new UDCForm();
            $validated->notation = $formob->notation;
            $validated->queryformvars($dbc);
                        
    		// Updating an existing record
            //echo "Saving form for ". $formob->notation . "<br>\n";
    		$sqlarray = array();
    		$errors = array();
    		$sql = $formob->GetUpdateSQL($sqlarray, $validated, $formob->validation_errors, $dbc);
    
    		if (count($formob->validation_errors) == 0)
    		{
    			// Save the SQL
    			include_once 'savesql.php';
    			SaveSQL($sqlarray, $formob, $dbc, DUMMYINSERT);
                array_push($formob->validation_errors, "Success");
    		}
            else
            {
                //echo "There were " . count($formob->validation_errors) . " errors..<br>\n";
            }
            
    		// ## Debug ##
    		//$formob->DumpVars($sqlarray, "SQL");
            if (count($formob->validation_errors) > 0 && $formob->validation_errors[0] != "Success")
    		     $formob->DumpVars($formob->validation_errors, "Errors");
            else
            {
                //echo "<strong>No Errors</strong><br>\n";
            }
            
    		$formob->queryformvars($dbc);
        }
    }
    
    else if (isset($_POST['CancelValidation']))
    {
        // Do nothing - this has the same effect as redisplaying the input record
    }
	else if (isset($_POST['New']))
	{
		$edition = $formob->edition;
		$formob->clearvars();
		$formob->edition = $edition;
	}
	else
	{
        //echo "Before query: " . $formob->scrollvalue . "<br>\n";
        if (isset($_POST['nextrecord']))
        {
            $formob->searchterm = $formob->nextrecordid;
        }
        if (isset($_GET['tag']))
        {
            $formob->searchterm = $_GET['tag'];           
            $formob->searchterm = str_replace("\\\"", "\"", $formob->searchterm);
            //echo "Pre form tag: " . $formob->searchterm . "<br>\n";
            $formob->searchterm = str_replace("%2B", "+", $formob->searchterm);
            $formob->searchterm = str_replace("%22", "\"", $formob->searchterm);
            //echo "Pre form: " . $formob->searchterm . "<br>\n";
        }        
        else
        {
            $_SESSION['nextnav'] = "";
        }
        $formob->queryformvars($dbc);
	}

    //echo "After query: " . $formob->scrollvalue . "<br>\n";
	$formob->setformvars($formfile, $validated, isset($_POST['Clone']));

	if (count($formob->times) > 0)
	{
		$timestring = "";
		foreach($formob->times as $time)
		{
			$timestring .= $time;
		}
		
		$formfile = str_replace("#times#", $timestring, $formfile);
		$formfile = str_replace("#timesshow#", "block", $formfile);
	}
	else
	{
		$formfile = str_replace("#times#", "", $formfile);
		$formfile = str_replace("#timesshow#", "none", $formfile);
	}


	echo $formfile;
	
	
	@mysql_close($dbc);
?>
