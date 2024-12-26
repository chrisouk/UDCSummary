<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */
 
    include_once("udcrecord.php");

    class FullProcessor
    {
        function ProcessSpecialChars($value)
        {
            $value = str_replace("<", "&lt;", $value);
            $value = str_replace(">", "&gt;", $value);
            return $value;
        }
        
        function ListRecords(&$dbc, &$classmarks, $joinclause, $whereclause)
        {
            $sql = "select c.classmark_id from classmarks c where c.active = 'Y' order by c.classmark_enc_tag";
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
        
        function RetrieveRecords(&$dbc, &$records, &$examples, &$references, $lang, $joinclause, $whereclause)
        {
            # Fetch all language fields
            $sql =  "select c.classmark_id, c.classmark_tag, f.field_id, f.description". 
                    " from classmarks c join language_fields f on c.classmark_id = f.classmark_id and f.language_id = " . $lang . " and f.field_id in (1,4,5,6,10)";
            if ($joinclause != "")
            {
                $sql .= " " . $joinclause . " ";
            } 
            $sql .= " where c.active = 'Y' ";
            if ($whereclause != "")
            {
                $sql .= " AND " . $whereclause . " ";
            }             
            $sql .= " order by f.classmark_id";
                   
            $last_id = 0;
            $record = new UDCRecord();
                               
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[0];

                    if ($last_id != $id)
                    {
                        if ($record->id != 0)
                            $records[$record->id] = $record;
                        
                        $last_id = $row[0];
                        $record = new UDCRecord();
                        $record->id = $row[0];                            
                        $record->notation = $row[1];                
                    }
                                        
                    switch($row[2])
                    {
                        case 1:
                            $record->caption = $row[3];
                            break;
                        case 4:
                            //echo "Including : " . $record->including . "<br>\n";
                            $record->including = $row[3];
                            break;
                        case 5:
                            $record->scopenote = $row[3];
                            break;
                        case 6:
                            $record->appnote = $row[3];
                            break;
                        case 10:
                            $record->infonote = $row[3];
                            break;
                    }
            	}

                if ($record->id != 0)
                    $records[$record->id] = $record;
                    
            	mysql_free_result($res);
            }
            
            # Examples of combination
            $sql = "select c.classmark_tag, e.field_type, e.tag, IFNULL(f.description, 'NULL'), c.classmark_id" . 
                   " from classmarks c" . 
                   " join example_classmarks e on c.classmark_id = e.classmark_id and c.active = 'Y'" .
                   " left outer join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 2 and e.seq_no = f.seq_no and f.language_id = " . $lang;
            if ($joinclause != "")
            {
                $sql .= " " . $joinclause . " ";
            } 
            $sql .= " where c.active = 'Y' ";
            if ($whereclause != "")
            {
                $sql .= " AND " . $whereclause . " ";
            }             
            $sql .= " order by c.classmark_id, e.seq_no";
                   
            $last_id = 0;
            $record = new UDCRecord();
                                 
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[4];

                    if ($last_id != $id)
                    {
                        if ($record->id != 0)
                            $examples[$record->id] = $record;
                        
                        $last_id = $id;
                        $record = new UDCRecord();
                        $record->id = $id;                            
                    }

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
            	}
                
                if ($record->id != 0)
                    $examples[$record->id] = $record;
                                    
            	mysql_free_result($res);
            }
    
            # References
            $sql = "select r.notation, IFNULL(f.description, 'NULL'), r.classmark_id" . 
                   " from classmark_refs r" .
                   " join classmarks c on c.classmark_tag = r.notation and c.active = 'Y'" .
                   " left outer join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang;
            if ($joinclause != "")
            {
                $sql .= " " . $joinclause . " ";
            } 
            $sql .= " where c.active = 'Y' ";
            if ($whereclause != "")
            {
                $sql .= " AND " . $whereclause . " ";
            }             
            $sql .= " order by r.classmark_id, r.sequence_no";
                                                
            $last_id = 0;
            $record = new UDCRecord();
                                 
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[2];        	   

                    if ($last_id != $id)
                    {
                        if ($record->id != 0)
                            $references[$record->id] = $record;
                        
                        $last_id = $id;
                        $record = new UDCRecord();
                        $record->id = $id;                            
                    }
                    
                    array_push($record->refs, $row[0] . " " . $row[1]);
            	}
                
                if ($record->id != 0)
                    $references[$record->id] = $record;
                
            	mysql_free_result($res);
            }
        }
        
        function OutputRecord($id, &$records, &$examples, &$references)
        {
            # Output the record
            if (isset($records[$id]))
            {
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

                if ($record->infonote != "")
                {
                    $label="Information Note";
                    if (isset($_SESSION['if_infonote']))
                    {
                        $label = $_SESSION['if_infonote'];
                    }
                    echo "\t" . $label . ": " . $record->infonote . "\r\n";
                }
                
                if (isset($examples[$record->id]))
                {
                    $example = $examples[$record->id];
                    echo "\t" . $_SESSION['if_examples'] . ": " . "\r\n";
                
                    foreach($example->examples as $example_line)
                    {
                        echo $example_line . "\r\n";
                    }
                }        
                
                if (isset($references[$record->id]))
                {
                    $reference = $references[$record->id];
                    foreach($reference->refs as $ref)
                    {
                        echo "\t==> " . $ref . "\r\n";
                    }
                }
                                
                echo "\r\n";
            }    
        }
        
        function ProcessRecords(&$classmarks, &$records, &$examples, &$references)
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
                $this->OutputRecord($id, $records, $examples, $references);
            }
        }               
    };

?>