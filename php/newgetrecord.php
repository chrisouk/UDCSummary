<?php
    session_start();

    require_once("DBConnectInfo.php");
    include_once("specialchars.php");
    include_once("getdisplaynotation.php");
    
    define(MAX_REF_DESC_LEN, 55);
    
    class classmark
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

    //echo "classmark end<br>\n";f
    
    $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
    @mysql_select_db (DBDATABASE);
    
    @mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    //@mysql_query("SET character_set_results = 'iso-8859-1', character_set_client = 'iso-8859-1', character_set_connection = 'iso-8859-1', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);        
    $id = "";
    $backid = "";
    $subclasses = true;
    $lang = 1;
    $savepath = false;
    $truncpath = false;
    $direction = "";
    
    if (isset($_GET['id']))
    {
    	$id=$_GET['id'];
        //echo "id=" . $_GET['id'] . "<br>\n";
    }

    if (isset($_GET['lang']))
    {
    	$lang=$_GET['lang'];
        //echo "Language=" . $lang. "<br>\n";
        //echo "id=" . $_GET['id'] . "<br>\n";
    }

    // Do we save the current navigation path (or clear it)? 
    if (isset($_GET['savepath']))
    {
    	$savepath=true;
    }
    
    if (isset($_GET['truncpath']))
    {
    	$truncpath=true;
    }
    
    if (isset($_GET['direction']))
    {
    	$direction=$_GET['direction'];
    }
    

    // Do we truncate the current navigation path from this point (if we're
    // not moving using Prev/Next but clicking on links for instance)?
    if (isset($_GET['truncpath']))
    {
    	$truncpath=true;
    }
        
    if (isset($_GET['subclasses']))
    {
    	$subclasses = (strcmp(strtoupper(trim($_GET['subclasses'])), "Y") == 0);
    }
    
    if ($subclasses)
    {
        $sql =  "select c.classmark_id, c.classmark_tag, c.broader_category, c.hierarchy_level, h.hierarchy_code, c.derived_from, f.description " .
                " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
                " left outer join language_fields f on f.classmark_id = (select c2.classmark_id from classmarks c2 where c2.classmark_tag = c.derived_from and c2.deleted = 'N') " .
                "where h.hierarchy_code like (select concat(h1.hierarchy_code, '%') from classmark_hierarchy h1 join classmarks c1 on h1.classmark_id = c1.classmark_id and c1.classmark_tag = '" . $id . 
                "' and c1.deleted = 'N') " .
                "and c.deleted = 'N' order by h.hierarchy_code";
    }
    else
    {                   
        $sql =  "select c.classmark_id, c.classmark_tag, c.broader_category, c.hierarchy_level, h.hierarchy_code, c.derived_from from classmarks c join classmark_hierarchy h ".
                "on c.classmark_id = h.classmark_id where c.classmark_tag = '" . $id . "' ";
    }

//    $start=explode(' ',microtime());
    $res = @mysql_query($sql, $dbc);
//    $end = explode(' ',microtime());
//    $exec_time = $end[1]+$end[0] - $start[1] - $start[0];
    //echo $sql . "<br>\n";     
//    echo "query time: " . $exec_time . "<br><br>\n";

    //echo $sql . "<br>\n";                     

    // Fetch class details for this class and potentially all subclasses (if requested by the
    // calling function)
    
    $rootclassmark_id = 0;
    $subclassarray = array();
    $rootclass = true;
    
    $rowcount=0;
    if ($res)
    {
    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
        {
            $record = new classmark();
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
            $record->dfdesc = $row[6];
            $subclassarray[$record->classmark_id] =  $record;
        }
    }

    @mysql_free_result($res);

    // For every record loaded, load extra details
    // Construct a list of classmark_ids
    $classmark_id_list = "";
    
    foreach($subclassarray as $record)
    {
        if (!empty($classmark_id_list))
        {
            $classmark_id_list .= ",";
        }
        $classmark_id_list .= $record->classmark_id;
    }
    
    // $sql = "select f.description, f.field_id, f.seq_no from language_fields f where f.language_id = 1 and f.classmark_id = " . $record->classmark_id . " order by f.field_id, f.seq_no";
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
    //echo $sql . "<br>\n";   
