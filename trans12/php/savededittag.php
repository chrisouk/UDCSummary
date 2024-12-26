<?php header("Content-type: text/html; charset=UTF-8");

	session_start();
	
	include_once("checksession.php");
	checksession();
	
    define("DUMMYINSERT", false);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC Translation</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

<link rel="stylesheet" href="../udcedit.css" type="text/css" />
<link rel="stylesheet" href="dtree.css" type="text/css" />
<script language="javascript" src="udcedit.js" type="text/javascript" ></script>
<script language="javascript" src="dtree.js" type="text/javascript" ></script>
<script language="javascript" src="php.default.js" type="text/javascript" ></script>
</head>


<body>
    <div class="transpane">
    
 <?php   

    include_once('DBConnectInfo.php');
	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");
	

	// Store post variables
	include('udcform.php');
	$formob = new udcform();
	$formob->setpostvars();

	$loaderror = "";
	$savesuccess = false;
	
	if (isset($_POST['SubmitSave']))
	{
		// This is a save operation
		$sql = "";


		// Updating an existing record
        //echo "Saving form for ". $formob->notation . "<br>\n";
        //echo "Charset = ". @mysql_client_encoding($dbc) . "<br>\n";
        
		$sqlarray = array();
		$errors = array();
		$sql = $formob->GetUpdateSQL($sqlarray, $formob->validation_errors, $dbc);

		if (count($formob->validation_errors) == 0)
		{
			// Save the SQL
			include_once 'savesql.php';
			$savesuccess = SaveSQL($sqlarray, $formob, $dbc, DUMMYINSERT);
		}
        else
        {
            echo "There were " . count($formob->validation_errors) . " errors..<br>\n";
        }
		// ## Debug ##
		//$formob->DumpVars($sqlarray, "SQL");
		//$formob->DumpVars($formob->validation_errors, "Errors");
		//$formob->queryformvars($dbc);
	}
    else
    {
        //echo "Not a save operation<br>\n";
    }

	//$formob->setformvars($dbc, $notation, $formfile, $savesuccess, false);

    echo "<div id=\"titleimage\"><img src=\"../images/udcsumtitle.jpg\" border=\"0\"></div>\n";
    
    if (isset($_SESSION['userid']) && ($_SESSION['userid'] == 'aida' || $_SESSION['userid'] == 'chris'))
    {
        echo "<div style=\"width: 1104px; float: left; clear: left; background-color: #dddddd; color:black; padding: 4px; height: 20px; text-align: right; font-family: tahoma; font-size: 13px;\">";
        echo "<a href=\"/udcsummary/stats/php/trans_stats2.php\">Stats</a>&nbsp;&nbsp;<a href=\"useractivity.php\">Activity</a>";
        echo "</div>\n";
    }
    
    echo "<div id=\"leftpane\" class=\"leftpane\">";
    require_once("leftpane.php");
    ShowLeftPane();
    echo "</div>\n";
    
    echo "<div id=\"rightpane\" class=\"rightpane\">\n";
    require_once("rightpane.php");
    echo "</div>\n";
?>

    </div>
</body>
</html>