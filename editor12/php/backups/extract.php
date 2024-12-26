<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    function GetFieldDescription($field_id)
    {
        $desc = "";
        
        switch($field_id)
        {
            case 1:
                $desc = "Caption";
                break;
            case 2:
                $desc = "Example";
                break;
            case 4:
                $desc = "Including";
                break;
            case 5:
                $desc = "Scope Note";
                break;
            case 6:
                $desc = "Application Note";
                break;
            case 10:
                $desc = "Information Note";
                break;
            default:
                $desc = "Unknown";
                break; 
        }
        
        return $desc;
    }
    
    function RefreshStats()
    {
        require_once("DBConnectInfo.php");
        
        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        
        $sql = "delete from translation_status_fields";
        echo $sql . "<br>\n";
                
        $res = @mysql_query($sql, $dbc);
        if ($res)
        {
            echo "SQL delete successful<br>\n";
        }
        else
        {
            echo "SQL failure: " . @mysql_error($res) . "<br>\n";
        }
        
        $sql =  "insert into translation_status_fields (classmark_id, language_id, field_id, lang_field_count) ".
                "select f.classmark_id, f.language_id, f.field_id, count(f.field_id) ".
                "from language_fields f join classmarks c on c.classmark_id = f.classmark_id ".
                "where c.active = 'Y' and f.field_id in (1,2,4,5,6) group by classmark_id, language_id, field_id";
                
        echo $sql . "<br>\n"; 
        $res = @mysql_query($sql, $dbc);
        if ($res)
        {
            echo "SQL update successful<br>\n";
        }
        else
        {
            echo "SQL failure: " . @mysql_error($res) . "<br>\n";
        }
        
        @mysql_close($dbc);
        
        echo "Stats refreshed<br>\n";
    }
    
    function ProcessField($str, $decode, $quote)
    {
        if ($decode)
        {
            //$str = utf8_decode($str);
        }
        
        if(strstr($str, '"')) 
        {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
        else if ($quote)
        {
            //$str = '"' . $str . '"';
        }
        
        return $str;  
    }
     
    function ProcessLevel2Captions($lang1, $lang2, $langdesc1, $langdesc2, &$results)
    {
        require_once("DBConnectInfo.php");
        
        $languages = array();
                
        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        
        $sql = "select c.classmark_tag, f.description, IFNULL(f2.description,'NULL'), h.hierarchy_code " .
                "from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " . 
                "join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang1 . 
                " left outer join language_fields f2 on c.classmark_id = f2.classmark_id and f2.field_id = 1 and f2.language_id = " . $lang2 .
                " where c.active = 'Y' and c.hierarchy_level < 3 " .
                "order by h.hierarchy_code";
                
        $res = @mysql_query($sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td>Field</td><td class=xl2216681 nowrap>" . $langdesc1 . "</td><td class=xl2216681 nowrap>" . $langdesc2 . 
                                  "</td></tr>");                                                                 

            
            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetFieldDescription(1) . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[1], ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[2], ENT_COMPAT, "UTF-8") . 
                                     "</td></tr>");                                                                 
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }

    function ProcessAllCaptions($lang1, $lang2, $langdesc1, $langdesc2, &$results)
    {
        require_once("DBConnectInfo.php");
        
        $languages = array();
                
        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        
        $sql = "select c.classmark_tag, f.description, IFNULL(f2.description,'NULL'), h.hierarchy_code " .
                "from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " . 
                "join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang1 . 
                " left outer join language_fields f2 on c.classmark_id = f2.classmark_id and f2.field_id = 1 and f2.language_id = " . $lang2 .
                " where c.active = 'Y' " .
                "order by h.hierarchy_code";
                
        $res = @mysql_query($sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td>Field</td><td class=xl2216681 nowrap>" . $langdesc1 . "</td><td class=xl2216681 nowrap>" . $langdesc2 . 
                                  "</td></tr>");                                                                 

            
            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetFieldDescription(1) . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[1], ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[2], ENT_COMPAT, "UTF-8") . 
                                     "</td></tr>");                                                                 
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }
    
    function ProcessCaptionsIncluding($lang1, $lang2, $langdesc1, $langdesc2, &$results)
    {
        require_once("DBConnectInfo.php");
        
        $languages = array();
                
        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        
        $sql = "select c.classmark_tag, f.field_id, f.description, IFNULL(f2.description,'NULL'), h.hierarchy_code " .
                "from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " . 
                "join language_fields f on c.classmark_id = f.classmark_id and f.field_id in (1,4) and f.language_id = " . $lang1 . 
                " left outer join language_fields f2 on c.classmark_id = f2.classmark_id and f2.field_id = f.field_id and f2.language_id = " . $lang2 .
                " where c.active = 'Y' " .
                "order by h.hierarchy_code, field_id";
                
        $res = @mysql_query($sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td>Field</td><td class=xl2216681 nowrap>" . $langdesc1 . "</td><td class=xl2216681 nowrap>" . $langdesc2 . 
                                  "</td></tr>");                                                                 

            
            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetFieldDescription($row[1]) . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[2], ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[3], ENT_COMPAT, "UTF-8") . 
                                     "</td></tr>");                                                                 
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }
    
    function ProcessAllFields($lang1, $lang2, $langdesc1, $langdesc2, &$results)
    {
        require_once("DBConnectInfo.php");
        
        $languages = array();
                
        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        
        $sql = "select c.classmark_tag, f.field_id, f.description, IFNULL(f2.description,'NULL'), h.hierarchy_code " .
                "from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " . 
                "join language_fields f on c.classmark_id = f.classmark_id and f.field_id in (1,4,5,6) and f.language_id = " . $lang1 . 
                " left outer join language_fields f2 on c.classmark_id = f2.classmark_id and f2.field_id = f.field_id and f2.language_id = " . $lang2 .
                " where c.active = 'Y' " .
                "order by h.hierarchy_code, field_id";
                
        $res = @mysql_query($sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td>Field</td><td class=xl2216681 nowrap>" . $langdesc1 . "</td><td class=xl2216681 nowrap>" . $langdesc2 . 
                                  "</td></tr>");                                                                 

            
            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetFieldDescription($row[1]) . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[2], ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[3], ENT_COMPAT, "UTF-8") . "</td></tr>");                                                                 
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }    

    function ProcessExamples($lang1, $lang2, $langdesc1, $langdesc2, &$results)
    {
        require_once("DBConnectInfo.php");
        
        $languages = array();
                
        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
        
        $sql =  "select c.classmark_tag, f.description, e.field_type, e.tag, f2.description, IFNULL(f3.description, 'NULL'), h.hierarchy_code ".
                " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id  ".
                " join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang1 .
                " join example_classmarks e on e.classmark_id = c.classmark_id ".
                " left outer join language_fields f2 on e.classmark_id = f2.classmark_id and f2.field_id = 2 and f2.language_id = " . $lang1 . " and f2.seq_no = e.seq_no ".
                " left outer join language_fields f3 on e.classmark_id = f3.classmark_id and f3.field_id = 2 and f3.language_id = " . $lang2 . " and f3.seq_no = e.seq_no ".
                " where c.active = 'Y' ".
                " order by h.hierarchy_code, f.field_id ";
               
        $res = @mysql_query($sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td class=xl2216681 nowrap>Caption</td><td>Type</td><td class=xl2216681 nowrap>Example Notation</td><td class=xl2216681 nowrap>" . 
                                    $langdesc1 . " Description</td><td class=xl2216681 nowrap>" . $langdesc2 . " Description</td></tr>");                                                                 

            
            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . $row[1] . "</td><td class=xl2216681 nowrap>" . $row[2] . "</td><td class=xl2216681 nowrap>" . $row[3] . 
                                     "</td><td class=xl2216681 nowrap>" . htmlentities($row[4], ENT_COMPAT, "UTF-8") . "</td><td class=xl2216681 nowrap>" .  htmlentities($row[5], ENT_COMPAT, "UTF-8") . "</td></tr>");                                                                 
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }    
    
    $results = array();

    if (!isset($_POST['query']))
    {
        echo "No query specified - please check the form<br>\n";
        return;
    }
    else
    {
        $query = $_POST['query'];        
    }
    
    switch($query)
    {
        case "allcaptions":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];
            
            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessAllCaptions($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results);
            break;
        case "level2captions":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];
            
            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessLevel2Captions($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results);
            break;
        case "allcaptionsincluding":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];
            
            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessCaptionsIncluding($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results);
            break;        
        case "allfields":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];
            
            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessAllFields($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results);
           break;
        case "examples":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];
            
            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessExamples($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results);
            break;
        case "refreshstats":
            RefreshStats();
            break;                
        default:
            break;
    }

    if ($query != "refreshstats")
    {    
        $today = date("Y_m_d_Hi");
        $filename = "UDC_Extract_" . $langarray1[1] . "_" . $langarray2[1] . "_" . $today . ".xls";
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
    
        foreach($results as $result)
        {         
            echo $result . "\n";
        }
    }
        
    echo "</div>\n";
    echo "</body>\n";
    echo "</html>\n";
?>