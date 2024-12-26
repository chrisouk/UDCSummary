<?php

    session_start();
    require_once("checksession.php");
    checksession();

    #header("Content-type: text/html; charset=utf-8");

	define("DEBUGON", false);

/**
 * @author Chris Overfield
 * @copyright 2009
 */

	function CheckCommentsNotification($formob)
	{
		if (!isset($_SESSION['oldeditorcomments']) || $formob->editor_comments != $_SESSION['oldeditorcomments'])
		{
			$subject = "Translation editor comment: " . $formob->notation . " by " . ucfirst($_SESSION['userid']);
			$emailbody = "Class: " . $formob->notation . "  " . $formob->caption . "\n\n";
			$emailbody .= "New: " . $formob->editor_comments . "\n\n";
			$emailbody .= "Old: " . $_SESSION['oldeditorcomments'] . "\n\n";

			$emailbody = wordwrap($emailbody, 70);

			//echo $mailstring . "<br>\n";
			$headers = 'From: udcs@udcc.org' . "\r\n" .
					   'Reply-To: udcs@udcc.org' . "\r\n" .
					   'X-Mailer: PHP/' . phpversion();

			//echo $subject . "<br>\n";
			//echo $emailbody . "<br>\n";
			//mail("udcs@udcc.org", $subject, $emailbody, $headers);
		}
	}

	function ajaxUnformat($value)
	{
		$value = str_replace("@@#@@", "+", $value);
		$value = str_replace("@@###@@", "\"", $value);
		$value = str_replace("@@#####@@", "<", $value);
		$value = str_replace("@@######@@", ">", $value);

		return $value;
	}

	function ajaxFormat($value)
	{
		$value = str_replace("+", "@@#@@", $value);
		$value = str_replace("\"", "@@###@@", $value);
		$value = str_replace("<", "@@#####@@", $value);
		$value = str_replace(">", "@@######@@", $value);

		return $value;
	}

	function SaveSQL($sqlarray, &$formob, $dbc, $dummy = false)
	{
		// Save the SQL
		$error = "";

		if (!$dummy && !@mysql_query("BEGIN", $dbc))
		{
			// Begin a transaction
			$error = "Failed to begin a database transaction";
		}
		else
		{
			foreach($sqlarray as $i => $sql)
			{
				if ($dummy)
					echo $sql . "<br>\n";

				if (!$dummy && !@mysql_query($sql, $dbc))
				{
					$error = @mysql_error($dbc);
					array_push($formob->validation_errors, "SQL Error: " . $error);
					if (!@mysql_query("ROLLBACK", $dbc))
					{
						array_push($formob->validation_errors, "Failed to rollback SQL!!");
					}
					echo "BAD: " . $sql . "(". $error . ")<br>\n";
					break;
				}
				else
				{
					if (!$dummy)
					{
						# Check to see if we need to send a comments email
						//echo "OK: " . $sql . "<br>\n";
					}
				}
			}
		}

		$returnvalue = false;
		if ($error == "")
		{
			if (!$dummy && !@mysql_query("COMMIT", $dbc))
			{
				array_push($formob->validation_errors, "Failed to commit SQL to database");
			}
			@mysql_query("COMMIT");

			//echo "Checking for comments<br>\n";
			//CheckCommentsNotification($formob);

	        $statsarray = array();
	        $formob->UpdateFieldStats($statsarray);

			foreach($statsarray as $i => $sql)
			{
				//if ($dummy)
					//echo $sql . "<br>\n";

				if (!$dummy && !@mysql_query($sql, $dbc))
				{
					$error = @mysql_error($dbc);
					array_push($formob->validation_errors, "SQL Error: " . $error);
					if (!@mysql_query("ROLLBACK", $dbc))
					{
						array_push($formob->validation_errors, "Failed to rollback SQL!!");
					}
					echo "BAD: " . $sql . "(". $error . ")<br>\n";
					break;
				}
				else
				{
					if (!$dummy)
					{
						# Check to see if we need to send a comments email
						//echo "OK: " . $sql . "<br>\n";
					}
				}
			}

	        @mysql_query("COMMIT");

			$returnvalue = true;
		}
		else
		{
			@mysql_query("ROLLBACK");
		}

		return $returnvalue;
	}

	function ShowFieldContents($field)
	{
		$error = "Field: " . $field . "<br><br>\n";

		for($i=0; $i<strlen($field); $i++)
		{
			if ($i % 10 == 0)
			{
				$error .= "<br>\n";
			}
			$val = ord($field[$i]);
			$error .= $val . " ";
		}

		return $error;
	}

	/**
	 * RemoveIllegalCharacters
	 * Remove all line feeds (currently) from the field and replace with spaces
	 * @param $field
	 * @return mixed
	 */

	function RemoveIllegalCharacters($field)
	{
		$field = str_replace("\\n", "#", $field);
		$field = str_replace("\\t", "#", $field);

		#echo ShowFieldContents($field);

		$parts = explode("#", $field);
		if (count($parts) > 1)
		{
			$output = "";

			foreach($parts as $part)
			{
				$part = trim($part);
				if (empty($part))
					continue;

				if (!empty($output))
				{
					$output .= " ";
				}

				$output .= $part;
			}

			$field = $output;
		}

		return $field;
	}

	include_once "dbconnection.php";

	# Extract the input parameters
	if (!isset($_POST['content']))
	{
		echo "*No record details received";
		return;
	}

	$xml_reader = new XMLReader();
	$xml_reader->XML($_POST['content']);

	$sqlarray = array();

	$caption = "";
	$notation = "";
	$language = 0;
	$id = 0;

	$output_notation = "";
	$output_caption = "";

	while($xml_reader->read())
	{
		$fieldlist = $xml_reader->value;
		if (trim($fieldlist == ""))
			continue;

		$fields = explode("|||||", $fieldlist);

		if (count($fields) < 10)
		{
			echo "*Too few fields submitted (" . count($fields) . ") [" . $fieldlist . "]";
			return;
		}

		$language = @mysql_real_escape_string(ajaxUnformat(trim($fields[0])), DBConnection::getInstance()->getConnection());
		$output_notation = ajaxUnformat(trim($fields[1]));
		$notation = @mysql_real_escape_string($output_notation, DBConnection::getInstance()->getConnection());
		$id = @mysql_real_escape_string(ajaxUnformat(trim($fields[2])), DBConnection::getInstance()->getConnection());
		$output_caption = ajaxUnformat(trim($fields[3]));
		$caption = @mysql_real_escape_string($output_caption, DBConnection::getInstance()->getConnection());
		$including= @mysql_real_escape_string(ajaxUnformat(trim($fields[4])), DBConnection::getInstance()->getConnection());
		$scope_note = @mysql_real_escape_string(ajaxUnformat(trim($fields[5])), DBConnection::getInstance()->getConnection());
		$app_note = @mysql_real_escape_string(ajaxUnformat(trim($fields[6])), DBConnection::getInstance()->getConnection());
		$comments_for_user = @mysql_real_escape_string(ajaxUnformat(trim($fields[7])), DBConnection::getInstance()->getConnection());
		$comments_for_editor = @mysql_real_escape_string(ajaxUnformat(trim($fields[8])), DBConnection::getInstance()->getConnection());
		$example_count = @mysql_real_escape_string(ajaxUnformat(trim($fields[9])), DBConnection::getInstance()->getConnection());

		$return_ajax = "";
		for($i=0; $i<strlen($app_note); $i++)
		{
			$substring = substr($app_note, $i, 1);
			$return_ajax .= ":" . dechex(ord($substring));
		}

		$debug = trim($fields[6])."~".$app_note."~".$return_ajax;

		$examples = array();
		for($i = 0; $i < $example_count; $i++)
		{
			if (count($fields) > ($i+10))
			{
				array_push($examples, @mysql_real_escape_string(ajaxUnformat(trim($fields[(10+$i)])), DBConnection::getInstance()->getConnection()));
			}
		}

		$sqlarray = array();
		if ($caption != "")
		{
			$caption = trim(RemoveIllegalCharacters($caption));
			$sql = "replace into language_fields (field_id, language_id, seq_no, description, classmark_id) select 1, " . $language . ", 1, convert('" . $caption . "' using utf8), " .
				   "classmark_id from classmarks where active = 'Y' and classmark_id = " . $id . ";";
			array_push($sqlarray, $sql);
		}
		else
		{
			$sql = "delete from language_fields where field_id = 1 and language_id = " . $language . " and classmark_id = " . $id . ";";
			array_push($sqlarray, $sql);
		}

		if ($including != "")
		{
			$including = trim(RemoveIllegalCharacters($including));
			$sql = "replace into language_fields (field_id, language_id, seq_no, description, classmark_id) select 4, " . $language . ", 1, convert('" . $including . "' using utf8), " .
				   "classmark_id from classmarks where active = 'Y' and classmark_id = " . $id . ";";
			array_push($sqlarray, $sql);
		}
		else
		{
			$sql = "delete from language_fields where field_id = 4 and language_id = " . $language . " and classmark_id = " . $id . ";";
			array_push($sqlarray, $sql);
		}

		if ($scope_note != "")
		{
			$scope_note = trim(RemoveIllegalCharacters($scope_note));
			$sql = "replace into language_fields (field_id, language_id, seq_no, description, classmark_id) select 5, " . $language . ", 1, convert('" . $scope_note . "' using utf8), " .
				   "classmark_id from classmarks where active = 'Y' and classmark_id = " . $id . ";";
			array_push($sqlarray, $sql);
		}
		else
		{
			$sql = "delete from language_fields where field_id = 5 and language_id = " . $language . " and classmark_id = " . $id . ";";
			array_push($sqlarray, $sql);
		}

		if ($app_note != "")
		{
			$app_note = trim(RemoveIllegalCharacters($app_note));
			$sql = "replace into language_fields (field_id, language_id, seq_no, description, classmark_id) select 6, " . $language . ", 1, convert('" . $app_note . "' using utf8), " .
				   "classmark_id from classmarks where active = 'Y' and classmark_id = " . $id . ";";
			array_push($sqlarray, $sql);
		}
		else
		{
			$sql = "delete from language_fields where field_id = 6 and language_id = " . $language . " and classmark_id = " . $id . ";";
			array_push($sqlarray, $sql);
		}

		$sql = "delete from udct_editor_comments where classmark_id = " . $id . " and reviewer = '" . $_SESSION['userid'] . "';";
		array_push($sqlarray, $sql);

		if ($comments_for_editor != "")
		{
			$sql = "insert into udct_editor_comments (classmark_id, reviewer, comments, date_changed) values (" . $id . ", '" . $_SESSION['userid']. "', convert('" .
					$comments_for_editor . "' using utf8), now());";
			array_push($sqlarray, $sql);
		}

		$sql = "delete from udct_comments where classmark_id = " . $id . " and reviewer = '" . $_SESSION['userid'] . "';";
		array_push($sqlarray, $sql);

		if ($comments_for_user != "")
		{
			$sql = "insert into udct_comments (classmark_id, reviewer, comments, date_changed) values (" . $id . ", '" . $_SESSION['userid']. "', convert('" .
		    $comments_for_user . "' using utf8), now());";
		    array_push($sqlarray, $sql);
		}

		$sql = "delete from language_fields where classmark_id = " . $id . " and field_id = 2 and language_id = " . $language . ";";
		array_push($sqlarray, $sql);

		foreach ($examples as $example)
		{
			$example_parts = explode("|", $example, 2);
			if (count($example_parts) == 2)
			{
				$example_description = $example_parts[0];
				$example_description = trim(RemoveIllegalCharacters($example_description));
				$example_seq = $example_parts[1];
                $sql =  "insert into language_fields (field_id, language_id, seq_no, description, classmark_id) values (2, " . $language. ", " . $example_seq .
                        ", convert('" . $example_description . "' using utf8), " . $id . ");";
                array_push($sqlarray, $sql);
			}
		}

        $sql = "delete from translation_status_fields where classmark_id = " . $id . " and language_id = " . $language . ";";
        array_push($sqlarray, $sql);

		$sql =  "insert into translation_status_fields (classmark_id, language_id, field_id, lang_field_count) " .
				"select " . $id . ", " . $language . ", f.field_id, count(*) " .
				"from language_fields f join classmarks c on c.classmark_id = f.classmark_id " .
				"where c.classmark_id = "  . $id . " and f.language_id = " . $language . " " .
				"and f.field_id in (select field_id from language_fields f2 where f2.classmark_id = f.classmark_id and f2.seq_no = f.seq_no and f2.field_id = f.field_id and f2.language_id = 1) " .
				"group by f.classmark_id, f.language_id, f.field_id";

        //$sql = "replace into translation_status_fields (classmark_id, language_id, field_id, lang_field_count) select " .
		//$id . ", " . $language . ", f.field_id, count(*) from language_fields f where f.classmark_id = " . $id . " and f.language_id = " . $language .
        //" group by f.classmark_id, f.language_id, f.field_id;";
        array_push($sqlarray, $sql);

		break;
	}

	$_SESSION['savesuccess'] = false;

	$errorstring = "";
	if (count($sqlarray) > 0)
	{
		foreach($sqlarray as $sql)
		{
			if (DEBUGON)
			{
				echo $sql . "<br>\n";
			}
			else if (!@mysql_query($sql, DBConnection::getInstance()->getConnection()))
			{
				$errorstring .= @mysql_error(DBConnection::getInstance()->getConnection() . "\n");
			}
		}

		if ($errorstring != "")
		{
			$errorstring = "*" . $errorstring;
			@mysql_query("ROLLBACK", DBConnection::getInstance()->getConnection());
		}
		else
		{
			@mysql_query("COMMIT", DBConnection::getInstance()->getConnection());
			$_SESSION['savesuccess'] = true;
		}
	}

	$count_1 = 0;
	$count_2 = 0;
	$equals = "N";

	if (empty($caption))
	{
		$sql = 	"select description from language_fields where field_id = 1 and language_id = 1 and classmark_id = " . $id;
		$res = @mysql_query($sql, DBConnection::getInstance()->getConnection());

		if ($res)
		{
			$row = @mysql_fetch_array($res, MYSQL_NUM);
			$output_caption = $row[0];
		}

		@mysql_free_result($res);
	}
	else
	{
		$sql = 	"select sum(s1.lang_field_count) " .
				" from translation_status_fields s1 " .
				" where s1.language_id = 1 and s1.classmark_id = " . $id;

		$res = @mysql_query($sql, DBConnection::getInstance()->getConnection());

		if ($res)
		{
			$row = @mysql_fetch_array($res, MYSQL_NUM);
			$count_1 = $row[0];
		}

		@mysql_free_result($res);

		$sql = 	"select sum(s1.lang_field_count) " .
				" from translation_status_fields s1 " .
				" where s1.language_id = " . $language . " and s1.classmark_id = " . $id;

		$res = @mysql_query($sql, DBConnection::getInstance()->getConnection());

		if ($res)
		{
			$row = @mysql_fetch_array($res, MYSQL_NUM);
			$count_2 = $row[0];
		}

		@mysql_free_result($res);

		if ($count_1 == $count_2)
		{
			$equals = "Y";
		}
	}

	#echo implode("\n", $sqlarray);
	echo $output_notation . "#" . $equals . "#" . $output_caption . '#'; // . $debug;
?>