//    $start=explode(' ',microtime());
    $res = @mysql_query($sql, $dbc);
//    $end = explode(' ',microtime());
//    $exec_time = $end[1]+$end[0] - $start[1] - $start[0];
//    echo $sql . "<br>\n";     
//    echo "query time: " . $exec_time . "<br><br>\n";
            
    if ($res)
    {
    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
        {
            $this_id = $row[0];
            if (array_key_exists($this_id, $subclassarray) == FALSE)
            {
                echo "No match for " . $this_id . "<br>\n";
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
                    // Caption
                    //echo "adding caption: " . $description . "<br>\n";
                    $record->caption = $description;
                    break;
                case "4":
                    // Including
                    //echo "adding including: " . $description . "<br>\n";                    
                    $record->including = $description;
                    break;
                case "5":
                    // Scope note
                    //echo "adding scope note: " . $description . "<br>\n";                    
                    $record->scope_note = $description;
                    break;
                case "6":
                    // App Note
                    //echo "adding app note: " . $description . "<br>\n";                    
                    $record->app_note = $description;
                    break;
                case "2":
                    // Example description
                    //echo "adding example: " . $description . " [" . $language . "]<br>\n";                
                    $record->examples[$sequence_no] = $description;
                    break;
                default:
                break;
            }
            
            $subclassarray[$this_id] = $record;
        }
        //$result = "<table width=\"100%\"><tr><td width=\"10%\">" . $row[1] . "</td><td width=\"90%\">" . $row[2] . "</td></tr></table>";
    }

    @mysql_free_result($res);

    $sql = "select e.classmark_id, e.field_type, e.seq_no, e.tag, c.classmark_tag, e.encoded_tag from example_classmarks e join classmarks c on e.classmark_id = c.classmark_id where e.classmark_id in (" . 
            $classmark_id_list . ") order by e.classmark_id, e.seq_no";
    //echo $sql . "<br>\n";
//    $start=explode(' ',microtime());
    $res = @mysql_query($sql, $dbc);
//    $end = explode(' ',microtime());
//    $exec_time = $end[1]+$end[0] - $start[1] - $start[0];
//    echo $sql . "<br>\n";     
//    echo "query time: " . $exec_time . "<br><br>\n";                        

	if ($res)
    {
    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
        {
            $this_id = $row[0];
            if (array_key_exists($this_id, $subclassarray) == FALSE)
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
                    $tag = $ex_class_tag . $ex_tag;
                    //echo "Tag: " . $tag . "<br>\n";
                    break;
                case "b":
                    $tag = $ex_class_tag . ":" . $ex_tag;
                    //echo "Tag: " . $tag . "<br>\n";
                    break;
                default:
                    $tag = $ex_tag;
                    //echo "Tag: " . $tag . "<br>\n";
                    break;
            }

            $record->example_tags[$ex_seq] = $tag;
            
            $subclassarray[$this_id] = $record;            
        }
        //$result = "<table width=\"100%\"><tr><td width=\"10%\">" . $row[1] . "</td><td width=\"90%\">" . $row[2] . "</td></tr></table>";
        @mysql_free_result($res);
    }
    
    $sql =  "select r.classmark_id, r.notation, f.description from classmark_refs r join classmarks c on c.classmark_tag = r.notation left outer join language_fields f " .
            "on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id";
    if ($lang != 1)
    {
        $sql .= " in (1, ".$lang . ")";
    }
    else
    {
        $sql .= " = " . $lang;
    } 
    $sql .= " where r.classmark_id in (" .  $classmark_id_list . ") order by r.classmark_id, f.language_id, r.sequence_no";
    
//    $start=explode(' ',microtime());
    $res = @mysql_query($sql, $dbc);
