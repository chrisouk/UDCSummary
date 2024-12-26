<?php

function DisplayTag($searchtag, &$mdb2, $langid=1)
{
	// First, get the hierarchy_code for this class
	$sql = "select c.classmark_id, f.description, h.heading_type_description, c.classmark_tag from classmarks c, headingtypes h, language_fields f ";
	$sql = $sql . " where c.heading_type = h.heading_type_id and c.classmark_id = f.classmark_id and f.language_id = " . $langid . " and c.classmark_tag = '" . $searchtag . "'";
	//echo $sql . "<br>\n";
	$res = @mysql_query($sql);
	
	$rowcount=0;
	$resultcount = mysql_num_rows($res);
	if ($resultcount > 0)
	{
		$row = mysql_fetch_array($res, MYSQL_NUM);
		$id = $row[0];
		$desc = $row[1];
		$heading = $row[2];
		$tag = $row[3];

		echo "<table class=\"resulttable\" cellspacing=\"1\" cellpadding=\"2\">\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"#ccccff\" width=\"87%\" colspan=\"6\" valign=\"top\"><strong><font color=\"#000000\">Classmark Details</font></strong></font></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Classmark</td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\">" . $tag . "</font></td>\n";
		echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Heading Type</td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\">" . $heading . "</td>\n";
		echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">MFN</td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\">" . $id . "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Description</td>\n";
		echo "<td bgcolor=\"#FFFFFF\" width=\"87%\" colspan=\"5\" valign=\"top\"><font color=\"#465088\" valign=\"top\">" . $desc . "</font></td>\n";
		echo "</tr>\n";
		
		mysql_free_result($res);
		
		// Add any verbal examples (one row per language)
		$sql = "select f.language_id, l.description, f.description from classmarks c, language_fields f, field_types t, language l ";
		$sql = $sql . " where c.classmark_id = f.classmark_id and f.field_id = t.field_id and f.language_id = l.language_id and f.language_id = " . $langid . " and t.description = 'verbal_example' and f.language_id = l.language_id and c.classmark_id = " . $id;
//		echo $sql."<br>\n";
		
		$res = @mysql_query($sql);
		
		$resultcount = mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				if ($resultcount > 1)
				{
					$lang=" (" . $row[1] . ")";
				}
				else 
				{
					$lang = "";
				}
				$langid = $row[0];
				$scopenote = $row[2];
				echo "<tr>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Including" . $lang . "</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"87%\" colspan=\"5\" valign=\"top\"><font color=\"#465088\">" . $scopenote . "</font></td>\n";
				echo "</tr>\n";
			}
		}

		mysql_free_result($res);

		// Add any application note (one row per language)
		$sql = "select f.language_id, l.description, f.description from classmarks c, language_fields f, field_types t, language l ";
		$sql = $sql . " where c.classmark_id = f.classmark_id and f.field_id = t.field_id and f.language_id = l.language_id and f.language_id = " . $langid . " and t.description = 'application_note' and f.language_id = l.language_id and c.classmark_id = " . $id;
//		echo $sql."<br>\n";
		
		$res = @mysql_query($sql);
		
		$resultcount = mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				if ($resultcount > 1)
				{
					$lang=" (" . $row[1] . ")";
				}
				else 
				{
					$lang = "";
				}
				$langid = $row[0];
				$scopenote = $row[2];
				echo "<tr>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Application Note" . $lang . "</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"87%\" colspan=\"5\" valign=\"top\"><font color=\"#465088\">" . $scopenote . "</font></td>\n";
				echo "</tr>\n";
			}
		}

		mysql_free_result($res);
		
		// Add any scope note (one row per language)
		$sql = "select f.language_id, l.description, f.description from classmarks c, language_fields f, field_types t, language l ";
		$sql = $sql . " where c.classmark_id = f.classmark_id and f.field_id = t.field_id and f.language_id = l.language_id and f.language_id = " . $langid . " and t.description = 'scope_note' and f.language_id = l.language_id and c.classmark_id = " . $id;
