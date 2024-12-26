<?php

require_once 'ResetLevels.php';
require_once 'SpecialChars.php';

function BrowseTag($searchtag, &$dbc, $langid=1)
{
	// First, get the hierarchy_code for this class
	$searchCode = "";
	$rowcount=0;
	$code = "";
	$desc = "";
	$broader = "";
	$gotroot = false;
	$searchcode = "";
	
	//echo "SearchTag = " . $searchtag . "<br>\n";
	
	if ($searchtag != "")
	{
		$sql = "select f.language_id, h.hierarchy_code, f.description, c.broader_category, h.hierarchy_count from classmark_hierarchy h, classmarks c, language_fields f ";
		$sql = $sql . " where c.classmark_id = h.classmark_id and c.classmark_id = f.classmark_id and f.field_id = 1 ";
		if ($searchtag != "")
		{
			$sql .= " and c.classmark_tag = '" . $searchtag . "' ";
		}
		//$sql .= "and c.record_marker = 'O'";
		
		//echo $sql . "<br>\n";
		//echo "Attempting SQL<br>\n";
		$res = @mysql_query($sql);
		//echo "SQL executed<br>\n";
		
		if ($res)
		{
			$resultcount = mysql_num_rows($res);
			while ($row = mysql_fetch_array($res, MYSQL_NUM))
			{
				$lang = $row[0];
				
				if ($lang == $langid || $resultcount == 1)
				{				
					$code = $row[1];
					$desc = $row[2];
					$broader = $row[3];
				}
				//echo $code . " " . $desc . "<br>\n";
			}
			$gotroot = true;
		}
		else
		{
			//echo "No rows browsed";
			mysql_free_result($res);
			return -1;
		}

		mysql_free_result($res);
		$searchcode = $code . ".%";
	}
	$charcount = substr_count($code, ".");
	$broadercode = "";
	
	//echo "Fetching hierarchy records<br><br>\n";
	$sql = "select h.classmark_id, c.classmark_tag, f.description, h.hierarchy_code, h.hierarchy_count, f.language_id from classmark_hierarchy h, language_fields f, classmarks c ";
	$sql = $sql . "where c.classmark_id = f.classmark_id and c.classmark_id = h.classmark_id and f.field_id = 1 and f.language_id in (1," . $langid . ")";
	//echo "searchcode = " . $searchcode . "<br>\n";
	if ($searchcode != "")
	{
		$sql .= " and h.hierarchy_code like '" . $searchcode . "'";
	}
	$sql .= " order by h.hierarchy_code, f.language_id";
	//$sql .= " and c.record_marker = 'O'";
	//echo $sql . "<br>\n";
	
	$res = @mysql_query($sql) or die (mysql_error());
	if ($res)
	{
		while ($row = mysql_fetch_array($res, MYSQL_NUM))
		{
			$id = $row[0];
			$tag = $row[1];
			$desc = $row[2];
			$hier = $row[3];
			$cnt = $row[4];
			$lang = $row[5];
			
			$datastring = $tag . '#' . $desc . '#' . $hier . '#' . $cnt . '#' . $lang;
			
			$data_array[$id] = $datastring;
		}

		$rootcount = 0;
		echo "<table class=\"resultpanel\">\n";
		echo "<tr class=\"resultheadertree\"><td><a href=\"javascript: d.openAll();\">expand all</a> | <a href=\"javascript: d.closeAll();\">collapse all</a>&nbsp;&nbsp;&nbsp;</td></tr>\n";
		echo "<tr><td>\n";

		$rowcount=0;
		
		
		echo "<script type=\"text/javascript\">\n";
		echo "<!--\n";
		echo "d = new dTree('d');\n";
		echo "d.config.useSelection = false;\n";
		echo "d.config.useCookies = false;\n";
		echo "d.config.useIcons = true;\n";
		echo "d.config.inOrder = false;\n";
		echo "d.add(0,-1,'Results');\n";
		
		$startrow = 1;
		/*
		if ($broadercode != "")
		{
			if ($broadercount == 0)
			{
				$broaderoutput = "";
			}
			else
			{
				$broaderoutput = " [" . $broadercount . "]";
			}	
			echo "d.add(1,0,'<a href=\"browse.php?tag=" . $broadercode . "\"><font color=\"#0000DD\"><a href=\"displayclassmark.php?tag=" . $broadercode . "\">" . specialcharstree($broadercode) . "</a></font><a href=browse.php?tag=" . $broadercode . "> " . specialcharstree($broaderdesc) . $broaderoutput . "</a>','','','','','',true);\n";
			$startrow = 2;
		}
		*/
		if ($rootcount == 0)
		{
			$rootoutput = "";
		}
		else
		{
			$rootoutput = " [" . $rootcount . "]";
		}	
		echo "d.add(" . chr($startrow+48) . "," . chr($startrow-1+48) . ",'<strong><font color=\"#0000DD\"><a href=\"displayclassmark.php?tag=". $searchtag . "\">" . specialcharstree($searchtag) . "</a></font> " . specialcharstree($desc) . $rootoutput . "</strong>','','','','','',true);\n";
	
		if (mysql_num_rows($res) > 0)
		{
			// Prepare the start count for 20 levels
			for ($i=0; $i<20; $i++)
			{
				$arr[$i] = $startrow;
			}
			
			$rowcount = $startrow + 1;
			$lastdotcount = $charcount;
			
			foreach($data_array as $key => $value)
			{
				//echo $key . " = " . $value . "<br>\n";
				
				$arr_row = @split('#', $value);
				
				$hcode = $arr_row[2];
				$lang = $arr_row[4];
				
				$txtcolr = "black";
				if ($lang != $langid)
				{
					$txtcolr="grey";
				}
				
				//echo "hcode=" . $hcode . "<br>\n";
				
				$dotcount = substr_count($hcode, ".");
				if ($dotcount > $charcount)
				{
					$parent = $arr[$dotcount-$charcount-1];
				}
				else
				{
					$parent = 1;
				}
				
				if ($parent > 1)
				{
					$flag = "false";
				}
				else
				{
					$flag = "true";
				}

				
				if ($dotcount-$charcount < 4)
				{
					$hiercount = $arr_row[3];
					if ($hiercount == 0)
					{
						$outputcount = "";
					}
					else
					{
						$outputcount = " [" . $hiercount . "]";
					}
					echo "d.add(" . $rowcount . "," . $parent . ",'<a href=\"displayclassmark.php?tag=". $arr_row[0] . "\"><font color=\"#0000DD\">" . specialcharstree($arr_row[0]) . "</font></a><a href=\"browse.php?tag=". $arr_row[0] . "\"> " . specialcharstree($arr_row[1]) . $outputcount . "</a>','','','','',''," . $flag .");\n";
					$arr[$dotcount-$charcount] = $rowcount;
			
					if ($dotcount < $lastdotcount)
					{
						ResetLevels($arr, $dotcount, 3);
					}
				}			
				$lastdotcount = $dotcount;
				$rowcount = $rowcount + 1;			
				
			}	
		}
		echo "document.write(d);\n";
		echo "//-->\n";
		echo "</script>\n";
		echo "</td></tr>\n";
		echo "</table>\n";			
	}
		
	$resultcount = mysql_num_rows($res);

	mysql_free_result($res);

	return $resultcount;
}

?>
