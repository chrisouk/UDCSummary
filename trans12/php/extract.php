<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    function GetCleanData($data)
    {
        $returnstring = str_replace("<", "&lt;", $data);
        return str_replace(">", "&gt;", $returnstring);
    }

	function GetFieldDescription($field_id)
	{
		$description = "Unknown";

		switch($field_id)
		{
			case 1:
				$description = "Caption";
				break;
			case 2:
				$description = "Example";
				break;
			case 4:
				$description = "Including";
				break;
			case 5:
				$description = "Scope Note";
				break;
			case 6:
				$description = "Application Note";
				break;
			default:
				# Nothing doing - keep it at 'unknown'
				break;
		}

		return $description;
	}

	function GetFetchSQL($fetch_type, &$sql, &$joinclause, &$whereclause)
	{
		$getsql = $sql . " " . $joinclause . " " . $whereclause;

		switch($fetch_type)
		{
			case '--':
				# Table 1a-1d
				$joinclause .= " JOIN headingtypes ht on c.heading_type = ht.heading_type_id and ht.heading_type_id in (1,2,3,4,5,6,7,8,9,10) ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '0':
				# Main numbers starting with 0
				$whereclause .= " AND c.classmark_tag like '0%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '1':
				# Main numbers starting with 1
				$whereclause .= " AND c.classmark_tag like '1%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '2':
				# Main numbers starting with 2
				$whereclause .= " AND c.classmark_tag like '2%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '3':
				# Main numbers starting with 2
				$whereclause .= " AND c.classmark_tag like '3%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '5':
				# Main numbers starting with 2
				$whereclause .= " AND c.classmark_tag like '5%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '6':
				# Main numbers starting with 2
				$whereclause .= " AND c.classmark_tag like '6%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '7':
				# Main numbers starting with 7
				$localwhere = $whereclause . " AND c.classmark_tag like '7%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '8':
				# Main numbers starting with 8
				$localwhere = $whereclause . " AND c.classmark_tag like '8%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '9':
				# Main numbers starting with 9
				$localwhere = $whereclause . " AND c.classmark_tag like '9%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
		}

		return $getsql;
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

        $sql = 	"insert into translation_status_fields (classmark_id, language_id, field_id, lang_field_count) " .
				"select f.classmark_id, f.language_id, f.field_id, count(*) ".
				"from classmarks c join language_fields f2 on c.classmark_id = f2.classmark_id and f2.language_id = 1 ".
				"join language_fields f on f.classmark_id = c.classmark_id and f.classmark_id = f2.classmark_id and f.field_id = f2.field_id and f.seq_no = f2.seq_no ".
				"where c.active = 'Y' and f.language_id > 0 group by f.classmark_id, f.language_id, f.field_id ";

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

    function ProcessLevel2Captions($lang1, $lang2, $langdesc1, $langdesc2, &$results, $dataset)
    {
        require_once("DBConnectInfo.php");

        $languages = array();

        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

        $sql = 			"select c.classmark_tag, f.description, IFNULL(f2.description,'NULL'), c.classmark_enc_tag " .
                		"from classmarks c ";
        $joinclause =   "join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang1 .
                		" left outer join language_fields f2 on c.classmark_id = f2.classmark_id and f2.field_id = 1 and f2.language_id = " . $lang2;
		$whereclause = 	" where c.active = 'Y' and c.hierarchy_level < 3 ";

		$exec_sql = GetFetchSQL($dataset, $sql, $joinclause, $whereclause) . " order by classmark_enc_tag asc";

        $res = @mysql_query($exec_sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td>Field</td><td class=xl2216681 nowrap>" . $langdesc1 . "</td><td class=xl2216681 nowrap>" . $langdesc2 .
                                  "</td></tr>");


            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetFieldDescription(1) . "</td><td class=xl2216681 nowrap>" . GetCleanData($row[1]) .
                                     "</td><td class=xl2216681 nowrap>" . GetCleanData($row[2]) .
                                     "</td></tr>");
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }

    function ProcessAllCaptions($lang1, $lang2, $langdesc1, $langdesc2, &$results, $dataset)
    {
        require_once("DBConnectInfo.php");

        $languages = array();

        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

        $sql = 		  	"select c.classmark_tag, f.description, IFNULL(f2.description,'NULL'), c.classmark_enc_tag " .
                	  	"from classmarks c ";
        $joinclause = 	"join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang1 .
                	  	" left outer join language_fields f2 on c.classmark_id = f2.classmark_id and f2.field_id = 1 and f2.language_id = " . $lang2;
        $whereclause = 	" where c.active = 'Y'";

        $exec_sql = GetFetchSQL($dataset, $sql, $joinclause, $whereclause) . " order by classmark_enc_tag asc";

        $res = @mysql_query($exec_sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td>Field</td><td class=xl2216681 nowrap>" . $langdesc1 . "</td><td class=xl2216681 nowrap>" . $langdesc2 .
                                  "</td></tr>");


            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetFieldDescription(1) . "</td><td class=xl2216681 nowrap>" . GetCleanData($row[1]) .
                                     "</td><td class=xl2216681 nowrap>" . GetCleanData($row[2]) .
                                     "</td></tr>");
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }

    function ProcessCaptionsIncluding($lang1, $lang2, $langdesc1, $langdesc2, &$results, $dataset)
    {
        require_once("DBConnectInfo.php");

        $languages = array();

        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

        $sql = 			"select c.classmark_tag, f.field_id, f.description, IFNULL(f2.description,'NULL'), c.classmark_enc_tag " .
                		"from classmarks c ";
		$joinclause = 	"join language_fields f on c.classmark_id = f.classmark_id and f.field_id in (1,4) and f.language_id = " . $lang1 .
                		" left outer join language_fields f2 on c.classmark_id = f2.classmark_id and f2.field_id = f.field_id and f2.language_id = " . $lang2;
		$whereclause =	" where c.active = 'Y' ";

		$exec_sql = GetFetchSQL($dataset, $sql, $joinclause, $whereclause) . " order by classmark_enc_tag, field_id asc";

        $res = @mysql_query($exec_sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td>Field</td><td class=xl2216681 nowrap>" . $langdesc1 . "</td><td class=xl2216681 nowrap>" . $langdesc2 .
                                  "</td></tr>");


            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetFieldDescription($row[1]) . "</td><td class=xl2216681 nowrap>" . GetCleanData($row[2]) .
                                     "</td><td class=xl2216681 nowrap>" . GetCleanData($row[3]) .
                                     "</td></tr>");
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }

    function ProcessAllFields($lang1, $lang2, $langdesc1, $langdesc2, &$results, $dataset)
    {
        require_once("DBConnectInfo.php");

        $languages = array();

        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

        $sql = 			"select c.classmark_tag, f.field_id, f.description, IFNULL(f2.description,'NULL'), c.classmark_enc_tag " .
                		"from classmarks c ";
		$joinclause = 	"join language_fields f on c.classmark_id = f.classmark_id and f.field_id in (1,4,5,6) and f.language_id = " . $lang1 .
                		" left outer join language_fields f2 on c.classmark_id = f2.classmark_id and f2.field_id = f.field_id and f2.language_id = " . $lang2;
        $whereclause =  " where c.active = 'Y' ";

        $exec_sql = GetFetchSQL($dataset, $sql, $joinclause, $whereclause) . " order by classmark_enc_tag, field_id asc";

        $res = @mysql_query($exec_sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td>Field</td><td class=xl2216681 nowrap>" . $langdesc1 . "</td><td class=xl2216681 nowrap>" . $langdesc2 .
                                  "</td></tr>");


            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetFieldDescription($row[1]) . "</td><td class=xl2216681 nowrap>" .
                                     GetCleanData($row[2]) . "</td><td class=xl2216681 nowrap>" . GetCleanData($row[3]) .
                                     "</td></tr>");
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    }

    function ProcessExamples($lang1, $lang2, $langdesc1, $langdesc2, &$results, $dataset)
    {
        require_once("DBConnectInfo.php");

        $languages = array();

        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

        $sql =  		"select c.classmark_tag, f.description, e.field_type, e.tag, f2.description, IFNULL(f3.description, 'NULL'), c.classmark_enc_tag ".
                		" from classmarks c ";
		$joinclause = 	" join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 and f.language_id = " . $lang1 .
                		" join example_classmarks e on e.classmark_id = c.classmark_id ".
                		" left outer join language_fields f2 on e.classmark_id = f2.classmark_id and f2.field_id = 2 and f2.language_id = " . $lang1 . " and f2.seq_no = e.seq_no ".
                		" left outer join language_fields f3 on e.classmark_id = f3.classmark_id and f3.field_id = 2 and f3.language_id = " . $lang2 . " and f3.seq_no = e.seq_no ";
		$whereclause = 	" where c.active = 'Y' ";

		$exec_sql = GetFetchSQL($dataset, $sql, $joinclause, $whereclause) . " order by classmark_enc_tag, tag";

        $res = @mysql_query($exec_sql, $dbc);
        if ($res)
        {
            array_push($results,  "<tr><td class=xl2216681 nowrap>Notation</td><td class=xl2216681 nowrap>Caption</td><td>Type</td><td class=xl2216681 nowrap>Example Notation</td><td class=xl2216681 nowrap>" .
                                    $langdesc1 . " Description</td><td class=xl2216681 nowrap>" . $langdesc2 . " Description</td></tr>");


            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
               array_push($results,  "<tr><td class=xl2216681 nowrap>" . $row[0] . "</td><td>" . GetCleanData($row[1]) . "</td><td class=xl2216681 nowrap>" . $row[2] . "</td><td class=xl2216681 nowrap>" . $row[3] .
                                     "</td><td class=xl2216681 nowrap>" . GetCleanData($row[4]) . "</td><td class=xl2216681 nowrap>" . GetCleanData($row[5]) . "</td></tr>");
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

    $dataset = '';
    if (isset($_POST['dataset']))
    {
    	$dataset = $_POST['dataset'];
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
            ProcessAllCaptions($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results, $dataset);
            break;
        case "level2captions":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];

            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessLevel2Captions($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results, $dataset);
            break;
        case "allcaptionsincluding":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];

            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessCaptionsIncluding($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results, $dataset);
            break;
        case "allfields":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];

            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessAllFields($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results, $dataset);
           break;
        case "examples":
            if (isset($_POST['language1']))
                $language1 = $_POST['language1'];
            if (isset($_POST['language1']))
                $language2 = $_POST['language2'];

            $langarray1 = explode(".", $language1, 2);
            $langarray2 = explode(".", $language2, 2);
            ProcessExamples($langarray1[0], $langarray2[0], $langarray1[1], $langarray2[1], $results, $dataset);
            break;
        case "refreshstats":
            RefreshStats();
            exit(1);
            break;
        default:
            break;
    }

    $today = date("Y_m_d_Hi");
    $filename = "UDC_Export_" . $langarray1[1] . "_" . $langarray2[1] . "_" . $today . ".xls";
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

    echo "</div>\n";
    echo "</body>\n";
    echo "</html>\n";
?>