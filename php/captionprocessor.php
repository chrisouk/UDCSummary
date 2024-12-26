<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    class CaptionProcessor
    {
        function CaptionProcessor($level)
        {
            $this->caption_level = $level;
        }
        
        function ListRecords(&$dbc, &$classmarks)
        {
            $sql = "select c.classmark_id from classmarks c join classmark_hierarchy h on c.classmark_id = h.classmark_id where c.deleted = 'N' ";
            if ($this->caption_level > 0)
            {
                $sql .= " and c.hierarchy_level <= " . $this->caption_level;
            } 
            $sql .= " order by h.hierarchy_code";
            #echo $sql . "\r\n";
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
            #echo "Caption level = " . $this->caption_level . "\r\n\r\n";
            # Fetch all notations and captions
            $sql =  "select c.classmark_id, c.classmark_tag, f.description". 
                    " from classmarks c join language_fields f on c.classmark_id = f.classmark_id and f.language_id = " . $lang . " and f.field_id = 1" .
                    " where c.deleted = 'N' ";
            if ($this->caption_level > 0)
            {
                $sql .= " and c.hierarchy_level <= " . $this->caption_level;
            }
                                               
            #echo $sql . "\r\n";
                                                 
           	$res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[0];
                    #echo "Found: " . $row[0] . "\r\n";

                    $record = new UDCRecord();
                  
                    $id = $row[0];
            		$record->notation = $row[1];
                    $record->caption = $row[2];
                    $records[$id] = $record;
                    #echo "Added: " . $row[0] . " " . $row[1] . "\r\n";
            	}
            	mysql_free_result($res);
            }
            else
            {
                #echo "No joy " . @mysql_error($res) . "\r\n";
            }
        }
        
        function OutputRecord($id, $records)
        {
            # Output the record
            if (!array_key_exists($id, $records))
            {
                #echo $id . " does not exist\r\n";
                return;
            }
            
            $record = $records[$id];
            
            echo $record->notation . "\t" . $record->caption . "\r\n";
            
            echo "\r\n";    
        }
        
        function ProcessRecords(&$classmarks, &$records)
        {
            if ($this->caption_level > 0)
            {
                echo "Level " . $this->caption_level . " Captions\r\n\r\n";
            }
            else
            {
                echo "All Captions\r\n\r\n";
            }
            
            #echo "Processing " . count($classmarks) . " records\r\n";
            foreach($classmarks as $id)
            {
                #echo "Output: " . $is . "\r\n";
                $this->OutputRecord($id, $records);
            }
        }        
        
        var $caption_level;        
    };

?>