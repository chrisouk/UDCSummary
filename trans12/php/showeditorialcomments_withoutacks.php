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
    <div id="pagecontainer">
        <div id="titleimagecontainer">&nbsp;</div>
<?php
	require_once("DBConnectInfo.php");
	include_once("specialchars.php");

    define("DUMMYINSERT", false);
    
	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");

    $post_reviewer = "All";
    if (isset($_POST['commentssubmit']))
    {
        $post_reviewer = $_POST['reviewer'];
    }
               
    $reviewers = array();
    
    $sql = "select reviewer, count(*) from udct_editor_comments group by reviewer";
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
        echo "<span style=\"width: 100px; float: left; text-align: left; \">&nbsp;<a href=\"edittag.php\">Back to Editor</a>&nbsp;</span><select name=\"reviewer\" id=\"reviewer\" class=\"combobox\">\n";
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
        
        echo "</select> <input type=\"submit\" name=\"commentssubmit\" value=\"Go\">&nbsp;</form></div>\n";
    }
    
    $sql = "select c.classmark_tag, u.reviewer, u.comments, DATE_FORMAT(u.date_changed, '%d-%m-%Y %H:%i:%S') from udct_editor_comments u join classmarks c on c.classmark_id = u.classmark_id ";
    if ($post_reviewer != "All")
    {
        $sql .= " where reviewer = '" . $post_reviewer . "'"; 
    }
    $sql .= " order by date_changed desc";
    //echo $sql . "<br>\n";
    
    echo "<div id=\"commentsdiv\">\n";
    echo "<table width=\"940\" border=\"0\" bgcolor=\"#efefef\" cellpadding=\"2\" cellspacing=\"1\">\n";
    echo "<tr><td width=\"20%\">Notation</td><td width=\"20%\">Reviewer</td><td width=\"40%\">Comment</td><td width=\"20%\">Date</td></tr>\n";
	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
		while(($row = @mysql_fetch_array($res, MYSQL_NUM)))
		{
            echo "<tr>";
            echo "<td bgcolor=\"white\"><a href=\"edittag.php?tag=" . $row[0] . "&resetsearch=Y\">". $row[0] . "</a></td>";
            echo "<td bgcolor=\"white\">". $row[1] . "</td>";
            echo "<td bgcolor=\"white\">". html_entity_decode($row[2], ENT_NOQUOTES) . "</td>";
            echo "<td bgcolor=\"white\">". $row[3] . "</td>";
            echo "</tr>\n";
		}
		@mysql_free_result($res);
	}

    echo "</table></div>\n";        
    @mysql_close($dbc);
 ?>
    </div>
</body>
</html>


