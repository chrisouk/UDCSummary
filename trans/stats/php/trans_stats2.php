<?php
	session_start();
    include_once("checksession.php");
    checksession();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>UDC Pocket Edition Completion Statistics</title>
  <!--[if IE]><script language="javascript" type="text/javascript" src="../excanvas.js"></script><![endif]-->

  <link rel="stylesheet" type="text/css" href="../jquery.jqplot.css" />
  <link rel="stylesheet" type="text/css" href="examples.css" />

  <!-- BEGIN: load jquery -->
  <script language="javascript" type="text/javascript" src="../jquery-1.3.2.min.js"></script>
  <!-- END: load jquery -->

  <!-- BEGIN: load jqplot -->
  <script language="javascript" type="text/javascript" src="../jquery.jqplot.js"></script>
		<script language="javascript" type="text/javascript" src="../plugins/jqplot.canvasTextRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="../plugins/jqplot.canvasAxisTickRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="../plugins/jqplot.dateAxisRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="../plugins/jqplot.barRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="../plugins/jqplot.categoryAxisRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="../plugins/jqplot.pieRenderer.js"></script>
        <!--script language="javascript" type="text/javascript" src="../plugins/jqplot.pointLabels.min.js"></script-->

  <!-- END: load jqplot -->
  <style type="text/css" media="screen">
    .jqplot-axis {
      font-size: 0.85em;
    }
    .jqplot-title {
      font-size: 1.1em;
    }
  </style>
  <script type="text/javascript" language="javascript">

<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    function GetStats($max, &$english_stats, &$stats, $fieldid, &$line1, &$line2, &$labels, $pos)
    {
        if (isset($english_stats->field_counts[$fieldid]))
        {
            $caption_stats = $english_stats->field_counts[$fieldid];
            if ($caption_stats > $max)
            {
                $max = $caption_stats;
            }
        }
        else
        {
            $caption_stats = 0;
            //echo "No key for field id " . $fieldid . "\n";
        }

        if ($line1 != "")
            $line1 .= ", ";

        $line1 .= "[" . $caption_stats . "," . $pos . "]";

        if (isset($stats->field_counts[$fieldid]))
        {
            $lang_caption_stats = $stats->field_counts[$fieldid];
            if ($lang_caption_stats > $max)
            {
                $max = $lang_caption_stats;
            }
        }
        else
            $lang_caption_stats = 0;

        if ($line2 != "")
            $line2 .= ", ";

        $line2 .= "[" . $lang_caption_stats . "," . $pos . "]";

        if ($labels != "")
            $labels .= ", ";

        $labels .= "'" . $lang_caption_stats . "'";

        //echo "Line1: " . $line1 . "<br>\n";
        //echo "Line2: " . $line2 . "<br>\n";

        //echo "Max: " . $max . "\n";

        return $max;
    }

    require_once('DBConnectInfo.php');

	$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");	//mysql_set_charset('latin1',$dbc);

    class StatsValues
    {
        var $field_counts = array();
    }

    $languages = array();

    $language_clause = "";

    $userid = "";
    if (isset($_SESSION['userid']))
    {
    	$userid = $_SESSION['userid'];
    }

    if ($userid != "chris" && $userid != "aida" && $userid != "aida32")
    {
    	$language_clause = "f.language_id in  (1," . $_SESSION['deflang'] . ")";
    }


    $sql = "select f.language_id, f.description from language f ";

    if ($language_clause != "")
    {
    	$sql .= "where " . $language_clause;
    }
    #echo $sql . "<br>\n"; ###


    $res = @mysql_query($sql);
	if ($res)
	{
		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
			$languages[$row[0]] = $row[1];
		}
	}

    $totals = array();
    $sorted_languages = array();

    $returnstring = "";

	@mysql_free_result($res);

    $sql =  "select f.language_id, f.field_id, l.description, count(f.field_id) from translation_status_fields f join language l on l.language_id = f.language_id ";

    if ($language_clause != "")
    {
    	$sql .= "and " . $language_clause;
    }

    $sql .= " group by f.language_id, f.field_id, l.description";
    #echo $sql . "<br>\n"; ###

    $res = @mysql_query($sql);
	if ($res)
	{
		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
			$lang_id = $row[0];
            $f_id = $row[1];
            $lang_desc = $row[2];
            $count = $row[3];

            if (!isset($totals[$lang_id]))
            {
                #echo "Added stats for " . $lang_id . "<br>\n";
                $totals[$lang_id] = new StatsValues();
            }

            #echo $lang_id . ":" . $f_id . ":" . $count . "<br>\n";
            $totals[$lang_id]->field_counts[$f_id] = $count;

            $sorted_languages[$lang_id] = $lang_desc;
		}
	}

	@mysql_free_result($res);

    asort($sorted_languages, SORT_STRING);

    $english_stats = $totals[1];
    //var_dump($english_stats);
    $max = 0;

    echo "$(document).ready(function(){\n";

    $index = 1;
    foreach ($sorted_languages as $lang => $thislang)
