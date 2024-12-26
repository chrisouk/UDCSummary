<?php

    session_cache_expire("120");
    session_start();
    require_once("checksession.php");
    checksession();

	define('DEBUGON', true);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Chris Overfield" />
    <link rel="stylesheet" href="../udcedit.css" type="text/css" />
    <link rel="stylesheet" href="../udc1000.css" type="text/css" />
	<link rel="stylesheet" href="copyrecords.css" type="text/css" />
    <link rel="shortcut icon" href="../img/udc.ico" type="image/x-icon" />

	<title><?php if (isset($_SESSION['mgmt_site_name'])) echo $_SESSION['mgmt_site_name']; ?> Editor</title>
</head>

<body>

<div id="pagecontainer">
	<div id="titleimagecontainer_thin" style="width: 945px; margin-left: auto; margin-right: auto;">&nbsp;</div>
	<div style="width: 645px; margin-left: auto; margin-right: auto; margin-top: 10px; font-family: Tahoma, Helvetica, sans-serif; font-size: 13px;">
		<div style="width: 645px; float: right"><a href="edittag.php">Back to Editor</a></div>
		<div style="width: 645px; float: right; clear: left; margin: 10px 0px;">

<?php
	function DebugEcho($output)
	{
		if (DEBUGON)
		{
			echo $output . "<br>\n";
		}
	}

	function LoadRecords(&$dbc, Array &$tag_list, &$loaded_records)
	{
		$sql =  "select c.classmark_id, c.classmark_tag, c.active, c2.classmark_tag, a.audit_date, a2.audit_date " .
				"from classmarks c " .
				"left outer join classmarks c2 on c.broader_category = c2.classmark_id " .
				"left outer join audit_history a on c.classmark_id = a.classmark_id and a.audit_type = 'I' " .
				"left outer join audit_history a2 on c.classmark_id = a2.classmark_id and a2.audit_type = 'C' " .
				"where c.classmark_tag in (";

		$quoted_tags = "";
		foreach ($tag_list as $tag)
		{
			if (!empty($quoted_tags))
			{
				$quoted_tags .= ", ";
			}
			$quoted_tags .= "'" . mysql_real_escape_string(trim($tag), $dbc) . "'";
		}

		$sql .= $quoted_tags . ")";
		DebugEcho($sql);
		$result = mysql_query($sql, $dbc);
		if ($result)
		{
			while ($row = mysql_fetch_array($result, MYSQL_NUM))
			{
				#echo "Loaded " . $row[1] . " (" . $row[2] . ")<br>\n";
				$record = new MRFRecord();
				$record->id = $row[0];
				$record->tag = trim($row[1]);
				if (trim($row[2]) == 'Y')
				{
					$record->cancelled = false;
				}
				else
				{
					$record->cancelled = true;
				}
				$record->broader = $row[3];
				$record->intro_date = $row[4];
				$record->cancel_date = $row[5];

				if (isset($loaded_records[$record->tag]))
				{
					if ($loaded_records[$record->tag]->cancelled == true)
					{
						$loaded_records[$record->tag] = $record;
					}
				}
				else
				{
					$loaded_records[$record->tag] = $record;
				}
			}

			mysql_free_result($result);
		}
		else
		{
			#echo "Failed: " . mysql_error() . "<br>\n";
		}
	}

	class MRFRecord
	{
		public $id;
		public $broader;
		public $tag;
		public $cancelled;
		public $intro_date;
		public $cancel_date;
		public $status;

		public function clear()
		{
			$this->id = 0;
			$this->broader = "";
			$this->tag = "";
			$this->cancelled = false;
			$this->intro_date = "";
			$this->cancel_date = "";
			$this->status = "";
		}

		public function __construct()
		{
			$this->clear();
		}
	}

	require_once('DBConnectInfo.php');
	require_once('MRFDBConnectInfo.php');

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    define("RECORDS_PER_PAGE", 30);
    
    $start_record = 0;
    if (isset($_GET['sr']))
    {
        $start_record = $_GET['sr'];
    }
 ?>

	<form action="copyrecords.php" method="POST">
	<div style="margin-bottom: 10px; overflow: auto">
		<div style="width: 320px; float: left">Pocket Edition Database: <span style="color: blue"><?php echo DBDATABASE; ?></span></div><div style="width: 320px; float: left">MRF Database: <span style="color: blue"><?php echo MRF_DBDATABASE; ?></span></div>
	</div>
	<div>
		<textarea style="width: 643px; height: 300px; border: 1px solid #afafaf" name="tags"><?php if (isset($_POST['tags'])) echo $_POST['tags']; ?></textarea>
	</div>
	<div style="width: 645px; text-align: right"><input type="submit" name="Submit" value="Submit"/></div>
	</form>

	<div>
		<table width="345" cellspacing="1" cellpadding="2" bgcolor="#efefef">
		<?php
			foreach ($tag_list as $tag)
			{
				echo "<tr><td width=\"50%\" bgcolor=\"white\">" . $tag ."</td><td width=\"50%\" bgcolor=\"white\">OK</td></tr>\n";
			}
		?>
		</table>
	</div>

	<form action="copyrecords.php" method="POST">
		<input type="hidden" name="tags" value="<?php echo htmlentities($_POST['tags'], ENT_COMPAT, 'UTF-8'); ?>" />
		<div>&nbsp;</div>
			<?php
				$sqlarray = array();
				$errors = array();

				$tag_list = "";
				if (isset($_POST['tags']))
				{
					$tags = trim($_POST['tags']);
					$tag_list = explode("\n", $tags);
				}

				if (isset($_POST['Submit']))
				{
					echo "<table class=\"results\" width=\"645\" bgcolor=\"#efefef\" cellspacing=\"1\" cellpadding=\"2\">\n";
					echo "<tr><td class=\"header\">ID</td><td class=\"header\">Notation</td><td class=\"header\">Broader</td><td class=\"header\">Introduced</td><td class=\"header\">Cancelled</td><td class=\"header\">Status</td></tr>\n";
					DebugEcho("Connecting: " . DBHOST . ", " . DBUSER . ", " . DBPASS . ", " . DBDATABASE);
					$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
					mysql_select_db (DBDATABASE, $dbc);
					mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
					mysql_query("SET CHARACTER SET utf8");
					mysql_query("SET NAMES utf8");	//mysql_set_charset('latin1',$dbc);

					DebugEcho("Connecting: " . MRF_DBHOST . ", " . MRF_DBUSER . ", " . MRF_DBPASS . ", " . MRF_DBDATABASE);
					$mrf_dbc = mysql_connect (MRF_DBHOST, MRF_DBUSER, MRF_DBPASS, true) or die ('Could not connect to MRF database: ' . mysql_error());
					mysql_select_db (MRF_DBDATABASE, $mrf_dbc);
					mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $mrf_dbc);
					mysql_query("SET CHARACTER SET utf8");
					mysql_query("SET NAMES utf8");

					if (count($tag_list) > 0)
					{
						$mrf_records = array();
						$dest_records = array();

						LoadRecords($mrf_dbc, $tag_list, $mrf_records);
						LoadRecords($dbc, $tag_list, $dest_records);

						foreach($tag_list as $tag)
						{
							$tag = trim($tag);

							$row_colour = "lightred";
							$broader_colour = "yellow";

							if (isset($mrf_records[$tag]))
							{
								$record =& $mrf_records[$tag];
								if ($record->cancelled == true)
								{
									$row_colour = "lightgrey";
								}
								else
								{
									$row_colour = "lightgreen";
								}

								if ($record->cancelled == true)
								{
									$record->status = "CANCELLED";
								}
								else
								{
									if (isset($dest_records[$tag]) && $dest_records[$tag]->cancelled == false)
									{
										$row_colour = "lightred";
										if ($dest_records[$tag]->id != $record->id)
										{
											$record->status = "ID MISMATCH (" . $dest_records[$tag]->id . ")";
										}
										else
										{
											$record->status = "DEST EXISTS";
										}
									}
									else
									{
										$record->status = "OK";
									}

									if (isset($dest_records[$record->broader]))
									{
										$broader_colour = $row_colour;
									}
								}

								echo "<tr>\n";
								echo "<td class=\"" . $row_colour . "\">" . $record->id . "</td>\n";
								echo "<td class=\"" . $row_colour . "\">" . $record->tag . "</td>\n";
								echo "<td class=\"" . $broader_colour . "\">" . $record->broader . "</td>\n";
								echo "<td class=\"" . $row_colour . "\">" . $record->intro_date . "</td>\n";
								echo "<td class=\"" . $row_colour . "\">" . $record->cancel_date . "</td>\n";
								echo "<td class=\"" . $row_colour . "\">" . $record->status . "</td>\n";
								echo "</tr>\n";
							}
							else
							{
								echo "<tr>\n";
								echo "<td class=\"" . $row_colour . "\">&nbsp;</td>\n";
								echo "<td class=\"" . $row_colour . "\">" . $tag. "</td>\n";
								echo "<td class=\"" . $broader_colour . "\">&nbsp;</td>\n";
								echo "<td class=\"" . $row_colour . "\">&nbsp;</td>\n";
								echo "<td class=\"" . $row_colour . "\">&nbsp;</td>\n";
								echo "<td class=\"" . $row_colour . "\">DNE</td>\n";
								echo "</tr>\n";
							}
						}
					}

					$_SESSION['records'] = $mrf_records;

					echo "</table>\n";

					mysql_close($mrf_dbc);
					mysql_close($dbc);
				}

				if (isset($_POST['Copy']))
				{
					DebugEcho("Connecting: " . DBHOST . ", " . DBUSER . ", " . DBPASS . ", " . DBDATABASE);
					$dbc = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
					mysql_select_db (DBDATABASE, $dbc);
					mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
					mysql_query("SET CHARACTER SET utf8");
					mysql_query("SET NAMES utf8");	//mysql_set_charset('latin1',$dbc);

					DebugEcho("Connecting: " . MRF_DBHOST . ", " . MRF_DBUSER . ", " . MRF_DBPASS . ", " . MRF_DBDATABASE);
					$mrf_dbc = mysql_connect (MRF_DBHOST, MRF_DBUSER, MRF_DBPASS, true) or die ('Could not connect to MRF database: ' . mysql_error());
					mysql_select_db (MRF_DBDATABASE, $mrf_dbc);
					mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $mrf_dbc);
					mysql_query("SET CHARACTER SET utf8");
					mysql_query("SET NAMES utf8");

					require_once('udcform.php');

					$output = "";
					$udcform = new UDCForm();

					if (isset($_SESSION['records']))
					{
						$records = $_SESSION['records'];
						if (count($records) > 0)
						{
							foreach($records as $record)
							{
								if ($record->status != "OK")
									continue;

								$tag = trim($record->tag);

								$udcform->notation = $tag;
								$udcform->language_id = 1;

								echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>\n";
								$udcform->queryformvars($mrf_dbc);
								if ($udcform->id > 0)
								{
									$status = "Loaded";
									$udcform->GetInsertSQL($sqlarray, $errors, $dbc);
									if (count($errors) > 0)
									{
										$status = "Errors: " . implode("; ", $sqlarray);
									}

									$output .= "<tr><td>$udcform->mfn</td><td>$tag</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>$status</td></tr>\n";
								}
								else
								{
									$output .= "<tr><td>&nbsp;</td><td>$tag</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>Load failed</td></tr>\n";
								}
							}
						}
					}

					mysql_close($mrf_dbc);
					mysql_close($dbc);
				}

				#echo $output;

			?>

		<?php
			if (count($sqlarray) > 0)
			{
				echo "<div>\n";
				echo "<textarea style=\"width: 643px; height: 300px; border: 1px solid #afafaf\" name=\"sql\">" . implode(";\n", $sqlarray) . "</textarea>\n";
				echo "</div>\n";
			}
			else
			{
				if (isset($_POST['Copy']))
				{
					echo "<div>\n";
					echo "No SQL generated";
					echo "</div>\n";
				}
			}
		?>

		<div style="width: 645px; text-align: right;"><input type="submit" name="Copy" value="Do the Copy"/></div>

	</form>
</div>
</div>
</div>
</body>
</html>
