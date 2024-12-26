<?php
    if (!isset($_SESSION))
        session_start();

# Reset navigation trail
$_SESSION['navpath'] = '';
$_SESSION['navpos'] = 0;

# Session variable 'preserverecord' records the notation of the last record selected in the tree
# for display in the right pane. If the user then changes language, the whole tree is redisplayed
# in the new language and an AJAX call is made immediately to reload the right pane, showing the
# preserverecord in the new language also

if (!isset($_GET['pr']))
{
	$_SESSION['preserverecord'] = "";
}

flush();

$page = $_SERVER['PHP_SELF'];
$langcode = "en";
$lang = 1;
$id = "";
$tag = "";

if (isset($_GET['id']))
{
	$id = $_GET['id'];
}

if (isset($_GET['tag']))
{
	$tag = $_GET['tag'];
}

if (isset($_GET["lang"]))
{
	$langcode = $_GET["lang"];
}

$langstring = $page;
if ($tag != "")
{
	$langstring .= "?tag=" . htmlentities($tag) . "&lang=";
}
else
{
	if ($id != "")
	{
		$langstring .= "?id=" . htmlentities($id) . "&lang=";
	}
	else
	{
		$langstring .= "?lang=";
	}
}

# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
# Connect to the database
# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

require_once("DBConnectInfo.php");

$dsn = 'mysql:dbname=' . DBDATABASE . ';host=127.0.0.1';
$database_user = DBUSER;
$database_password = DBPASS;

