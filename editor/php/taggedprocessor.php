<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */
 
    include_once("udcrecord.php");
    
    class TaggedProcessor
    {
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
            //echo $operation . "<br>\n";
            //echo "Executed in " .$totaltime. " seconds.<br>\n";
            //flush();
        }
        
        function ProcessSpecialChars($value)
        {
            $value = str_replace("<", "&lt;", $value);
            $value = str_replace(">", "&gt;", $value);
            return $value;
        }

        function ListRecords(&$dbc, &$classmarks, $joinclause, $whereclause)
        {
            $this->StartTime($starttime);
            $sql = "select c.classmark_id from classmarks c";
            if ($joinclause != "")
            {
                $sql .= " " . $joinclause . " ";
            } 
            $sql .= " where c.active = 'Y' ";
            if ($whereclause != "")
            {
                $sql .= " AND " . $whereclause . " ";
            } 
            $sql .= " order by c.classmark_enc_tag";
            //echo $sql . "<br>\n";
            
           	$res = @mysql_query($sql, $dbc);
            $this->EndTime($sql, $starttime);
            
            if ($res)
            {
                while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    //echo "Listing record: " . $row[0] . "\r\n";
            		array_push($classmarks, $row[0]);
            	}
            	mysql_free_result($res);
            }
        }
        
        function RetrieveRecords(&$dbc, &$records, &$examples, &$references, $lang, $joinclause, $whereclause)
        {
            # All language fields
            $this->StartTime($starttime);
            $sql =  "select c.classmark_id, c.classmark_tag, f.field_id, IFNULL(f.description, 'NULL')". 
                    " from classmarks c left outer join language_fields f on c.classmark_id = f.classmark_id and f.language_id = " . $lang . " and f.field_id in (1,4,5,6,10)";
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
            //echo $sql . "<br>\n";
                        
           	$res = @mysql_query($sql, $dbc);
            $this->EndTime($sql, $starttime);                   
            $this->StartTime($starttime);
            
            $last_id = 0;
            $record = new UDCRecord();
            
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    if ($last_id != $row[0])
                    {
                        if ($record->id != 0)
                            $records[$record->id] = $record;
                        
                        $last_id = $row[0];
                        $record = new UDCRecord();
                        $record->id = $row[0];                            
                        $record->notation = $row[1];                
                    }
                    
                    if (count($row) > 2)
                    {
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
            	}

                if ($record->id != 0)
                    $records[$record->id] = $record;
                    
            	mysql_free_result($res);
            }
            $this->EndTime("Classmark/Language field population", $starttime);
            
            # Examples of combination
            $this->StartTime($starttime);            
            $sql = "select c.classmark_tag, e.field_type, e.tag, f.description, c.classmark_id" . 
                   " from classmarks c" . 
                   " join example_classmarks e on c.classmark_id = e.classmark_id " .
                   " join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 2 and e.seq_no = f.seq_no and f.language_id = " . $lang;
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
            //echo $sql . "<br>\n";
                        
           	$res = @mysql_query($sql, $dbc);
            $this->EndTime($sql, $starttime);
            $this->StartTime($starttime);   
            
            $last_id = 0;
            $record = new UDCRecord();
                                 
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
                        $record->notation = $row[1];                
                    }

                    $example = "";
                    switch($row[1])
                    {
                        case 'a':
                            # Addition
                            $example = "<06>\t" . $row[0] . $row[2] . " " . $row[3];
                            break;
                        case 'b':
                            # Colon combination
                            $example = "<06>\t" . $row[0] . ":" . $row[2] . " " . $row[3];                    
                            break;
                        case 'c':
                            # Full notation
                            $example = "<06>\t" . $row[2] . " " . $row[3];                    
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
            $this->EndTime("Examples", $starttime);
    
            # References
            $this->StartTime($starttime);            
            $sql = "select r.notation, IFNULL(f.description, 'NULL'), r.classmark_id" . 
                   " from classmark_refs r" .
                   " join classmarks c on c.classmark_tag = r.notation " .
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
            //echo $sql . "<br>\n";
                                                          
           	$res = @mysql_query($sql, $dbc);
            $this->EndTime("References", $starttime);
            $this->StartTime($starttime);            

            $last_id = 0;
            $record = new UDCRecord();
                                 
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
                        $record->notation = $row[0];                
                    }

                    array_push($record->refs, $row[0] . " " . $row[1]);
            	}

                if ($record->id != 0)
                    $references[$record->id] = $record;

            	mysql_free_result($res);
            }
            $this->EndTime("References population", $starttime);
        }
        
        function OutputRecord($id, &$records, &$examples, &$references)
        {
            # Output the record
            $record = $records[$id];
            if ($record == null)
                return;
                
            echo "<01>\t" . $record->notation . "\r\n";
            echo "<02>\t" . $record->caption . "\r\n";
            if ($record->including != "")
            {
                echo "<03>\t" . $record->including . "\r\n";
            }
            
            if ($record->scopenote != "")
            {
                echo "<04>\t" . $record->scopenote . "\r\n";
            }
    
            if ($record->appnote != "")
            {
                echo "<05>\t" . $record->appnote . "\r\n";
            }

            if (isset($examples[$record->id]))
            {            
                $example = $examples[$record->id];
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
                    echo "<09>\t" . $ref . "\r\n";
                }
            }
            
            if ($record->infonote != "")
            {
                echo "<10>\t" . $record->infonote. "\r\n";
            }

            echo "\r\n";    
        }
        
        function ProcessRecords(&$classmarks, &$records, &$examples, &$references)
        {
            echo "Key:\r\n\r\n";
            echo "<01>\tUDC Notation\r\n";
            echo "<02>\tCaption\r\n";
            echo "<03>\tIncluding\r\n";
            echo "<04>\tScope Note\r\n";
            echo "<05>\tApplication Note\r\n";
            echo "<06>\tExample of combination\r\n";
            echo "<09>\tReference\r\n";
            echo "<10>\tInformation Note\r\n\r\n";
            
            $exportlanguage = $_SESSION['exportlang'];
            if ($exportlanguage != 1)
                echo "Note: A field description of NULL indicates that the record has not been translated from English\r\n\r\n";
                                   
            foreach($classmarks as $id)
            {
                $this->OutputRecord($id, $records, $examples, $references);
            }
        }        
    };

?>