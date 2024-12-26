<?php
    session_start();
    include_once("checksession.php");
    checksession();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Chris Overfield" />
    <link rel="stylesheet" href="../extract.css" type="text/css" />
    <link rel="stylesheet" href="../udcedit.css" type="text/css" />
    <link rel="stylesheet" href="../udc1000.css" type="text/css" />
    <link rel="shortcut icon" href="../images/udc.ico" type="image/x-icon" />
	<title>UDC MRF Translator</title>
</head>

<body>
    <?php
        require_once("DBConnectInfo.php");

        $admin_user = false;
        if (strstr($_SESSION['userid'], "aida") !== FALSE ||
        	strstr($_SESSION['userid'], "gerhard") !== FALSE ||
        	strstr($_SESSION['userid'], "chris") !== FALSE)
       	{
       		$admin_user = true;
       	}

        $languages = array();

        $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
        mysql_select_db (DBDATABASE);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

        $sql = "select language_id, description from language";
        if ($admin_user == false)
        {
        	$sql .= " where language_id in (1, " . $_SESSION['deflang'] .")";
        }
        #echo $sql . "<br>\n";

        $res = @mysql_query($sql, $dbc);
        if ($res)
        {
            while(($row = mysql_fetch_array($res, MYSQL_NUM)))
        	{
                $languages[$row[0]] = $row[1];
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    ?>

    <div style="width: 500px; margin-left: auto; margin-right: auto; margin-top: 10px; font-size: -1;">
    <div id="titleimagecontainer_thin">&nbsp;</div>
    <a href="edittag.php">Back to Translation Page</a>
    <p>Please select required options from one of the extracts below and click <strong>Export</strong>.</p><p>Monolingual exports should be opened with Microsoft Word, selecting <strong>Unicode (UTF-8)</strong> as the encoding type - see <a href="../images/word_attach.jpg" target="_blank">screenshot</a>. Bilingual exports should be opened with Microsoft Excel.</p>
    </div>

    <div style="width: 480px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; padding: 10px 10px 10px 10px; overflow: auto; ">
        <form action="extract.php" method="post">
            <div style="float: left; clear: left; width: 390px; border: 1px solid #888888; margin-right: 20px">
                <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#888888">
                    <tr>
                        <td bgcolor="#efefef"><div style="width: 380px; height: 16px; line-height: 16px; vertical-align: middle;"><strong><img src="../images/excel.png" alt="Excel" width="16" height="16" style="margin-top: -3px; line-height:  16px; vertical-align: middle;"/>&nbsp;&nbsp;Bilingual Exports</strong></div></td>
                    </tr>
                    <tr>
                        <td bgcolor="white"><div style="float: left; width: 95px;  height: 20px; line-height: 20px; vertical-align: middle;">Dataset</div>
                        <div style="float: left; width: 280px;">
                            <select name="query" id="query" style="width: 280px;">
                            <option value="level2captions">Level 2 Captions</option>
                            <option value="allcaptions">All Captions</option>
                            <option value="allcaptionsincluding">All Captions and Including</option>
                            <option value="allfields">All Fields (excluding examples of combination)</option>
                            <option value="examples">Examples of Combination</option>
                            </select>
                        </div>
						<div style="float: left; width: 95px; height: 20px; line-height: 20px; vertical-align: middle;">&nbsp;</div><div style="float: left; width: 280px; font-style: italic; color: #8899ee; font-size: 13px; height: 20px; line-height: 20px; vertical-align: middle;">Choose the fields you want to export</div>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="white"><div style="float: left; width: 95px; height: 20px; line-height: 20px; vertical-align: middle;">Languages</div>
                        	<div style="float: left; width: 240px; height: 20px; line-height: 20px; vertical-align: middle;">
                            <select name="language1" id="language1" style="width: 80px;">
                            <?php
                                foreach($languages as $id => $desc)
                                {
                                    echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                }
                            ?>
                            </select>&nbsp;&nbsp;
                            <select name="language2" id="language2" style="width: 80px;">
                            <?php
                                foreach($languages as $id => $desc)
                                {
                                    echo "      <option value=\"" . $id . "." . $desc . "\">" . $desc . "</option>\n";
                                }
                            ?>
                            </select>
                            </div>

                        </td>
                    </tr>
                    <!--tr>
                        <td bgcolor="white"><div style="float: left; width: 95px; height: 20px; line-height: 20px; vertical-align: middle;">Export Range</div>
                            <div style="float: left; width: 280px; height: 20px; line-height: 20px; vertical-align: middle;">
                            <select name="dataset" id="dataset">
                                <option value="--">Common Auxiliaries</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                            </div>
                        </td>
                    </tr-->

                </table>
            </div>

            <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom;">
                <input type="submit" value="Export" />
            </div>
        </form>

        <form action="export.php" method="post">
            <div style="float: left; clear: left; width: 390px; border: 1px solid #888888; margin-right: 20px; margin-top:20px;">
                <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#888888">
                    <tr>
                        <td bgcolor="#efefef"><div style="width: 380px; height: 16px; line-height: 16px; vertical-align: middle;"><strong><img src="../images/word.png" alt="Word" width="16" height="16" style="margin-top: -3px; height: 16px; line-height:  16px; vertical-align: middle;"/>&nbsp;&nbsp;Monolingual Exports</strong></div></td>
                    </tr>
                    <tr>
                        <td bgcolor="white"><div style="float: left; width: 95px;  height: 20px; line-height: 20px; vertical-align: middle;">Dataset</div>
                        <div style="float: left; width: 280px;">
                            <select name="query" id="query">
                            <option value="captions">Captions</option>
                            <option value="tagged">Full Tagged</option>
                            <option value="full">Full Plain</option>
                            </select>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="white"><div style="float: left; width: 95px; height: 20px; line-height: 20px; vertical-align: middle;">Language</div>
                        	<div style="float: left; width: 240px; height: 20px; line-height: 20px; vertical-align: middle;">
                            <select name="language" id="language" style="width: 80px;">
                            <?php
                                foreach($languages as $id => $desc)
                                {
                                    echo "      <option value=\"" . $id . "\">" . $desc . "</option>\n";
                                }
                            ?>
                            </select>
                            </div>
                        </td>
                    </tr>
                    <!--tr>
                        <td bgcolor="white"><div style="float: left; width: 95px;  height: 20px; line-height: 20px; vertical-align: middle;">Export Range</div>
	                        <div style="float: left; width: 280px; height: 20px; line-height: 20px; vertical-align: middle;">
                            <select name="dataset" id="dataset">
                                <option value="--">Common Auxiliaries</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                            </div>
                        </td>
                    </tr-->
                </table>

            </div>

            <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom; margin-top: 20px;">
            <input type="submit" value="Export" />
            </div>
        </form>
    </div>
</body>
</html>