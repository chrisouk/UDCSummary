<?php
    /**
     * MRF Updates
     *  
     * This package takes an input text block and converts the contents 
     * into a format usable by the E&C update process.  It then uploads 
     * them to an mrf_udpate table ready for processing. It expects the 
     * input block to contain records of the form:
     * 
     * <001>	821.512.1
     * <100>	^eLiterature of Turkic languages
     * <921>	0912
     * <923>	E&C31
     * <922>	100
     * #
     *     
     * i.e. a number of fields - one per line, a label tab separated from 
     * a value with a # character on a separate line denoting the record 
     * end
     * 
     * @author       Chris Overfield
     * @copyright    2010
     * @package      MRFUpdates
     */

    session_start();
    
    require_once("checksession.php");
    checksession();
        
    if(!isset($_SESSION['updates_allowed']) || $_SESSION['updates_allowed'] == "N")
    {
        header("Location: ../login.htm");
        exit();
    } 
    
    class Record
    {
        var $id = 0;
        var $caption = "";
        var $notation = "";
        var $including = "";
        var $scope_note = "";
        var $app_note = "";
        var $info_note = "";
        var $examples = array();
        var $refs = array();
        var $revision_date = "";
        var $revision_fields = array();
        var $revision_source = "";
        var $editorial_note = "";
        
        function Clear()
        {
            $this->id = 0;
            $this->caption = "";
            $this->notation = "";
            $this->including = "";
            $this->scope_note = "";
            $this->app_note = "";
            $this->info_note = "";
            while(count($this->examples) > 0) array_shift($this->examples);
            while(count($this->refs) > 0) array_shift($this->refs);
            $this->revision_date = "";
            while(count($this->revision_fields) > 0) array_shift($this->revision_fields);
            $this->revision_source = "";
            $this->editorial_note = "";
        }
    }
    
    function CompareField($field_label, $field_id, $id, $record_field, &$langfields, &$comparisons, $classmark_id, $generatesql, &$changed_fields)
    {
        $diagnosis = "NO CHANGE";
        if ($record_field != "")
        {
            $old_field = "";
            
            //echo "Looking for: " . $field_id . "<br>\n";
            //var_dump($langfields);
            if (array_key_exists($field_id, $langfields))
            {
                $old_field = $langfields[$field_id];
                if ($record_field != $old_field)
                {
                    $diagnosis = "CHANGE";
                }
            }
            else
            {
                $diagnosis = "NEW VALUE";
            }
            
            if ($generatesql)
            {
                if ($diagnosis != "NO CHANGE")
                {
                    $compstring =  "replace into language_fields (field_id, language_id, seq_no, description, classmark_id) values (" . $field_id . ", 1, 1, convert('" . @mysql_real_escape_string($record_field) . 
                                   "' using utf8), " . $classmark_id  . ")";
                }               
            }
            else
            {
                $compstring = "<tr><td class=xl2216681 nowrap>";
                if ($field_id == 1)
                    $compstring .= $id;
                else
                    $compstring .= "&nbsp;";
                    
                $compstring .= "</td><td class=xl2216681 nowrap>" . htmlentities($field_label, ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" . htmlentities($old_field, ENT_COMPAT, "UTF-8") . 
                               "</td><td class=xl2216681 nowrap>" . htmlentities($record_field, ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" . $diagnosis . "</td></tr>";
            } 
            array_push($comparisons, $compstring);
        }
        
        if ($diagnosis != "NO CHANGE")
        {
            switch($field_id)
            {
                case 1:
                    if ($changed_fields != "") $changed_fields .= ", 100"; else $changed_fields .= "100";
                    break;
                case 4:
                    if ($changed_fields != "") $changed_fields .= ", 105"; else $changed_fields .= "105";
                    break;
                case 5:
                    if ($changed_fields != "") $changed_fields .= ", 110"; else $changed_fields .= "110";
                    break;
                case 6:
                    if ($changed_fields != "") $changed_fields .= ", 111"; else $changed_fields .= "111";
                    break;
                case 10:
                    if ($changed_fields != "") $changed_fields .= ", 114"; else $changed_fields .= "114";
                    break;
                default:
                    break;
            }
        }
    }               

    function CompareRevisions(&$dbc, $record, &$comparisons, $generatesql, &$changed_fields)
    {
        $refarray = array();
        $seq_no = 0;
        $sql =  "select r.notation, f.description, r.sequence_no from classmark_refs r join classmarks c on r.notation = c.classmark_tag left outer join language_fields f on f.classmark_id = c.classmark_id " .
                "where f.field_id = 1 and f.language_id = 1 and f.seq_no = 1 and c.active = 'Y' and r.classmark_id = " . $record->id . " order by r.sequence_no";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $key = trim($row[0]);
                //echo "Adding: [" . $key . "] = " . $row[1] . "<br>\n";
                $refarray[$key] = $row[1];
                $seq_no = $row[2];
            }
        }
        @mysql_free_result($res);
        
        $seq_no++;
        
        $changed = false;
        foreach($record->refs as $ref)
        {
            $ref = trim(str_replace('^a', "", $ref));
            $compstring = "";
            
            if (array_key_exists($ref, $refarray))
            {
                if ($generatesql == false)
                {
                    $compstring = "<tr><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>Reference</td><td class=xl2216681 nowrap>" . 
                                  htmlentities($ref, ENT_COMPAT, "UTF-8"). "</td><td class=xl2216681 nowrap>" . htmlentities($ref, ENT_COMPAT, "UTF-8"). "</td><td class=xl2216681 nowrap>NO CHANGE</td></tr>";
                }
            }
            else
            {
                if ($generatesql)
                {
                    $changed = true;
                    $compstring = "insert into classmark_refs (classmark_id, sequence_no, notation) values (" . $record->id . ", " . $seq_no++ . ", '" . @mysql_real_escape_string($ref) . "')"; 
                }
                else
                {
                    $compstring = "<tr><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>Reference</td><td class=xl2216681 nowrap>" . 
                                  "&nbsp;</td><td class=xl2216681 nowrap>" . htmlentities($ref, ENT_COMPAT, "UTF-8"). "</td><td class=xl2216681 nowrap>NEW VALUE</td></tr>";
                }                
            }
            array_push($comparisons, $compstring);
        }                 
        
        if ($changed)
        {
            if ($changed_fields != "") $changed_fields .= ", 125"; else $changed_fields .= "125";
        }
    }
    
    function CompareExamples(&$dbc, $record, &$comparisons, $generatesql, &$changed_fields)
    {
        $seq_no = 0;
        $exarray = array();
        $sql =  "select e.tag, e.field_type, f.description, e.seq_no from example_classmarks e left outer join language_fields f on f.classmark_id = e.classmark_id " .
                "where f.field_id = 2 and f.language_id = 1 and f.seq_no = e.seq_no and e.classmark_id = " . $record->id . " order by e.seq_no";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $tag = $row[0];
                $type = $row[1];
                $desc = $row[2];
                $seq_no = $row[3];
                //echo "Adding: [" . $key . "] = " . $row[1] . "<br>\n";
                $exarray[$tag] = $seq_no . "#". $type . '#' . $desc;
            }
        }
        @mysql_free_result($res);

        $seq_no++;
        
        $changed = false;
        foreach($record->examples as $ex)
        {
            $recordex = explode("^d", $ex, 2);
            $extype = "";
            $exnotation = "";
            $exdesc = "";
            if (count($recordex) == 2)
            {
                $extype = trim(substr($recordex[0],1,1));
                $exnotation = trim(substr($recordex[0],2));
                $exdesc = trim($recordex[1]);
            }
            
            $compstring = "";
            if (array_key_exists($exnotation, $exarray))
            {
                $dbex = $exarray[$exnotation];
                $dbexar = explode("#", $dbex, 3);
                
                if (trim($extype) != trim($dbexar[1]) || trim($exdesc) != trim($dbexar[2]))
                {
                    if ($generatesql)
                    {
                        $changed = true;
                        $compstring = "update example_classmarks set field_type = '" . $extype . "', tag='" . @mysql_real_escape_string($exnotation) . "' where classmark_id = " . $record->id . 
                                      " and seq_no = " . $dbexar[0];
                        array_push($comparisons, $compstring);
                        $compstring = "update language_fields set description = convert('" . @mysql_real_escape_string($exdesc) . "' using utf8) where classmark_id = " . $record->id . 
                                      " and field_id = 2 and language_id = 1 and seq_no = " . $dbexar[0];
                    }
                    else
                    {
                        $compstring = "<tr><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>Example</td><td class=xl2216681 nowrap>" .
                                      $exnotation . " [" . $dbexar[1] . "] " . htmlentities($dbexar[2], ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" . 
                                      $exnotation . " [" . $extype . "] " . htmlentities($exdesc, ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>CHANGED</td></tr>";
                    }
                }
                else
                {
                    if ($generatesql == false)
                    {
                        $compstring = "<tr><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>Example</td><td class=xl2216681 nowrap>" .
                                      $exnotation . " [" . $dbexar[1] . "] " . htmlentities($dbexar[2], ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" . 
                                      $exnotation . " [" . $extype . "] " . htmlentities($exdesc, ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>NO CHANGE</td></tr>";
                    }
                }
            }
            else
            {
                if ($generatesql)
                {
                    $changed = true; 
                    $compstring = "insert into example_classmarks (classmark_id, field_type, seq_no, tag) values (" . $record->id . ", '" . $extype . "', " . $seq_no . ", '" . 
                    @mysql_real_escape_string($exnotation) . "')";
                    array_push($comparisons, $compstring);
                    $compstring = "insert into language_fields (classmark_id, field_id, language_id, seq_no, description) values (" . $record->id . ", 2, 1, " . $seq_no++ . ", convert('" . 
                    @mysql_real_escape_string($exdesc) . "' using utf8))";
                }
                else
                {
                    $compstring = "<tr><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>Example</td><td class=xl2216681 nowrap>" .
                                  "&nbsp;</td><td class=xl2216681 nowrap>" . 
                                  $exnotation . " [" . $extype . "] " . htmlentities($exdesc, ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>NEW VALUE</td></tr>";
                }
            }

            array_push($comparisons, $compstring);
        }
        
        if ($changed)
        {
            if ($changed_fields != "") $changed_fields .= ", 115"; else $changed_fields .= "115";
        }                         
    }

    function CompareEditorialNote(&$dbc, $record, &$comparisons, $generatesql, &$changed_fields)
    {
        $editorial_note = trim($record->editorial_note);
        
        if ($editorial_note == "")
            return;
            
        $edarray = explode("\t", $editoral_note, 2);
        $note = trim($edarray[1]);
            
        $ednote = "";
        $sql =  "select a.annotation from other_annotations a where a.revision_field = '955' and a.classmark_id = " . $record->id;
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            if (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $ednote = trim($row[0]);
            }
        }
        @mysql_free_result($res);

        $changed = false;
        $compstring = "";
        if ($ednote == "")
        {
            if ($generatesql)
            {
                $changed = true;
                $compstring = "insert into other_annotations (classmark_id, revision_field, annotation) values (" . $record->id . ", '955', convert('" . @mysql_real_escape_string($note) . "' using utf8))";
            }
            else
            {
                $compstring .= "<tr><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>Editorial Note</td><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>" . 
                               htmlentities($note, ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>NEW VALUE</td></tr>";
            }
        } 
        else if ($ednote != $note)
        {
            if ($generatesql)
            {
                $changed = true;
                $compstring = "update other_annotations set annotation = convert('" . @mysql_real_escape_string($note) . "' using utf8) where revision_field = '955' and classmark_id = " . $record->id;
            }
            else
            {
                $compstring .= "<tr><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>Editorial Note</td><td class=xl2216681 nowrap>" . htmlentities($ednote, ENT_COMPAT, "UTF-8") . 
                               "</td><td class=xl2216681 nowrap>" . htmlentities($note, ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>CHANGED</td></tr>";
            }
        }
        else
        {
            if ($generatesql == false)
            {
                $compstring .= "<tr><td class=xl2216681 nowrap>&nbsp;</td><td class=xl2216681 nowrap>Editorial Note</td><td class=xl2216681 nowrap>" . htmlentities($ednote, ENT_COMPAT, "UTF-8") . 
                               "</td><td class=xl2216681 nowrap>" . htmlentities($note, ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>NO CHANGE</td></tr>";
            }            
        }
        array_push($comparisons, $compstring);
        
        if ($changed)
        {
            if ($changed_fields != "") $changed_fields .= ", 955"; else $changed_fields .= "955";
        }        
    }

    function ProcessRecord(&$dbc, $record, &$comparisons)
    {
        $field_list = "1";
        if ($record->including != "")
        {
            $field_list .= ", 4";
        }

        if ($record->scope_note != "")
        {
            $field_list .= ", 5";
        }

        if ($record->app_note != "")
        {
            $field_list .= ", 6";
        }

        if ($record->info_note != "")
        {
            $field_list .= ", 10";
        }
            
        $langfields = array();
        
        $sql = "select field_id, description from language_fields where language_id = 1 and field_id in (" . $field_list . ") and classmark_id = " . $record->id;
        //echo $sql . "<br>\n";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $key = $row[0];
                //echo "Adding: [" . $key . "] = " . $row[1] . "<br>\n";
                $langfields[$key] = $row[1];
            }
        }
        @mysql_free_result($res);
        
        $changed_fields = "";
        
        CompareField("Caption", 1, $record->notation, $record->caption, $langfields, $comparisons, $record->id, false, $changed_fields);
        CompareField("Including", 4, $record->notation, $record->including, $langfields, $comparisons, $record->id, false, $changed_fields);
        CompareField("Scope Note", 5, $record->notation, $record->scope_note, $langfields, $comparisons, $record->id, false, $changed_fields);
        CompareField("App Note", 6, $record->notation, $record->app_note, $langfields, $comparisons, $record->id, false, $changed_fields);
        CompareField("Information Note", 10, $record->notation, $record->info_note, $langfields, $comparisons, $record->id, false, $changed_fields);
        CompareRevisions($dbc, $record, $comparisons, false, $changed_fields);
        CompareExamples($dbc, $record, $comparisons, false, $changed_fields);
        CompareEditorialNote($dbc, $record, $comparisons, false, $changed_fields);
    }

    function PerformRecordUpdate(&$dbc, $record, &$comparisons)
    {
        $field_list = "1";
        if ($record->including != "")
        {
            $field_list .= ", 4";
        }

        if ($record->scope_note != "")
        {
            $field_list .= ", 5";
        }

        if ($record->app_note != "")
        {
            $field_list .= ", 6";
        }

        if ($record->info_note != "")
        {
            $field_list .= ", 10";
        }
            
        $langfields = array();
        
        $sql = "select field_id, description from language_fields where language_id = 1 and field_id in (" . $field_list . ") and classmark_id = " . $record->id;
        //echo $sql . "<br>\n";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $key = $row[0];
                //echo "Adding: [" . $key . "] = " . $row[1] . "<br>\n";
                $langfields[$key] = $row[1];
            }
        }
        @mysql_free_result($res);
        
        $changed_fields = "";
        
        CompareField("Caption", 1, $record->notation, $record->caption, $langfields, $comparisons, $record->id, true, $changed_fields);
        CompareField("Including", 4, $record->notation, $record->including, $langfields, $comparisons, $record->id, true, $changed_fields);
        CompareField("Scope Note", 5, $record->notation, $record->scope_note, $langfields, $comparisons, $record->id, true, $changed_fields);
        CompareField("App Note", 6, $record->notation, $record->app_note, $langfields, $comparisons, $record->id, true, $changed_fields);
        CompareField("Information Note", 10, $record->notation, $record->info_note, $langfields, $comparisons, $record->id, true, $changed_fields);
        CompareRevisions($dbc, $record, $comparisons, true, $changed_fields);
        CompareExamples($dbc, $record, $comparisons, true, $changed_fields);
        CompareEditorialNote($dbc, $record, $comparisons, true, $changed_fields);
        
        if ($changed_fields != "")
        {
            $audit_date = "";
            $audit_source = "";
            $audit_comment = "";
            
            $sql = "select audit_date, audit_source, audit_comment from audit_history where audit_type = 'R' and classmark_id = " . $record->id; 
            $res = mysql_query($sql, $dbc);
            if ($res)
            {
                if (($row = mysql_fetch_array($res, MYSQL_NUM)))
                {
                    $audit_date = $row[0];
                    $audit_source = $row[1];
                    $audit_comment = $row[2];
                }
            }
            @mysql_free_result($res);
            
            if ($audit_date != "")
            {
                if ($audit_date != $_SESSION['revision_date'])
                {
                    // Store the current audit_history data in revision_history and revision_history_fields
                    $sql = "insert into revision_history (classmark_id, revision_date, revision_source, revision_comment) select " . $record->id . ", a.audit_date, a.audit_source, a.audit_comment " .
                           "from audit_history a where a.classmark_id = " . $record->id . " and a.audit_type = 'R' " .
                           "and not exists (select 1 from revision_history h where h.classmark_id = a.classmark_id and h.revision_date = a.audit_date)";
                    array_push($comparisons, $sql);
    
                    $sql =  "insert into revision_history_fields (classmark_id, revision_date, revision_field) select f.classmark_id, f.revision_date, f.revision_field from revision_fields f " .
                            "where f.classmark_id = " . $record->id . " and not exists (select 1 from revision_history_fields h where h.classmark_id = f.classmark_id " .
                            "and h.revision_date = f.revision_date and h.revision_field = f.revision_field)"; 
                    array_push($comparisons, $sql);
                }
                                
                // Store the new revision details in audit_history and revision_fields
                $sql =  "update audit_history set audit_date = '" . $_SESSION['revision_date'] . "', audit_source = '" . $_SESSION['revision_name'] . "', audit_comment = '' ".
                        "where audit_type = 'R' and classmark_id = " . $record->id;
                array_push($comparisons, $sql);
                
                array_push($comparisons, "delete from revision_fields where classmark_id = " . $record->id);
                $revision_fields = explode(",", $changed_fields);
                foreach($revision_fields as $field)
                {
                    $sql =  "insert into revision_fields (classmark_id, revision_date, revision_field) values (" . $record->id . ", '" . $_SESSION['revision_date'] . 
                            "', '" . trim($field) . "')"; 
                    array_push($comparisons, $sql);
                }
            }
            else
            {
                $sql =  "insert into audit_history (classmark_id, audit_type, audit_date, audit_source) values (" . $record->id . ", 'R', '" . $_SESSION['revision_date'] . 
                        "', '" . $_SESSION['revision_name'] . "')";
                array_push($comparisons, $sql);
                
                $revision_fields = explode(",", $changed_fields);
                foreach($revision_fields as $field)
                {
                    $sql =  "insert into revision_fields (classmark_id, revision_date, revision_field) values (" . $record->id . ", '" . $_SESSION['revision_date'] . 
                            "', '" . trim($field) . "')";
                    array_push($comparisons, $sql);
                }            
            }
        }        
    }
    
    function CompareRecord(&$dbc, $record, &$comparisons)
    {
        $compare_record = new Record();
        
        $sql = "select c.classmark_id, f.field_id, f.description from classmarks c join language_fields f on c.classmark_id = f.classmark_id where c.active = 'Y' and c.classmark_tag = '" . $record->notation . "'";
        array_push($comparisons, $sql);
        
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $compare_record->id = $row[0];
                switch($row[1])
                {
                    case 1:
                        $compare_record->caption = trim($row[2]);
                        array_push($comparisons, "Loaded caption: " . $compare_record->caption);
                        break;
                    case 4:
                        $compare_record->including = trim($row[2]);
                        array_push($comparisons, "Loaded including: " . $compare_record->including);
                        break;
                    case 5:
                        $compare_record->scope_note = trim($row[2]);
                        array_push($comparisons, "Loaded scope: " . $compare_record->scope_note);
                        break;
                    case 6:
                        $compare_record->app_note = trim($row[2]);
                        array_push($comparisons, "Loaded app: " . $compare_record->app_note);
                        break;
                    case 10:
                        $compare_record->info_note = trim($row[2]);
                        array_push($comparisons, "Loaded info: " . $compare_record->info_note);
                        break;
                    default:
                        array_push($comparisons, "Unknown field: " . $row[2]);
                        break;
                }
            }
        }
        @mysql_free_result($res);
        
        if ($record->caption != "")
        {
            if ($compare_record->caption != $record->caption)
            {
                array_push($comparisons, "UNMATCHED CAPTION: [". $record->caption . "] vs [" . $compare_record->caption . "]");
            }
            else
            {
                array_push($comparisons, "MATCHED CAPTION: [". $record->caption . "] vs [" . $compare_record->caption . "]");
            }
        }

        if ($record->including != "")
        {
            if ($compare_record->including  != $record->including )
            {
                array_push($comparisons, "UNMATCHED including : [". $record->including  . "] vs [" . $compare_record->including  . "]");
            }
        }

        if ($record->scope_note != "")
        {
            if ($compare_record->scope_note != $record->scope_note)
            {
                array_push($comparisons, "UNMATCHED CAPTION: [". $record->scope_note . "] vs [" . $compare_record->scope_note . "]");
            }
        }

        if ($record->app_note != "")
        {
            if ($compare_record->app_note != $record->app_note)
            {
                array_push($comparisons, "UNMATCHED appnote: [". $record->app_note . "] vs [" . $compare_record->app_note . "]");
            }
        }

        if ($record->info_note != "")
        {
            if ($compare_record->info_note != $record->info_note)
            {
                array_push($comparisons, "UNMATCHED info_note: [". $record->info_note . "] vs [" . $compare_record->info_note . "]");
            }
        }

    }
    
    function Reconcile(&$dbc, &$errors, &$comparisons)
    {
        $record = new Record();

        $rectext = $_POST['updatetext'];
        array_push($comparisons, $rectext);
        
        $lines = explode("\n", $rectext);
        
        $currentfield = 0;
        
        foreach($lines as $line)
        {
            $line = trim($line);
            if ($line == "")
                continue;
                
            array_push($comparisons, "LINE: " . $line);
            
            switch($line[0])
            {
                case '!':
                case '+':
                case 'x':
                    // New record
                    if ($record->notation != "")
                    {
                        CompareRecord($dbc, $record, $comparisons);
                        $record->Clear();
                    }
                    
                    $currentfield = 1;
                    $recdetails = explode("\t", $line, 3);
                    $record->notation = trim($recdetails[1]);
                    $record->caption = trim($recdetails[2]);
                    
                    array_push($comparisons, "New notation = " . $record->notation);
                    array_push($comparisons, "New caption  = " . $record->caption);                    
                    break;
                default:
                    switch($currentfield)
                    {
                        case 1:
                            $record->caption .= " " . trim($line);
                            array_push($comparisons, "Extended caption: " . $record->caption);
                            break;
                        default:
                            array_push($comparisons, "IGNORED: " . trim($line));
                            break; 
                    }
                    break;
            }
        }

        /*           
        $sql = "select classmark_id, update_data from mrf_updates";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $classmark_id = $row[0];
                if ($classmark_id != $last_classmark_id && $last_classmark_id != 0)
                {
                    ProcessRecord($dbc, $record, $comparisons);
                    $record->Clear();                        
                }
                
                if ($record->id == 0)
                {
                    $record->id = $classmark_id;
                    $last_classmark_id = $classmark_id;
                }
                
                $datarow = explode("\t", $row[1], 2);
                if (count($datarow) == 2)
                {
                    if (strlen($datarow[0]) >= 5)
                    {
                        $field_id = substr($datarow[0], 1, 3);
                    }
                    else
                    {
                        array_push($errors, "<tr><td class=xl2216681 nowrap>Bad line: [" . $classmark_id . "] " . $row[1] . " (" . $datarow[0] . ")</td></tr>");
                    }
                    $update = $datarow[1];
                }
                else
                {
                    array_push($errors, "<tr><td class=xl2216681 nowrap>Bad line: [" . $classmark_id . "] " . $row[1] . " (" . $datarow[0] . ")</td></tr>");
                }            

                
                switch($field_id)
                {
                    case "001":
                        $record->notation = trim($update);
                        break;
                    case "100":
                        $record->caption = trim($update);
                        break;
                    case "105":
                        $record->including = trim($update);
                        break;
                    case "110":
                        $record->scope_note = trim($update);
                        break;
                    case "111":
                        $record->app_note = trim($update);
                        break;
                    case "114":
                        $record->info_note = trim($update);
                        break;
                    case "115":
                        array_push($record->examples, trim($update));
                        break;
                    case "125":
                        array_push($record->refs, trim($update));
                        break;
                    case "921":
                        $record->revision_date = trim($update);
                        break;
                    case "922":
                        array_push($record->record->fields, trim($update));
                        break;
                    case "923":
                        $record->source = trim($update);
                        break;
                    case "955":
                        $record->editorial_note = trim($update);
                        break;
                    default:
                        array_push($errors, "Unknown field: " . $row[1] . "<br>\n");
                        break;
                }
            }
        }
        @mysql_free_result($res);
        */          
    }

    function CompareUpdates(&$dbc, &$errors, &$comparisons)
    {
        $record = new Record();
        
        $last_classmark_id = 0;
        $sql = "select classmark_id, update_data from mrf_updates";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $classmark_id = $row[0];
                if ($classmark_id != $last_classmark_id && $last_classmark_id != 0)
                {
                    ProcessRecord($dbc, $record, $comparisons);
                    $record->Clear();                        
                }
                
                if ($record->id == 0)
                {
                    $record->id = $classmark_id;
                    $last_classmark_id = $classmark_id;
                }
                
                $datarow = explode("\t", $row[1], 2);
                if (count($datarow) == 2)
                {
                    if (strlen($datarow[0]) >= 5)
                    {
                        $field_id = substr($datarow[0], 1, 3);
                    }
                    else
                    {
                        array_push($errors, "<tr><td class=xl2216681 nowrap>Bad line: [" . $classmark_id . "] " . $row[1] . " (" . $datarow[0] . ")</td></tr>");
                    }
                    $update = $datarow[1];
                }
                else
                {
                    array_push($errors, "<tr><td class=xl2216681 nowrap>Bad line: [" . $classmark_id . "] " . $row[1] . " (" . $datarow[0] . ")</td></tr>");
                }            

                
                switch($field_id)
                {
                    case "001":
                        $record->notation = trim($update);
                        break;
                    case "100":
                        $record->caption = trim($update);
                        break;
                    case "105":
                        $record->including = trim($update);
                        break;
                    case "110":
                        $record->scope_note = trim($update);
                        break;
                    case "111":
                        $record->app_note = trim($update);
                        break;
                    case "114":
                        $record->info_note = trim($update);
                        break;
                    case "115":
                        array_push($record->examples, trim($update));
                        break;
                    case "125":
                        array_push($record->refs, trim($update));
                        break;
                    case "921":
                        $record->revision_date = trim($update);
                        break;
                    case "922":
                        array_push($record->record->fields, trim($update));
                        break;
                    case "923":
                        $record->source = trim($update);
                        break;
                    case "955":
                        $record->editorial_note = trim($update);
                        break;
                    default:
                        array_push($errors, "Unknown field: " . $row[1] . "<br>\n");
                        break;
                }
            }
        }
        @mysql_free_result($res);          
    }
    
    function PerformUpdates(&$dbc, &$errors, &$comparisons)
    {
        $record = new Record();
        
        $last_classmark_id = 0;
        $sql = "select classmark_id, update_data from mrf_updates";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $classmark_id = $row[0];
                if ($classmark_id != $last_classmark_id && $last_classmark_id != 0)
                {
                    PerformRecordUpdate($dbc, $record, $comparisons);
                    $record->Clear();                        
                }
                
                if ($record->id == 0)
                {
                    $record->id = $classmark_id;
                    $last_classmark_id = $classmark_id;
                }
                
                $datarow = explode("\t", $row[1], 2);
                if (count($datarow) == 2)
                {
                    if (strlen($datarow[0]) >= 5)
                    {
                        $field_id = substr($datarow[0], 1, 3);
                    }
                    else
                    {
                        array_push($errors, "<tr><td class=xl2216681 nowrap>Bad line: [" . $classmark_id . "] " . $row[1] . " (" . $datarow[0] . ")</td></tr>");
                    }
                    $update = $datarow[1];
                }
                else
                {
                    array_push($errors, "<tr><td class=xl2216681 nowrap>Bad line: [" . $classmark_id . "] " . $row[1] . " (" . $datarow[0] . ")</td></tr>");
                }            

                
                switch($field_id)
                {
                    case "001":
                        $record->notation = trim($update);
                        break;
                    case "100":
                        $record->caption = trim($update);
                        break;
                    case "105":
                        $record->including = trim($update);
                        break;
                    case "110":
                        $record->scope_note = trim($update);
                        break;
                    case "111":
                        $record->app_note = trim($update);
                        break;
                    case "114":
                        $record->info_note = trim($update);
                        break;
                    case "115":
                        array_push($record->examples, trim($update));
                        break;
                    case "125":
                        array_push($record->refs, trim($update));
                        break;
                    case "921":
                        $record->revision_date = trim($update);
                        break;
                    case "922":
                        array_push($record->record->fields, trim($update));
                        break;
                    case "923":
                        $record->source = trim($update);
                        break;
                    case "955":
                        $record->editorial_note = trim($update);
                        break;
                    default:
                        array_push($errors, "Unknown field: " . $row[1] . "<br>\n");
                        break;
                }
            }
        }
        @mysql_free_result($res);          
    }

    function UploadUpdates(&$dbc)
    {
        $update_text = "";
        $clear_update_table = false;
        
        if (isset($_POST['updatetext']))
        {
            $update_text = $_POST['updatetext'];
        }
    
        if (isset($_POST['cleartable']))
        {
            $sql = "delete from mrf_updates";
            $res = mysql_query($sql, $dbc);
            @mysql_free_result($res);
            $clear_update_table = true;
        }
        
        // Get the next ID number

        $update_id = 1;        
        $sql = "select max(update_id) from mrf_updates";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            if (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $update_id = $row[0];
                $update_id++;                
            }
        }
        @mysql_free_result($res);   
         
        $line_array = explode("\n", $update_text);
        $update_line = "";    
        $line_no = 1;
        
        $errors = array();
        $successful_writes = 0;
        
        $record_lines = array();
        
        foreach($line_array as $line)
        {
            $line = trim($line);
            if ($line == "")
            {
                continue;
            }
            
            //echo "Line: [" . $line . "]<br>\n";
            
            if ($line[0] == '#')
            {
                if ($tag == "")
                {
                    echo "No notation defined for record [" . $update_line . "] at line " . $line_no . "\n";
                }
                else
                {
                    $tag = str_replace("'", "`", trim($tag));
                    foreach($record_lines as $line)
                    {
                        $line = str_replace('^e', '', $line);
                        $line = str_replace("\n", "", $line);
                        
                        $sql =  "insert into mrf_updates (update_id, classmark_id, classmark_tag, update_data, revision_date, revision_name) values (" . $update_id++ . ", 0, '" . @mysql_real_escape_string($tag) . 
                                "', convert('" . @mysql_real_escape_string($line) . "' using utf8), '" . $_SESSION['revision_date'] . "', '" . @mysql_real_escape_string($_SESSION['revision_name']) . "')";
                        //echo $sql . "<br>\n";
                        $res = mysql_query($sql, $dbc);
                        if (!$res)
                        {
                            echo "Problems: " . $sql . "<br>\n";
                        }
                        @mysql_free_result($res);
                    }
                    
                    $successful_writes++;
                }
                    
                $sql =  "commit";
                //echo $sql . "<br>\n";
                $res = mysql_query($sql, $dbc);
                if (!$res)
                {
                    echo "Problems: " . $sql . "<br>\n";
                }
                @mysql_free_result($res);
                
                $tag = "";
                while(count($record_lines) > 0)
                {
                    array_shift($record_lines);
                }                    
            }
            else
            {
                if (strlen($line) > 5)
                {
                    if (substr($line, 0, 5) == "<001>")
                    {
                        $tag = trim(substr($line, 5), "\t ");
                    }
                }
                
                array_push($record_lines, $line);
            }
        }
        
        $errorstring = $successful_writes . " records upload successfully<br>\n";
        if (count($errors) == 0)
        {
            $errorstring .= "There were " . count($errors) . " errors detected:<br><br>\n";
            foreach($errors as $error)
            {
                echo $error . "<br>\n";
            }             
        }
        
        if ($clear_update_table == true)
        {
            $errorstring .= "The update table was cleared before the upload took place<br>.";                
        }

        $sql =  "select count(*) from mrf_updates";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            if (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $errorstring .= "The update table now contains " . $row[0] . " records<br>\n";                
            }
        }
        @mysql_free_result($res);

        // Update classmark IDs
        $sql = "update mrf_updates u set u.classmark_id = (select c.classmark_id from classmarks c where c.classmark_tag = u.classmark_tag and c.active = 'Y')";         
        $res = mysql_query($sql, $dbc);
        if (!$res)
        {
            $errorstring .= "Problems: " . $sql . "<br>\n";
        }
        @mysql_free_result($res);
        
        // Checks
        $errorstring .= "Checking for problems<br>\n";
        $badtags = array();

        $sql = "select classmark_tag from mrf_updates where classmark_id = 0";         
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            while (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                array_push($badtags, $row[0]);                 
            }
        }
        @mysql_free_result($res);
        
        if (count($badtags) >0)
        {
            $errorstring .= "The following notations were not recognised:<br>\n";
            foreach($badtags as $badtag)
            {
                $errorstring .= $badtag . "<br>\n";
            }
        }
        else
        {
            $errorstring .= "No problems detected<br>\n";
        }
        
        $sql =  "select count(*) from mrf_updates where last_rev_date != ''";
        $res = mysql_query($sql, $dbc);
        if ($res)
        {
            if (($row = mysql_fetch_array($res, MYSQL_NUM)))
            {
                $errorstring .= $row[0] . " records have previous revision dates<br>\n";                
            }
        }
        @mysql_free_result($res);
        $errorstring .= "Checks complete<br>\n";
        
        return $errorstring;
    }
    
	require_once("DBConnectInfo.php");
	include_once("specialchars.php");

	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    
    $errors = array();
    $comparisons = array();

    echo $_POST['action'] . "<br>\n";
    
    switch($_POST['action'])
    {
        /*
        case "upload":
            if (isset($_SESSION['revision_date']) && isset($_SESSION['revision_name']))
            {
                echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n";
                echo "\"http://www.w3.org/TR/html4/loose.dtd\">\n";
                echo "<html>\n";
                echo "<head>\n";
                echo "<title>UDC Summary</title>\n";
                echo "<link rel=\"stylesheet\" href=\"../reset.css\" type=\"text/css\" />\n";
                echo "<link rel=\"stylesheet\" href=\"../udc1000.css\" type=\"text/css\" />\n";
                echo "<link rel=\"StyleSheet\" href=\"dtree.css\" type=\"text/css\" />\n";
                echo "<script type=\"text/javascript\" src=\"dtree.js\"></script>\n";
                echo "<script type=\"text/javascript\" src=\"udcdisplay_7.js\"></script>\n";
                echo "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">\n";
                echo "</head>\n";
                echo "<body>\n";    
            
                echo UploadUpdates($dbc);
                                
                echo "</body>\n";    
                echo "</html>\n";    
            }
            else
            {
                echo "Please set the revision date and name fields<br>\n"; 
            }
            break;
        case "compare":
                      
            $today = date("Y_m_d_Hi");
            $filename = "MRF09Updates_" . $today . ".xls";
            header('Content-type: application/ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename=' . $filename);
            
            echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n";
            echo "xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n";
            echo "xmlns=\"http://www.w3.org/TR/REC-html40\">\n";
            echo "\n";
            echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
            echo "<html>\n";
            echo "<head>\n";
            echo " <meta http-equiv=\"Content-type\" content=\"text/html;charset=utf-8\" />\n";
            echo "<style id=\"Classeur1_16681_Styles\">\n";
            echo "</style>\n";
            echo "\n";
            echo "</head>\n";
            echo "<body>\n";
            echo "\n";
            echo "<div id=\"Classeur1_16681\" align=center x:publishsource=\"Excel\">\n";
            echo "\n";
            echo "<table x:str border=0 cellpadding=0 cellspacing=0 width=100% style='border-collapse: collapse'>\n";
            echo "<tr><td class=xl2216681 nowrap>Notation</td><td class=xl2216681 nowrap>Field</td><td class=xl2216681 nowrap>Old Value</td><td class=xl2216681 nowrap>New Value</td><td class=xl2216681 nowrap>Analysis</td></tr>\n";
             
            CompareUpdates($dbc, $errors, $comparisons);
            foreach($comparisons as $comparison)
            {
                echo $comparison . "\n";
            }
                        
            echo "<tr><td class=xl2216681 nowrap>&nbsp;</td></tr>\n";
            echo "<tr><td class=xl2216681 nowrap>Errors</td></tr>\n";
            foreach($errors as $error)
            {
                echo $error . "\n";
            }
            echo "</table>\n";
            echo "</div>\n";
            echo "</body>\n";
            echo "</html>\n";
            break;
        case "update":
            
            echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n";
            echo "\"http://www.w3.org/TR/html4/loose.dtd\">\n";
            echo "<html>\n";
            echo "<head>\n";
            echo "<title>UDC Summary</title>\n";
            echo "<link rel=\"stylesheet\" href=\"../reset.css\" type=\"text/css\" />\n";
            echo "<link rel=\"stylesheet\" href=\"../udc1000.css\" type=\"text/css\" />\n";
            echo "<link rel=\"StyleSheet\" href=\"dtree.css\" type=\"text/css\" />\n";
            echo "<script type=\"text/javascript\" src=\"dtree.js\"></script>\n";
            echo "<script type=\"text/javascript\" src=\"udcdisplay_7.js\"></script>\n";
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">\n";
            echo "</head>\n";
            echo "<body>\n";    
            
            PerformUpdates($dbc, $errors, $comparisons);
            
            foreach($comparisons as $comparison)
            {
                if (trim($comparison) == "")
                    continue;

                //echo $comparison . "<br>\n";
                if (@mysql_query(trim($comparison), $dbc))
                {
                    echo "OK: " . $comparison . "<br>\n";
                }
                else
                {
                    echo "BAD: " . $comparison . "<br>\n";
                    echo ">>> " . @mysql_error() . "<br>\n";
                }
            }
                            
            echo "</body>\n";    
            echo "</html>\n";
            */
            
        case "reconcile":
            
            echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n";
            echo "\"http://www.w3.org/TR/html4/loose.dtd\">\n";
            echo "<html>\n";
            echo "<head>\n";
            echo "<title>UDC Summary</title>\n";
            echo "<link rel=\"stylesheet\" href=\"../reset.css\" type=\"text/css\" />\n";
            echo "<link rel=\"stylesheet\" href=\"../udc1000.css\" type=\"text/css\" />\n";
            echo "<link rel=\"StyleSheet\" href=\"dtree.css\" type=\"text/css\" />\n";
            echo "<script type=\"text/javascript\" src=\"dtree.js\"></script>\n";
            echo "<script type=\"text/javascript\" src=\"udcdisplay_7.js\"></script>\n";
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">\n";
            echo "</head>\n";
            echo "<body>\n";    
            
            Reconcile($dbc, $errors, $comparisons);
            
            foreach($comparisons as $comparison)
            {
                if (trim($comparison) == "")
                    continue;

                echo $comparison . "<br>\n";
            }
                            
            echo "</body>\n";    
            echo "</html>\n";
            break;               
        default:
            echo "Unknown operation<br>\n";
            break;
  }
  
  @mysql_close($dbc);
  
?>