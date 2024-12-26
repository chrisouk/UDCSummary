<?php header("Content-type: text/html; charset=UTF-8");

	if (!isset($_SESSION))
		session_start();

    session_cache_expire("120");

	include_once("checksession.php");
	checksession();

    define("DUMMYINSERT", false);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC Summary Translator</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

<link rel="stylesheet" href="../udc1000.css" type="text/css" />
<link rel="stylesheet" href="../udcedit.css" type="text/css" />
<?php if(isset($_SESSION['rtl']) && $_SESSION['rtl'] == true) : ?>
<link rel="stylesheet" href="../udcedit_rtl.css" type="text/css" />
<?php endif; ?>
<link rel="stylesheet" href="dtree.css" type="text/css" />
<link rel="shortcut icon" href="../images/udc.ico" type="image/x-icon" />

<script language="javascript" src="udcedit_trans_ajax.js" type="text/javascript" ></script>
<script language="javascript" src="dtree.js" type="text/javascript" ></script>
<script language="javascript" src="php.default.js" type="text/javascript" ></script>


</head>


<body <?php if (isset($_SESSION['rtl']) && $_SESSION['rtl'] == true) echo " dir=\"rtl\""; ?>>
    <div class="transpane">

 <?php

 	// Check if we need to reset tge previus search results
    if (isset($_GET['resetsearch']) && $_GET['resetsearch'] == 'Y')
    {
    	$_SESSION['search_results'] = '';
        unset($_SESSION['search_results']);
    }

 	// The notation we're editing or searching for
    $notation = "";
    if (isset($_GET['notation']))
    {
        $notation = $_GET['notation'];
    }

    if (isset($_GET['tag']))
    {
        $notation = $_GET['tag'];
    }

 	// Connect to the database
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

		$sqlarray = array();
		$formob->GetUpdateSQL($sqlarray, $formob->validation_errors, $dbc);

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
 	}

    $_SESSION['savesuccess'] = $savesuccess;
    $formob->setdsn($dbc);
	$formob->setformvars($dbc, $notation, $formfile, $savesuccess, false);

 ?>

	 <div id="titleimagecontainer_thin" class="transwidth"><div class="topmenurightbox">&nbsp;

<?php

	// Does user have permissions to export data?
    if (isset($_SESSION['userrole']) && $_SESSION['userrole'] == 2)
    {
        echo "<a href=\"udcsextract.php\"><img src=\"../images/button-exports.jpg\" border=\"0\"></a>&nbsp;" .
             "&nbsp;<a href=\"useractivity.php\"><img src=\"../images/button-activity.jpg\" border=\"0\"></a>";
    }

    echo "&nbsp;<a href=\"showeditorialcomments.php\"><img src=\"../images/button-comments.jpg\" border=\"0\"></a>".
         "&nbsp;<a href=\"../stats/php/trans_stats2.php\"><img src=\"../images/button-statistics.jpg\" border=\"0\"></a>".
         "&nbsp;<a href=\"logout.php\"><img src=\"../images/button-logout.jpg\" border=\"0\"></a>".
         "</div><div class=\"topmenurightbox topmenurightbox2\">&nbsp;</div></div>\n";

    if (!isset($_SESSION['fieldstats']))
    {
        $_SESSION['fieldstats'] = "";
    }

//    echo "<!--div class=\"transmenubox\">";
//    echo "<div style=\"float: left;\">";
    $sessionstats = "";
    if (isset($_SESSION['fieldstat']))
    {
        $sessionstats = $_SESSION['fieldstat'];
    }

    $showlastrevs = "";
    if (isset($_SESSION['showlastrevs']))
    {
    	$sessionstats = $_SESSION['showlastrevs'];
    }
