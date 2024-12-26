<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */
 
    include_once("getrestrictednotations.php");

    class TaggedProcessor
    {
        function ListRecords(&$dbc, &$classmarks, $fetch_type)
        {
            $sql = "select c.classmark_id, c.classmark_enc_tag from classmarks c ";
            $joinclause = "";
            $whereclause = "where c.active = 'Y'";
            
            
            $getsql = GetFetchSQL($fetch_type, $sql, $joinclause, $whereclause) . " order by classmark_enc_tag";
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
            # Fetch all language fields
            
            $sql = "select c.classmark_id, c.classmark_tag from classmarks c";
            $joinclause = "";
            $whereclause = " where c.active = 'Y'";
                   
            $getsql = GetFetchSQL($fetch_type, $sql, $joinclause, $whereclause); 
            #echo $getsql . "<br>\n";
                   
           	$res = @mysql_query($getsql, $dbc);
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
            $sql =  "select c.classmark_id, f.field_id, f.description from classmarks c";
            $joinclause = "join language_fields f on c.classmark_id = f.classmark_id and f.language_id = " . $lang . " and f.field_id in (1,4,5,6)";
            $whereclause = "where c.active = 'Y'";
              
            $getsql = GetFetchSQL($fetch_type, $sql, $joinclause, $whereclause);     
            ##echo $getsql . "<br>\n";
                   
           	$res = @mysql_query($getsql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[0];
                    if (isset($records[$id]))
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
            $sql = "select c.classmark_tag, e.field_type, e.tag, f.description, c.classmark_id, e.seq_no from classmarks c"; 
            $joinclause = " join example_classmarks e on c.classmark_id = e.classmark_id and c.active = 'Y'" .
                          " join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 2 and e.seq_no = f.seq_no and f.language_id = " . $lang;
            $whereclause = "";
            
            $getsql = GetFetchSQL($fetch_type, $sql, $joinclause, $whereclause) . " order by 5,6";
            #echo $getsql . "<br>\n";
                   
           	$res = @mysql_query($getsql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[4];
                    if (isset($records[$id]))
                    {
                        $record = $records[$id];
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
                        $records[$id] = $record;                    
                    }
            	}
            	mysql_free_result($res);
            }
    
            # References
            $sql = "select r.notation, f.description, r.classmark_id, r.sequence_no from classmark_refs r";
            $joinclause = " join classmarks c on c.classmark_tag = r.notation and c.active = 'Y'" .
                          " join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang;
            $whereclause = "";
            
            $getsql = GetFetchSQL($fetch_type, $sql, $joinclause, $whereclause) . " order by 3,4";       
            #echo $getsql . "<br>\n";
                             
           	$res = @mysql_query($getsql, $dbc);
            if ($res)
            {
            	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $id = $row[2];        	   
                    if (isset($records[$id]))
                    {
                        $record = $records[$id];
                        array_push($record->refs, $row[0] . " " . $row[1]);
                        $records[$id] = $record;                    
                    }
            	}
            	mysql_free_result($res);
            }
        }
        
        function OutputRecord($id, $records)
        {
            # Output the record
            if (!isset($records[$id]))
            {
                return;
            }
            
            $record = $records[$id];
            
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
            
            if (count($record->examples) > 0)
            {
                foreach($record->examples as $example)
                {
                    echo $example . "\r\n";
                    /*
                    if (strpos($example, "&") !== FALSE && strpos($example, ";") !== FALSE)
                        echo utf8_encode(html_entity_decode($example, ENT_QUOTES, "UTF-8")) . "\r\n";
                        #echo html_entity_decode($example, ENT_QUOTES, "UTF-8") . "\r\n";
                    else
                        //#echo utf8_encode($example) . "\r\n";
                        echo $example . "\r\n";
                    */
                }
            }
    
            if (count($record->refs) > 0)
            {
                foreach($record->refs as $ref)
                {
                    echo "<09>\t" . $ref . "\r\n";
                }
            }
            
            echo "\r\n";    
        }
        
        function ProcessRecords(&$classmarks, &$records)
        {
            echo "Key:\r\n\r\n";
            echo "<01>\tUDC Notation\r\n";
            echo "<02>\tCaption\r\n";
            echo "<03>\tIncluding\r\n";
            echo "<04>\tScope Note\r\n";
            echo "<05>\tApplication Note\r\n";
            echo "<06>\tExample of combination\r\n";
            echo "<09>\tReference\r\n\r\n";
                    
            foreach($classmarks as $id)
            {
                $this->OutputRecord($id, $records);
            }
        }        
    };

?>