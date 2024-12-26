<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */
 	function GetHierarchyTree()
 	{
		include_once("DBConnectInfo.php");
		$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
		mysql_select_db (DBDATABASE);
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

 		$returnstring = "";
	
	    class TreeRecord
	    {
	        var $id = 0;
	        var $broader = "";
	        var $tag = "";
	        var $description = "";
	        var $title = "";
	        var $field_id = 0;
	        var $hierarchy_code = "";
	        var $level = 0;
	        var $headingtype = 0;
	        var $language = 0;
	    };
	
		$topid = "";
	    $toptag = "";
	    $toplevelfetch = false;
	    $lang = 1;

		$if_expandall = "expand all";
		$if_collapseall = "collapse all";
		$if_top = "TOP";
	    //@mysql_query("SET character_set_results = 'iso-8859-1', character_set_client = 'iso-8859-1', character_set_connection = 'iso-8859-1', character_set_database = 'utf8', character_set_server = 'utf8'");
	    //@mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
		$hierarchy_level = 0;
		$hierarchy_code = "";
		$rootclassmark_tag = "";
		$rootdescription = "";
		$broader_category = 0;
	    $rootclassmark_id = 0;
	
		if ($topid != "" || $toptag != "")
		{
			// Now fetch all subclasses
			$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, f.description, f.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type, f.language_id " .
	                " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
	                " join language_fields f" .
					" on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1," . $lang . ") " .
					" where active = 'Y' " . // and c.hierarchy_level > " . $hierarchy_level . " and c.hierarchy_level < " . ($hierarchy_level+2) .
					" order by h.hierarchy_code, f.language_id";
			//$returnstring .= $sql . "<br>";
		}
		else
		{
			// Now fetch all subclasses
			$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, f.description, f.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type, f.language_id " .
	                " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
	                " join language_fields f" .
					" on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1," . $lang . ") " .
					" where active = 'Y' " .
					" order by h.hierarchy_code, f.language_id";
			//$returnstring .= $sql . "<br>";
			
			$toplevelfetch = true;
			/*
			// Retrieve all the root level classmarks
			$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, l.description, l.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type, l.language_id " .
	                " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id and c.hierarchy_level in (0,1) " .
	                " join language_fields l " .
	                " on c.classmark_id = l.classmark_id and l.field_id = 1 and l.language_id in (1, " . $lang . ") " .
	                " order by h.hierarchy_code, l.language_id";
	        //$returnstring .= $sql . "<br>\n";
	        */
		}

	
		$nodetoclassmarks = array();
		$records = array();
		$inextnode = 1;
		$bfirst = true;

		$res = @mysql_query($sql);
		if ($res)
		{
	        $treerecords = array();
	        $topclassid = 0;
			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
			{
			    //$returnstring .= $row . "<br>\n";
				// Ignore the initial record - we already have it
	
				if ($toplevelfetch == false && $bfirst)
				{
	                $topclassid = $row[0];
					$bfirst = false;
					continue;
				}
	
	            if ($row[0] == $topclassid)
	                continue;
	
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
	
	        @mysql_free_result($res);
	
	
	        //$returnstring .= "records<br>\n";
	
	
	        foreach($treerecords as $record)
	        {
	            $is_href = false;
	            $recordline = "";
	
				// First of all add this record into the classmark id/ node id map
				$nodetoclassmarks[$record->id] = $inextnode;
	
				// See if we have a node if for the broader category
				$parentnode = 0;
				if (isset($nodetoclassmarks[$record->broader]))
				{
					$parentnode = $nodetoclassmarks[$record->broader];
				}
	
				$recordline .= "d.add(" . $inextnode++ . "," . $parentnode .",'";
	            $recordline .= $record->tag . "','";
	            include_once("getdisplaynotation.php");
	            $dn = GetDisplayNotation($record->tag, false);
	            //$returnstring .= "DN=" . $dn . "<br>\n";
	            $recordline .= $dn;
				$recordline .= "</span>&nbsp;&nbsp;";
	
	            if ($record->language != $lang)
	            {
	                $recordline .= "<span style=\"color: #7b4b0e\">" . addslashes($record->description) . "</span>";
	            }
	            else
	            {
	                $recordline .= addslashes($record->description);
	            }
	
	            $recordline .= "','";
	
	            if ($record->headingtype == 1 || $record->headingtype == 2 || $record->headingtype == 8)
	            {
	                $recordline .= $record->tag . "'";
	                $is_href = true;
	            }
	            else if ($record->headingtype == 13 && $record->tag != "--")
	            {
	                if ($rootdescription == "")
	                {
	                    $recordline .= "'";
	                }
	                else
	                {
	                    $recordline .= $record->tag . "'";
	                    $is_href = true;
	                }
	            }
	            else
	            {
//					if ($topid == "" || $record->tag == "--")
//					{
//						$recordline .= "index.php?id=" . $record->id . "&lang=" . $langcode . "'";
//					}
//					else
					{
						$recordline .= $record->tag . "'";
	                    $is_href = true;
					}
	            }
	
				$recordline .= ",'" . addslashes($record->title) . "','','','',";
//	            if ($toplevelfetch)
//	            {
//	                $recordline .= "true";
//	            }
//	            else
	            {
	                $recordline .= "false";
	            }
	
	            if ($is_href)
	            {
	                $recordline .= ",true";
	            }
	            else
	            {
	                $recordline .= ",false";
	            }
	            $recordline .= ");\n";
	
				array_push($records, $recordline);
	        }
	
	        //$returnstring .= "international<br>\n";
	
			if (count($records) > 0)
			{
	            $returnstring .= "<div id=\"openclosemenu\"><a href=\"javascript: d.openAll();\">&nbsp;" . $if_expandall . "</a> | <a href=\"javascript: d.closeAll();\">" . $if_collapseall . "</a></div>\n";
	            $returnstring .= "<div id=\"classtree\">\n";
				$returnstring .= "<script type=\"text/javascript\">\n";
				$returnstring .= "<!--\n";
				$returnstring .= "d = new dTree('d');\n";
	
	            if (!empty($topid))
	            {
	                $returnstring .= "d.config.hrefIsClick = true;\n";
	            }
	
	            $display_tag = "";
	            $rootclass = false;
	            if ($rootdescription == "")
				{
	                $rootclass = true;
	                $rootdescription = $if_top;
					$rootclassmark_tag = "";
				}
				else
				{
	                include_once("checkauxtag.php");
	                $rootclassmark_tag = trim($rootclassmark_tag);
	                $display_tag = CheckAuxTag($rootclassmark_tag);
				}
	
	            //if (strlen($rootdescription) > 45)
	            //{
	            //    $rootdescription = substr($rootdescription, 0, 45) . "...";
	            //}
	
				$returnstring .= "d.add(0,-1,'" . $rootclassmark_tag . "','<span class=\"nodetag\">" . $display_tag . "</span>";
	            if (strlen($display_tag) > 0)
	            {
	                $returnstring .= "&nbsp;&nbsp;";
	            }
	
	            $returnstring .= $rootdescription . "','" . $rootclassmark_tag . "'";
	
	            if ($rootclassmark_id > 0)
	            {
	                $returnstring .= ",'','','','',false";
	            }
	
	            if (!$rootclass)
	            {
	                $returnstring .= ",true";
	            }
	            else
	            {
	                $returnstring .= ",false";
	            }
	
	            $returnstring .= ");\n";
				foreach($records as $record)
				{
					$returnstring .= $record;
				}
	
				$returnstring .= "d.config.useSelection = false;\n";
				$returnstring .= "d.config.inOrder = true;\n";
				$returnstring .= "d.config.useIcons = false;\n";
				$returnstring .= "document.write(d);\n";
				$returnstring .= "//-->\n";
				$returnstring .= "</script>\n";
	            $returnstring .= "</div>\n";
	        }

	        //$returnstring .= "ended<br>\n";
	        
		}

		@mysql_close($dbc);
			    
	    return $returnstring;
	}

?>