//
//    echo "Show unfinished only ";
//    echo "<input type=\"checkbox\" name=\"exstats\" value=\"exstats\" onchange=\"javascript:fieldstat(9, this.checked); return false;\"";
//    if (strpos($sessionstats, "9") !== FALSE)
//    {
//        echo "checked";
//    }
//    echo ">";
//
//    echo "&nbsp;&nbsp;Show MRF Revisions <input type=\"checkbox\" name=\"lastrevs\" value=\"lastrevs\" onchange=\"javascript:showlastrevs(this.checked); return false;\"";
//    if (!empty($showlastrevs))
//    {
//    	echo "checked";
//    }
//    echo ">";
//
//    echo "</div>";
//    echo "<a href=\"../stats/php/trans_stats2.php\">Stats</a>&nbsp;&nbsp;<a href=\"showeditorialcomments.php\">Comments</a>\n";
//    if (isset($_SESSION['userrole']) && $_SESSION['userrole'] == 2)
//    {
//        echo "&nbsp;&nbsp;<a href=\"udcsextract.php\">Extracts</a>&nbsp;&nbsp;<a href=\"useractivity.php\">Activity</a>&nbsp;<a href=\"advanced_search.php\">Advanced Search</a>&nbsp;&nbsp;<a href=\"setuplogin.php\">User Setup</a>";
//    }
//    echo "</div-->\n";

//    @mysql_close($dbc);
//
//	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
//	mysql_select_db (DBDATABASE);
//    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
//    mysql_query("SET CHARACTER SET utf8");
//    mysql_query("SET NAMES utf8");

    $_SESSION['menu_signs'] = "SIGNS";
    $sql = "select signs from interface_fields where language_id = " . $formob->target_language_id;

	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
		if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
			$_SESSION['menu_signs']  = $row[0];
		}
	}

	@mysql_free_result($res);

    @mysql_close($dbc);

    $captionsearch = "";
    if (isset($_POST['searchcaption']))
    {
        $_SESSION['searchcaption'] = stripslashes($_POST['searchcaption']);
        unset($_SESSION['searchnotation']);
        unset($_SESSION['menuchoice']);
    }

    $notationsearch = "";
    if (isset($_POST['searchnotation']))
    {
        if (isset($_POST['searchnotation']))
        {
            $_SESSION['searchnotation'] = str_replace('"', "&quot;", stripslashes($_POST['searchnotation']));
            unset($_SESSION['menuchoice']);
            unset($_SESSION['searchcaption']);
        }
    }

    $chosenmenu = "";
    if (isset($_SESSION['menuchoice']))
    {
        unset($_SESSION['searchcaption']);
        unset($_SESSION['searchnotation']);
    }

    $captionsearch = "";
	if (isset($_SESSION['searchcaption']))
		$captionsearch = $_SESSION['searchcaption'];

	$notationsearch = "";
	if (isset($_SESSION['searchnotation']))
		$notationsearch = $_SESSION['searchnotation'];

 	$chosenmenu = '';
 	if (isset($_SESSION['menuchoice']))
    	$chosenmenu = $_SESSION['menuchoice'];

 	if ($_SESSION['rtl'] == false)
	{
 		include_once('leftpane_header.inc');
	}
 	else
	{
		include_once('leftpane_header_rtl.inc');
	}
?>

        <div class="searchbox searchkeywordbox backcolorwhite">
<?php
	$leftpane_style = 'leftpane';
	$rightpane_style = 'rightpane';

    echo "<div id=\"leftpane\" class=\"leftpane\" onscroll=\"fScroll(this);\">";
    require_once("leftpane.php");
    ShowLeftPane();
    echo "</div> <!-- end of leftpane -->\n";
    echo "</div> <!-- end of searchbox -->\n";
    echo "</div> <!-- end of leftcontainer-->\n";

    echo "<div id=\"rightpane\" class=\"rightpane\">\n";
    require_once("rightpane.php");
    echo "</div>\n";
?>

    </div>
</body>
</html>

<script type="text/javascript">
function init()
{
	var scrollpos = document.getElementById('scrollvalue');
	var leftpane_div = document.getElementById('leftpane');
	if (scrollpos != null)
	{
		leftpane_div.scrollTop = scrollpos.value;
	}

    var leftpane = leftpane_div.innerHTML;

    leftpane = leftpane.replace(/@@###@@/gi, '"');
    leftpane = leftpane.replace(/@@####@@/gi, '%22');
    leftpane = leftpane.replace(/@@#@@/gi, '+');
    leftpane_div.innerHTML = leftpane;

    var searchchoice = document.getElementById('searchchoice');
    if (searchchoice != null)
    {
        if (searchchoice.value != '')
            openrecord(searchchoice.value);
    }
}
window.onload = init;
</script>