//    $end = explode(' ',microtime());
//    $exec_time = $end[1]+$end[0] - $start[1] - $start[0];
//    echo $sql . "<br>\n";     
//    echo "query time: " . $exec_time . "<br><br>\n";

    if ($res)
    {
    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
        {
            $this_id = $row[0];
            
            if (array_key_exists($this_id, $subclassarray) == FALSE)
            {
                continue;
            }

            $record = $subclassarray[$this_id];
                        
            $tag = $row[1];
            $description = $row[2];
            
            $record->references[$tag] = $description;
            
            $subclassarray[$this_id] = $record;
        }
        //$result = "<table width=\"100%\"><tr><td width=\"10%\">" . $row[1] . "</td><td width=\"90%\">" . $row[2] . "</td></tr></table>";
    }

    @mysql_free_result($res);

    $sql =  "select c.classmark_tag, f.description from classmarks c join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id";
    if ($lang != 1)
    {
        $sql .= " in (1, ".$lang . ") ";
    }
    else
    {
        $sql .= " = " . $lang;
    } 
    $sql .= " where c.classmark_id = (select broader_category from classmarks c1 where c1.classmark_id = " . $rootclassmark_id . ") order by f.language_id";

//    $start=explode(' ',microtime());
    $res = @mysql_query($sql, $dbc);
//    $end = explode(' ',microtime());
//    $exec_time = $end[1]+$end[0] - $start[1] - $start[0];
//    echo $sql . "<br>\n";     
//    echo "query time: " . $exec_time . "<br><br>\n";

    $broader_tag = "";
    $broader_desc = "";
    
    if ($res)
    {
    	while (($row = @mysql_fetch_array($res, MYSQL_NUM)))
        {
            $broader_tag = $row[0];
            $broader_desc = $row[1];
        }
        //$result = "<table width=\"100%\"><tr><td width=\"10%\">" . $row[1] . "</td><td width=\"90%\">" . $row[2] . "</td></tr></table>";
    }

    @mysql_free_result($res);

    $firstrecord = true;
