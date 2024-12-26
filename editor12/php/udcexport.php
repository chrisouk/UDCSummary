<?php
    session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<meta name="author" content="Chris Overfield" />
    <link rel="shortcut icon" href="../img/udc.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../udcedit.css" type="text/css" />
	<title>UDCS Editor</title>
</head>

<body style="font-family: Tahoma,Helvetica,sans-serif; font-size: 13px; line-height: 1.2em;">

    <div style="width: 1040px; height: 55px; margin-left: auto; margin-right: auto; margin-top: 10px;">
        <img src="../img/udceditorialtitle_thin.jpg" border="0" /><br /><br />
    </div>
    <div id="topmenu" style="width: 1040px; padding: 2px 0px; margin-left: auto; margin-right: auto;">&nbsp;<a href="edittag.php">Back to Editor</a></div>
    <div style="width: 1040px; margin-left: auto; margin-right: auto; margin-top: 10px;">

        <div style="float: left; width: 500px; margin: 10px 5px; padding: 4px; border: 1px solid #ababab;">
            <form action="export.php" method="GET">
                Please select a language to export and click <strong>Export</strong>.
                The file produced should be opened with Microsoft Word, selecting <strong>Unicode (UTF-8)</strong> as the encoding type - see <a href="../img/word_attach.jpg">screenshot</a>.<br /><br />
                <div style="width: 350px; margin: 0px auto;">
                    Export Type:<br /><select name="exporttype">
                    <option value="tagged">Full Tagged</option>
                    <option value="full">Full Plain</option>
                    </select> <br /><br />

                    <select name="exportrange" id="exportrange">
                        <option value="Tbl1a">Table Ia - Coordination. Extension</option>
                        <option value="Tbl1b">Table Ib - Relation. Subgrouping. Order-fixing</option>
                        <option value="Tbl1c">Table Ic - Common Auxiliaries of Language</option>
                        <option value="Tbl1d">Table Id - Common Auxiliaries of Form</option>
                        <option value="Tbl1e">Table Ie - Common Auxiliaries of Place</option>
                        <option value="Tbl1f">Table If - Common Auxiliaries of Ethnic Grouping</option>
                        <option value="Tbl1g">Table Ig - Common Auxiliaries of Type</option>
                        <option value="Tbl1h">Table Ih - Subject specication by notations from n...</option>
                        <option value="Tbl1i">Table Ii - Common Auxiliaries of Viewpoint</option>
                        <option value="Tbl1k">Table Ik - Common Auxiliaries of Persons and Mater...</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="5only">5 (only)</option>
                        <option value="51">51</option>
                        <option value="52">52</option>
                        <option value="53">53</option>
                        <option value="54">54</option>
                        <option value="55">55</option>
                        <option value="56">56</option>
                        <option value="57">57</option>
                        <option value="58">58</option>
                        <option value="59">59</option>
                        <option value="6only">6 (only)</option>
                        <option value="61">61</option>
                        <option value="62">62</option>
                        <option value="63">63</option>
                        <option value="64">64</option>
                        <option value="65">65</option>
                        <option value="66">66</option>
                        <option value="67">67</option>
                        <option value="68">68</option>
                        <option value="69">69</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>

                    <br /><br />

                    <?php
                    # Establish database connection
                	require_once("DBConnectInfo.php");

                    $languages = array();

                	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
                	mysql_select_db (DBDATABASE);
                    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

                    $sql = "select language_id, description from language";
                   	$res = @mysql_query($sql, $dbc);
                    if ($res)
                    {
                        while(($row = mysql_fetch_array($res, MYSQL_NUM)))
                    	{
                            $languages[$row[0]] = $row[1];
                            echo "<input type=\"radio\" name=\"lang\" value=\"" . $row[0] . "\">" . $row[1] . "<br>\n";
                    	}
                    	mysql_free_result($res);
                    }

                    @mysql_close($dbc);
                    ?>
                    <br />

                    <input type="submit" value="Export">
                </div>
            </form>
        </div>

        <div style="float: left; width:500px; margin: 10px 5px; padding: 4px; border: 1px solid #ababab;">
            <div style="width: 450px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
                <form action="extract.php" method="POST">
                    <div style="float: left; clear: right; width: 375px;">
                        <table border="0" cellpadding="3" cellspacing="1" bgcolor="#888888">
                            <tr>
                                <td bgcolor="#efefef"><strong>Level 2 Captions</strong></td>
                            </tr>
                            <tr>
                                <td bgcolor="white">Language 1:&nbsp;&nbsp;
                                    <select name="language1" id="language1">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>&nbsp;&nbsp;&nbsp; Language 2:&nbsp;&nbsp;
                                    <select name="language2" id="language2">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom;">
                        <input type="hidden" name="query" value="level2captions" />
                        <input type="submit" value="Export" />
                    </div>
                </form>
            </div>

            <div style="width: 450px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
                <form action="extract.php" method="POST">
                    <div style="float: left; clear: right; width: 375px;">
                        <table border="0" cellpadding="3" cellspacing="1" bgcolor="#888888">
                            <tr>
                                <td bgcolor="#efefef"><strong>All Captions</strong></td>
                            </tr>
                            <tr>
                                <td bgcolor="white">Language 1:&nbsp;&nbsp;
                                    <select name="language1" id="language1">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>&nbsp;&nbsp;&nbsp; Language 2:&nbsp;&nbsp;
                                    <select name="language2" id="language2">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom;">
                        <input type="hidden" name="query" value="allcaptions" />
                        <input type="submit" value="Export" />
                    </div>
                </form>
            </div>

            <div style="width: 450px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
                <form action="extract.php" method="POST">
                    <div style="float: left; clear: right; width: 375px;">
                        <table border="0" cellpadding="3" cellspacing="1" bgcolor="#888888">
                            <tr>
                                <td bgcolor="#efefef"><strong>All Captions and Including</strong></td>
                            </tr>
                            <tr>
                                <td bgcolor="white">Language 1:&nbsp;&nbsp;
                                    <select name="language1" id="language1">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>&nbsp;&nbsp;&nbsp; Language 2:&nbsp;&nbsp;
                                    <select name="language2" id="language2">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom;">
                        <input type="hidden" name="query" value="allcaptionsincluding" />
                        <input type="submit" value="Export" />
                    </div>
                </form>
            </div>

            <div style="width: 450px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
                <form action="extract.php" method="POST">
                    <div style="float: left; clear: right; width: 375px;">
                        <table border="0" cellpadding="3" cellspacing="1" bgcolor="#888888">
                            <tr>
                                <td bgcolor="#efefef"><strong>All Fields (excluding examples of combination)</strong></td>
                            </tr>
                            <tr>
                                <td bgcolor="white">Language 1:&nbsp;&nbsp;
                                    <select name="language1" id="language1">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>&nbsp;&nbsp;&nbsp; Language 2:&nbsp;&nbsp;
                                    <select name="language2" id="language2">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom;">
                        <input type="hidden" name="query" value="allfields" />
                        <input type="submit" value="Export" />
                    </div>
                </form>
            </div>

            <div style="width: 450px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
                <form action="extract.php" method="POST">
                    <div style="float: left; clear: right; width: 375px;">
                        <table border="0" cellpadding="3" cellspacing="1" bgcolor="#888888">
                            <tr>
                                <td bgcolor="#efefef"><strong>Examples of combination</strong></td>
                            </tr>
                            <tr>
                                <td bgcolor="white">Language 1:&nbsp;&nbsp;
                                    <select name="language1" id="language1">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>&nbsp;&nbsp;&nbsp; Language 2:&nbsp;&nbsp;
                                    <select name="language2" id="language2">
                                    <?php
                                        foreach($languages as $id => $desc)
                                        {
                                            echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom;">
                        <input type="hidden" name="query" value="examples" />
                        <input type="submit" value="Export" />
                    </div>
                </form>
            </div>

            <div style="width: 450px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
                <form action="extract.php" method="POST">

                <div style="float: left; clear: right; width: 375px;">

                <table border="0" cellpadding="3" cellspacing="1" bgcolor="#888888">
                    <tr>
                        <td bgcolor="#efefef"><strong>Refresh Stats</strong></td>
                    </tr>
                </table>

                    </div>

                    <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom;">
                    <input type="hidden" name="query" value="refreshstats" />
                    <input type="submit" value="Refresh" />
                    </div>
                </form>
            </div>


        </div>

    </div>

</body>
</html>