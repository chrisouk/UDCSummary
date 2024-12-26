<?php
    session_start();
    include_once("checksession.php");
    checksession();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDCS Editor</title>
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
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="1" <?php if (array_key_exists("1", $fieldarray)) echo " checked "; ?>/> Caption</div>
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="4" <?php if (array_key_exists("4", $fieldarray)) echo " checked "; ?>/> Including</div>
                            </div>
                            <div class="searchrow">
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="5" <?php if (array_key_exists("5", $fieldarray)) echo " checked "; ?>/> Scope Note</div>
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="6" <?php if (array_key_exists("6", $fieldarray)) echo " checked "; ?>/> Application Note</div>
                            </div>
                            <div class="searchrow">
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="10" <?php if (array_key_exists("10", $fieldarray)) echo " checked "; ?>/> Information Note</div>
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="2" <?php if (array_key_exists("2", $fieldarray)) echo " checked "; ?>/> Examples</div>
                            </div>
                            <div class="searchrow">
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="125" <?php if (array_key_exists("125", $fieldarray)) echo " checked "; ?>/> References</div>
                                <div class="searchcheck"><input type="checkbox" name="chk_text[]" value="955" <?php if (array_key_exists("955", $fieldarray)) echo " checked "; ?>/> Editorial Note<//div>
                            </div>
        				</div>
                    </div>
    				<div class="editlabel fixedwidthlabel rightmargin5">Language</div>
    				<div class="editvalue searchpanel">
    					<div class="searchrow">
                            <?php
                                $list_colors = array( 'All' => 'black', 'English' => 'black', 'Dutch' => '#b27340');

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
                                $sql = "select language_id, description from language where language_id in (1,2) order by language_id";
                                echo "<option value=\"0\"";
                                if ($language == 0) echo " selected";
                                echo ">All\n";

                                $res = @mysql_query($sql, $dbc);
                                if ($res)
                                {
                                    while (($row = @mysql_fetch_array($res, MYSQL_NUM)))
                                    {
                                        $language_names[$row[0]] = $row[1];
                                        echo "<option value=\"" . $row[0] . "\" style=\"color: " . $list_colors[$row[1]] . ";\"";
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

                if (isset($_POST['captionsearchterm']))
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
                            $sql .= "and language_id = " . $language;
                        }

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
                                " join example_classmarks e on e.classmark_id = c.classmark_id where e.tag like '%" . @mysql_real_escape_string($_POST['captionsearchterm']) . "%' and c.active = 'Y')";
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

                            if ($restrict_results == true)
                            {
                                if (!array_key_exists($result->notation, $restrictionarray))
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
                    echo "<tr><td bgcolor=\"" . ($row_no % 2 == 0 ? "white" : "#f4f4f4") . "\"><a href=\"edittag.php?tag=" . $not. "\">" . $result->notation . "</a></td>";
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
                    echo "<div class=\"searchbox searchkeywordbox searcherrorbox\">No results found</div>\n";
            }
        ?>
    	<!--form id="udcform" name="udcform" method="post" action="edittag.php" accept-charset="UTF-8">
            <input type="hidden" name="scrollvalue" id="scrollvalue" value="#scrollvalue#" />
            <div id="validationdiv" name="validationdiv" style="display:#validationon#;">
            #validation#
            </div>
            <div id="searchcontainer" style="display:#validationoff#;">
        		<div class="searchbox searchkeywordbox">
                	<form id="udcform" name="udcform" method="post" action="edittag.php" accept-charset="UTF-8">
            			<div class="editleftcolumn">
            				<div class="editlabel fixedwidthlabel rightmargin5">Notation Search</div>
            				<div class="editvalue">
            					<input type="text" id="notationsearchterm" name="notationsearchterm" class="textinput" style="width: 180px;" value="#notationsearchterm#" />
            				</div>
            				<div style="float: left; line-height: 32px; vertical-align: middle;">
            					&nbsp;
            					<input class="inputbutton" type="submit" id="SubmitNotationSearch" name="SubmitNotationSearch" value="Search" />
            				</div>
            			</div>
                    </form>
                	<form id="udcform" name="udcform" method="post" action="edittag.php" accept-charset="UTF-8">
            			<div class="editleftcolumn">
            				<div class="editlabel fixedwidthlabel rightmargin5">Caption Search</div>
            				<div class="editvalue">
            					<input type="text" id="captionsearchterm" name="captionsearchterm" class="textinput" style="width: 180px;" value="#captionsearchterm#" />
            				</div>
            				<div style="float: left; line-height: 32px; vertical-align: middle;">
            					&nbsp;
            					<input class="inputbutton" type="submit" id="SubmitCaptionSearch" name="SubmitCaptionSearch" value="Search" />
            				</div>
            			</div>
                    </form>
        		</div>
        		<div class="searchbox searchkeywordbox" style="display:#validationoff#;">
                    <input type="hidden" name="scrollvalue" id="scrollvalue" value="#scrollvalue#" />
        			<div class="editleftcolumn">
                        <div id="searchresultsdiv" class="searchresults bottommargin5" onscroll="fScroll(this);">
                        #searchresults#
                        </div>
        			</div>
        		</div>
            </div>

            <div id="maincontainer" style="display:#validationoff#;">
        		<div class="searchbox">
                    <span style="float: left; overflow: none;"><a href="logoff.php">Logoff</a> #showactivity# #showeditorialcomments# </span>
        			<span style="float:right; overflow:none;">
        				&nbsp;&nbsp;&nbsp;Language&nbsp;
        				<select id="language" name="language" class="combobox">
            				<option value="1" #lang-eng#>English [eng]</option>
        				</select>
        			</span>
        		</div>
        		<div class="searchbox">
        			<div class="editleftcolumn widecolumn">
        				<div class="editlabel fixedwidthlabel">UDC</div>
        				<div class="editvalue">
        					<input type="text" id="searchterm" name="searchterm" class="textinput inputfield" value="#searchterm#" />
        				</div>
                        <input type="hidden" name="notation" id="notation" value="#notation#" />
        				<div style="float: left; line-height: 32px; vertical-align: middle;">
        					&nbsp;
        					<input class="inputbutton" type="submit" id="SearchButton" name="Search" value="Search" />
        					<input type="submit" id="SubmitSave" name="SubmitSave" value="Save"/>
        					<input type="submit" id="EditCancel" name="EditCancel" value="Cancel" style="display:none" onclick="javascript:CancelEditForm(); return false;"/>
        					<input type="submit" id="PrevNotation" name="PrevNotation" value="Prev"/ style="#displayprevnotation#" onclick="javascript:GotoNotation('#prevnotation#'); return false;">
        					<input type="submit" id="NextNotation" name="NextNotation" value="Next"/ style="#displaynextnotation#" onclick="javascript:GotoNotation('#nextnotation#'); return false;">
        				</div>
            			<div class="editrightcolumn rightalign">
                        	<div class="editlabel nonedit rightmargin5">ID</div>
                            <div id="mfndiv" name="mfndiv" class="editvalue">#mfn#<input type="hidden" name="MFN" id="MFN" value="#mfn#" /><input type="hidden" name="mfnvalue" id="mfnvalue" value="#mfn#" /></div>
            			</div>
                    </div>
        		</div>
        		<div class="errorbox" style="display:#errorshow#">
        			#errorreasons#
        		</div>
        		<div class="errorbox successbox" style="display:#successshow#">
        			Record saved successfully
        		</div>
        		<div class="searchbox">
        			<div id="pagenumber" class="editrightcolumn rightalign">
                    #pageaccess#
        			</div>
        			<div class="editleftcolumn">
                    	<div class="editlabel fixedwidthlabel nonedit"><span style="color: #9a9a9a;">001</span> Encoded </div>
                        <div id="EUN" class="editvalue fixedwidthvalue">#EUN#&nbsp;</div>
        			</div>
    				<div class="editleftcolumn widecolumn">
    					<div class="editlabel fixedwidthlabel"><span style="color: #9a9a9a;">100</span> Caption</div>
    					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #captioncolor#" id="caption" name="caption" rows="1">#caption#</textarea></div>
    					<div class="errorbox" style="display:none;" id="captionerror">This is an error</div>
    				</div>
    				<div class="editleftcolumn widecolumn">
    					<div class="editlabel fixedwidthlabel"><span style="color: #9a9a9a;">105</span> Including</div>
    					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #verbalexamplescolor#" id="verbalexamples" name="verbalexamples" rows="2">#verbalexamples#</textarea></div>
    				</div>
        		</div>
                <div id="page1" #page1access# >
        			<div class="searchbox">
        				<div class="editleftcolumn">
        					<div class="editlabel fixedwidthlabel "><span style="color: #9a9a9a;">002</span> Table</div>
        					<div class="editvalue">
        						<select name="headingtype" class="editcombo combowidth">
        							<option value="a" #heading-a#>Table Ia - Coordination. Extension</option>
        							<option value="b" #heading-b#>Table Ib - Relation. Subgrouping. Order-fixing</option>
        							<option value="c" #heading-c#>Table Ic - Common Auxiliaries of Language</option>
        							<option value="d" #heading-d#>Table Id - Common Auxiliaries of Form</option>
        							<option value="e" #heading-e#>Table Ie - Common Auxiliaries of Place</option>
        							<option value="f" #heading-f#>Table If - Common Auxiliaries of Ethnic Grouping</option>
        							<option value="g" #heading-g#>Table Ig - Common Auxiliaries of Time</option>
        							<option value="h" #heading-h#>Table Ih - Subject specication by notations from non-UDC sources</option>
        							<option value="i" #heading-i#>Table Ii - Common Auxiliaries of Viewpoint</option>
        							<option value="k" #heading-k#>Table Ik - Common Auxiliaries of Persons and Materials</option>
        							<option value="l" #heading-l#>Section II. Special auxiliary subdivisions</option>
        							<option value="M" #heading-M#>Main table</option>
        						</select>
        					</div>
        				</div>
        				<div class="editrightcolumn rightalign">
        					<div class="editlabel fixedwidthlabel ">Special Aux&nbsp;</div>
        					<div class="editvalue">
        						<select name="specialauxtype" class="editcombo combowidth">
        							<option value="0" #notspecialaux#>Not a special aux</option>
        							<option value="1" #hyphenaux#>hyphen (-) auxiliary</option>
        							<option value="2" #pointaux#>point nought (.0) auxiliary</option>
        							<option value="3" #apostropheaux#>apostrophe (') auxiliary</option>
        							<option value="4" #otheraux#>other, e.g. final digits (...1/...9)</option>
        						</select>
        					</div>
        				</div>
        				<div class="editleftcolumn">
        					<div class="editlabel fixedwidthlabel">Heading Type</div>
        					<div class="editvalue">
        						<select name="udcheadingtype" class="editcombo combowidth">
        							<option value="C" #CAux#>Single common auxiliary</option>
        							<option value="CS" #CSAux#>Single common auxiliary with Special Auxiliary</option>
        							<option value="CR" #CRAux#>Range of common auxiliary (/)</option>
        							<option value="CC" #CCAux#>Common auxiliary connected by :</option>
        							<option value="CP" #CPAux#>Common auxiliary connected by +</option>
        							<option value="M" #MAux#>Simple main number</option>
        							<option value="MS" #MSAux#>Combined special auxiliary</option>
        							<option value="MSS" #MSSAux#>Combined with special auxiliary</option>
        							<option value="outside" #outsideAux#>Special auxiliary table</option>
        							<option value="MC" #MCAux#>Combined with common auxiliary</option>
        							<option value="MCS" #MCSAux#>Combined with common and special auxiliary</option>
        							<option value="MM" #MMAux#>Two main numbers combined with / : or +</option>
        							<option value="MMSC" #MMSCAux#>Two main numbers combined with common or special auxiliary</option>
        						</select>
        					</div>
        				</div>
        				<div class="editrightcolumn rightalign">
        					<div class="editlabel fixedwidthlabel ">Group ID&nbsp;</div>
        					<div class="editvalue"><input id="auxgroup" name="auxgroup" class="textinput" style="width: 174px;" type="text" value="#auxgroupid#" onBlur="checkAuxGroup(); return true;" /></div>
        				</div>
        			</div>
        			<div class="searchbox">
            			<div class="editleftcolumn widecolumn">
            				<div class="editlabel fixedwidthlabel clearleft singleline">Broader&nbsp;</div>
            				<div class="editvalue"><input id="broader" name="broader" class="textinput inputfieldextended #broadercategorycolor#" type="text" value="#broadercategory#"
                                onBlur ="javascript:checkBroader(); return true;" /></div>
                            <div class="editlabel fixedwidthlabel clearleft singleline">Derived From&nbsp;</div>
            				<div class="editvalue "><input id="derivedfrom" name="derivedfrom" class="textinput inputfieldextended #derivedfromcolor#" type="text" value="#derivedfrom#"
                                onBlur ="javascript:checkDerivedFrom(); return true;" /></div>
            			</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">110</span> Scope Note</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #scopenotecolor#" name="scopenote" rows="3">#scopenote#</textarea></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">111</span> App Note</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #appnotecolor#" name="appnote" rows="3">#appnote#</textarea></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">114</span> Info Note</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #informationnotecolor#" id="informationnote" name="informationnote" rows="2">#infonote#</textarea></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">125</span> Refs</div>
        					<div class="editvalue widevalue" id="references">#references#</div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">115</span> Examples</div>
        					<div class="editvalue widevalue" id="examples">#examples#</div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline">Parallel Div Instructions</div>
        					<div class="editvalue widevalue" id="pardivinst">#pardivinst#</div>
        				</div>
        			</div>
        		</div>

        		<div id="page2" #page2access# >
        			<div class="searchbox">
        				<div class="editleftcolumn">
        					<div class="editlabel fixedwidthlabel">Intro Date</div><div class="editvalue"><input id="introdate" name="introdate" class="textinput inputfield" type="text" value="#introdate#" /></div>
        				</div>
        				<div class="editrightcolumn rightalign">
        					<div class="editlabel fixedwidthlabel">Source&nbsp;</div><div class="editvalue"><input id="introsource" name="introsource" class="textinput inputfield" type="text" value="#introsource#" /></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel">Intro Comment</div>
        					<div class="editvalue widevalue"><input id="introcomment" name="introcomment" class="textinput inputfieldextended" type="text" value="#introcomment#" /></div>
        				</div>
        				<div class="editleftcolumn">
        					<div class="editlabel fixedwidthlabel">Last Rev Date</div>
                            <div class="editvalue"><input id="lastrevdate" name="lastrevdate" class="textinput inputfieldshort" type="text" value="#lastrevdate#" /></div>
        					<div class="editlabel shortfixedwidthlabel rightalign">Fields&nbsp;</div>
        					<div class="editvalue"><input id="lastrevfields" name="lastrevfields" class="textinput" style="width:138px;" type="text" value="#lastrevfields#" /></div>
        					<div class="editlabel shortfixedwidthlabel rightalign">Source&nbsp;</div>
                            <div class="editvalue">
                                <input id="lastrevsource" name="lastrevsource" class="textinput inputfieldshort" type="text" value="#lastrevsource#" />
                            </div>
                        </div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel clearleft fixedwidthlabel">Rev Comment</div>
        					<div class="editvalue widevalue"><input id="lastrevcomment" name="lastrevcomment" class="textinput inputfieldextended" type="text" value="#lastrevcomment#" /></div>
        				</div>
        				<div class="editleftcolumn">
                            <div class="editlabel fixedwidthlabel">&nbsp;</div>
                            <div class="editvalue noborder">
                                <div class="edittextarea wideeditvalue revcopy">#lastrevcopy#</div>
                            </div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel">Revision Hist</div>
        					<div class="editvalue widevalue" id="revisions">#revisions#<a href="#" onMouseDown="javascript:addRevision();">Add</a></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel">Editorial Note</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue" name="editorialnote" rows="1">#editorialnote#</textarea></div>
        				</div>
        			</div>
        		</div>
        		<div id="page3" #page3access# >
        			<div class="searchbox">
        				<div class="editleftcolumn">
        					<a name="keywordinput"></a>
        					<div class="editlabel inputeditlabel fixedwidthlabel">Scanned Terms&nbsp;</div>
        					<div class="editvalue">
        						<textarea class="edittextarea leftarea" id="keywords" name="keywords" rows="10"></textarea>&nbsp;
        						<a href="#keywordinput" title="Extract keywords from Caption and Verbal Examples" onmousedown="javascript:scansearchterms();">Scan</a>
        					</div>
        				</div>
                        <div class="editleftcolumn">
        					<a name="keywordlist"></a>
        					<div class="editlabel inputeditlabel fixedwidthlabel">Approved Keywords&nbsp;</div>
        					<div class="editvalue">
        						<textarea class="edittextarea leftarea" id="approvedkeywords" name="approvedkeywords" rows="10">#approvedkeywords#</textarea>
        					</div>
        				</div>
        				<div class="editrightcolumn">
        					<a name="alphaindex"></a>
        					<div class="editlabel inputeditlabel fixedwidthlabel">Alphabetical Index&nbsp;</div>
        					<div class="editvalue">
        						<textarea class="edittextarea leftarea" id="alphabeticalindex" name="alphabeticalindex"rows="10">#alphaindex#</textarea>&nbsp;
        					</div>
        				</div>
        			</div>
        		</div>
        		<div class="searchbox" #reviewercomments# ">
                    <div class="editleftcolumn widecolumn">
                		<div class="editlabel fixedwidthlabel">Edit Comments</div>
                		<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue" id="editcomments" name="editcomments" rows="3">#editcomments#</textarea></div>
                	</div>
        		</div>
        		<div class="searchbox" #changedfields# ">
                    <div class="editleftcolumn widecolumn">
    					<div class="editlabel fixedwidthlabel singleline"><span style="color: magenta;">Fields different from MRF</span></div>
    					<div class="editvalue widevalue" id="examples">
                            <table class="reftable" width="100%" bgcolor="#cccccc" border="0" cellpadding="2" cellspacing="1">
                                <tr>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_including" id="changed_including" value="105" #changed_including#> Including</td>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_scopenote" id="changed_scopenote" value="110" #changed_scopenote#> Scope Note</td>
                                </tr>
                                <tr>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_appnote" id="changed_appnote" value="111" #changed_appnote#> Application Note</td>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_references" id="changed_references" value="115" #changed_references#> References</td>
                                </tr>
                                <tr>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_examples" id="changed_examples" value="125" #changed_examples#> Examples</td>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_editorial_note" id="changed_editorial_note" value="957" #changed_editorial_note#> Editorial Note</td>
                                </tr>
                            </table>
                	</div>
        		</div>
            </div>
       	</form!-->
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