//		echo $sql."<br>\n";
		
		$res = @mysql_query($sql);
		
		$resultcount = mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				if ($resultcount > 1)
				{
					$lang=" (" . $row[1] . ")";
				}
				else 
				{
					$lang = "";
				}
				$langid = $row[0];
				$scopenote = $row[2];
				echo "<tr>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Scope Note" . $lang . "</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"87%\" colspan=\"5\" valign=\"top\"><font color=\"#465088\">" . $scopenote . "</font></td>\n";
				echo "</tr>\n";
			}
		}

		mysql_free_result($res);
		
		// Examples of combination
		// Add any scope note (one row per language)
		$sql = "select e.field_type, e.tag, f.description, c.classmark_tag ";
		$sql = $sql . "from classmarks c, language_fields f, example_classmarks e, field_types t ";
		$sql = $sql . "where c.classmark_id = " . $id . " ";
		$sql = $sql . "and e.classmark_id = c.classmark_id ";
		$sql = $sql . "and c.classmark_id = f.classmark_id ";
		$sql = $sql . "and f.field_id = t.field_id ";
		$sql = $sql . "and f.language_id = 1 ";
		$sql = $sql . "and t.description = 'example_desc' ";
		$sql = $sql . "and e.seq_no = f.seq_no ";		
		$sql = $sql . "order by e.field_type ";
		//echo $sql."<br>\n";
		
		$res = @mysql_query($sql);
		
		$resultcount = mysql_num_rows($res);
		if ($resultcount > 0)
		{
			$bFirstRow = true;
			while(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				$fieldtype = $row[0];
				$exampletag = $row[1];
				$exampledesc = $row[2];
				$classtag = $row[3];
				
				$rowspan = "";
				if ($bFirstRow && $resultcount > 1)
				{
					$rowspan = " rowspan=\"" . $resultcount . "\"";
				}
				
				switch ($fieldtype)
				{
					case 'a':
						$example = $classtag . $exampletag;
						break;
					case 'b':
						$example = $classtag . ":" . $exampletag;
						break;
					case 'c':
						$example = $exampletag;
						break;
					case 'r':
						$example = "see <a href=\"displayclassmark.php?tag=" . $exampletag . ">";
						break;
					default:
						$example = "unknown example type " . $fieldtype;
						break;
				}

				echo "<tr>\n";
				if ($bFirstRow)
				{
					echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\"" . $rowspan . ">Examples of Combination</td>\n";
				}
				echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\">" . $example . "</font></td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"74%\" colspan=\"4\" valign=\"top\"><font color=\"#465088\">" . $exampledesc . "</font></td>\n";
				echo "</tr>\n";
				$bFirstRow = false;
			}
		}

		mysql_free_result($res);

		// Add any references
		$sql = "select r.notation, f.description from classmarks c, classmarks c2, classmark_refs r, language_fields f, field_types t ";
		$sql = $sql . " where c2.classmark_tag = r.notation and c2.classmark_id = f.classmark_id and f.language_id = " . $langid . " and c.classmark_id = r.classmark_id and f.field_id = t.field_id and t.description = 'classmark_tag' and c.classmark_id = " . $id . " order by r.sequence_no";
		//echo $sql."<br>\n";
		
		$res = @mysql_query($sql);
		
		$resultcount = mysql_num_rows($res);
		if ($resultcount > 0)
		{
			$bFirstRow = true;
			while(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				$ref = $row[0];
				$tag = $row[1];
				$rowspan = "";
				if ($bFirstRow && $numRows > 1)
				{
					$rowspan = " rowspan=\"" . $numRows . "\"";
				}
				
				echo "<tr>\n";
				if ($bFirstRow)
				{
					echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\"" . $rowspan . ">See also</td>\n";
				}
				echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\"><a href=\"displayclassmark.php?tag=" . $ref . "\">" . $ref . "</a></font></td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"74%\" colspan=\"4\" valign=\"top\"><font color=\"#465088\">" . $tag . "</font></td>\n";
				echo "</tr>\n";
				$bFirstRow = false;
			}
		}

		mysql_free_result($res);	
		
		// Introduction dates
		$sql = "select h.audit_date, h.audit_source, h.audit_comment from audit_history h where h.classmark_id = " . $id . " and h.audit_type = 'I'";
//		echo $sql."<br>\n";
		
		$res = @mysql_query($sql);
		
		$resultcount = mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				$adate = $row[0];
				$asource = $row[1];
				$acomment = $row[2];
				echo "<tr>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Introduced</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\">" . $adate. "</font></td>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Source</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\">" . $asource. "</font></td>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Comment</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"15%\" valign=\"top\"><font color=\"#465088\">" . $acomment. "</font></td>\n";
				echo "</tr>\n";
			}
		}

		mysql_free_result($res);	

		// Revision dates
		$sql = "select h.audit_date, h.audit_source, h.audit_comment from audit_history h where h.classmark_id = " . $id . " and h.audit_type = 'R'";
//		echo $sql."<br>\n";
		
		$res = @mysql_query($sql);
		
		$resultcount = mysql_num_rows($res);
		if ($resultcount > 0)
		{
			while(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				$adate = $row[0];
				$asource = $row[1];
				$acomment = $row[2];
				echo "<tr>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Last Revised</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\">" . $adate. "</font></td>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Source</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"13%\" valign=\"top\"><font color=\"#465088\">" . $asource. "</font></td>\n";
				echo "<td bgcolor=\"#eeeeff\" width=\"13%\" valign=\"top\">Comment</td>\n";
				echo "<td bgcolor=\"#FFFFFF\" width=\"15%\" valign=\"top\"><font color=\"#465088\">" . $acomment. "</font></td>\n";
				echo "</tr>\n";
			}
		}

		mysql_free_result($res);	

/*		echo "<table bgcolor=\"#000080\" cellspacing=\"1\">\n";
		echo "<tr><td bgcolor=\"#ffffff\">Classmark Details<td></tr>\n";
		echo "<tr>\n";
		echo "<td bgcolor=\"#CEE7FF\">Classmark</td><td bgcolor=\"#FFFFFF\">" . $tag . "</td>\n";
		echo "<td>Heading Type</td><td>" . $heading . "</td>\n";
		echo "<td>" . $id . "</td><td>MFN</td>\n";
		echo "</tr>\n";*/
		echo "</table>\n";
//		echo "</div>\n";
		
	}
	else
	{
		mysql_free_result($res);
		return -1;
	}
	
	return $resultcount;
}

?>
