<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    class FullProcessor
    {
         function ListRecords(&$dbc, &$classmarks)
        {
            $sql = "select c.classmark_id from classmarks c join classmark_hierarchy h on c.classmark_id = h.classmark_id where c.deleted = 'N' order by h.hierarchy_code";
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
                while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    #echo "Listing record: " . $row[0] . "\r\n";
            		array_push($classmarks, $row[0]);
            	}
            	mysql_free_result($res);
            }
        }
        
        function RetrieveRecords(&$dbc, &$records, $lang)
        {
            # Fetch all language fields
            
            $sql = "select c.classmark_id, c.classmark_tag from classmarks c where c.deleted = 'N'";
                   
            //echo $sql . "\r\n";              
                   
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $record = new UDCRecord();
                  
                    $id = $row[0];
            		$record->notation = $row[1];
                    
                    //echo "Adding record " . $id . "<br>\n";
                    $records[$id] = $record;
            	}
            	mysql_free_result($res);
            }
    
            # All language fields
            $sql =  "select c.classmark_id, f.field_id, f.description". 
                    " from classmarks c join language_fields f on c.classmark_id = f.classmark_id and f.language_id = " . $lang . " and f.field_id in (1,4,5,6)" .
                    " where c.deleted = 'N'";
                   
            //echo $sql . "\r\n";              
                   
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[0];
                    if (array_key_exists($id, $records))
                    {
                        $record = $records[$id];
                        switch($row[1])
                        {
                            case 1:
                                $record->caption = $row[2];
                                break;
                            case 4:
                                //echo "Including : " . $record->including . "<br>\n";
                                $record->including = $row[2];
                                break;
                            case 5:
                                $record->scopenote = $row[2];
                                break;
                            case 6:
                                $record->appnote = $row[2];
                                break;
                        }
                        $records[$id] = $record;
                    }
            	}
            	mysql_free_result($res);
            }
            
            # Examples of combination
            $sql = "select c.classmark_tag, e.field_type, e.tag, f.description, c.classmark_id" . 
                   " from classmarks c" . 
                   " join example_classmarks e on c.classmark_id = e.classmark_id and c.deleted = 'N'" .
                   " join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 2 and e.seq_no = f.seq_no and f.language_id = " . $lang .
                   " order by c.classmark_id, e.seq_no";
                   
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[4];
                    if (array_key_exists($id, $records))
                    {
                        $record = $records[$id];
                        $example = "";
                        switch($row[1])
                        {
                            case 'a':
                                # Addition
                                $example = "\t" . $row[0] . $row[2] . " " . $row[3];
                                break;
                            case 'b':
                                # Colon combination
                                $example = "\t" . $row[0] . ":" . $row[2] . " " . $row[3];                    
                                break;
                            case 'c':
                                # Full notation
                                $example = "\t" . $row[2] . " " . $row[3];                    
                                break;                        
                            case 'r':
                                # Reference - shouldn't be here
                                $example = $row[2] . " #REFERENCE# " . $row[3];                    
                                break;                        
                            default:
                                $example = "CORRUPTED (type)";
                                break;
                        }
                        array_push($record->examples, $example);
                        $records[$id] = $record;                    
                    }
            	}
            	mysql_free_result($res);
            }
    
            # References
            $sql = "select r.notation, f.description, r.classmark_id" . 
                   " from classmark_refs r" .
                   " join classmarks c on c.classmark_tag = r.notation and c.deleted = 'N'" .
                   " join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang . 
                   " order by r.classmark_id, r.sequence_no";
                   
                             
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[2];        	   
                    if (array_key_exists($id, $records))
                    {
                        $record = $records[$id];
                        array_push($record->refs, $row[0] . " " . $row[1]);
                        $records[$id] = $record;                    
                    }
            	}
            	mysql_free_result($res);
            }
        }
        
        function OutputRecord($id, $records, $lang)
        {
            # Output the record
            if (!array_key_exists($id, $records))
            {
                return;
            }
            
            $record = $records[$id];
            
            echo $record->notation . "\t" . $record->caption . "\r\n";
            if ($record->including != "")
            {
                echo "\t" . $_SESSION['if_including'] . ": " . $record->including . "\r\n";
            }
            
            if ($record->scopenote != "")
            {
                echo "\t" . $_SESSION['if_scopenote'] . ": " . $record->scopenote . "\r\n";
            }
    
            if ($record->appnote != "")
            {
                echo "\t" . $_SESSION['if_appnote'] . ": " . $record->appnote . "\r\n";
            }
            
            if (count($record->examples) > 0)
            {
                echo "\t" . $_SESSION['if_examples'] . ": " . "\r\n";
                foreach($record->examples as $example)
                {
                    echo $example . "\r\n";
                    /*
                    switch($lang)
                    {
                        case 21:
                            # Don't do anything for Armenian
                            echo ($example, ENT_QUOTES, "UTF-8") . "\r\n";
                            //echo $example . "\r\n";
                            break;
                        default:
                            if (strpos($example, "&") !== FALSE && strpos($example, ";") !== FALSE)
                                echo utf8_encode(html_entity_decode($example, ENT_QUOTES, "UTF-8")) . "\r\n";
                            else
                                echo utf8_encode($example) . "\r\n";
                    }
                    */
                }
            }
    
            if (count($record->refs) > 0)
            {
                foreach($record->refs as $ref)
                {
                    echo "\t==> " . $ref . "\r\n";
                }
            }
            
            echo "\r\n";    
        }
        
        function ProcessRecords(&$classmarks, &$records)
        {
            /*
            echo "Key:\r\n\r\n";
            echo "SN:\tScope Note\r\n";
            echo "AN:\tApplication Note\r\n";
            echo "EX(A):\tExample of combination: direct addition\r\n";
            echo "EX(B):\tExample of combination: colon addition\r\n";
            echo "EX(C):\tExample of combination: full notation\r\n\r\n";
            */
                  
            $exportlanguage = $_SESSION['exportlang'];
              
            foreach($classmarks as $id)
            {
                $this->OutputRecord($id, $records, $exportlanguage);
            }
        }               
    };

?>