try
{
	$dbc = new PDO($dsn, $database_user, $database_password);
	$dbc->exec("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
}
catch (PDOException $e)
{
	$error = 'Cannot get a connection to the database.  Please email support.';
	exit(1);
}

$languages = array();
$rtl = false;

# Get the language combo values for the top menu
$sql = "select language_id, code, description, native, rtl from language order by code";
foreach ($dbc->query($sql) as $row)
{
	$option = $row[2] . ":" . $row[1] . ":" . $row[0] . ":" . $row[3] . ":" . $row[4];
	$languages[] = $option;
}

asort($languages, SORT_STRING);

$language_options = "";

foreach ($languages as $option)
{
	$row = explode(":", $option);
	$lcode = $row[1];
	$ldesc = $row[0];
	$lid = $row[2];
	$lnative = $row[3];
	$rightleft = $row[4];

	$option = "<option style=\"unicode-bidi: bidi-override; direction: ltr\" value=\"" . $langstring . $lcode . "&pr=Y\"";
	if ($langcode == $lcode || $langcode == $lid)
	{
		$option .= " selected";
		$lang = $lid;
		$langcode = $lcode;

		if ($rightleft == 'Y')
		{
			$rtl = true;
		}
	}
	$option .= ">" . $ldesc . " (" . $lnative . ") </option>\n";
	$language_options .= $option;
}

$_SESSION['lang'] = $lang;
$_SESSION['langcode'] = $langcode;
$_SESSION['rtl'] = $rtl;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>UDC Summary</title>
    <link rel="stylesheet" href="../reset.css" type="text/css"/>
    <link rel="stylesheet" href="../udc2000.css" type="text/css"/>
	<?php if ($rtl == true) : ?>
        <link rel="stylesheet" href="../udc2000_rtl.css" type="text/css"/>
	<?php endif; ?>
    <link rel="StyleSheet" href="dtree2.css" type="text/css"/>
	<?php if ($rtl == true) : ?>
        <script type="text/javascript" src="dtree-a.js"></script>
	<?php else : ?>
        <script type="text/javascript" src="dtree.js"></script>
	<?php endif; ?>
    <script type="text/javascript" src="udcdisplay_9.js"></script>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

</head>

<body <?php if ($rtl == true)
{
	echo 'dir="rtl"';
} ?>>
<div id="centercontainer" class="debugbkg">
    <div id="titlebox" class="debugbkg">
        <div id="rightbox">
            <div id="languagecount"><?php echo count($languages) . " languages" ?></div>
            <div id="languagebox">
                <select id="selectedlang" style="vertical-align: middle;" onchange="location = this.options[this.selectedIndex].value;">
					<?php echo $language_options; ?>
                </select>
            </div>
        </div>
    </div>
    <div id="menubox" class="debugbkg">
		<?php
		include_once("specialchars.php");
		include_once("getdisplaynotation.php");

		define("MAX_CM_LENGTH", 50);

		# Get the selected language interface display values
		$if_translations = "TRANSLATIONS";
		$if_mappings = "MAPPINGS";
		$if_exports = "EXPORTS";
		$if_abc_index = "ABC INDEX";
		$if_guide = "GUIDE";
		$if_about = "ABOUT";
		$if_top = "TOP";
		$if_signs = "SIGNS";
		$if_auxiliaries = "AUXILIARIES";
		$if_expandall = "expand all";
		$if_collapseall = "collapse all";
		$if_rootclasses = "TOP";
		$if_click = "click on a class to the left to display records";
		$if_loading = "Loading";

		$sql = "select translations, mappings, exports, abc_index, guide, about, top, signs, auxiliaries, expandall, collapseall, rootclasses, click, loading" .
			" from interface_fields where language_id = " . $lang;

		foreach ($dbc->query($sql) as $row)
		{
			$if_translations = $row[0];
			$if_mappings = $row[1];
			$if_exports = $row[2];
			$if_abc_index = $row[3];
			$if_guide = $row[4];
			$if_about = $row[5];
			$if_top = $row[6];
			$if_signs = $row[7];
			$if_auxiliaries = $row[8];
			$if_expandall = $row[9];
			$if_collapseall = $row[10];
			$if_rootclasses = $row[11];
			$if_click = $row[12];
			$if_loading = $row[13];
		}

        $_SESSION['expandall'] = $if_expandall;
        $_SESSION['collapseall'] = $if_collapseall;
        $_SESSION['top'] = $if_top;

		if ($rtl == true)
		{
			echo "<ul class=\"menu";
			if ($lang != 27)
			{
				echo " boldmenu";
			}
			echo "\">\n";
			echo "<li><a href=\"../about.htm\" title=\"About the UDC Summary\">" . $if_about . "</a></li>\n";
			echo "<li><a href=\"#\" title=\"Under Development\">" . $if_guide . "</a></li>\n";
			echo "<li><a href=\"#\" title=\"Under Development\">" . $if_abc_index . "</a></li>\n";
			echo "<li><a href=\"../exports.htm\" title=\"Data Exports\">" . $if_exports . "</a></li>\n";
			echo "<li><a href=\"#\" title=\"Under Development\">" . $if_mappings . "</a></li>\n";
			echo "<li><a href=\"../translation.htm\">" . $if_translations . "</a></li>\n";
			echo "</ul>\n";

			# Display class division links on the top left menu
			echo "<ul class=\"rightmenu";
			if ($lang != 27)
			{
				echo " boldmenu";
			}
			echo "\">\n";
			echo "<li><a href=\"index.php?lang=" . $langcode . "\">" . $if_top . "</a></li>\n";
			echo "<li><a href=\"index.php?tag=--&lang=" . $langcode . "\">" . $if_signs . "</a></li>\n";
			echo "<li><a href=\"index.php?tag=---&lang=" . $langcode . "\">" . $if_auxiliaries . "</a></li>\n";
			echo "<li><a href=\"index.php?tag=0&lang=" . $langcode . "\">0</a></li>\n";
			echo "<li><a href=\"index.php?tag=1&lang=" . $langcode . "\">1</a></li>\n";
			echo "<li><a href=\"index.php?tag=2&lang=" . $langcode . "\">2</a></li>\n";
			echo "<li><a href=\"index.php?tag=3&lang=" . $langcode . "\">3</a></li>\n";
			echo "<li><a  class=\"vacant\" href=\"#\">4</a></li>\n";
			echo "<li><a href=\"index.php?tag=5&lang=" . $langcode . "\">5</a></li>\n";
			echo "<li><a href=\"index.php?tag=6&lang=" . $langcode . "\">6</a></li>\n";
			echo "<li><a href=\"index.php?tag=7&lang=" . $langcode . "\">7</a></li>\n";
			echo "<li><a href=\"index.php?tag=8&lang=" . $langcode . "\">8</a></li>\n";
			echo "<li><a href=\"index.php?tag=9&lang=" . $langcode . "\">9</a></li>\n";
			echo "</ul>\n";

			echo "<input type=\"hidden\" id=\"if_loading\" name=\"if_loading\" value=\"" . $if_loading . "\"/>\n";
			echo "<input type=\"hidden\" id=\"if_lang\" name=\"if_lang\" value=\"" . $lang . "\"/>\n";
		}
		else
		{
			# Display class division links on the top right menu
			echo "<ul class=\"menu";
			if ($lang != 27)
			{
				echo " boldmenu";
			}
			echo "\">\n";
			echo "<li><a href=\"index.php?lang=" . $langcode . "\">" . $if_top . "</a></li>\n";
			echo "<li><a href=\"index.php?tag=--&lang=" . $langcode . "\">" . $if_signs . "</a></li>\n";
			echo "<li><a href=\"index.php?tag=---&lang=" . $langcode . "\">" . $if_auxiliaries . "</a></li>\n";
			echo "<li><a href=\"index.php?tag=0&lang=" . $langcode . "\">0</a></li>\n";
			echo "<li><a href=\"index.php?tag=1&lang=" . $langcode . "\">1</a></li>\n";
			echo "<li><a href=\"index.php?tag=2&lang=" . $langcode . "\">2</a></li>\n";
			echo "<li><a href=\"index.php?tag=3&lang=" . $langcode . "\">3</a></li>\n";
			echo "<li><a  class=\"vacant\" href=\"#\">4</a></li>\n";
			echo "<li><a href=\"index.php?tag=5&lang=" . $langcode . "\">5</a></li>\n";
			echo "<li><a href=\"index.php?tag=6&lang=" . $langcode . "\">6</a></li>\n";
			echo "<li><a href=\"index.php?tag=7&lang=" . $langcode . "\">7</a></li>\n";
			echo "<li><a href=\"index.php?tag=8&lang=" . $langcode . "\">8</a></li>\n";
			echo "<li><a href=\"index.php?tag=9&lang=" . $langcode . "\">9</a></li>\n";
			echo "</ul>\n";

			echo "<input type=\"hidden\" id=\"if_loading\" name=\"if_loading\" value=\"" . $if_loading . "\"/>\n";
			echo "<input type=\"hidden\" id=\"if_lang\" name=\"if_lang\" value=\"" . $lang . "\"/>\n";

			echo "<ul class=\"rightmenu";
			if ($lang != 27)
			{
				echo " boldmenu";
			}
			echo "\">\n";

			echo "<li><a href=\"../translation.htm\">" . $if_translations . "</a></li>\n";
			echo "<li><a href=\"#\" title=\"Under Development\">" . $if_mappings . "</a></li>\n";
			echo "<li><a href=\"../exports.htm\" title=\"Data Exports\">" . $if_exports . "</a></li>\n";
			echo "<li><a href=\"#\" title=\"Under Development\">" . $if_abc_index . "</a></li>\n";
			echo "<li><a href=\"#\" title=\"Under Development\">" . $if_guide . "</a></li>\n";
			echo "<li><a href=\"../about.htm\" title=\"About the UDC Summary\">" . $if_about . "</a></li>\n";
			echo "</ul>\n";
		}
		?>

    </div>
    <input type="hidden" id="leftWidth" value="420"/><input type="hidden" id="rightWidth" value="522"/>
    <div style="width:100%;overflow:auto">
		<?php

		# Temporary class for storing classmark data
		class TreeRecord
		{
			var $id = 0;
			var $broader = "";
			var $tag = "";
			var $description = "";
			var $title = "";
			var $field_id = 0;
			var $hierarchy_code = "";
			var $level = 0;
			var $headingtype = 0;
			var $language = 0;
		}

		;

		if ($rtl == true)
		{
			include 'recordbox.inc';
			include 'separator.inc';
			include 'classmarkbox.php';
		}
		else
		{
			include 'classmarkbox.php';
			include 'separator.inc';
			include 'recordbox.inc';
		}
		?>
    </div>
</div>
<div style="font-family: Calibri, Tahoma, Helvetica, sans-serif; font-size: 0.75em; line-height: 1.1em;margin: 1px 0px 1px 0px;padding:12px 0px;width:100%;overflow:auto;color:black;background-color:#e9e09c">
    <div style="margin:0px 10px">ONLINE WORKSHOP "Introduction to Universal Decimal Classification" organized by ISKO UK within its education series of lectures will take place from 25 November to 9 December 2021. To read more and to register go to the <a href="https://www.iskouk.org/event-4516930" style="color:black">workshop registration page</a>.</div>
</div>
<div id="footer">
    <div class="footersectionleft">
        The UDC Summary (UDCS) provides a selection of around 2,600 classes from the whole scheme which comprises more than 70,000 entries.
        Please send questions and suggestions to <a href="mailto:udcs@udcc.org?subject=UDC Summary Enquiry">udcs@udcc.org</a><br><br>
        <p>
            The data provided in this Summary is released under the <a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">Creative Commons Attribution Share Alike 3.0
                license</a> <a href="../about.htm">[more]</a>
        </p>
    </div>
    <div class="footermiddle">
        <a href="http://www.udcc.org"><img src="../images/udclogowhite.png" border="0"></a>
    </div>
    <div class="footerright">
        For complete UDC schedules see <a href="http://www.udc-hub.com/">UDC Online Hub</a><br/><a href="http://www.udc-hub.com/"><img width="60" height="35" src="../images/udclogo.png"/></a>

    </div>
</div>
</div>
</body>
</html>

<?php
# This code reloads the right hand pane with the last selected
# record in the left hand tree if it was preserved for refresh
if ($_SESSION['preserverecord'] != "")
{
	echo "<script type=\"text/javascript\">\n";
	echo "function init() {\n";
	echo "	openrecord('" . $_SESSION['preserverecord'] . "', " . $_SESSION['navpos'] . ", false, '" . ($rtl == true ? "Y" : "N") . "'); return false;\n";
	echo "}\n";
	echo "window.onload = init;";
	echo "</script>\n";
}
# Add Google Analytics tracking
echo "<script type=\"text/javascript\">\n";
echo "var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");\n";
echo "document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));\n";
echo "</script>\n";
echo "<script type=\"text/javascript\">\n";
echo "try {\n";
echo "var pageTracker = _gat._getTracker(\"UA-13138465-1\");\n";
echo "pageTracker._trackPageview();\n";
echo "} catch(err) {}</script>\n";
?>
