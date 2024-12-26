<?php


class HierarchyData
{
    var $classmark_id = 0;
    var $hierarchy_code = "";
    var $new_hierarchy_code = "";
    var $hierarchy_level = 0;
    var $broader_category = 0;
    var $classmark_tag = "";
    var $encoded_tag = "";
};

// 
// InsertIntoHierarchy - inserts a new classmark into the hierarchy 
function InsertIntoHierarchy($dbc, $classmark_id, $encoded_tag, $broader_category, &$sqlarray, $sublevel)
{
    // First, find the classmark's broader category and the broader category's hierarchy_code
    // This will help us find all sub classmarks

    echo "Inserting into hierarchy<br>\n";
    
    $broader_classmark_id = 0;
    $broader_classmark_level = 0;
    $broader_classmark_hierarchy_code = "";
    
    $sql =  "select c.classmark_id, c.hierarchy_level, h.hierarchy_code " .
            "from classmarks c join classmark_hierarchy h on c.classmark_id = h.classmark_id " .
            "where c.classmark_id = " . $broader_category;
    echo $sql . "<br>\n";
    
    $res = mysql_query($sql);

    if ($res)
    {
          $row = mysql_fetch_array($res, MYSQL_NUM);
          $broader_classmark_id = $row[0];
          $broader_classmark_level = $row[1];
          $broader_classmark_hierarchy_code = $row[2];
          
          echo "Broader = " . $broader_classmark_id . "\n";
          echo "HCode   = " . $broader_classmark_hierarchy_code . "\n";
          echo "Level   = " . $broader_classmark_level . "\n";
    }

    mysql_free_result($res);

    // Now get all immediate subclasses of the broader category (i.e. one level down)
    
    $records = array();
    $sorted_hierarchies = array();
    $sorted_classmarks = array();
    
    $data = new HierarchyData();
    $data->classmark_id = $classmark_id;
    $data->hierarchy_level = ($broader_classmark_level+1);
    $data->encoded_tag = $encoded_tag;

    $records[$classmark_id] = $data;     
    $sorted_classmarks[$classmark_id] = $data->encoded_tag;
    $reverse_sorted_classmarks[$classmark_id] = "";
    
    $sql =  "select c.classmark_id, c.hierarchy_level, c.classmark_tag, c.classmark_enc_tag, h.hierarchy_code " .
            "from classmarks c join classmark_hierarchy h on c.classmark_id = h.classmark_id " .
            "where h.hierarchy_code like '" . $broader_classmark_hierarchy_code . ".%' and c.hierarchy_level = " . ($broader_classmark_level+1) . " " .
            "order by h.hierarchy_code";
    echo $sql . "<br>\n";

    $res = mysql_query($sql);

    if ($res)
    {
          while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
          {
              $data = new HierarchyData();
              $data->classmark_id= $row[0];
              $data->hierarchy_level = $row[1];
              $data->classmark_tag = $row[2];
              $data->encoded_tag = $row[3];
              $data->hierarchy_code = $row[4];
              
              $records[$data->classmark_id] = $data;
              $sorted_classmarks[$data->classmark_id] = $data->encoded_tag;            
          }
    }

    @mysql_free_result($res);
    
    //arsort($reverse_sorted_classmarks);        
    //echo "Reverse sorted classmarks:<br>\n";
    //var_dump($reverse_sorted_classmarks);  
    asort($sorted_classmarks);
    echo "<br>Sorted classmarks:<br>\n";
    var_dump($sorted_classmarks);  
    echo "<br>\n";
    // Now assign new hierarchy codes to the sorted list
    $next_code = 1;
    
    foreach($sorted_classmarks as $key => $value)
    {
        echo "Checking: " . $key . "<br>\n";
        if (array_key_exists($key, $records))
        {
            $record =& $records[$key];            
            $new_code = sprintf("%03d", $next_code++);
            $record->new_hierarchy_code = $broader_classmark_hierarchy_code . "." . $new_code;         
            echo "Old/New code = [" . $record->hierarchy_code . "] [" . $record->new_hierarchy_code . "]<br>\n";        
            $sorted_hierarchies[$record->classmark_id] = $record->new_hierarchy_code;
        }
        else
        {
            echo "Key " . $key . " does not exist<br>\n";            
        }
    }
    
    echo "Sorted classmark hierarchies:<br>\n";
    asort($sorted_hierarchies);
    foreach($sorted_hierarchies as $key => $code)
    {
        echo $key . " = " . $code . "<br>\n";
    }            
    
    // For classes where the hierarchy code has changed, generate SQL
    // statements to update the class's hierarchy code (and all its
    // sub classes too).
    // This is a two-stag process to preserve hierarchies as they are renumbered
    // First, prepend all changed hierarchies with 'X.'

    foreach($sorted_hierarchies as $key => $value)
    {
        $data = $records[$key];
        if ($data->hierarchy_code != $data->new_hierarchy_code)
        {
            if ($data->hierarchy_code == "")
            {
                // Update this classmark's hierarchy code as it has changed
                $sql = "insert into classmark_hierarchy (classmark_id, hierarchy_code) values (" . $data->classmark_id . ", '" . $data->new_hierarchy_code . "')";
                array_push($sqlarray, $sql);

                // Update the classmark hierarchy_level
                $sql = "update classmarks set hierarchy_level = " . $data->hierarchy_level . " where classmark_id = " . $data->classmark_id;
                array_push($sqlarray, $sql);
            }
            else
            {
                // Update this classmark's hierarchy code as it has changed
                $sql = "update classmark_hierarchy set hierarchy_code = '" . $data->new_hierarchy_code . "' where classmark_id = " . $data->classmark_id;
                array_push($sqlarray, $sql);

                // Update the hierarchy code of all sub-classmarks to reflect this classmark's new hierarchy code, initially prepending an X1 to the new
                // hierarchy code
                $hierarchy_code_length = strlen($data->new_hierarchy_code);
                $sql =  "update classmark_hierarchy set hierarchy_code = CONCAT('X.','" . $data->new_hierarchy_code . "',MID(hierarchy_code, " . ($hierarchy_code_length+1) . 
                        ")) where hierarchy_code like '" . $data->hierarchy_code . ".%'";
                array_push($sqlarray, $sql);
            }
        }
    }

    // Now remove the 'X' from all updates marked so (above)
    $sql = "update classmark_hierarchy set hierarchy_code = SUBSTRING(hierarchy_code, 3) where hierarchy_code like 'X.%'";
    array_push($sqlarray, $sql);

    var_dump($sqlarray);
    echo "<br>\n";
    
    /*
    
    // For classes where the hierarchy code has changed, generate SQL
    // statements to update the class's hierarchy code (and all its
    // sub classes too).  Traverse the list of hierarchy codes in reverse so 
    // existing records are not duplicated.
    foreach($reverse_sorted_classmarks as $key => $value)
    {
        $data = $records[$key];
        if ($data->hierarchy_code != $data->new_hierarchy_code)
        {
            if ($data->hierarchy_code == "")
            {
                // Update this classmark's hierarchy code as it has changed
                $sql = "insert into classmark_hierarchy (classmark_id, hierarchy_code) values (" . $data->classmark_id . ", '" . $data->new_hierarchy_code . "')";
                array_push($sqlarray, $sql);

                // Update the classmark hierarchy_level                
                $sql = "update classmarks set hierarchy_level = " . $data->hierarchy_level . " where classmark_id = " . $data->classmark_id;
                array_push($sqlarray, $sql);                
                
            }
            else
            {
                // Update this classmark's hierarchy code as it has changed
                $sql = "update classmark_hierarchy set hierarchy_code = '" . $data->new_hierarchy_code . "' where classmark_id = " . $data->classmark_id;
                array_push($sqlarray, $sql);
                
                // Update the hierarchy code of all sub-classmarks to reflect this classmark''s new hierarchy code
                $hierarchy_code_length = strlen($data->new_hierarchy_code);
                $sql = "update classmark_hierarchy set hierarchy_code = CONCAT('" . $data->new_hierarchy_code . "',MID(hierarchy_code, " . 
                        ($hierarchy_code_length+1) . ")) where hierarchy_code like '" . $data->hierarchy_code . ".%'";
                array_push($sqlarray, $sql);          
            }
        }
    }        
    
    // Now add the new record

    var_dump($sqlarray);
    echo "<br>\n";    
    */
}

/*
// test

	require_once("DBConnectInfo.php");
	include_once("specialchars.php");

	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);

    $sqlarray = array();
    InsertIntoHierarchy($dbc, 18696, $sqlarray, 0);   
    */ 
?>