<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title>Set Translator Login Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="udc1000.css" type="text/css">
</head>

<body>
	<div id="maincontainer">
		<div id="logincontainer">
		    <form name="form1" method="post" action="php/setlogin.php">
		    <form name="form1" method="post" action="php/setlogin.php">
		        <div class="loginlabel">Name</div><div style="text-align: right"><input id="userid" name="userid" type="text" class="loginfield" maxlength="35"></div>
		        <div class="loginlabel">Password</div><div style="text-align: right"><input id="password" name="password" type="password" class="loginfield" maxlength="35"></div>
		        <div class="loginlabel">Page1 Access</div><div style="float: left; width=100px; text-align: left"><input type="checkbox" id="access_page1" name="access_page1"></div>
                <div class="loginlabel">Page2 Access</div><div style="float: left;  width=100px;text-align: left"><input type="checkbox" id="access_page1" name="access_page2"></div>
                <div class="loginlabel">Page3 Access</div><div style="float: left;  width=100px;text-align: left"><input type="checkbox" id="access_page1" name="access_page3"></div>
                <div class="loginlabel">Show RevComment</div><div style="float: left;  width=100px;text-align: left"><input type="checkbox" id="show_reviewer_comment" name="show_reviewer_comment"></div>
		        <div class="loginlabel">Revision Name</div><div style="text-align: right"><input id="revision_name" name="revision_name" type="text" class="loginfield" maxlength="16"></div>
		        <div class="loginlabel">Revision Date</div><div style="text-align: right"><input id="revision_date" name="revision_date" type="text" class="loginfield" maxlength="4"></div>
                <div class="loginlabel">Updates Allowed</div><div style="float: left;  width=100px;text-align: left"><input type="checkbox" id="updates_allowed" name="updates_allowed"></div>
		        <input class="loginsubmit" name="Submit" type="submit" value="Submit">
                <select id="defaultlanguage" name="defaultlanguage">

                <?php
					require_once("php/DBConnectInfo.php");

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
