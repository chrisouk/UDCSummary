 <?php

    session_start();

    unset($_SESSION['submenu']);
    
    require_once("DBConnectInfo.php");
    include_once("specialchars.php");
    	
    $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
    mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    
    $notation = "";
    if (isset($_GET['notation']))
    {
    	$notation=$_GET['notation'];
    }	
    
    $sql = "select classmark_tag from classmarks where broader_category = (select classmark_id from classmarks where classmark_tag = '" . $notation . "' and active='Y') order by classmark_enc_tag";
    $res = @mysql_query($sql, $dbc);
    
    $rowcount=0;
    $resultcount = mysql_num_rows($res);
    if ($resultcount > 0)
    {
		$returnstring = "&nbsp;<select class=\"menuchoice\" id=\"menuchoiceselect\" onchange=\"browsetree(this.value, false, false, false); return false;\">";

		$dir = (isset($_SESSION['rtl']) && $_SESSION['rtl'] == true ? "right" : "left");
    	while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    	{
            $not = $row[0];
			$returnstring .= "<option value=\"" . urlencode($not) . "\"><div style=\"display: inline-block; unicode-bidi: bidi-override; direction: ltr\">" . $not . "</div></option>\n";
    	}

		$returnstring .= "</select>\n";
    }
    else
    {
    	$errorstring = "*[" . $category . "] is not a valid notation*";
    }
    
    mysql_free_result($res);
    
    $_SESSION['menuchoice'] = $notation;
    $_SESSION['submenu'] = $returnstring;
    
    $returnstring = urlencode($returnstring);

    echo $returnstring;
?>