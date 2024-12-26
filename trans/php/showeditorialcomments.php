<?php
/**
 * @author Chris Overfield
 * @copyright 2010
 */

	session_start();
	require_once 'checksession.php';
	checksession();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC PE Translator</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

<link rel="stylesheet" href="../udcedit.css" type="text/css" />
<link rel="shortcut icon" href="../images/udc.ico" type="image/x-icon" />

<script language="javascript" src="udcedit.js" type="text/javascript" ></script>
<script language="javascript" src="php.default.js" type="text/javascript" ></script>
</head>


<body>
    <div id="commentspagecontainer">
        <div id="titleimagecontainer_thin">&nbsp;</div>
<?php
	require_once("DBConnectInfo.php");
	include_once("specialchars.php");

    define("DUMMYINSERT", false);

	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");

    $idarray = array();

    $post_reviewer = "All";
    if (isset($_POST['reviewer']))
    {
        $post_reviewer = $_POST['reviewer'];
    }

    $show_completed = false;
    if (isset($_POST['showcompleted']))
    {
    	$show_completed = true;
    }

    if (isset($_POST['commentssubmit']))
    {
        if (isset($_SESSION['commentacks']))
        {
            unset($_SESSION['commentacks']);
        }
    }
    else if(isset($_POST['archive']))
    {
    	$sql = "insert into udct_editor_comments_arch select * from udct_editor_comments where acknowledged = 'Y'";
    	@mysql_query($sql, $dbc);

    	$sql = "delete from udct_editor_comments where acknowledged = 'Y'";
    	@mysql_query($sql, $dbc);
    }
    else if(isset($_POST['saveacks']))
    {
        $sqlarray = array();

        if (isset($_SESSION['commentacks']))
        {
            $idarray = $_SESSION['commentacks'];

            # Save acknowledgements that have changed
            foreach($idarray as $id => $state)
            {
                $state_change = false;

                //echo "Checking " . $id . ": ";
                if (isset($_POST["cb_" . $id]))
                {
                    $value = $_POST["cb_" . $id];
                    //echo "CB=" . $value. " STATE=" . $state . "<br>\n";
                        // Find the corresponding value
                    if ($state != $value)
                    {
                        $state_change = true;
                    }
                }
                else
                {
                    if ($state == "Y")
                    {
                        // No longer acknowledged - state change
                        $state_change = true;
                        $value = "N";
                    }
                }

                if ($state_change == true)
                {
                    $sql = "update udct_editor_comments set acknowledged = '";
                    if ($value == "Y")
                    {
                        $sql .= "Y";
                    }
                    else
                    {
                        $sql .= "N";
                    }
                    $sql .= "' where comment_id = " . $id . ";";
                    array_push($sqlarray, $sql);
                    //echo $sql . "<br>\n";
                }

            }

            foreach($sqlarray as $sql)
            {
                @mysql_query($sql, $dbc);
            }
        }
        else
        {
            echo "No commentacks<br>\n";
        }

        unset($_SESSION['commentacks']);
    }

    $reviewers = array();

    $table_name = "udct_editor_comments";
    if ($show_completed == true)
    {
    	$table_name = "udct_editor_comments_arch";
    }

    $sql = "select reviewer, count(*) from " . $table_name . " group by reviewer";
	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
            $reviewers[$row[0]] = $row[1];
		}
		@mysql_free_result($res);
	}

    if (count($reviewers) > 0)
    {
        echo "<div id=\"commentsfilterdiv\">\n";
   	    echo "<form id=\"commentsform\" name=\"commentsform\" method=\"post\" action=\"showeditorialcomments.php\" accept-charset=\"UTF-8\">\n";
        echo "<span style=\"width: 100px; float: left; text-align: left; \">&nbsp;<a href=\"edittag.php\">Back to Editor</a>&nbsp;</span><input type=\"checkbox\" id=\"showcompleted\" name=\"showcompleted\" ";
        if ($show_completed == true)
        	echo "checked";
        echo "> Show Completed Only &nbsp;<select name=\"reviewer\" id=\"reviewer\" class=\"combobox\">\n";
        echo "<option value=\"All\">--All--</option>\n";

        foreach($reviewers as $rev => $amt)
        {
            echo "<option value=\"" . $rev . "\"";
            if ($rev == $post_reviewer)
            {
                echo " selected";
            }
            echo ">" . $rev . " [" . $amt . "]</option>\n";
        }

        echo "</select> <input type=\"submit\" name=\"commentssubmit\" value=\"Go\">";
        if ($_SESSION['userrole'] == 2 && $show_completed == false) echo "&nbsp;<input type=\"submit\" name=\"saveacks\" value=\"Save Actions\"/>&nbsp;<input type=\"submit\" name=\"archive\" value=\"Archive\"/>";
        echo "&nbsp;</div>\n";
    }

    $sql = "select c.classmark_tag, u.reviewer, u.comments, DATE_FORMAT(u.date_changed, '%d-%m-%Y %H:%i:%S'), u.acknowledged, u.comment_id from " . $table_name . " u join classmarks c on c.classmark_id = u.classmark_id ";
    if ($post_reviewer != "All")
    {
        $sql .= " where reviewer = '" . $post_reviewer . "'";
    }
    $sql .= " order by date_changed desc";
    //echo $sql . "<br>\n";

    echo "<div id=\"commentsdiv\">\n";
    echo "<table width=\"750\" border=\"0\" bgcolor=\"#efefef\" cellpadding=\"2\" cellspacing=\"1\">\n";
    echo "<tr><td width=\"8%\">Notation</td><td width=\"43%\">Comment</td><td width=\"7%\">Reviewer</td><td width=\"15%\">Date</td><td width=\"4%\" align=\"center\">Done</td></tr>\n";
	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
        $row_no = 0;
        $bgcolor = "white";
		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
            if ($row_no % 2 == 0)
            {
                $bgcolor = "white";
            }
            else
            {
                $bgcolor = "#efefef";
            }
            echo "<tr>";
            $enc_notation = str_replace("\"", "%22", $row[0]);
            $enc_notation = str_replace("+", "%2B", $enc_notation);
            echo "<td bgcolor=\"" . $bgcolor . "\"><a href=\"edittag.php?tag=" . trim($enc_notation) . "\">" . $row[0]. "</td>";
            echo "<td bgcolor=\"" . $bgcolor . "\">". html_entity_decode($row[2], ENT_NOQUOTES) . "</td>";
            echo "<td bgcolor=\"" . $bgcolor . "\">". $row[1] . "</td>";
            echo "<td bgcolor=\"" . $bgcolor . "\">". $row[3] . "</td>";

            $ack = "N";
            $checked = "";
            if ($row[4] == "Y")
            {
                $checked = "checked";
                $ack = "Y";
            }

            echo "<td bgcolor=\"" . $bgcolor . "\" align=\"center\"><input type=\"checkbox\" name=\"cb_" . $row[5] . "\"";
            echo "id=\"cb_" . $row[5] . "\" value=\"" . $ack . "\" onclick=\"acknowledge('" . $row[5] . "'); return true;\" " . $checked . "></td>\n";
            echo "</tr>\n";

            $idarray[$row[5]] = $row[4];

            $row_no++;
		}
		@mysql_free_result($res);
	}

    echo "</table></form></div>\n";
    @mysql_close($dbc);

    $_SESSION['commentacks'] = $idarray;
 ?>
    </div>
</body>
</html>


