<?php
    if (!isset($_SESSION))
    {
        session_start();
    }
?>

<div id="classmarkbox" class="debugbkg">

<?php
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
        echo $error . "\n";
        exit(1);
    }

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

    $lang = (isset($_SESSION['lang']) ? $_SESSION['lang'] : 1);

	if ($topid != "" || $toptag != "")
	{
		# Retrieve this classmark and all its subclasses
		$sql = 	"select c.hierarchy_level, h.hierarchy_code, c.classmark_tag, f.description, c.broader_category, c.classmark_id, f.language_id " .
			" from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id join language_fields f " .
			" on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1, " . $lang . ") where ";

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

		foreach ($dbc->query($sql) as $row)
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

		# Fetch all subclasses
		$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, f.description, f.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type, f.language_id " .
			" from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id " .
			" join language_fields f" .
			" on f.classmark_id = c.classmark_id and f.field_id = 1 and f.language_id in (1," . $lang . ") " .
			" where h.hierarchy_code like '" . $hierarchy_code. "%' and deleted = 'N' " . // and c.hierarchy_level > " . $hierarchy_level . " and c.hierarchy_level < " . ($hierarchy_level+2) .
			" order by h.hierarchy_code, f.language_id";
	}
	else
	{
		$toplevelfetch = true;
		# Retrieve all the root level classmarks
		$sql = 	"select c.classmark_id, c.broader_category, c.classmark_tag, l.description, l.field_id, h.hierarchy_code, c.hierarchy_level, c.heading_type, l.language_id " .
			" from classmarks c join classmark_hierarchy h on h.classmark_id = c.classmark_id and c.hierarchy_level in (0,1) " .
			" join language_fields l " .
			" on c.classmark_id = l.classmark_id and l.field_id = 1 and l.language_id in (1, " . $lang . ") " .
			" order by h.hierarchy_code, l.language_id";
	}

	$nodetoclassmarks = array();
	$records = array();
	$inextnode = 1;
	$bfirst = true;

    $treerecords = array();
    $topclassid = 0;

    foreach ($dbc->query($sql) as $row)
	{
        if ($toplevelfetch == false && $bfirst)
        {
            #Ignore the initial record - we already have it
            $topclassid = $row[0];
            $bfirst = false;
            continue;
        }

        if ($row[0] == $topclassid)
            {continue;}

        $record_id = $row[0];
        if (isset($treerecords[$record_id]))
        {
            $record = $treerecords[$record_id];
        }
        else
        {
            $record = new TreeRecord();
        }

        # Construct the structure
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

    foreach($treerecords as $record)
    {
        $is_href = false;
        $recordline = "";

        # First of all add this record into the classmark id/ node id map
        $nodetoclassmarks[$record->id] = $inextnode;

        # See if we have a node if for the broader category
        $parentnode = 0;
        if (isset($nodetoclassmarks[$record->broader]))
        {
            $parentnode = $nodetoclassmarks[$record->broader];
        }

        $recordline .= "d.add(" . $inextnode++ . "," . $parentnode .",'";
        $recordline .= $record->tag . "','";
        $dn = GetDisplayNotation($record->tag, false);
        $recordline .= $dn;
        $recordline .= "</span>&nbsp;&nbsp;";

        if ($record->language != $lang)
        {
            $recordline .= "<span style=\"color: #7b4b0e;";
            if (isset($_SESSION['rtl']) && $_SESSION['rtl'] == true)
            {
                $recordline .= " unicode-bidi: bidi-override; direction: ltr; text-align: right";
            }
            $recordline .= "\">" . addslashes($record->description) . "</span>";
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
                $recordline .= "index.php?id=" . $record->id . "&lang=" . (isset($_SESSION['langcode']) ? $_SESSION['langcode'] : 'en') . "'";
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
        echo "<div id=\"openclosemenu\"><a href=\"javascript: d.openAll();\">&nbsp;" .
            (isset($_SESSION['expandall']) ? $_SESSION['expandall'] : '') .
            "</a> | <a href=\"javascript: d.closeAll();\">" .
	        (isset($_SESSION['collapseall']) ? $_SESSION['collapseall'] : '') .
            "</a></div>\n";

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
            $rootdescription = (isset($_SESSION['top']) ? $_SESSION['top'] : '');
            $rootclassmark_tag = "";
        }
        else
        {
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
?>
</div>