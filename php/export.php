<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */
 
    session_start();

    class UDCRecord
    {
        var $notation = "";
        var $caption = "";
        var $including = "";
        var $scopenote = "";
        var $appnote = "";
        var $examples = array();
        var $refs = array();
    };
      
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
    $filename = "UDCSExport_" . $language . "_" . $fileexporttype . "_" . $today . ".doc";
    header('Content-type: application/ms-word; charset=UTF-8');
    header('Content-Disposition: attachment; filename=' . $filename);    
    
    echo "UDC Summary Export - " . $fileexportdesc . " - " . $native . " (" . $language . ")  " . date("d-M-Y") . "\r\n\r\n";
    
    $processor->ListRecords($dbc, $classmarks);
    $processor->RetrieveRecords($dbc, $records, $lang); 
    $processor->ProcessRecords($classmarks, $records);
    
    # Close up
    @mysql_close($dbc);
?>