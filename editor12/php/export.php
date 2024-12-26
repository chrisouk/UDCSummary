<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */
 
    session_start();

    # Get language to export in - default to English  
    $lang = 1;
    $_SESSION['exportlang'] = $lang;
    $exporttype = "caption";
    
    if (isset($_GET['lang']))
    {
        $lang = $_GET['lang'];
        $_SESSION['exportlang'] = $lang;
    }

    if (isset($_GET['exporttype']))
    {
        $exporttype = $_GET['exporttype'];
    }

    # Establish database connection
	require_once("DBConnectInfo.php");

	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    
    # Retrieve all classmarks in hierarchy_code order
    $classmarks = array();
    $records = array();
    $examples = array();
    $references = array();
    
    $joinclause = "";
    $whereclause = "";
    
    if (isset($_GET['exportrange']))
    {
        switch($_GET['exportrange'])
        {
            case "Tbl1a":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 1";
                break; 
            case "Tbl1b":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 2";
                break; 
            case "Tbl1c":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 3";
                break; 
            case "Tbl1d":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 4";
                break; 
            case "Tbl1e":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 5";
                break; 
            case "Tbl1f":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 6";
                break; 
            case "Tbl1g":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 7";
                break; 
            case "Tbl1h":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 8";
                break; 
            case "Tbl1i":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 9";
                break; 
            case "Tbl1k":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 10";
                break; 
            case "Tbl1l":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id = 11";
                break; 
            case "0":
            case "1":
            case "2":
            case "3":
            case "51":
            case "52":
            case "53":
            case "54":
            case "55":
            case "56":
            case "57":
            case "58":
            case "59":
            case "61":
            case "62":
            case "63":
            case "64":
            case "65":
            case "66":
            case "67":
            case "68":
            case "69":
            case "7":
            case "8":
            case "9":
                 $whereclause = "c.classmark_tag like '" . $_GET['exportrange'] . "%'";
                break; 
            case "5only":           
                $whereclause = "c.classmark_tag = '5'";
                break;
            case "5aux":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id != 12";
                $whereclause = "c.classmark_tag like '5%'";
                break;
            case "6only":
                $whereclause = "c.classmark_tag = '6'";
                break;
            case "6aux":
                $joinclause = "join headingtypes h on c.heading_type = h.heading_type_id and h.heading_type_id != 12";
                $whereclause = "c.classmark_tag like '5%'";
                break;            
        }
    }
    
    //echo "Join: " . $joinclause . "<br>\n";
    //echo "Where: " . $whereclause . "<br>\n";

    $native = "";
    $language = "";
    
    $sql = "select native, description from language where language_id = " . $lang;
   	$res = @mysql_query($sql, $dbc);
    if ($res)
    {
        if($row = mysql_fetch_array($res, MYSQL_NUM))
    	{
    		$native = $row[0];
            $language = $row[1];
    	}
    	mysql_free_result($res);
    }

    $sql = "select examples, scopenote, appnote, including from interface_fields where language_id = " . $lang;
   	$res = @mysql_query($sql, $dbc);
    if ($res)
    {
        if($row = mysql_fetch_array($res, MYSQL_NUM))
    	{
    		$_SESSION['if_examples'] = $row[0];
            $_SESSION['if_scopenote'] = $row[1];
            $_SESSION['if_appnote'] = $row[2];
            $_SESSION['if_including'] = $row[3];
    	}
    	@mysql_free_result($res);
    }
   
    #ProcessRecords($dbc, $records, $lang);
    
    # Output Excel headers
    $fileexporttype = "";
    $fileexportdesc = "";
    switch($exporttype)
    {
        case "caption0":
            $fileexporttype = "All_Captions";
            $fileexportdesc = "All Captions";
            include_once('captionprocessor.php');
            $processor = new CaptionProcessor(0);
            break;
        case "caption1":
            $fileexporttype = "Level_1_Captions";
            $fileexportdesc = "Level 1 Captions";
            include_once('captionprocessor.php');
            $processor = new CaptionProcessor(1);
            break;
        case "caption2":
            $fileexporttype = "Level_2_Captions";
            $fileexportdesc = "Level 2 Captions";
            include_once('captionprocessor.php');
            $processor = new CaptionProcessor(2);
            break;
        case "caption3":
            $fileexporttype = "Level_3_Captions";
            $fileexportdesc = "Level 3 Captions";
            include_once('captionprocessor.php');
            $processor = new CaptionProcessor(3);
            break;
        case "full":
            $fileexporttype = "Full_Plain";
            $fileexportdesc = "Full Plain";
            include_once('fullprocessor.php');
            $processor = new FullProcessor();
            break;
        case "tagged":
        default:
            $fileexporttype = "Full_Tagged";
            $fileexportdesc = "Full Tagged";
            include('taggedprocessor.php');
            $processor = new TaggedProcessor();
            break;
    }

    $today = date("Y_m_d");
    $filename = "UDC_Export_" . $language . "_" . $fileexporttype . "_" . $today . ".doc";
    header('Content-type: application/ms-word; charset=UTF-8');
    header('Content-Disposition: attachment; filename=' . $filename);    
    
    echo "UDC MRF Export - " . $fileexportdesc . " - " . $native . " (" . $language . ")  " . date("d-M-Y") . "\r\n\r\n";
    
    $processor->ListRecords($dbc, $classmarks, $joinclause, $whereclause);
    $processor->RetrieveRecords($dbc, $records, $examples, $references, $lang, $joinclause, $whereclause); 
    $processor->ProcessRecords($classmarks, $records, $examples, $references);
    
    # Close up
    @mysql_close($dbc);
?>