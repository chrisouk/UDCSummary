<?php
    ## getrecord.php is invoked by an AJAX call when the user clicks on a classmark
    ## in the classmark tree on the UDC Summary page.  It returns the details of the
    ## selected class record and all its children, and displays these details in the
    ## righthand pane
	session_start();
	
    # Maximum reference description length, beyond which the description is truncated 
    # and appended with "..."	
    
    define(MAX_REF_DESC_LEN, 55);
    
    # Maximum number of elements in navigaton path
    define(MAX_NAVCOUNT, 50);
        
    class classmark
    {
        var $classmark_id = 0;
        var $caption = array();
        var $broader_category = 0;
        var $classmark_tag = "";        
        var $hierarchy_level = 0;
        var $hierarchy_code = "";
        var $scope_note = array();
        var $app_note = array();
        var $including = array();
        var $derivedfrom = "";
        var $dfdesc = "";
        var $references = array();
        var $examples = array();
        var $example_tags = array();
        
        function GetAsXML()
        {
        	$result = "";
        	if ($this->classmark_id == 0)
        	{
        		return $result;
        	}
        	
        	if ($this->broader_category == 0)
        	{
        		return $result;
        	}
        	
        	$result .= "<UDC>\n";
        	$result .= "\t<classmark>\n";
        	$result .= "\t\t<id>http://udcdata.info/" . $this->classmark_id . "</id>\n";
            $result .= "\t\t<broader>http://udcdata.info/$this->broader_category</broader>\n";
           	if (!empty($this->derivedfrom))
        	{
        		$result .= "\t\t<derivedfrom><notation>$this->derivedfrom</notation>\n";
        		$result .= "</derivedfrom>";
        	}
        	
        	$result .= "\t\t<notation>$this->classmark_tag</notation>\n";
        	foreach($this->caption as $language => $caption)
        	{
        		$result .= "\t\t<caption language=\"$language\">$caption</caption>\n";
        	}
        	foreach($this->including as $language => $including)
        	{
        		$result .= "\t\t<including language=\"$language\">$including</including>\n";
        	}
        	
        	foreach($this->scope_note as $language => $scope_note)
        	{
        		$result .= "\t\t<scopeNote language=\"$language\">$scope_note</scopeNote>\n";
        	}
        	
        	foreach($this->app_note as $language => $app_note)
        	{
        		$result .= "\t\t<applicationNote language=\"$language\">$app_note</applicationNote>\n";
        	}
        	
        	if (count($this->references) > 0)
        	{
        		$result .= "\t\t<references>\n";
	        	foreach($this->references as $id => $desc)
	        	{
	        		$result .= "\t\t\t\t<reference><notation>http://udcdata.info/$id</notation><description>$desc</description></reference>\n";
	        	}
        		$result .= "\t\t</references>\n";
        	}
    		
       		if (count($this->examples) > 0)
       		{
       			$result .= "\t\t<examples>\n";
       			 
       			foreach($this->examples as $seq => $examples)
       			{
       				foreach($examples as $language => $description)
       				{
       					$example_tag = $this->example_tags[$seq];
       					$result .= "\t\t\t\t<example><notation>$example_tag</notation><description language=\"$language\">$description</description></example>\n";
       				}
       			}
       			$result .= "\t\t</examples>\n";
       		}
        		
        	$result .= "\t</classmark>\n";
        	
        	$result .= "</UDC>\n";
        	
        	return $result;
        }
    };

    require_once("DBConnectInfo.php");
    
    $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
    @mysql_select_db (DBDATABASE);
    
    @mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    
    $id = "";
    $backid = "";
    $subclasses = true;
    $lang = 1;
    
    $record_list = array();
    
    # First, select all active IDs from the database
    $sql = "select c.classmark_id, h.hierarchy_code from classmarks c join classmark_hierarchy h on c.classmark_id = h.classmark_id where c.active = 'Y' order by h.hierarchy_code";
    $res = @mysql_query($sql, $dbc);
    if ($res)
    {
    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
    	{
    		array_push($record_list, $row[0]);
    	}
    	@mysql_free_result($res);    
    }
    
    echo count($record_list) . " classmarks selected for export\n";

    /*
    $sql =  "select including, scopenote, appnote, derivedfrom, examples" . 
            " from interface_fields where language_id = " . $lang;  
    
	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
		if($row = @mysql_fetch_array($res, MYSQL_NUM))
		{
            $if_including = $row[0];
            $if_scopenote = $row[1];
            $if_appnote = $row[2];
            $if_derivedfrom = $row[3];
            $if_examples = $row[4];
		}
		@mysql_free_result($res);
        
	}	
	*/    
    
    $record_count = 0;
    
    foreach($record_list as $id)
    {
	    # Fetch class details for this class and potentially all subclasses 
	    $sql =  "select c.classmark_id, c.classmark_tag, c.broader_category, c.derived_from from classmarks c where c.classmark_id = '" . $id . "' ";
	
	    $res = @mysql_query($sql, $dbc);
	
	    $record = new classmark();

	    $rowcount=0;
	    if ($res)
	    {
	    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
	        {
	            $record = new classmark();
	            $record->classmark_id = $row[0];
	            $record->classmark_tag = $row[1];
	            $record->broader_category = $row[2];
	            $record->derivedfrom = $row[3]; 
	        }
	    }
	
	    @mysql_free_result($res);
	    
	    # Load fields that can be expressed in different languages:
	    # - Caption
	    # - Including
	    # - Scope Note
	    # - Application Note
	    # - Examples of combination description
	    
	    $sql =  "select f.classmark_id, f.description, f.field_id, f.seq_no, f.language_id, l.code from language_fields f join language l on f.language_id = l.language_id where f.language_id in (1,2,3,27) and f.classmark_id = " . $record->classmark_id . " order by f.classmark_id, f.language_id, f.field_id, f.seq_no";
	    
	    $res = @mysql_query($sql, $dbc);
	            
	    if ($res)
	    {
	        $gotlang = false;
	    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
	        {
	            $field_id = $row[2];
	            $description = $row[1];
	            $sequence_no = $row[3];
	            $language = $row[5];
	            
	            switch($field_id)
	            {
	                case "1":
	                    # Caption
	                    $record->caption[$language] = $description;
	                    break;
	                case "4":
	                    # Including
	                    $record->including[$language] = $description;
	                    break;
	                case "5":
	                    # Scope note
	                    $record->scope_note[$language] = $description;
	                    break;
	                case "6":
	                    # App Note
	                    $record->app_note[$language] =  $description;
	                    break;
	                case "2":
	                    # Example of combination description
	                    if (!isset($record->examples[$sequence_no]))
	                    {
	                    	$record->examples[$sequence_no] = array();
	                    }
	                    $record->examples[$sequence_no][$language] = $description;
	                    break;
	                default:
	                	break;
	            }
	        }
	    }
	
	    @mysql_free_result($res);
	
	    # Load examples of combination notations
	    $sql = "select e.classmark_id, e.field_type, e.seq_no, e.tag, c.classmark_tag, e.encoded_tag from example_classmarks e join classmarks c on e.classmark_id = c.classmark_id where e.classmark_id in (" . 
	            $record->classmark_id . ") order by e.classmark_id, e.seq_no";
	
	    $res = @mysql_query($sql, $dbc);
	
		if ($res)
	    {
	    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
	        {           
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
	        }
	        @mysql_free_result($res);
	    }
	    
	    # Load references
	    $sql =  "select r.classmark_id, r.notation, f.description, f.language_id, c.classmark_id from classmark_refs r join classmarks c on c.classmark_tag = r.notation left outer join language_fields f " .
	            "on c.classmark_id = f.classmark_id and f.field_id = 1 ";
	    $sql .= " where r.classmark_id in (" .  $record->classmark_id . ") order by r.classmark_id, f.language_id, r.sequence_no";
	    
	    $res = @mysql_query($sql, $dbc);
	
	    if ($res)
	    {
	    	while($row = @mysql_fetch_array($res, MYSQL_NUM))
	        {
	            $tag = $row[1];
	            $description = $row[2];
	            $language = $row[3];
	            $classmark_id = $row[4];
	            
	            if (!isset($record->references[$classmark_id]))
	            {
	            	$record->references[$classmark_id] = array();
	            }
	            $record->references[$classmark_id][$language] = $description;
	        }
	    }
	
	    @mysql_free_result($res);
	
	    /*
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
	    $sql .= " where c.classmark_id = (select broader_category from classmarks c1 where c1.classmark_id = " . $record->classmark_id . ") order by f.language_id";
	
	
	    $broader_tag = "";
	    $broader_desc = "";
	    
	    $res = @mysql_query($sql, $dbc);
	    if ($res)
	    {
	    	while (($row = @mysql_fetch_array($res, MYSQL_NUM)))
	        {
	            $broader_tag = $row[0];
	            $broader_desc = $row[2] . "~" . $row[1];
	        }
	    }
	
	    @mysql_free_result($res);
		*/
	    
	    # OK, output the retrieved records in the right hand pane
	
	    echo $record->GetAsXML();
	    
	    $record_count++;
	    
	    if ($record_count > 9)
	    {
	    	break;
	    }
    }
   
    @mysql_close($dbc);
     
?>