<?php
    ## getrecord.php is invoked by an AJAX call when the user clicks on a classmark
    ## in the classmark tree on the UDC Summary page.  It returns the details of the
    ## selected class record and all its children, and displays these details in the
    ## righthand pane

	define("DEBUGON", false);

	function DebugEcho($output)
	{
		if (DEBUGON == true)
		{
			echo $output . "<br>\n";
		}
	}

	if (!isset($_SESSION))
		session_start();

    # Maximum reference description length, beyond which the description is truncated 
    # and appended with "..."	
    
    define("MAX_REF_DESC_LEN", 55);
    
    # Maximum number of elements in navigaton path
    define("MAX_NAVCOUNT", 50);
        
    class gr_classmark
    {
        var $classmark_id = 0;
        var $caption = "";
        var $broader_category = 0;
        var $classmark_tag = "";        
        var $hierarchy_level = 0;
        var $hierarchy_code = "";
        var $scope_note = "";
        var $app_note = "";
        var $including = "";
        var $derivedfrom = "";
        var $dfdesc = "";
        var $references = array();
        var $examples = array();
        var $example_tags = array();
        var $mappings = array();
    };

	include_once("specialchars.php");
	include_once("getdisplaynotation.php");
	include_once('managenavpath.php');

	require_once("DBConnectInfo.php");
	require_once('DBConnection.php');

	DebugEcho("Connecting to " . DBDATABASE);

	$dbc = DBConnection::getConnection(DBHOST, DBUSER, DBPASS, DBDATABASE);

    $dbc->exec("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

	DebugEcho("Connected successfully");

    $id = "";
    $backid = "";
    $subclasses = true;
    $lang = 1;
	$rtl = false;
    
    # id = classmark notation
    if (isset($_GET['id']))
    {
    	$id=$_GET['id'];
    }

    # lang = language the classmark should be displayed in
    if (isset($_GET['lang']))
    {
    	$lang=$_GET['lang'];
    }

	# lang = language the classmark should be displayed in
	if (isset($_GET['rtl']))
	{
		$rtl = ($_GET['rtl'] == 'Y');
	}

    if ($id == "")
    {
    	return "";
    }
    
    # Not used yet, but if set to 'N' the right hand will only display the 
    # selected record and not include its children
    if (isset($_GET['subclasses']))
    {
    	$subclasses = (strcmp(strtoupper(trim($_GET['subclasses'])), "Y") == 0);
    }

    if (isset($_GET['clearpath']))
    {
        $_SESSION['navpath'] = $id;
        $_SESSION['navpos'] = 0;          
    }
    else
    {
        if (isset($_GET['navpos']))
        {
            ManageNavPath(count(explode("#", $_SESSION['navpath'])), $_GET['navpos']); 
        }
        else
        {
            $navarray = explode("#", $_SESSION['navpath']);
            if (count($navarray) >= MAX_NAVCOUNT)
            {
                $navarray[MAX_NAVCOUNT-1] = $id;
                $_SESSION['navpath'] = implode("#", $navarray);
            }
            else
            {
                $newnavpath = AddNavPath($id, $_SESSION['navpath'], $_SESSION['navpos']);
                $_SESSION['navpath'] = $newnavpath;
                $_SESSION['navpos'] = count(explode("#", $_SESSION['navpath']))-1;        
            }
        }    
    }
    # Remember this record ID in case we switch languages and need to display it again
	$_SESSION['preserverecord'] = $id;
	
    $if_including = "Including";
    $if_scopenote = "Scope Note";
    $if_appnote = "Application Note";
    $if_derivedfrom = "Derived from";
    $if_examples = "Examples of Combination(s)";

	DebugEcho("Selecting interface fields");

    $sql =  "select including, scopenote, appnote, derivedfrom, examples" . 
            " from interface_fields where language_id = " . $lang;  

	foreach ($dbc->query($sql) as $row)
	{
        $if_including = $row[0];
        $if_scopenote = $row[1];
        $if_appnote = $row[2];
        $if_derivedfrom = $row[3];
        $if_examples = $row[4];
	}

	DebugEcho('Fetched');

    # Fetch class details for this class and potentially all subclasses 
    if ($subclasses)
    {
		DebugEcho("Fetching subclasses for $id");

        $sql =  "select c.classmark_id, c.classmark_tag, c.broader_category, c.hierarchy_level, h.hierarchy_code, c.derived_from " .
                " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
                "where h.hierarchy_code like (select concat(h1.hierarchy_code, '%') from classmark_hierarchy h1 join classmarks c1 on h1.classmark_id = c1.classmark_id and c1.classmark_tag = '" . $id . 
                "' and c1.deleted = 'N') " .
                "and c.deleted = 'N' order by h.hierarchy_code";

		DebugEcho($sql);
    }
    else
    {
		DebugEcho('Fetching normal classes for $id');

        $sql =  "select c.classmark_id, c.classmark_tag, c.broader_category, c.hierarchy_level, h.hierarchy_code, c.derived_from from classmarks c join classmark_hierarchy h ".
                "on c.classmark_id = h.classmark_id where c.classmark_tag = '" . $id . "' ";
    }

    $rootclassmark_id = 0;
    $subclassarray = array();
    $rootclass = true;

	DebugEcho("Fetching results");

	$stmt = $dbc->query($sql, PDO::FETCH_BOTH);
	foreach ($stmt as $row)
    {
        $record = new gr_classmark();
        $record->classmark_id = $row[0];
        if ($rootclass)
        {
            $rootclassmark_id = $record->classmark_id;
            $rootclass = false;
        }
        $record->classmark_tag = $row[1];
        $record->broader_category = $row[2];
        $record->hierarchy_level = $row[3];
        $record->hierarchy_code = $row[4];
        $record->derivedfrom = $row[5];
        if (count($row) > 6)
            $record->dfdesc = $row[6];

        $subclassarray[$record->classmark_id] = $record;
    }

	DebugEcho("End of results");

	if ($stmt->rowCount() == 0)
	{
		DebugEcho("No rows");
	}

    # For every record loaded, load extra details. Construct a list of classmark_ids
    $classmark_id_list = "";
    
    foreach($subclassarray as $record)
    {
        if (!empty($classmark_id_list))
        {
            $classmark_id_list .= ",";
        }
        $classmark_id_list .= $record->classmark_id;
    }
    
    # Load fields that can be expressed in different languages:
    # - Caption
    # - Including
    # - Scope Note
    # - Application Note
    # - Examples of combination description

    $sql =  "select f.classmark_id, f.description, f.field_id, f.seq_no, f.language_id from language_fields f where f.language_id";
    if ($lang != 1)
    {
        $sql .= " in (1, ".$lang . ") ";
    }
    else
    {
        $sql .= " = " . $lang;
    } 
    $sql .= " and f.classmark_id in (" . $classmark_id_list . ") order by f.classmark_id, f.language_id, f.field_id, f.seq_no";

	DebugEcho($sql);

	$gotlang = false;

	foreach ($dbc->query($sql) as $row)
    {
        $this_id = $row[0];
        if (isset($subclassarray[$this_id]) == FALSE)
        {
            continue;
        }

        $record = $subclassarray[$this_id];

        $field_id = $row[2];
        $description = $row[1];
        $sequence_no = $row[3];
        $language = $row[4];

        switch($field_id)
        {
            case "1":
                # Caption
                $record->caption = $language . "~" . $description;
                break;
            case "4":
                # Including
                $record->including = $language . "~" . $description;
                break;
            case "5":
                # Scope note
                $record->scope_note = $language . "~" . $description;
                break;
            case "6":
                # App Note
                $record->app_note = $language . "~" . $description;
                break;
            case "2":
                # Example of combination description
                $record->examples[$sequence_no] = $language . "~" . $description;
                break;
            default:
            break;
        }

        $subclassarray[$this_id] = $record;
    }

    # Load examples of combination notations
    $sql = "select e.classmark_id, e.field_type, e.seq_no, e.tag, c.classmark_tag, e.encoded_tag from example_classmarks e join classmarks c on e.classmark_id = c.classmark_id where e.classmark_id in (" . 
            $classmark_id_list . ") order by e.classmark_id, e.seq_no";

	DebugEcho($sql);

	foreach ($dbc->query($sql) as $row)
    {
        $this_id = $row[0];
        if (isset($subclassarray[$this_id]) == FALSE)
        {
            continue;
        }

        $record = $subclassarray[$this_id];
        $tag = "";
        $ex_seq = $row[2];
        $ex_tag = $row[3];
        $ex_class_tag = $row[4];

        switch($row[1])
        {
            case "a":
                # Direct addition
                $tag = $ex_class_tag . $ex_tag;
                break;
            case "b":
                # Colon combination
                $tag = $ex_class_tag . ":" . $ex_tag;
                break;
            default:
                # Default = replacement
                $tag = $ex_tag;
                break;
        }

        $record->example_tags[$ex_seq] = $tag;

        $subclassarray[$this_id] = $record;
    }

    # Load references
    $sql =  "select r.classmark_id, r.notation, f.description, f.language_id from classmark_refs r join classmarks c on c.classmark_tag = r.notation left outer join language_fields f " .
            "on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id";
    if ($lang != 1)
    {
        # Fetch the English and target language (in case the target reference description does not exist)
        $sql .= " in (1, ".$lang . ")";
    }
    else
    {
        $sql .= " = " . $lang;
    } 
    $sql .= " where r.classmark_id in (" .  $classmark_id_list . ") order by r.classmark_id, f.language_id, r.sequence_no";

	DebugEcho($sql);

	foreach ($dbc->query($sql) as $row)
    {
        $this_id = $row[0];

        if (isset($subclassarray[$this_id]) == FALSE)
        {
            continue;
        }

        $record = $subclassarray[$this_id];

        $tag = $row[1];
        $description = $row[2];

        $record->references[$tag] = $row[3] . "~" . $description;

        $subclassarray[$this_id] = $record;
    }

    # Load captions of broader categories
    $sql =  "select c.classmark_tag, f.description, f.language_id from classmarks c join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id";
    if ($lang != 1)
    {
        $sql .= " in (1, ".$lang . ") ";
    }
    else
    {
        $sql .= " = " . $lang;
    } 
    $sql .= " where c.classmark_id = (select broader_category from classmarks c1 where c1.classmark_id = " . $rootclassmark_id . ") order by f.language_id";

	DebugEcho($sql);

	$broader_tag = "";
    $broader_desc = "";

	foreach ($dbc->query($sql) as $row)
    {
	    $broader_tag = $row[0];
        $broader_desc = $row[2] . "~" . $row[1];
    }

    $firstrecord = true;
    
    $result = "";

	# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    # OK, output the retrieved records in the right hand pane
	# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    # First, show any navigation trail
    $navpath = GetNavPathString($rtl);
    if (empty($navpath) == false)
    {
        echo $navpath;
    }    

	# Now the class and any subclasses
    include_once("checkauxtag.php");
    include_once("getlangvalues.php");
    if ($broader_tag != "")
    {
        $broader_tag = CheckAuxTag($broader_tag);
        
        // Add the broader category
        $result .= "<div class=\"record\">";
        $result .= "<div class=\"headerrow\"><div class=\"notation\">";
        $fontweight = "normal";
 
        $result .= "<span style=\"font-weight: " . $fontweight . "; color: #aaaaaa;\">" . $broader_tag . "</span></div><div class=\"caption\"><span style=\"font-weight: " . $fontweight . "; color: #aaaaaa;\">";
        $fielddesc = "";
        GetLanguageValues($broader_desc, $lang, $fielddesc);
        $result .= $fielddesc;
        $result .= "</span>"; 
        $result .= "</div></div></div>\n";
        
        $firstrecord = false;
    }    
        
    $toprecord = true;
    foreach($subclassarray as $record)
    {
        if ($firstrecord != true)
        {
            $result .= "<div class=\"recordseparator\">&nbsp;</div>";
        }            
        else
        {
            $firstrecord = false;
        }
        
        $result .= "<div class=\"record\">";
        $result .= "<div class=\"headerrow\">";

		$result .= "<div class=\"notation\">";
        $fontweight = "normal";
        if ($toprecord == true)
        {
            $fontweight = "bold";
            $toprecord = false;
        }
        
        # Retrieve the display notation in a web-displayable format
        $displaynotation = GetDisplayNotation($record->classmark_tag, true);
        $result .= "<span style=\"font-weight: " . $fontweight . "\">" . $displaynotation . "</span></div><div class=\"caption\"><span style=\"font-weight: " . $fontweight . "\">";
        $fielddesc = "";  
        if (GetLanguageValues($record->caption, $lang, $fielddesc) == false)
		{
          	$result .= "<span style=\"color: #7b4b0e;";
			if ($rtl == true)
			{
				$result .= " unicode-bidi: bidi-override; direction: ltr; text-align: right";
			}
			$result .= "\">" . $fielddesc . "</span>";
        }
        else
        {
            $result .= $fielddesc;
        } 
        $result .= "</span>";
         
        if (!empty($record->including))
        {
			$fielddesc = "";
			$thislang = GetLanguageValues($record->including, $lang, $fielddesc);
			if ($rtl == false || $thislang == true)
			{
				$result .= "<div class=\"including\">";
				$result .= $if_including . ": ";
				if ($thislang == false)
				{
					$result .= "<span style=\"color: #7b4b0e\">" . $fielddesc . "</span>";
				}
				else
				{
					$result .= $fielddesc;
				}
				$result .= "</div>";
			}
        }
        $result .= "</div></div>\n";
        
        if (!empty($record->scope_note)) 
        {
			$fielddesc = "";
			$thislang = GetLanguageValues($record->scope_note, $lang, $fielddesc);
			if ($rtl == false || $thislang == true)
			{
				$result .= "<div class=\"recordrow\">";
				$temp_notation = "<div class=\"label\">&nbsp;</div>";
				$temp_langfield = "<div class=\"recordvalue\">";
				$temp_langfield .= $if_scopenote . ": ";
				if ($thislang == false)
				{
					$temp_langfield .= "<span style=\"color: #7b4b0e; font-style: italic;\">" . $fielddesc . "</span>";
				}
				else
				{
					$temp_langfield .= "<span style=\"font-style: italic;\">" . $fielddesc . "</span>";
				}
				$temp_langfield .= "</div>";

				$result .= $temp_notation . $temp_langfield;
				$result .= "</div>\n";
			}
        }

        if (!empty($record->app_note)) 
        {
			$fielddesc = "";
			$thislang = GetLanguageValues($record->app_note, $lang, $fielddesc);
			if ($rtl == false || $thislang == true)
			{
				$result .= "<div class=\"recordrow\"><div class=\"label\">&nbsp;</div><div class=\"recordvalue\">";
				$result .= $if_appnote . ": ";
				//echo "Before: " . $record->app_note . "<br>\n";
				if ($thislang == false)
				{
					$result .= "<span style=\"color: #7b4b0e; font-style: italic;\">" . specialchars($fielddesc) . "</span>";
				}
				else
				{
					$result .= "<span style=\"font-style: italic;\">" . specialchars($fielddesc) . "</span>";
				}
				$result .= "</div></div>\n";
			}
        }
    
        if ($record->derivedfrom != "")
        {
            $result .= "<div class=\"recordrow\">";
            $result .= "<div class=\"label\">&nbsp;</div>";
            $result .= "<div class=\"recordvalue\">";
    
            $result .= "<table width=\"100%\">";  
            $result .= "<tr>";
            $result .= "<td class=\"extag\">";
            $result .= $if_derivedfrom . ": ";
            $result .= "</td><td class=\"exdesc\"><a href=\"#\" onclick=\"javascript:openrecord('". $record->derivedfrom . "', -1, false, '" . ($rtl == true ? "Y" : "N") . "')\">";
            $result .= $record->derivedfrom;
            $result .= "</a></td>";
            $result .= "</tr>";   
            $result .= "</table></div>";
            $result .= "</div>";
        }

		$examplestring = "";
        if (count($record->examples) > 0)
        {
            foreach($record->examples as $seq => $desc)
            {
				$fielddesc = "";
				$thislang = GetLanguageValues($desc, $lang, $fielddesc);
				if ($rtl == false || $thislang == true)
				{
					$examplestring .= "<tr>";
					$examplestring .= "<td class=\"extag\">". $record->example_tags[$seq] . "</td><td class=\"exdesc\">";
					if ($thislang == false)
					{
						$examplestring .= "<span style=\"color: #7b4b0e\">" . $fielddesc . "</span>";
					}
					else
					{
						$examplestring .= $fielddesc;
					}
					$examplestring .= "</td>";
					$examplestring .= "</tr>";
				}
            }
        }

		if (!empty($examplestring))
		{
			$result .= "<div class=\"recordrow\">";
			$result .= "<div class=\"label\">&nbsp;</div>";
			$result .= "<div class=\"recordvalue\">";
			$result .= "<span style=\"font-size: 0.85em;\">" . $if_examples . "</span>: ";
			$result .= "</div>";
			$result .= "<div class=\"label\">&nbsp;</div>";
			$result .= "<div class=\"recordvalue\">";
			$result .= "<table width=\"100%\">";
			$result .= $examplestring;
			$result .= "</table></div>";
			$result .= "</div>";
		}

		$refstring = "";
        if (count($record->references) > 0)
        {
            # If reference captions are longer than the display maximum allowed,
            # truncate the description and append with "..."
            foreach($record->references as $tag => $desc)
            {                
                if (strlen($desc) > MAX_REF_DESC_LEN)
                {
                    $foundsep = false;
                                       
                    for ($i=MAX_REF_DESC_LEN-1; $i >=0; $i--)
                    {
                        if (substr($desc, $i, 1) == ".")
                        {
                            $desc = substr($desc, 0, $i);
                            $foundsep = true;
                            break;
                        }
                    }
                    
                    if ($foundsep == false)
                    {
                        for ($i=MAX_REF_DESC_LEN-1; $i >=0; $i--)
                        {
                            if (substr($desc, $i, 1) == " ")
                            {
                                $desc = substr($desc, 0, $i);
                                $foundsep = true;
                                break;
                            }
                        }                        
                    }
                    
                    $desc .= "...";
                }

				$fielddesc = "";
				$thislang = GetLanguageValues($desc, $lang, $fielddesc);
				if ($rtl == false || $thislang == true)
				{
					$refstring .= "<tr>";
					$refstring .= "<td class=\"reftag\" valign=\"middle\">";

					if ($rtl == true)
					{
						$refstring .= "<img src=\"../images/rtl_ref.png\" align=\"right\"> ";
						$refstring .= "<a href=\"#\" onclick=\"javascript:openrecord('" . $tag. "', -1, false, 'Y')";
					}
					else
					{
						$refstring .= "<img src=\"../images/ref.png\" align=\"left\"> ";
						$refstring .= "<a href=\"#\" onclick=\"javascript:openrecord('" . $tag. "', -1, false, 'N')";
					}

					$refstring .= "\">";
					$refstring .= $tag;
					$refstring .= "</a>";
					$refstring .= "</td><td class=\"exdesc\">";

					if ($thislang == false)
					{
						$refstring .= "<span style=\"color: #7b4b0e\">" . $fielddesc . "</span>";
					}
					else
					{
						$refstring .= $fielddesc;
					}

					$refstring .= "</td>";
					$refstring .= "</tr>";
				}
            }
        }

		if (!empty($refstring))
		{
			$result .= "<div class=\"recordrow\">";
			$result .= "<div class=\"label\">&nbsp;</div>";
			$result .= "<div class=\"recordvalue\">";
			$result .= "<table width=\"100%\">";
			$result .= $refstring;
			$result .= "</table></div>";
			$result .= "</div>";
		}

		$result .= "</div>\n";
    }

    echo $result;
?>