//    foreach($totals as $lang => $stats)
    {
        //echo "Processing " . $lang . "\n";
        if ($lang <= 1)
            continue;
        if (!isset($totals[$lang]))
        {
            continue;
        }
        $stats = $totals[$lang];
        $line1 = "";
        $line2 = "";
        $labels = "";
        //echo "Getting stats\n";

        $pos = 1;
        $max = GetStats($max, $english_stats, $stats, 2, $line1, $line2, $labels, $pos++);
        $max = GetStats($max, $english_stats, $stats, 6, $line1, $line2, $labels, $pos++);
        $max = GetStats($max, $english_stats, $stats, 5, $line1, $line2, $labels, $pos++);
        $max = GetStats($max, $english_stats, $stats, 4, $line1, $line2, $labels, $pos++);
        $max = GetStats($max, $english_stats, $stats, 1, $line1, $line2, $labels, $pos++);

        $line1 = "[" . $line1 . "];";
        $line2 = "[" . $line2 . "];";
        $labels = "[" . $labels . "]";

        echo "line1 = " . $line2 . "\n";
        echo "line2 = " . $line1 . "\n";

        echo "plot3 = $.jqplot('chart" . $index . "', [line1, line2], {\n";
        echo "stackSeries: false,\n";
        echo "legend: {\n";
        echo "     show: true,\n";
        echo "     location: 'se'\n";
        echo " },\n";
        echo "title: '" . $languages[$lang] . "',\n";
        echo " seriesDefaults: {\n";
        echo "     renderer: $.jqplot.BarRenderer,\n";
        echo "     rendererOptions: {\n";
        echo "         barDirection: 'horizontal',\n";
        echo "         barPadding: 6,\n";
        echo "         barMargin:8\n";
        echo "     }\n";
        echo " },\n";
        echo " series: [{label: '" . $languages[$lang] . "'},\n";
        echo "          {label: 'English'}\n";
        //echo "          {pointLabels: {labels: " . $labels . "}}\n";
        echo "         ],\n";
        echo " axes: {\n";
        echo "     yaxis: {\n";
        echo "         renderer: $.jqplot.CategoryAxisRenderer,\n";
        echo "         ticks: ['Examples', 'Application Note', 'Scope Note', 'Including', 'Caption']\n";
        echo "         \n";
        echo "     },\n";

        $max_xaxis = (($max / 500) + 1) * 500;
        echo "     xaxis: {min: 0, max:" . $max_xaxis . ", numberTicks:5}\n";
        echo " }\n";
        echo "});\n\n";
        $index++;
    }

	echo "});\n";
	echo "</script>\n";
	echo "</head>\n";
	echo "<body>\n";

    $index = 1;

	foreach($sorted_languages as $langid => $description)
	{
		if ($langid <= 1)
			continue;

        	#echo "Fetching stats for language " . $langid . "<br>\n";

		$stats = $totals[$langid];
		$eng_stats = $totals[1];

		$cap_count = 0;
		$ex_count = 0;
		$inc_count = 0;
		$sn_count = 0;
		$an_count = 0;

		if (isset($stats->field_counts[1]))
		{
			$cap_count = $stats->field_counts[1];
        }
		if (isset($stats->field_counts[2]))
		{
			$ex_count = $stats->field_counts[2];
        }
		if (isset($stats->field_counts[4]))
		{
			$inc_count = $stats->field_counts[4];
        }
		if (isset($stats->field_counts[5]))
		{
			$sn_count = $stats->field_counts[5];
        }
		if (isset($stats->field_counts[6]))
		{
			$an_count = $stats->field_counts[6];
        }

		echo "<div style=\"float: left; width: 370px; height: 510px;\">\n";
		echo "	<div id=\"chart" . $index . "\" style=\"float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;\"></div>\n";
		echo "	<div style=\"float: left; clear: left; width: 360px; margin-left: 130px; margin-top: 15px; font-size: 13px; font-family: Tahoma, Helvetica, sans-serif;\">\n";
		echo "		<table width=\"270\" bgcolor=\"#aaaaaa\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\">\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"#EEE6B7\"><strong>Field</strong></td>\n";
		echo "				<td bgcolor=\"#EEE6B7\" align=\"right\"><strong>English</strong></td>\n";
		echo "				<td bgcolor=\"#EEE6B7\" align=\"right\"><strong>" . $description . "</strong></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\">Caption</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $eng_stats->field_counts[1] . "</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $cap_count . "</td>\n";
		echo "			</tr>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\">Including</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $eng_stats->field_counts[4] . "</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $inc_count . "</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\">Scope Note</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $eng_stats->field_counts[5] . "</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $sn_count . "</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\">Application Note</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $eng_stats->field_counts[6] . "</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $an_count . "</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\">Examples</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $eng_stats->field_counts[2] . "</td>\n";
		echo "				<td bgcolor=\"white\" width=\"33%\" align=\"right\">" . $ex_count . "</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "		</table>\n";
		echo "	</div>\n";
		echo "</div>\n";
        $index++;
	}
?>

    <!--div id="chart2" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart3" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart4" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart5" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart6" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart7" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart8" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart9" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart10" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart11" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart12" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart13" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart14" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart15" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart16" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart17" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart18" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart19" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart20" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div>
    <div id="chart21" style="float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;"></div-->
    <div style="float:left; clear: left; margin-top: 20px; margin-bottom: 20px; color: #666666; font-family: Tahoma, sans-serif; font-size: 18px; text-indent: 127px;">Graphs created using <a href="http://www.jqplot.com">jqPlot</a></div>
   </body>
</html>
