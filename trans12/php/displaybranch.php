<?php header("Content-type: text/html; charset=utf-8");
	/**
	 * @author Chris Overfield
	 * @copyright 2010
	 */

	if (!isset($_SESSION))
		session_start();

    require_once('checksession.php');
    checksession();

    include_once("dbconnection.php");

    include_once("getdisplaynotation.php");

	function NewGetAjaxNotation($notation, $referenceformat)
	{
		$notation = str_replace("+", "@@#@@", $notation);
		if ($referenceformat)
			$notation = str_replace("\"", "@@####@@", $notation);
		else
			$notation = str_replace("\"", "@@###@@", $notation);

		return $notation;
	}

	function NewDecodeAjaxNotation($notation, $referenceformat)
	{
		$notation = str_replace("@@#@@", "+", $notation);
		if ($referenceformat)
			$notation = str_replace("@@####@@", "\"", $notation);
		else
			$notation = str_replace("@@###@@", "\"", $notation);

		return $notation;
	}

    function ProcessRecord(&$record, $nodetoclassmarks, &$records, $inextnode, $lang)
    {
    	$description = NewGetAjaxNotation($record->description, false);

    	$recordline = "<a name=\"" . NewGetAjaxNotation($record->tag, true) . "\" style=\"cursor:pointer\" onclick=\"return openrecord('" . NewGetAjaxNotation($record->tag, true) . "');\" title=\"" . $description . "\">\n";
    	$recordline .= "&nbsp;";

        $record_incomplete = true;
        if ($record->language == $lang)
        {
        	if($record->eng_lang_field_count == $record->targ_lang_field_count)
	        {
	            $record_incomplete = false;
	        }
        }

        if ($record_incomplete == false)
        {
            $recordline .= "<img src=\"img/greensquare.gif\">";
        }
        else
        {
            $recordline .= "<img src=\"img/redsquare.gif\">";
        }

        $dn = GetDisplayNotation($record->tag, false);

        $recordline .= "&nbsp;" . $dn;
    	$recordline .= "</span>&nbsp;&nbsp;";

		$dir = "";
        if ($record->language != $lang)
        {
			$dir = "unicode-bidi: bidi-override; direction: ltr; ";
            $recordline .= "<span style=\"" . $dir . " color: #000000\">" . $description . "</span>";
        }
        else if ($record_incomplete == true)
        {
            $recordline .= "<span style=\"" . $dir . " color: #AA0000\">" . $description . "</span>";
        }
        else
        {
            $recordline .= "<span style=\"" . $dir . " color: #000000\">" . $description . "</span>";
        }
        $recordline .= "</a>";

        array_push($records, $recordline);
    }

    function StartTime(&$starttime)
    {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;
    }

    function EndTime($operation, $starttime)
    {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $totaltime = ($endtime - $starttime);
        #echo $operation . "<br>\n";
        #echo "Executed in " .$totaltime. " seconds.<br>\n";
        #flush();
    }

 	function GetHierarchyBranch($notation, $captionsearch, $notationsearch, $lang)
 	{
		$rtl = false;
        if (isset($_SESSION['rtl']))
        {
			$rtl = $_SESSION['rtl'];
        }

        $starttime = 0;
        $endtime = 0;

        $set_session_var = true;

        if (isset($_SESSION['search_results']) && $_SESSION['search_results'] != "" && !isset($_GET['resetsearch']) && !isset($_POST['resetsearch']))
        {
        	return $_SESSION['search_results'];
        }

 		$returnstring = "";

		$topid = "";
	    $toptag = "";
	    $toplevelfetch = false;

		$if_expandall = "expand all";
		$if_collapseall = "collapse all";
		$if_top = "TOP";

		$hierarchy_level = 0;
		$hierarchy_code = "";
		$rootclassmark_tag = "";
		$rootdescription = "";
		$broader_category = 0;
	    $rootclassmark_id = 0;

	    $showlastrevs = false;
	    if (isset($_SESSION['showlastrevs']))
	    {
	    	$showlastrevs = $_SESSION['showlastrevs'];
	    }

		$records = array();

		if ($captionsearch != "" || $notationsearch != "")
		{
        	$fieldstring = "1,2,4,5,6";
        	$showfinished = true;
        	if (isset($_SESSION['fieldstat']) && $_SESSION['fieldstat'] != "")
        	{
        		$temp = $_SESSION['fieldstat'];
        		$temp = str_replace("9", "", $temp);
        		$temp = trim($temp, ", ");
        		if ($temp != "")
        		{
        			$fieldstring = $temp;
        		}

        		if ($temp != $_SESSION['fieldstat'])
        		{
        			#echo "Showing unfinished<br>\n";
        			$showfinished = false;
        		}
			}

			$sql = "";
			if($captionsearch != "")
			{
	            $sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, f.description, f.field_id, " .
	                    " c.hierarchy_level, c.heading_type, f.language_id, s1.field_id, s1.lang_field_count, s2.field_id, s2.lang_field_count " .
		                " from classmarks c join language_fields f on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1," . $lang . ") " .
	            		" join translation_status_fields s1 on s1.classmark_id = c.classmark_id and s1.field_id in (" . $fieldstring . ") and s1.language_id = 1 " .
	                    " left outer join translation_status_fields s2 on s2.classmark_id = s1.classmark_id and s2.field_id in (" . $fieldstring .
	                    ") and s2.language_id = " . $lang . " and s1.field_id = s2.field_id ";
	            if (!empty($showlastrevs))
	            {
	            	$sql .= "join audit_history a on a.classmark_id = c.classmark_id and a.audit_date = '" . $showlastrevs . '" ';
	            }
	            $sql .= " where active = 'Y' " .
						" and f.description like '%" . @mysql_real_escape_string($captionsearch, DBConnection::getInstance()->getConnection()) . "%' " .
						" order by c.classmark_enc_tag, f.language_id, s1.field_id";
			}
			else if ($notationsearch != "")
			{
	            $sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, f.description, f.field_id, " .
	                    " c.hierarchy_level, c.heading_type, f.language_id, s1.field_id, s1.lang_field_count, s2.field_id, s2.lang_field_count " .
		                " from classmarks c join language_fields f on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1," . $lang . ") " .
	            		" join translation_status_fields s1 on s1.classmark_id = c.classmark_id and s1.field_id in (" . $fieldstring . ") and s1.language_id = 1 " .
	                    " left outer join translation_status_fields s2 on s2.classmark_id = s1.classmark_id and s2.field_id in (" . $fieldstring .
	                    ") and s2.language_id = " . $lang . " and s1.field_id = s2.field_id ";
	            if (!empty($showlastrevs))
	            {
	            	$sql .= "join audit_history a on a.classmark_id = c.classmark_id and a.audit_date = '" . $showlastrevs . "' ";
	            }
	            $sql .= " where active = 'Y' " .
						" and c.classmark_tag like '" . @mysql_real_escape_string($notationsearch, DBConnection::getInstance()->getConnection()) . "%' " .
						" order by c.classmark_enc_tag, f.language_id, s1.field_id";
	            #echo $sql . "<br>\n";
			}

			#echo "SQL: " . $sql . "<br>\n";

			$set_session_var = true;

        	// Now fetch all subclasses
            $nodetoclassmarks = array();
    		$inextnode = 1;
    		$bfirst = true;

            include_once("TreeRecord.php");

            $toprecord = new TreeRecord();

            StartTime($starttime);
            #echo "Getting SQL<br>\n";
    		$res = @mysql_query($sql, DBConnection::getInstance()->getConnection());
    		#echo "Got SQL<br>\n";
            EndTime($sql, $starttime);

    		if ($res)
    		{
    			#echo "SQL successful<br>\n";

    			$treerecords = array();
    	        $topclassid = 0;
    	        StartTime($starttime);
    			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
    			{
    				// Ignore the initial record - we already have it
    	            if (isset($treerecords[$row[0]]))
    	            {
    	                $record = $treerecords[$row[0]];
    	            }
    	            else
    	            {
    	                $record = new TreeRecord();
    	            }

    	            // Construct the structure
    				$record->id = $row[0];
    				$record->broader = $row[1];
    				$record->tag = trim($row[2]);
    				$record->description = $row[3];
    	            $record->title = $record->description;
    	            $record->field_id = $row[4];
    	            $record->level = $row[5];
    	            $record->headingtype = $row[6];
    	            $record->language = $row[7];
    	            $record->eng_lang_field_count += $row[9];
    	            $record->targ_lang_field_count += $row[11];
    	            switch($row[8])
    	            {
    	            	case 1:
    	            		$record->eng_lang_field_count_cap = $row[9];
    	            		break;
    	            	case 2:
    	            		$record->eng_lang_field_count_ex = $row[9];
    	            		break;
    	            	case 4:
    	            		$record->eng_lang_field_count_inc = $row[9];
    	            		break;
    	            	case 5:
    	            		$record->eng_lang_field_count_scope = $row[9];
    	            		break;
    	            	case 6:
    	            		$record->eng_lang_field_count_app = $row[9];
    	            		break;
    	            }
    	            switch($row[10])
    	            {
    	            	case 1:
    	            		$record->targ_lang_field_count_cap = $row[11];
    	            		break;
    	            	case 2:
    	            		$record->targ_lang_field_count_ex = $row[11];
    	            		break;
    	            	case 4:
    	            		$record->targ_lang_field_count_inc = $row[11];
    	            		break;
    	            	case 5:
    	            		$record->targ_lang_field_count_scope = $row[11];
    	            		break;
    	            	case 6:
    	            		$record->targ_lang_field_count_app = $row[11];
    	            		break;
    	            	default:
    	            		# Do nothing
    	            		break;
    	            }

    	            $treerecords[$record->id] = $record;
    	        }
    	        EndTime("Record population", $starttime);

    	        StartTime($starttime);
    	        @mysql_free_result($res);
    	        EndTime("Freeing MySQL results", $starttime);

                $inextnode = 0;

                StartTime($starttime);
    	        foreach($treerecords as $record)
    	        {
                    if ($showfinished == true || ($record->targ_lang_field_count < $record->eng_lang_field_count))
                    {
                    	#echo $record->tag . ": " . $record->targ_lang_field_count . " vs " . $record->eng_lang_field_count . "\n";
                        ProcessRecord($record, $nodetoclassmarks, $records, $inextnode++, $record->language);
                    }
    	        }
    	        EndTime("Processing records", $starttime);
            }
        }
        else if($notation != "")
		{
            $set_session_var = true;

			$hierarchy_string = "";

			$notation = urldecode($notation);

			$sql = "select h.hierarchy_code from classmark_hierarchy h join classmarks c on c.classmark_id = h.classmark_id where c.classmark_tag = '" . $notation . "' and c.active = 'Y'";
            #echo $sql . "<br>\n";
            StartTime($starttime);
    		$res = @mysql_query($sql, DBConnection::getInstance()->getConnection());
            EndTime($sql, $starttime);
			if ($res)
			{
				if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
				{
					$hierarchy_string = $row[0];
					#echo "Hierarchy string = " . $hierarchy_string . "<br>\n";
				}
				else
				{
					#echo "No row<br>\n";
				}
			}
			else
			{
				#echo "No results<br>\n";
			}

			@mysql_free_result($res);

            if ($hierarchy_string != "")
            {
    			# Now fetch all subclasses
    			$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, f.description, f.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type, f.language_id " .
    	                " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
    	                " join language_fields f on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1," . $lang . ") ";
            	if (!empty($showlastrevs))
            	{
            		$sql .= "join audit_history a on a.classmark_id = c.classmark_id and a.audit_date = '" . $showlastrevs . "' ";
           		}
           		$sql .=	" where c.active = 'Y' " .
    					" and h.hierarchy_code like '" . $hierarchy_string . "%' " .
    					" order by h.hierarchy_code, f.language_id";
                #echo $sql . "<br>\n";

        		$nodetoclassmarks = array();
        		$inextnode = 1;
        		$bfirst = true;

                include_once("TreeRecord.php");

                $toprecord = new TreeRecord();

                StartTime($starttime);
        		$res = @mysql_query($sql, DBConnection::getInstance()->getConnection());
                EndTime($sql, $starttime);
        		if ($res)
        		{
        			StartTime($starttime);
        	        $treerecords = array();
        	        $topclassid = 0;
        	        $rowcount = @mysql_num_rows($res);
        	        #echo $rowcount . " rows returned<br>\n";
        			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
        			{
        				// Ignore the initial record - we already have it
                        if (isset($treerecords[$row[0]]))
        	            {
        	                $record = $treerecords[$row[0]];
        	            }
        	            else
        	            {
        	                $record = new TreeRecord();
        	            }

        	            // Construct the structure
        				$record->id = $row[0];
        				$record->broader = $row[1];
        				$record->tag = trim($row[2]);
        				$record->description = $row[3];
        	            $record->title = $record->description;
        	            $record->field_id = $row[4];
        	            $record->hierarchy_code = $row[5];
        	            $record->level = $row[6];
        	            $record->headingtype = $row[7];
        	            $record->language = $row[8];

        	            $treerecords[$record->id] = $record;
        	        }
        	        EndTime("Notation subclasses", $starttime);

        	        StartTime($starttime);
        	        @mysql_free_result($res);
        	        EndTime("Freeing results", $starttime);

        			// Now fetch all subclasses
                    $fieldstring = "1,2,4,5,6";
                    $showfinished = true;
                    if (isset($_SESSION['fieldstat']) && $_SESSION['fieldstat'] != "")
                    {
                        $temp = $_SESSION['fieldstat'];
                        $temp = str_replace("9", "", $temp);
                        $temp = trim($temp, ", ");
                        if ($temp != "")
                        {
                            $fieldstring = $temp;
                        }

                        if ($temp != $_SESSION['fieldstat'])
                        {
                            $showfinished = false;
                        }
                    }

        			$sql = 	"select c.classmark_id, s1.field_id, s1.lang_field_count, s2.field_id, s2.lang_field_count, c.classmark_tag " .
        	                " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
                            " join translation_status_fields s1 on s1.classmark_id = c.classmark_id and s1.field_id in (" . $fieldstring . ") and s1.language_id = 1 " .
                            " left outer join translation_status_fields s2 on s2.classmark_id = s1.classmark_id and s2.field_id in (" . $fieldstring . ") and s2.language_id = " . $lang . " and s1.field_id = s2.field_id ";
        			if (!empty($showlastrevs))
        			{
        				$sql .= "join audit_history a on a.classmark_id = c.classmark_id and a.audit_date = '" . $showlastrevs . "' ";
        			}
        			$sql .= " where c.active = 'Y' " .
        					" and h.hierarchy_code like '" . $hierarchy_string . "%' " .
        					" order by h.hierarchy_code, s1.field_id";
        			#echo $sql . "<br>\n";

                    StartTime($starttime);
            		$res = @mysql_query($sql, DBConnection::getInstance()->getConnection());
                    EndTime($sql, $starttime);
            		if ($res)
            		{
            			StartTime($starttime);
            			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
            			{
							$record = null;
                            if ($toprecord->id == $row[0])
                            {
                                $record = $toprecord;
                            }
                            else if (isset($treerecords[$row[0]]))
            	            {
            	                $record = $treerecords[$row[0]];
                            }

							if (!is_null($record))
							{
								$record->eng_lang_field_count += $row[2];
								$record->targ_lang_field_count += $row[4];
								switch($row[1])
								{
									case 1:
										$record->eng_lang_field_count_cap = $row[2];
										break;
									case 2:
										$record->eng_lang_field_count_ex = $row[2];
										break;
									case 4:
										$record->eng_lang_field_count_inc = $row[2];
										break;
									case 5:
										$record->eng_lang_field_count_scope = $row[2];
										break;
									case 6:
										$record->eng_lang_field_count_app = $row[2];
										break;
								}
								switch($row[3])
								{
									case 1:
										$record->targ_lang_field_count_cap = $row[4];
										break;
									case 2:
										$record->targ_lang_field_count_ex = $row[4];
										break;
									case 4:
										$record->targ_lang_field_count_inc = $row[4];
										break;
									case 5:
										$record->targ_lang_field_count_scope = $row[4];
										break;
									case 6:
										$record->targ_lang_field_count_app = $row[4];
										break;
								}

								if ($toprecord->id == $row[0])
									$toprecord = $record;
								else
									$treerecords[$row[0]] = $record;
							}
                        }
                        EndTime("Statistics", $starttime);
                    }
                    else
                    {
                        echo @mysql_error($res);
                    }

                    StartTime($starttime);
        	        @mysql_free_result($res);
                    EndTime("Freeing results", $starttime);

                    //if($toprecord->id != 0)
                    //    ProcessRecord($toprecord, $nodetoclassmarks, $records, 0, $toprecord->language);

                    $inextnode = 0;

                    StartTime($starttime);
        	        foreach($treerecords as $record)
        	        {
                        if ($showfinished == true || ($record->targ_lang_field_count < $record->eng_lang_field_count))
                        {
                            ProcessRecord($record, $nodetoclassmarks, $records, $inextnode++, $record->language);
                        }
        	        }
        	        EndTime("Processing records", $starttime);
                }
            }
            else
            {
            	#echo "No hierarchy code<br>\n";
            }
        }

		if (count($records) > 0)
		{
			StartTime($starttime);

			$item_id = 1;

			foreach($records as $record)
			{
				$nodetype = "leftlistitem";
				if ($rtl)
				{
					$nodetype .= " rtl";
				}
				$returnstring .= "<div id=\"leftlistitem_" . $item_id++ . "\" class=\"" . $nodetype. "\">";
				$returnstring .= $record;
				$returnstring .= "</div>\n";
			}

            EndTime("Generating string", $starttime);
        }

	    if ($set_session_var == true)
        {
            $_SESSION['search_results'] = $returnstring;
        }

	    #return count($records) . " records<br>\n" . $returnstring;
	    return $returnstring;
	}
?>