//    $start=explode(' ',microtime());
    
    $result = "";
    
    include_once("managenavpath.php");
    
    ManageNavPath($id, $savepath);
    
    include_once("checkauxtag.php");
    
    if ($broader_tag != "")
    {
        $broader_tag = CheckAuxTag($broader_tag);
        
        // Add the broader category
        $result .= "<div class=\"record\">";
        $result .= "<div class=\"headerrow\"><div class=\"notation\">";
        $fontweight = "normal";  
        $result .= "<span style=\"font-weight: " . $fontweight . "; color: #aaaaaa;\">" . $broader_tag . "</span></div><div class=\"caption\"><span style=\"font-weight: " . $fontweight . "; color: #aaaaaa;\">" . 
                    $broader_desc . "</span>"; 
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
        $result .= "<div class=\"headerrow\"><div class=\"notation\">";
        $fontweight = "normal";
        if ($toprecord == true)
        {
            $fontweight = "bold";
            $toprecord = false;
        }
        
        $displaynotation = GetDisplayNotation($record->classmark_tag, true);
        $result .= "<span style=\"font-weight: " . $fontweight . "\">" . $displaynotation . "</span></div><div class=\"caption\"><span style=\"font-weight: " . $fontweight . "\">" .
                    $record->caption . "</span>"; 
                    //$record->caption . " [" . $record->classmark_id . "]</span>"; 
        if (!empty($record->including))
        {
            $result .= "<br><span style=\"font-style: italic\">";
            switch($lang)
            {
                case "2":
                    $result .= "Inclusief: ";
                    break;
                default:
                    $result .= "Includes: ";
                    break;                
            }
            $result .= $record->including . "</span>";
        }
        $result .= "</div></div>\n";
        
        if (!empty($record->scope_note)) 
        {
            $result .= "<div class=\"recordrow\"><div class=\"label\">&nbsp;</div><div class=\"recordvalue\">";
            switch($lang)
            {
                case "2":
                    $result .= "TOELICHTING: ";
                    break;
                default:
                    $result .= "SCOPE NOTE: ";
                    break;                
            }
            $result .= $record->scope_note . "</div></div>\n";
        }
        if (!empty($record->app_note)) 
        {
            $result .= "<div class=\"recordrow\"><div class=\"label\">&nbsp;</div><div class=\"recordvalue\">";
            switch($lang)
            {
                case "2":
                    $result .= "GEBRUIK: ";
                    break;
                default:
                    $result .= "APPLICATION NOTE: ";
                    break;                
            }
            $result .= $record->app_note . "</div></div>\n";
        }
    
        if ($record->derivedfrom != "")
        {
            $result .= "<div class=\"recordrow\">";
            $result .= "<div class=\"label\">&nbsp;</div>";
            $result .= "<div class=\"recordvalue\">";
    
            $result .= "<table width=\"100%\">";  
            $result .= "<tr>";
            $result .= "<td class=\"extag\">";
            switch($lang)
            {
                case "2":
                    $result .= "Ontleend aan: ";
                    break;
                default:
                    $result .= "Derived from: ";
                    break;                
            }
            $result .= "</td><td class=\"exdesc\"><a href=\"#\" onclick=\"javascript:openrecord('". $record->derivedfrom . "')\">" . $record->derivedfrom . "</a></td>";
            $result .= "</tr>";   
            $result .= "</table></div>";
            $result .= "</div>";
        }
            
        if (count($record->examples)> 0)
        {
            $result .= "<div class=\"recordrow\">";
            $result .= "<div class=\"label\">&nbsp;</div>";
            $result .= "<div class=\"recordvalue\">";
            switch($lang)
            {
                case "2":
                    $result .= "COMBINATIEVOORBEELD(EN): ";
                    break;
                default:
                    $result .= "EXAMPLES OF COMBINATION(S): ";
                    break;                
            }
            $result .= "</div>";
            $result .= "<div class=\"label\">&nbsp;</div>";
            $result .= "<div class=\"recordvalue\">";
    
            $result .= "<table width=\"100%\">";
    
            foreach($record->examples as $seq => $desc)
            {
                $result .= "<tr>";
                $result .= "<td class=\"extag\">". $record->example_tags[$seq] . "</td><td class=\"exdesc\">" . $desc . "</td>";
                $result .= "</tr>";
            }
    
            $result .= "</table></div>";
            $result .= "</div>";
        }
    
        if (count($record->references) > 0)
        {
            $result .= "<div class=\"recordrow\">";
            //$result .= "<div class=\"label\">&nbsp;</div>";
            //$result .= "<div class=\"recordvalue\"><span style=\"font-weight: bold; font-style: normal;\">References:</span></div>";
            $result .= "<div class=\"label\">&nbsp;</div>";
            $result .= "<div class=\"recordvalue\">";
    
            $result .= "<table width=\"100%\">";
    
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
                $result .= "<tr>";
                $result .= "<td class=\"reftag\" valign=\"middle\"><img src=\"../images/ref.png\" align=\"left\"> ";
                $result .= "<a href=\"#\" onclick=\"javascript:openrecord('" . $tag. "')\">";   
                $result .= $tag;
                $result .= "</a>";
                $result .= "</td><td class=\"exdesc\">" . $desc . "</td>";
                $result .= "</tr>";
            }
    
            $result .= "</table></div>";
            $result .= "</div>";
        }
    
        $result .= "</div>\n";
        //$result .= "</table>\n";
        //$result = "<table width=\"100%\"><tr><td width=\"10%\">" . $row[1] . "</td><td width=\"90%\">" . $row[2] . "</td></tr></table>";
    }
    
    //$end = explode(' ',microtime());
    //$exec_time = $end[1]+$end[0] - $start[1] - $start[0];
    //echo $sql . "<br>\n";     
    //echo "processing time: " . $exec_time . "<br>\n";
    //echo "Done";
    echo $result;
?>