<?php header("text/html; charset=UTF-8");
    session_start();
    # Reset navigation trail 
    $_SESSION['navpath'] = '';
    $_SESSION['navpos'] = 0;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC Summary</title>
<link rel="stylesheet" href="../reset.css" type="text/css" />
<link rel="stylesheet" href="../udc1000.css" type="text/css" />
<link rel="StyleSheet" href="dtree.css" type="text/css" />
<script type="text/javascript" src="dtree.js"></script>
<script type="text/javascript" src="udcdisplay_7.js"></script>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

</head>

<?php
    # Session variable 'preserverecord' records the notation of the last record selected in the tree
    # for display in the right pane. If the user then changes language, the whole tree is redisplayed
    # in the new language and an AJAX call is made immediately to reload the right pane, showing the
    # preserverecord in the new language also
    
    if (!isset($_GET['pr']))
    {
		$_SESSION['preserverecord'] = "";
	}
    
?>

<body>
	<div id="centercontainer" class="debugbkg">
		<div id="titlebox" class="debugbkg">
            <div id="languagebox">
                <?php
                    # Set defaults and determine the user's choice of language 
                    echo "<select id=\"selectedlang\" style=\"vertical-align: middle;\" onchange=\"location = this.options[this.selectedIndex].value;\">\n";
                    
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

     				if(isset($_GET['lang']))
    				{
    					$langcode = $_GET['lang'];
    				}

                    $langstring = $page;
                    if ($tag != "")
                    {
                        $langstring .= "?tag=" . htmlentities($tag) . "&lang=";
                    }
                    else if ($id != "")
                    {
                        $langstring .= "?id=" . htmlentities($id) . "&lang=";
                    }
                    else
                    {
                        $langstring .= "?lang=";
                    }

                    # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                    # Connect to the database
                    # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                   
					require_once("DBConnectInfo.php");

					$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
					@mysql_select_db (DBDATABASE);
		            @mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

		            $languages = array();

					# Get the language combo values for the top menu
					$sql = "select language_id, code, description, native from language";
					$res = mysql_query($sql, $dbc);
			        if ($res)
			        {
						while($row = @mysql_fetch_array($res, MYSQL_NUM))
						{
							$option = $row[3] . ":" . $row[2] . ":" . $row[0] . ":" . $row[1];
							array_push($languages, $option);
						}
			    		@mysql_free_result($res);
			        }

					asort($languages, SORT_STRING);

                    echo "<div id=\"rightbox\">\n";
                    echo "<div id=\"languagecount\">" . count($languages) . " languages</div>\n";
                    echo "<div id=\"languagebox\">\n";
                    echo "<select id=\"selectedlang\" style=\"vertical-align: middle;\" onchange=\"location = this.options[this.selectedIndex].value;\">\n";

					foreach($languages as $option)
					{
						$row = explode(":", $option);
						$option = "<option value=\"" . $langstring . $row[3] . "&pr=Y\"";
						if ($langcode==$row[3] || $langcode==$row[2])
						{
							$option .= " selected";
							$lang = $row[2];
							$langcode = $row[3];
						}
						$option .= ">" . $row[0] . " (" . $row[1] .  ") [" . $row[3] . "]</option>\n";
						echo $option;
					}
                 ?>
                </select>
            </div>
        </div>
        
        <!-- Left hand menu -->
		<div id="menubox" class="debugbkg">
            <?php
                    echo "<ul class=\"menu";
                    if ($lang != 27)
                    {
                        echo " boldmenu";
                    }
                    echo "\">\n";
                      
    				include_once("specialchars.php");
                    include_once("getdisplaynotation.php");
    
    				define("MAX_CM_LENGTH", 50);
    
                    // Get the selected language interface display values
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
                    
                    $sql =  "select translations, mappings, exports, abc_index, guide, about, top, signs, auxiliaries, expandall, collapseall, rootclasses, click, loading" .
                            " from interface_fields where language_id = " . $lang;
    
    				$res = @mysql_query($sql, $dbc);
    				if ($res)
    				{
    					if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
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
    					@mysql_free_result($res);
    				}
                       
                    # Display class division links on the top left menu 
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
                ?>
            
            </ul>
            
            <?php
                echo "<input type=\"hidden\" id=\"if_loading\" name=\"if_loading\" value=\"" . $if_loading . "\"/>\n";
                echo "<input type=\"hidden\" id=\"if_lang\" name=\"if_lang\" value=\"" . $lang . "\"/>\n";
            ?>
            
            <?php
                echo "<ul class=\"rightmenu";
                if ($lang != 27)
                {
                    echo " boldmenu";
                }
                echo "\">\n";                # Display external links on the top right menu
                echo "<li><a href=\"../translation.htm\">" . $if_translations . "</a></li>\n";
                echo "<li><a href=\"#\" title=\"Under Development\">" . $if_mappings . "</a></li>\n";
                echo "<li><a href=\"#\" title=\"Under Development\">" . $if_exports . "</a></li>\n";
                echo "<li><a href=\"#\" title=\"Under Development\">" . $if_abc_index . "</a></li>\n";
                echo "<li><a href=\"#\" title=\"Under Development\">" . $if_guide . "</a></li>\n";
                echo "<li><a href=\"../about.htm\" title=\"About the UDC Summary\">" . $if_about . "</a></li>\n";
               ?>
            </ul>
        </div>

		<div id="classmarkbox" class="debugbkg">
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
            };
        
            # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            # Function:     GetFetchRecordSQL
            # Description:  Returns SQL for retrieving classes relevant to 
            #               the current class selection
            # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        
            function GetFetchRecordSQL(&$dbc, $topid, $toptag, &$toplevelfetch, $lang)
            {
            	if ($topid != "" || $toptag != "")
            	{
            		// Retrieve specific (requested) classmarks details
            		$sql = 	"select c.hierarchy_level, h.hierarchy_code, c.classmark_tag, f.description, c.broader_category, c.classmark_id, f.language_id " .
                            " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id join language_fields f " .
            				" on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1, " . $lang . ") where ";
            
                    if ($toptag != "")
                    {
                        $sql .= "c.classmark_tag = '" . $toptag . "' ";
                    }
                    else
                    {
                        $sql .= "c.classmark_id = " . $topid . " ";
                    }
                    $sql .= "order by f.language_id";
            
            		$res = mysql_query($sql, $dbc);
            		if ($res)
            		{
            			while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
            			{
            				$hierarchy_level = $row[0];
            				$hierarchy_code = $row[1];
            				$rootclassmark_tag = trim($row[2]);
            				$rootdescription = $row[3];
            				$broader_category = $row[4];
                            $rootclassmark_id = $row[5];
                            $rootlanguage_id = $row[6];
            
                            if ($toptag != "")
                            {
                                $topid = $row[5];
                            }
            			}
            			@mysql_free_result($res);
            		}
            
            		# Now fetch all the class's narrower classmarks
            		$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, f.description, f.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type, f.language_id " .
                            " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
                            " join language_fields f" .
            				" on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1," . $lang . ") " .
            				" where h.hierarchy_code like '" . $hierarchy_code. "%' and deleted = 'N' " .
            				" order by h.hierarchy_code, f.language_id";
            	}
            	else
            	{
            	    $toplevelfetch = true;
            		# A specific classmark was not requested, so retrieve all the root level classmarks
            		$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, l.description, l.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type, l.language_id " .
                            " from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id and c.hierarchy_level in (0,1) " .
                            " join language_fields l " .
                            " on c.classmark_id = l.classmark_id and l.field_id = 1 and l.language_id in (1, " . $lang . ") " .
                            " order by h.hierarchy_code, l.language_id";
            	}
                
                return $sql;
            }
            
            function FetchTopLevelRecords(&$res, $toplevelfetch, &$firstrecord, &$treerecords, $topclassid)
            {
                # Fill a list of classmarks retrieved 
        		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
        		{
        			// Ignore the initial record - we already have it
        			if ($toplevelfetch == false && $firstrecord)
        			{
                        $topclassid = $row[0];
        				$firstrecord = false;
        				continue;
        			}
        
                    if ($row[0] == $topclassid)
                        continue;
        
                    if (array_key_exists($row[0], $treerecords))
                    {
                        $record = $treerecords[$row[0]];
                    }
                    else
                    {
                        $record = new TreeRecord();
                    }
        
                    // Construct the structure
        			$record->id = $row[0];
        			$record->broader = $row[1];
        			$record->tag = trim($row[2]);
        			$record->description = $row[3];
                    $record->title = $record->description;
                    $record->field_id = $row[4];
                    $record->hierarchy_code = $row[5];
                    $record->level = $row[6];
                    $record->headingtype = $row[7];
                    $record->language = $row[8];
        
                    $treerecords[$record->id] = $record;
                }
        
                @mysql_free_result($res);        
            }
        
            # Initialisation
        	$topid = "";
            $toptag = "";
            $toplevelfetch = false;
        
        	if(isset($_GET["id"]))
        	{
        		$topid = $_GET["id"];
        	}
        
        	if(isset($_GET["tag"]))
        	{
        		$toptag = $_GET["tag"];
        	}

        	$hierarchy_level = 0;
        	$hierarchy_code = "";
        	$rootclassmark_tag = "";
        	$rootdescription = "";
        	$broader_category = 0;
            $rootclassmark_id = 0;
        
        	$nodetoclassmarks = array();   # map of treenode to classmark
        	$records = array();            # classmark records
        
            # If the user requested a specific classmark, fetch it and all its children
            # otherwise fetch all root classmarks (hierarchy level 0 or 1)
            
            $sql = GetFetchRecordSQL($dbc, $topid, $toptag, $toplevelfetch, $lang);
            
            # echo $sql . "<br>\n";
            
        	$nextnode = 1;
        	$firstrecord = true;
            
            # Construct the tree
        	$res = @mysql_query($sql, $dbc);
        	if ($res)
        	{
                $treerecords = array();
                $topclassid = 0;
        
                FetchTopLevelRecords($res, $toplevelfetch, $firstrecord, $treerecords);
        
                foreach($treerecords as $record)
                {
                    $is_href = false;
                    $recordline = "";
        
        			// First of all add this record into the classmark id/ node id map
        			$nodetoclassmarks[$record->id] = $nextnode;
        
        			// See if we have a node if for the broader category. If we do, link them in the tree
        			$parentnode = 0;
        			if (array_key_exists($record->broader, $nodetoclassmarks))
        			{
        				$parentnode = $nodetoclassmarks[$record->broader];
        			}
        
        			$recordline .= "d.add(" . $nextnode++ . "," . $parentnode .",'";
                    $recordline .= $record->tag . "','";
                    $dn = GetDisplayNotation($record->tag, false);
        
                    $recordline .= $dn;
        			$recordline .= "</span>&nbsp;&nbsp;";
        
                    # If we couldn't find a record caption in the target language, display it in brown and show the 
                    # English version
                    if ($record->language != $lang)
                    {
                        $recordline .= "<span style=\"color: #7b4b0e\">" . addslashes($record->description) . "</span>";
                    }
                    else
                    {
                        $recordline .= addslashes($record->description);
                    }
        
                    $recordline .= "','";
        
                    if ($record->headingtype == 1 || $record->headingtype == 2 || $record->headingtype == 8)
                    {
                        $recordline .= $record->tag . "'";
                        $is_href = true;
                    }
                    else if ($record->headingtype == 13 && $record->tag != "--")
                    {
                        # For level 0 classmarks, don't show the dummy notation (--, ---, and ----)
                        if ($rootdescription == "")
                        {
                            $recordline .= "'";
                        }
                        else
                        {
                            $recordline .= $record->tag . "'";
                            $is_href = true;
                        }
                    }
                    else
                    {
        				if ($topid == "" || $record->tag == "--")
        				{
        					$recordline .= "index.php?id=" . $record->id . "&lang=" . $langcode . "'";
        				}
        				else
        				{
        					$recordline .= $record->tag . "'";
                            $is_href = true;
        				}
                    }
        
        			$recordline .= ",'" . addslashes($record->title) . "','','','',";
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
                }
        
                # If any records were retrieved, display the tree         
        		if (count($records) > 0)
        		{
                    echo "<div id=\"openclosemenu\"><a href=\"javascript: d.openAll();\">&nbsp;" . $if_expandall . "</a> | <a href=\"javascript: d.closeAll();\">" . $if_collapseall . "</a></div>\n";
                    echo "<div id=\"classtree\">\n";
        			echo "<script type=\"text/javascript\">\n";
        			echo "<!--\n";
        			echo "d = new dTree('d');\n";
        
                    if (!empty($topid))
                    {
                        echo "d.config.hrefIsClick = true;\n";
                    }
        
                    $display_tag = "";
                    $rootclass = false;
                    if ($rootdescription == "")
        			{
                        $rootclass = true;
                        $rootdescription = $if_top;
        				$rootclassmark_tag = "";
        			}
        			else
        			{
        			     # Display auxiliary parts of the classmark notation in red 
                        include_once("checkauxtag.php");
                        $rootclassmark_tag = trim($rootclassmark_tag);
                        $display_tag = CheckAuxTag($rootclassmark_tag);
        			}
        
        			echo "d.add(0,-1,'" . $rootclassmark_tag . "','<span class=\"nodetag\">" . $display_tag . "</span>";
                    if (strlen($display_tag) > 0)
                    {
                        echo "&nbsp;&nbsp;";
                    }
        
                    echo $rootdescription . "','" . $rootclassmark_tag . "'";
        
                    if ($rootclassmark_id > 0)
                    {
                        echo ",'','','','',false";
                    }
        
                    if (!$rootclass)
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
            echo "<div class=\"fronthint\">" . $if_click . "</div>";
        ?>
        
        </div>
        <div id="footer">
            <div class="footersectionleft">
                This UDC Summary (UDCS) provides a selection of around 2,000 classes from the whole scheme which comprises more than 68,000 entries.
                Please send questions and suggestions to <a href="mailto:udcs@udcc.org?subject=UDC Summary Enquiry">udcs@udcc.org</a>
            </div>
            <div class="footermiddle">
                <a href="http://www.udcc.org"><img src="../images/udclogowhite.png" border="0"></a>
            </div>
			<div class="footerright">
				The data provided in this Summary is released under the <a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">Creative Commons Attribution Share Alike 3.0 license</a>
                <a href="../about.htm">[more]</a>
			</div>
            <div class="footercc">
                <a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank"><img src="../images/cclogo.jpg" style="margin-top: 7px;"></a>
			<div>
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
        echo "	openrecord('" . $_SESSION['preserverecord'] . "', " . $_SESSION['navpos'] . ", false); return false;\n";
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
