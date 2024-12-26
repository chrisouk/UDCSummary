<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    include_once("getrestrictednotations.php");
    
    class CaptionProcessor
    {
        function ListRecords(&$dbc, &$classmarks, $fetch_type)
        {
            $sql = "select c.classmark_id, classmark_enc_tag from classmarks c ";
            $joinclause = "";
            $whereclause = " where c.active = 'Y' ";
            
            $getsql = GetFetchSQL($fetch_type, $sql, $joinclause, $whereclause) . " order by classmark_enc_tag";
            #echo $getsql . "\r\n";
            
           	$res = @mysql_query($getsql, $dbc);
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
                
        function RetrieveRecords(&$dbc, &$records, $lang, $fetch_type)
        {
            #echo "Caption level = " . $this->caption_level . "\r\n\r\n";
            # Fetch all notations and captions
            $sql =  "select c.classmark_id, c.classmark_tag, f.description, c.classmark_enc_tag". 
                    " from classmarks c ";
            $joinclause = " join language_fields f on c.classmark_id = f.classmark_id and f.language_id = " . $lang . " and f.field_id = 1";
            $whereclause = " where c.active = 'Y' ";

            $getsql = GetFetchSQL($fetch_type, $sql, $joinclause, $whereclause) . " order by classmark_enc_tag";
            
            #echo $sql . "\r\n";
                                                 
           	$res = @mysql_query($getsql, $dbc);
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
            if (!isset($records[$id]))
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