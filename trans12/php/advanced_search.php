<?php
    session_start();
    include_once("checksession.php");
    checksession();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC MRF Translator</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

<link rel="stylesheet" href="../udcedit.css" type="text/css" />
<link rel="shortcut icon" href="../img/udc.ico" type="image/x-icon" />

<script language="javascript" src="udcedit.js" type="text/javascript" ></script>
<script language="javascript" src="php.default.js" type="text/javascript" ></script>
</head>


<body>
    <div id="pagecontainer">
        <div id="titleimagecontainer_thin">&nbsp;</div>
        <div class="searchcontainer">
            <div class="searchbox backtoeditor"><a href="edittag.php">Back to Editor</a></div>
            <div class="searchbox searchcontrolbox">
            	<form id="udcform" name="udcform" method="post" action="advanced_search.php" accept-charset="UTF-8">
        			<div class="editleftcolumn">
        				<div class="editlabel fixedwidthlabel rightmargin5">Notation Search</div>
        				<div class="editvalue searchpanel">
        					<input type="text" id="notationsearchterm" name="notationsearchterm" class="textinput" style="width: 293px;"
                            value="<?php if (isset($_POST['notationsearchterm'])) echo $_POST['notationsearchterm']; else if (isset($_SESSION['as_notation'])) echo $_SESSION['as_notation']; else echo ""; ?>"/>

        				</div>
        				<div style="float: left; line-height: 32px; vertical-align: middle;">
        					&nbsp;
        					<input class="inputbutton" type="submit" id="SubmitSearch" name="SubmitSearch" value="Search" />
        				</div>
        			</div>
        			<div class="editleftcolumn">
        				<div class="editlabel fixedwidthlabel rightmargin5">Text Search</div>
        				<div class="editvalue searchpanel">
        					<div class="searchrow"><input type="text" id="captionsearchterm" name="captionsearchterm" class="textinput" style="width: 293px;"
                            value="<?php if (isset($_POST['captionsearchterm'])) echo $_POST['captionsearchterm']; else if (isset($_SESSION['as_text'])) echo $_SESSION['as_text']; else echo ""; ?>"/></div>
                        </div>
        				<div class="editlabel fixedwidthlabel rightmargin5">Fields</div>
        				<div class="editvalue searchpanel">
                            <?php $fieldarray = array(); if (isset($_POST['chk_text'])) { $_SESSION['as_chk_text'] = $_POST['chk_text']; } else { if (isset($_POST['SubmitSearch'])) unset($_SESSION['as_chk_text']); }
                                  if (isset($_SESSION['as_chk_text'])) { foreach($_SESSION['as_chk_text'] as $chk_value) $fieldarray[$chk_value] = $chk_value; } ?>
                            <div class="searchrow topsearchrow">
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="1" <?php if (isset($fieldarray["1"])) echo " checked "; ?>/> Caption</div>
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="4" <?php if (isset($fieldarray["4"])) echo " checked "; ?>/> Including</div>
                            </div>
                            <div class="searchrow">
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="5" <?php if (isset($fieldarray["5"])) echo " checked "; ?>/> Scope Note</div>
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="6" <?php if (isset($fieldarray["6"])) echo " checked "; ?>/> Application Note</div>
                            </div>
                            <div class="searchrow">
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="10" <?php if (isset($fieldarray["10"])) echo " checked "; ?>/> Information Note</div>
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="2" <?php if (isset($fieldarray["2"])) echo " checked "; ?>/> Examples</div>
                            </div>
                            <div class="searchrow">
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="125" <?php if (isset($fieldarray["125"])) echo " checked "; ?>/> References</div>
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="955" <?php if (isset($fieldarray["955"])) echo " checked "; ?>/> Editorial Note</div>
                            </div>
        				</div>
                    </div>
    				<div class="editlabel fixedwidthlabel rightmargin5">Language</div>
    				<div class="editvalue searchpanel">
    					<div class="searchrow">
                            <?php
                                $list_colors = array( 'Dutch' => '#b27340');

                                $language = 0;
                                if (isset($_SESSION['as_language']))
                                {
                                    $language = $_SESSION['as_language'];
                                }
                                if (isset($_POST['language']))
                                {
                                    $language = $_POST['language'];
                                    $_SESSION['as_language'] = $language;
                                }

                                $language_names = array();

                            	require_once("DBConnectInfo.php");
                            	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
                            	mysql_select_db (DBDATABASE);

                                echo "<select name=\"language\" style=\"width: 299px; font-family: Tahoma, Helvetica, sans-serif; font-size: 13px;\">\n";
                                $sql = "select language_id, description from language order by language_id";
                                echo "<option value=\"0\"";
                                if ($language == 0) echo " selected";
                                echo ">All\n";

                                $res = @mysql_query($sql, $dbc);
                                if ($res)
                                {
                                    while (($row = @mysql_fetch_array($res, MYSQL_NUM)))
                                    {
                                        $language_names[$row[0]] = $row[1];
                                        $color = "black";
                                        if (isset($list_colors[$row[1]]))
                                        {
                                        	$color = $list_colors[$row[1]];
                                        }
                                        echo "<option value=\"" . $row[0] . "\" style=\"color: " . $color . ";\"";
                                        if ($language == $row[0]) echo " selected";
                                        echo ">" . $row[1] . "\n";
                                    }

                                    @mysql_free_result($res);
                                }
                                echo "</select>\n";
                             ?>
                        </div>
    				</div>
                </form>
            </div>
        </div>

        <br/>

        <?php

        	require_once("DBConnectInfo.php");
        	include_once("specialchars.php");

            class SearchResult
            {
                var $id = 0;
                var $notation = "";
                var $field_id = 0;
                var $description = "";
                var $lang_id = 0;
            };

            //var_dump($_POST['chk_text']);

        	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        	mysql_select_db (DBDATABASE);
            mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
            mysql_query("SET CHARACTER SET utf8");
            mysql_query("SET NAMES utf8");

            $resultsarray = array();
            $captionresultsarray = array();
            $restrictionarray = array();

            if (isset($_POST['SubmitSearch']))
            {
                if (isset($_POST['notationsearchterm']) && strlen($_POST['notationsearchterm']) > 0)
                {
                    $_SESSION['as_notation'] = $_POST['notationsearchterm'];
                    $sql =  "select c.classmark_id, c.classmark_tag, f.field_id, f.description, c.classmark_enc_tag, f.language_id from classmarks c join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 ";
                    if ($language > 0)
                    {
                        $sql .= "and f.language_id = " . $language;
                    }
                    $sql .= " where c.classmark_tag like '" . @mysql_real_escape_string($_POST['notationsearchterm']) . "%' and c.active = 'Y'";

                    //echo $sql;

                    $res = @mysql_query($sql, $dbc);
                    if ($res)
                    {
                        while (($row = @mysql_fetch_array($res, MYSQL_NUM)))
                        {
                            $result = new SearchResult();
                            $result->id = $row[0];
                            $result->notation = $row[1];
                            $result->field_id = $row[2];
                            $sortfield = ($row[2] == 2 ? 99 : $row[2]);
                            $result->description = $row[3];
                            $classmark_enc_tag = $row[4];
                            $result->lang_id = $row[5];

                            $resultsarray[$classmark_enc_tag . $sortfield . "#" . $result->lang_id] = $result;
                            $restrictionarray[$result->notation] = $result->notation;
                        }

                        @mysql_free_result($res);

                        $_SESSION['as_results'] = $resultsarray;
                    }
                    else
                    {
                        unset($_SESSION['as_results']);
                    }
                }
                else
                {
                  //  echo "No notation search<br>\n";
                }

                if (isset($_POST['captionsearchterm']) && strlen($_POST['captionsearchterm']) > 0)
                {
                    //echo "Caption search<br>\n";
                    $_SESSION['as_text'] = $_POST['captionsearchterm'];
                    if (strlen($_POST['captionsearchterm']) == 0)
                    {
                        $_POST['captionsearchterm'] = "%";
                    }
                    //echo "Caption search<br>\n";
                    $restrict_results = false;
                    if (count($resultsarray) > 0)
                    {
                        //echo "Results restriction is on<br>\n";
                        $restrict_results = true;
                    }

                    $sortarray = array();

                    if ($restrict_results == true)
                    {
                        $field_list = "1";
                    }
                    else
                    {
                        $field_list = "1,2,4,5,6";
                    }

                    $editorial_note = false;
                    $example_notation = false;
                    $references = false;

                    if (isset($_POST['chk_text']))
                    {
                        $field_list = "";

                        foreach($_POST['chk_text'] as $checkvalue)
                        {
                            switch($checkvalue)
                            {
                                case 2:
                                    $example_notation = true;
                                    # falls through deliberately
                                case 1:
                                case 4:
                                case 5:
                                case 6:
                                case 10:
                                    if ($field_list != "")
                                    {
                                        $field_list .= ",";
                                    }
                                    $field_list .= $checkvalue;
                                    break;
                                case 125:
                                    $references = true;
                                    break;
                                case 955:
                                    $editorial_note = true;
                                    break;
                                default:
                                break;
                            }
                        }
                    }

                    $sql = "";
                    if ($field_list != "")
                    {
                        $sql .= "(select c.classmark_id, c.classmark_tag, f.field_id, f.description, c.classmark_enc_tag, f.language_id from classmarks c join language_fields f on c.classmark_id = f.classmark_id " .
                                " and f.field_id in ( " . $field_list . ") ";

                        if ($language > 0)
                        {
                            $sql .= "and f.language_id = " . $language;
                        }

	                    $sql .= " join language_fields f2 on f2.classmark_id = f.classmark_id and f2.field_id = f.field_id and f2.seq_no = f.seq_no and f2.language_id = 1 ";
                        $sql .= " where f.description like '%" . @mysql_real_escape_string($_POST['captionsearchterm']) . "%' and c.active = 'Y')";
                    }

                    if ($editorial_note == true)
                    {
                        if ($sql != "")
                        {
                            $sql .= " UNION ALL ";
                        }
                        $sql .= "(select o.classmark_id, c.classmark_tag, o.revision_field, o.annotation, c.classmark_enc_tag, 1 from other_annotations o join classmarks c on o.classmark_id = c.classmark_id " .
                                " where o.revision_field = 955 and o.annotation like '%" . @mysql_real_escape_string($_POST['captionsearchterm']) . "%' and c.active = 'Y')";
                    }

                    if ($example_notation == true)
                    {
                        if ($sql != "")
                        {
                            $sql .= " UNION ALL ";
                        }
                        $sql .= "(select c.classmark_id, c.classmark_tag, 3, e.tag, c.classmark_enc_tag, f.language_id from classmarks c join language_fields f on c.classmark_id = f.classmark_id " .
                                " join example_classmarks e on e.classmark_id = c.classmark_id where f.language_id = 1 and e.seq_no = f.seq_no and e.tag like '%" . @mysql_real_escape_string($_POST['captionsearchterm']) . "%' and c.active = 'Y')";
                    }

                    if ($references == true)
                    {
                        if ($sql != "")
                        {
                            $sql .= " UNION ALL ";
                        }
                        $sql .= "(select c.classmark_id, c.classmark_tag, 126, concat(r.notation, ' - ', f.description), c.classmark_enc_tag, f.language_id from classmark_refs r " .
                                "join classmarks c on r.classmark_id = c.classmark_id " .
                                "join classmarks c2 on r.notation = c2.classmark_tag " .
                                "join language_fields f on c2.classmark_id = f.classmark_id and f.field_id = 1 ";
                        if ($language > 0)
                        {
                            $sql .= "and f.language_id = " . $language;
                        }
                        $sql .= " where f.description like '%" . @mysql_real_escape_string($_POST['captionsearchterm']) . "%' and c.active = 'Y' and c2.active = 'Y')";
//                        $sql .= "(select c.classmark_id, c2.classmark_tag, 125, f.description, c2.classmark_enc_tag from classmarks c join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 " .
//                                " join classmark_refs r on r.notation = c.classmark_tag join classmarks c2 on r.classmark_id = c2.classmark_id where f.description like '%";
//
//                        $sql .= @mysql_real_escape_string($_POST['captionsearchterm']) . "%' ";
//                        if ($language > 0)
//                        {
//                            $sql .= "and f.language_id = " . $language;
//                        }
//                        $sql .= " and c.active = 'Y' and c2.active = 'Y')";

                        $sql .= " UNION ALL ";

                        $sql .= "(select c.classmark_id, c.classmark_tag, 126, concat(r.notation, ' - ', f.description), c.classmark_enc_tag, f.language_id from classmark_refs r " .
                                "join classmarks c on r.classmark_id = c.classmark_id " .
                                "join classmarks c2 on r.notation = c2.classmark_tag " .
                                "join language_fields f on c2.classmark_id = f.classmark_id and f.field_id = 1 ";

                        if ($language > 0)
                        {
                            $sql .= "and f.language_id = " . $language;
                        }
                        $sql .= " where r.notation like '%" . @mysql_real_escape_string($_POST['captionsearchterm']) . "%' and c.active = 'Y' and c2.active = 'Y')";
                    }

                    #echo $sql;

                    $res = @mysql_query($sql, $dbc);
                    if ($res)
                    {
                        while (($row = @mysql_fetch_array($res, MYSQL_NUM)))
                        {
                            $result = new SearchResult();
                            $result->id = $row[0];
                            $result->notation = $row[1];
                            $result->field_id = $row[2];
                            $sortfield = ($row[2] == 2 ? 99 : $row[2]);
                            $result->description = $row[3];
                            $classmark_enc_tag = $row[4];
                            $result->lang_id = $row[5];

                            if ($restrict_results == true)
                            {
                                if (!isset($restrictionarray[$result->notation]))
                                {
                                    continue;
                                }
                            }

                            $captionresultsarray[$classmark_enc_tag . $sortfield . "#" . $result->lang_id] = $result;
                            //$sortarray[$notation] = $classmark_enc_tag;
                        }

                        @mysql_free_result($res);

                    }

                    if (count($captionresultsarray) > 0 || $restrict_results == true)
                    {
                        //echo "Showing caption results [" . count($captionresultsarray) . "] items<br>\n";
                        ksort($captionresultsarray, SORT_STRING);
                        $_SESSION['as_results'] = $captionresultsarray;
                    }
                    else if (count($resultsarray) > 0)
                    {
                        //echo "Showing results [" . count($resultsarray) . "] items<br>\n";
                        ksort($resultsarray, SORT_STRING);
                        $_SESSION['as_results'] = $resultsarray;
                    }
                    else
                    {
                        unset($_SESSION['as_results']);
                    }
                }
            }

            if (isset($_SESSION['as_results']))
            {
                $results_array = $_SESSION['as_results'];

                echo "<div style=\"width: 1080px; float: right; margin: 5px 5px; font-family: Tahoma, Helvetica, sans-serif; font-size: 13px; text-align: right; color: #980098;\">" . count($results_array) . " results</div>\n";
                echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" bgcolor=\"#efefef\">\n";
                echo "<tr><td width=\"10%\"><strong>Notation</strong></td><td width=\"12%\"><strong>Field</strong></td><td width=\"78%\"><strong>Description</strong></td></tr>\n";
                $row_no = 0;

                foreach($results_array as $result)
                {
                    $not = str_replace("+", "%2B", $result->notation);
                    $not = str_replace("\"", "%22", $not);

                    $url = "<a href=\"edittag.php?tag=" . $not . "&resetsearch=Y\">" . $result->notation . "</a>";

                    echo "<tr><td bgcolor=\"" . ($row_no % 2 == 0 ? "white" : "#f4f4f4") . "\">" . $url . "</a></td>";
                    echo "<td bgcolor=\"" . ($row_no % 2 == 0 ? "white" : "#f4f4f4") . "\">";
                    switch($result->field_id)
                    {
                        case 1:
                            echo "Caption";
                            break;
                        case 4:
                            echo "Including";
                            break;
                        case 5:
                            echo "Scope Note";
                            break;
                        case 6:
                            echo "Application Note";
                            break;
                        case 10:
                            echo "Information Note";
                            break;
                        case 125:
                            echo "Reference";
                            break;
                        case 126:
                            echo "Reference Notation";
                            break;
                        case 955:
                            echo "Editorial Note";
                            break;
                        case 2:
                            echo "Example";
                            break;
                        case 3:
                            echo "Example Notation";
                            break;
                    }
                    echo "</td>";
                    $description = htmlentities($result->description, ENT_COMPAT, "UTF-8");
                    //$description = str_replace("<", "&lt;", $result->description);
                    //$description = str_replace(">", "&gt;", $description);
                    $color_language = $language_names[$result->lang_id];
                    echo "<td bgcolor=\"" . ($row_no % 2 == 0 ? "white" : "#f4f4f4") . "\" style=\"color: " . $list_colors[$color_language] . ";\">" . $description . "</td></tr>\n";
                    $row_no++;
                }
                echo "</table>\n";
            }
            else
            {
                if (isset($_POST['SubmitSearch']))
                    echo "<div class=\"searcherrorbox\">No results found</div>\n";
            }
        ?>
    </div>
</body>
</html>
<script type="text/javascript">
function init()
{
    var hidScroll = document.getElementById('scrollvalue');
    document.getElementById('searchresultsdiv').scrollTop = hidScroll.value;
}
window.onload = init;
</script>

