<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>UDC Summary Completion Statistics</title>
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

    function GetStats($max, $english_stats, $stats, $fieldid, &$line1, &$line2, &$labels, $pos)
    {      
        if (array_key_exists($fieldid, $english_stats->field_counts))
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
                
        if (array_key_exists($fieldid, $stats->field_counts))
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
    
    $sql = "select language_id, description from language";
    $res = @mysql_query($sql);
	if ($res)
	{
		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
			$languages[$row[0]] = $row[1];					
		}
	}
    
    $totals = array();
    
    $returnstring = "";
    
	@mysql_free_result($res);

    $sql = "select f.language_id, f.field_id, count(f.field_id) from language_fields f join classmarks c on c.classmark_id = f.classmark_id where c.deleted = 'N' group by f.language_id, f.field_id";
    //echo $sql . "\n";
    $res = @mysql_query($sql);
	if ($res)
	{
		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
			$lang_id = $row[0];
            $f_id = $row[1];
            $count = $row[2];	
            
            if (!array_key_exists($lang_id, $totals))
            {
                //echo "Added stats for " . $lang_id . "<br>\n";
                $totals[$lang_id] = new StatsValues();
            }

            //echo $lang_id . ":" . $f_id . ":" . $count . "<br>\n";
            $totals[$lang_id]->field_counts[$f_id] = $count;
		}
	}

	@mysql_free_result($res);

    $english_stats = $totals[1];
    //var_dump($english_stats);
    $max = 0;
                 
    echo "$(document).ready(function(){\n";
                 
    foreach($totals as $lang => $stats)
    {
        //echo "Processing " . $lang . "\n";
        if ($lang <= 1)
            continue;
        
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
        
        echo "line1 = " . $line1 . "\n";
        echo "line2 = " . $line2 . "\n";

        echo "plot3 = $.jqplot('chart" . ($lang-1) . "', [line2, line1], {\n";
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
        echo "     xaxis: {min: 0, max:2500, numberTicks:5}\n";
        echo " }\n";
        echo "});\n\n";       
    }    

	echo "});\n";
	echo "</script>\n";
	echo "</head>\n";
	echo "<body>\n";

	foreach($languages as $langid => $description)
	{
		if ($langid == 1)
			continue;
			
		$stats = $totals[$langid];
		$eng_stats = $totals[1];

		$cap_count = 0;
		$ex_count = 0;
		$inc_count = 0;
		$sn_count = 0;
		$an_count = 0;
		
		if (array_key_exists(1, $stats->field_counts))
		{
			$cap_count = $stats->field_counts[1];
        }
		if (array_key_exists(2, $stats->field_counts))
		{
			$ex_count = $stats->field_counts[2];
        }
		if (array_key_exists(4, $stats->field_counts))
		{
			$inc_count = $stats->field_counts[4];
        }
		if (array_key_exists(5, $stats->field_counts))
		{
			$sn_count = $stats->field_counts[5];
        }
		if (array_key_exists(6, $stats->field_counts))
		{
			$an_count = $stats->field_counts[6];
        }
		
		echo "<div style=\"float: left; width: 370px; height: 510px;\">\n";
		echo "	<div id=\"chart" . ($langid-1) . "\" style=\"float: left; margin-top:20px; margin-left:50px; width:360px; height:300px;\"></div>\n";
		echo "	<div style=\"float: left; clear: left; width: 360px; margin-left: 130px; margin-top: 15px; font-size: 13px; font-family: Tahoma, Helvetica, sans-serif;\">\n";
		echo "		<table width=\"270\" bgcolor=\"#aaaaaa\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\">\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"#EEE6B7\"><strong>Field</strong></td>\n";
		echo "				<td bgcolor=\"#EEE6B7\" align=\"right\"><strong>% completion</strong></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\">Caption</td>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\" align=\"right\">" . number_format(($cap_count / $eng_stats->field_counts[1]) * 100, 1) . "</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\">Including</td>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\" align=\"right\">" . number_format(($inc_count / $eng_stats->field_counts[4]) * 100, 1) . "</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\">Scope Note</td>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\" align=\"right\">" . number_format(($sn_count / $eng_stats->field_counts[5]) * 100, 1) . "</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\">Application Note</td>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\" align=\"right\">" . number_format(($an_count / $eng_stats->field_counts[6]) * 100, 1) . "</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\">Examples</td>\n";
		echo "				<td bgcolor=\"white\" width=\"50%\" align=\"right\">" . number_format(($ex_count / $eng_stats->field_counts[2]) * 100, 1) . "</td>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</div>\n";
		echo "</div>\n";
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
