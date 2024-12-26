<?php
    session_start();
    include_once("checksession.php");
    checksession();
    
    if (isset($_SESSION['userrole']))
    {
        if ($_SESSION['userrole'] != 2)
        {
            header("Location: logout.php");
        }
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title>UDC PE Translator</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="../udc1000.css" type="text/css">
</head>

<body>
	<div id="maincontainer">
        <div id="titleimagecontainer">&nbsp;</div>
		<div id="logincontainer">
		    <form name="form1" method="post" action="setlogin.php">
		        <div class="loginlabel">Name</div><div style="text-align: right"><input id="userid" name="userid" type="text" class="loginfield" size="37.75" maxlength="35"></div>
		        <div class="loginlabel">Password</div><div style="text-align: right"><input id="password" name="password" type="text" class="loginfield" size="37.75" maxlength="35"></div>
		        <div class="loginlabel">Role</div><div style="text-align: right">
		        <select id="usertype" name="usertype">
		        <option value="1" selected>Normal</option>
		        <option value="2">Editor</option>
				</select></div>
		        <div class="loginlabel">Language</div><div style="text-align: right">
		        <select id="defaultlanguage" name="defaultlanguage">

                <?php
					require_once("DBConnectInfo.php");

					$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
					@mysql_select_db (DBDATABASE);
		            @mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);

		            $languages = array();

					// Get the language options for the menu
					$sql = "select language_id, description from language order by description";
					$res = mysql_query($sql, $dbc);
			        if ($res)
			        {
						while($row = @mysql_fetch_array($res, MYSQL_NUM))
						{
                            echo "<option value=\"" . $row[0] . "\"";
                            if ($firstrow)
                            {
                                echo " selected";
                            } 
                            
                            echo ">";
                            echo $row[1];
                            echo "</option>\n";
						}
			    		@mysql_free_result($res);
			        }
                    
                    @mysql_close($dbc);
                ?>
                
				</select></div>
		        <input class="loginsubmit" name="Submit" type="submit" value="Submit">
		    </form>
		</div>
	</div>
</body>
</html>
