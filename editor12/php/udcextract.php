<?php
    session_start();
    include_once("checkrestrictedsession.php");
    checkrestrictedsession();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Chris Overfield" />
    <link rel="stylesheet" href="../extract.css" type="text/css" />
	<title>UDCS Data Extract</title>
</head>

<body>
    <?php
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
        	}
        	mysql_free_result($res);
        }
        @mysql_close($dbc);
    ?>
        
    <div style="width: 500px; margin-left: auto; margin-right: auto; margin-top: 10px;">
    <img src="../images/udcsumtitle.jpg" border="0" /><br /><br />
    Please select an extract below and click <strong>Export</strong>.<br/>
    <?php echo "Logged in as [" . $_SESSION['userid'] . "]&nbsp;&nbsp;&nbsp;<a href=\"edittag.php\">Translation Page</a><br>\n"; ?>
    <br/>
    </div>

    <div style="width: 480px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
        <form action="extract.php" method="POST">

        <div style="float: left; clear: right; width: 410px;">            

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
    <div style="width: 480px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
        <form action="extract.php" method="POST">

        <div style="float: left; clear: right; width: 410px;">            

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
    <div style="width: 480px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
        <form action="extract.php" method="POST">

        <div style="float: left; clear: right; width: 410px;">            

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
    <div style="width: 480px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
        <form action="extract.php" method="POST">

        <div style="float: left; clear: right; width: 410px;">            

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
        <div style="width: 480px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
        <form action="extract.php" method="POST">

        <div style="float: left; clear: right; width: 410px;">            

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
    <div style="width: 480px; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #fefefe; border: 1px solid #989898; padding: 10px 10px 10px 10px; overflow: auto; ">
        <form action="extract.php" method="POST">

        <div style="float: left; clear: right; width: 410px;">            

        <table border="0" cellpadding="3" cellspacing="1" bgcolor="#888888">
            <tr>
                <td bgcolor="#efefef"><strong>Refresh Stats</strong></td>
            </tr>
        </table>

            </div>
            
            <div style="float: left; clear: right; width: 50px; height: 50px; vertical-align: bottom;">            
            <input type="hidden" name="query" value="refreshstats" />
            <input type="submit" value="Export" />
            </div>
        </form>
    </div>    
</body>
</html>