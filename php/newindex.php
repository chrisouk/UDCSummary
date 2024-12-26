<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC Summary</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--meta http-equiv="Content-Type" content="text/html; charset=utf-8"/-->
<link rel="stylesheet" href="../reset.css" type="text/css" />
<link rel="stylesheet" href="../udc1000.css" type="text/css" />
<link rel="StyleSheet" href="dtree.css" type="text/css" />
<script type="text/javascript" src="dtree.js"></script>
<script type="text/javascript" src="udcdisplay.js"></script>

</head>

<body>
	<div id="centercontainer" class="debugbkg">
		<div id="titlebox" class="debugbkg">
			<div id="languagebox">
				<select style="vertical-align: middle;">
				<?php
					$lang = 1;

					if(isset($_GET["lang"]))
					{
						$lang = $_GET["lang"];
					}

					echo "<option lang=\"1\""; if ($lang == 1) echo " selected"; echo ">English</option>\n";
					echo "<option lang=\"2\""; if ($lang == 2) echo " selected"; echo ">Dutch</option>\n";
				?>
				</select>
			</div>
		</div>
		<div id="menubox" class="debugbkg">
            <ul class="menu">
            <?php
                echo "<li><a href=\"newindex.php?lang=" . $lang . "\">TOP</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=--&lang=" . $lang . "\">SIGNS</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=---&lang=" . $lang . "\">AUXILIARIES</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=0&lang=" . $lang . "\">0</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=1&lang=" . $lang . "\">1</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=2&lang=" . $lang . "\">2</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=3&lang=" . $lang . "\">3</a></li>\n";
                echo "<li><a  class=\"vacant\" href=\"#\">4</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=5&lang=" . $lang . "\">5</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=6&lang=" . $lang . "\">6</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=7&lang=" . $lang . "\">7</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=8&lang=" . $lang . "\">8</a></li>\n";
                echo "<li><a href=\"newindex.php?tag=9&lang=" . $lang . "\">9</a></li>\n";
            ?>
            </ul>
            <ul class="rightmenu">
                <li><a href="../translation.htm" title="Translation">TRANSLATIONS</a></li>
                <li><a href="#" title="Under Development">MAPPINGS</a></li>
                <li><a href="#" title="Under Development">EXPORTS</a></li>
                <li><a href="#" title="Under Development">ABC INDEX</a></li>
                <li><a href="#" title="Under Development">GUIDE</a></li>
                <li><a href="../about.htm" title="About the UDC Summary">ABOUT</a></li>
            </ul>
        </div>

		<div id="classmarkbox" class="debugbkg">

			<?php

				require_once("DBConnectInfo.php");
				include_once("specialchars.php");
                include_once("getdisplaynotation.php");

				define("MAX_CM_LENGTH", 50);

				$topid = "";
                $toptag = "";
                $toplevelfetch = false;
                $lang = 1;

                if(isset($_GET["id"]))
				{
					$topid = $_GET["id"];
				}

				if(isset($_GET["tag"]))
				{
					$toptag = $_GET["tag"];
				}

				if(isset($_GET["lang"]))
				{
					$lang = $_GET["lang"];
				}

				$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
				@mysql_select_db (DBDATABASE);

                //@mysql_query("SET character_set_results = 'iso-8859-1', character_set_client = 'iso-8859-1', character_set_connection = 'iso-8859-1', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
                //@mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

				$hierarchy_level = 0;
				$hierarchy_code = "";
				$rootclassmark_tag = "";
				$rootdescription = "";
				$broader_category = 0;
                $rootclassmark_id = 0;

				if ($topid != "" || $toptag != "")
				{
					// Retrieve this classmark and all its subclasses
					$sql = 	"select c.hierarchy_level, h.hierarchy_code, c.classmark_tag, f.description, c.broader_category, c.classmark_id " .
                            " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id left outer join language_fields f " .
							" on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id = " . $lang . " where ";

                    if ($toptag != "")
                    {
                        $sql .= "c.classmark_tag = '" . $toptag . "';";
                    }
                    else
                    {
                        $sql .= "c.classmark_id = " . $topid . ";";
                    }

                   	//echo $sql . "<br>\n";

					$res = @mysql_query($sql, $dbc);
					if ($res)
					{
						if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
						{
							$hierarchy_level = $row[0];
							$hierarchy_code = $row[1];
							$rootclassmark_tag = trim($row[2]);
							$rootdescription = $row[3];
							$broader_category = $row[4];
                            $rootclassmark_id = $row[5];

                            if ($toptag != "")
                            {
                                $topid = $row[5];
                            }
						}
						@mysql_free_result($res);
					}

					// Now fetch all subclasses
					$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, f.description, f.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type " .
                            " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
                            " left outer join language_fields f" .
							" on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id = " . $lang . " " .
							" where h.hierarchy_code like '" . $hierarchy_code. "%' " . // and c.hierarchy_level > " . $hierarchy_level . " and c.hierarchy_level < " . ($hierarchy_level+2) .
							" order by h.hierarchy_code;";
					//echo $sql . "<br>";
				}
				else
				{
				    $toplevelfetch = true;
					// Retrieve all the root level classmarks
					$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, l.description, l.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type " .
                            " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id and c.hierarchy_level in (0,1) " .
                            " left outer join language_fields l " .
                            " on c.classmark_id = l.classmark_id and l.field_id = 1 and l.language_id = " . $lang .
                            " order by h.hierarchy_code;";
                    //echo $sql . "<br>\n";
				}

				$nodetoclassmarks = array();
				$records = array();
				$inextnode = 1;
				$bfirst = true;

				$res = @mysql_query($sql, $dbc);
				if ($res)
				{


					//echo "<table width=\"100%\">\n";
					//if ($classmark_tag != "")
					//{
					//	echo "<tr>";
					//	echo "<td style=\"padding-right: 10px; padding-top: 2px; padding-bottom: 2px; padding-left: 2px; font-weight: bold;\">" . $classmark_tag . "</td>";
					//	echo "<td style=\"font-weight: bold;\">" . $description . "</td>";
					//	echo "</tr>\n";
					//	echo "<tr>";
					//	echo "<td colspan=\"2\"\" style=\"height: 1px; background-color: white;\"></td>";
					//	echo "</tr>\n";
					//}
                    $clicklink = false;

					while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
					{
                        $is_href = false;
    				    //echo $row . "<br>\n";
						// Ignore the initial record - we already have it

                        $recordline = "";

						if ($toplevelfetch == false && $bfirst)
						{
							$bfirst = false;
							continue;
						}


                        // Construct the structure
						$id = $row[0];
						$broader = $row[1];
						$tag = trim($row[2]);
						$description = utf8_decode($row[3]);
                        $title = $description;
                        $level = $row[6];
                        $headingtype = $row[7];


                        /*
                        if ($broader == 0)
                        {
                            if ($clicklink == true)
                            {
                                $recordline .= "d.config.hrefIsClick = false;\n";
                            }
                            $clicklink = false;
                        }
                        else
                        {
                            if ($clicklink == false)
                            {
                                $recordline .= "d.config.hrefIsClick = true;\n";
                            }
                            $clicklink = true;
                        }
                        */
						// First of all add this record into the classmark id/ node id map
						$nodetoclassmarks[$id] = $inextnode;

						// See if we have a node if for the broader category
						$parentnode = 0;
						if (array_key_exists($broader, $nodetoclassmarks))
						{
							$parentnode = $nodetoclassmarks[$broader];
						}

						$recordline .= "d.add(" . $inextnode++ . "," . $parentnode .",'";
                        $recordline .= $row[2] . "','";
                        $dn = GetDisplayNotation($row[2], false);
                        //echo "DN=" . $dn . "<br>\n";
                        $recordline .= $dn;
                        /*
						$aux = false;
						$notationarray = array();
						$delim = "";
						if (strpos($row[2], "-") > 0)
						{
							$notationarray = explode("-", $row[2], 2);
							$delim = "-";
							$aux = true;
						}
						else if (strpos($row[2], ".0") > 0)
						{
							$notationarray = explode(".0", $row[2], 2);
							$delim = ".0";
							$aux = true;
						}
						else if (strpos($row[2], "`") > 0)
						{
							$notationarray = explode("`", $row[2], 2);
							$delim = "`";
							$aux = true;
						}
						else
						{
							array_push($notationarray, $row[2]);
						}

						$recordline .= "<span class=\"nodetag\">";
						if ($aux)
						{
							if (count($notationarray) == 2)
							{
								$recordline .= addslashes($notationarray[0]);
								$recordline .= "<span class=\"auxiliary\">";
								$recordline .= addslashes($delim . $notationarray[1]);
                                $recordline .= "</span>";
							}
							else
							{
								$recordline .= addslashes($notationarray[0]);+
							}
						}
						else
						{
							$recordline .= addslashes($notationarray[0]);
						}
                        */

                        $iMaxDescriptionLen = 40-(level*3) - strlen($notation);
                        if (strlen($description) > $iMaxDescriptionLen)
                        {
                            $description = substr($description, 0, $iMaxDescriptionLen) . "...";
                        }

						$recordline .= "</span>&nbsp;&nbsp;";

                        $recordline .= addslashes($description) . "','";

                        if ($headingtype == 1 || $headingtype == 2 || $headingtype == 8)
                        {
                            $recordline .= $tag . "'";
                            $is_href = true;
                        }
                        else if ($headingtype == 13 && $tag != "--")
                        {
                            if ($rootdescription == "")
                            {
                                $recordline .= "'";
                            }
                            else
                            {
                                $recordline .= $tag . "'";
                                $is_href = true;
                            }
                        }
                        else
                        {
    						if ($topid == "" || $tag == "--")
    						{
    							$recordline .= "newindex.php?id=" . $id . "&lang=" . $lang . "'";
    						}
    						else
    						{
    							$recordline .= $tag . "'";
                                $is_href = true;
    						}
                        }

						$recordline .= ",'" . addslashes($title) . "','','','',";
                        if ($toplevelfetch)
                        {
                            $recordline .= "true";
                        }
                        else
                        {
                            $recordline .= "false";
                        }

                        if ($is_href)
                        {
                            $recordline .= ",true";
                        }
                        else
                        {
                            $recordline .= ",false";
                        }
                        $recordline .= ");\n";

						array_push($records, $recordline);

						//echo $row[2] . "<br>\n";
						//echo "d.add(" . $inextnode++ . ",0,'<span class=\"nodetag\">" . $row[2] . "</span>&nbsp;&nbsp;" . $row[3] . "','newindex.php?id=" . $row[0] . "&lang=" . $lang . "');\n";

						$field_type = $row[3];
						$caption = "";
						switch($field_type)
						{
							case "1":
								// Caption
								$caption = $row[2];

								if (strlen($caption) > MAX_CM_LENGTH)
								{
									$position = MAX_CM_LENGTH;
									$capchar = substr($caption, $position, 1);
									while($capchar != " " && $capchar != "\t" && $position > 0)
									{
										$position--;
										$capchar = substr($caption, $position, 1);
									}

									if ($position > 0)
									{
										$caption = substr($caption, 0, $position) . "...";
									}
									else
									{
										$caption = substr($caption, 0, MAX_CM_LENGTH) . "...";
									}
								}
								break;
							default:
								break;
						}

						//echo "<tr>";
						//echo "<td style=\"padding-right: 10px; padding-top: 2px; padding-bottom: 2px; padding-left: 2px;\"><a href=\"index.php?id=" . $row[0] . "\">" . $row[1] . "</a></td>";
						//echo "<td>" . $caption . "</td>";
						//echo "</tr>\n";
						//echo "<tr>";
						//echo "<td colspan=\"2\"\" style=\"height: 1px; background-color: white;\"></td>";
						//echo "</tr>\n";

					}

					@mysql_free_result($res);

					if (count($records) > 0)
					{
                        echo "<div id=\"openclosemenu\"><a href=\"javascript: d.openAll();\">&nbsp;expand all</a> | <a href=\"javascript: d.closeAll();\">collapse all</a></div>\n";
                        echo "<div id=\"classtree\">\n";
						echo "<script type=\"text/javascript\">\n";
						echo "<!--\n";
						echo "d = new dTree('d');\n";

                        if (!empty($topid))
                            echo "d.config.hrefIsClick = true;\n";

                        $display_tag = "";
						if ($rootdescription == "")
						{
							$rootdescription = "ROOT CLASSES";
							$rootclassmark_tag = "";
						}
						else
						{
                            include_once("checkauxtag.php");
                            $rootclassmark_tag = trim($rootclassmark_tag);
                            $display_tag = CheckAuxTag($rootclassmark_tag);
						}

                        if (strlen($rootdescription) > 45)
                        {
                            $rootdescription = substr($rootdescription, 0, 45) . "...";
                        }

						echo "d.add(0,-1,'" . $rootclassmark_tag . "','<span class=\"nodetag\">" . $display_tag . "</span>";
                        if (strlen($display_tag) > 0)
                        {
                            echo "&nbsp;&nbsp;";
                        }

                        echo strtoupper($rootdescription) . "','" . $rootclassmark_tag . "'";

                        if ($rootclassmark_id > 0)
                        {
                            echo ",'','','','',false";
                        }

                        if ($rootdescription != "ROOT CLASSES")
                        {
                            echo ",true";
                        }
                        else
                        {
                            echo ",false";
                        }

                        echo ");\n";
						foreach($records as $record)
						{
							echo $record;
						}

						echo "d.config.useSelection = false;\n";
						echo "d.config.inOrder = true;\n";
						echo "d.config.useIcons = false;\n";
						echo "document.write(d);\n";
						echo "//-->\n";
						echo "</script>\n";
                        echo "</div>\n";
                    }
				}

    		echo "</div>\n";
    		echo "<div id=\"recordbox\" class=\"debugbkg\">";
            echo "<div class=\"fronthint\">click on a class\nto the left to\ndisplay records</div>";
            echo "</div>"
			?>
        <div id="footer">
            <div class="footersectionleft">
                This UDC Summary (UDCS) provides a selection of around 2,000 classes from the whole scheme which comprises more than 68,000 entries.
                Please send questions and suggestions to <a href="mailto:udcs@udcc.org?subject=UDC Summary Enquiry">udcs@udcc.org</a>
            </div>
            <div class="footermiddle">
                <a href="http://www.udcc.org"><img src="../images/udclogowhite.png" border="0"></a>
            </div>
			<div class="footerright">
				The data provided in this Summary is released under the <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">Creative Commons Attribution Non-Commercial Share Alike 3.0 license</a> <a href="../about.htm">[more]</a>
			</div>
            <div class="footercc">
                <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank"><img src="../images/cclogo.jpg" style="margin-top: 7px;"></a>
			<div>
        </div>
</div>
</body>
</html>
