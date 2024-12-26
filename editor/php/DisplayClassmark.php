<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC Data Management - Browse</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
@import url("../grail.css");
html { overflow: -moz-scrollbars-vertical !important; }
-->
</style>

<link rel="StyleSheet" href="dtree.css" type="text/css"/>

<script type="text/javascript" src="dtree.js"></script>
</head>

<body>
<?php	
	require_once("DBConnectInfo.php");
	include_once("specialchars.php");
	include_once("resetlevels.php");
		
	if (isset($_GET['tag']))
		$searchtag = $_GET['tag'];
	else if (isset($_POST['tag']))
		$searchtag = $_POST['tag'];
		
	$langid=1;
	if (isset($_GET['langid']))
		$langid = $_GET['langid'];	
	
	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
	
	echo "<div id=\"imagecontainer\"><img src=\"../images/title.jpg\" align=\"left\"></div>\n";
	echo "<div id=\"loginheader\">User: Aida Slavic-Overfield<br>Role: Administrator<br>Last Login: 23-Jan-2007</div>\n";
	
?>
	<div id="menucontainer">
		<div id="nav-menu">
			<ul>
				<li><a href="udcindex.php">Home</a></li>
				<li class="sep">•</li>
				<li><a href="administration.php">Administration</a></li>
				<li class="sep">•</li>
				<li><a href="searchbrowse.php">Search/Browse</a></li>
			</ul>
		</div>
	</div>
	
<?php
	include_once("displaytag.php");
	$result = DisplayTag($searchtag, $dbc, $langid);
	if ($result != -1)
	{
		include_once("browsetag.php");
		BrowseTag($searchtag, $dbc, $langid);
	}
?>	
	
<!--form action="/udcman/php/test.php" method="post" name="fieldform" id="fieldform">
  Search 
  <input name="fieldcontent" type="text" id="fieldcontent">
</form-->

</body>
</html>
