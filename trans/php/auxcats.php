<?php

session_start();
//include('checksession.php');
//CheckSession();
	
require_once("DBConnectInfo.php");
include_once("specialchars.php");

$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
mysql_select_db (DBDATABASE);

ob_start();
include('form_auxcats.php');
$formfile = ob_get_contents();
ob_end_clean();

include('auxcategory.php');
$formob = new AuxCategory();
$selectstring = $formob->queryauxcategories($dbc);

/*		
	$sql = "";
	
	if ($formob->mfn == 0)
	{
		// New form
		$sql = "select max(classmark_id) from classmarks";
		$res = @mysql_query($sql, $dbc);
		if ($res)
		{
			$row = mysql_fetch_array($res, MYSQL_NUM);
			$formob->mfn = $row[0] + 1;
			mysql_free_result($res);
		}
		$sqlarray = array();
		$errors = array();
		$formob->GetInsertSQL($sqlarray, $errors, $dbc);
		
		echo "SQL:<br>\n";
		$formob->DumpVars($sqlarray, "SQL");

		echo "\nErrors:<br>\n";
		$formob->DumpVars($errors, "Errors");
	}
	else
	{
		$sqlarray = array();
		$errors = array();		
		$sql = $formob->GetUpdateSQL($sqlarray, $errors, $dbc);
		echo "SQL:<br>\n";
		$formob->DumpVars($sqlarray, "SQL");
		
		echo "\nErrors:<br>\n";
		$formob->DumpVars($errors, "Errors");
	}
*/

$formob->setformvars($formfile);

echo $formfile;

if (isset($_POST['getgroups']))
{
	if (isset($_POST['auxgroups']))
	{
		//echo "AuxGroups set: " .  $_POST['auxgroups'] . "<br>\n";
	}
}

if (isset($_POST['addgroup']))
{
	//echo "Group Adder<br>\n";
	
	$groupname = "";
	if (isset($_POST['newgroupname']))
	{
		$groupname = trim($_POST['newgroupname']);
	}
	
	//echo "Got: " . $groupname . "<br>\n";
	
	if ($groupname != "")
	{
		$sql = "select count(aux_cat_id) from aux_categories where aux_cat_name = '" . @mysql_real_escape_string($groupname) . "'";
		//echo $sql . "<br>\n";
		
		$res = @mysql_query($sql, $dbc);
		
		//echo "Done<br>\n";
		
		$count = 0;
		if($row = mysql_fetch_array($res, MYSQL_NUM))
			$count = $row[0];
		
		mysql_free_result($res);		
			
		if ($count == 0)
		{
			$sql = "select max(aux_cat_id) from aux_categories";
			//echo $sql . "<br>\n";
			
			$res = @mysql_query($sql, $dbc);
			
			//echo "Done<br>\n";
			
			$nextid = 0;
			
			if(($row = mysql_fetch_array($res, MYSQL_NUM)))
			{
				$nextid = $row[0] + 1;
			}
			
			if ($nextid == 0)
				$nextid = 1;
				
			mysql_free_result($res);		
			
			$sql = "insert into aux_categories (aux_cat_id, aux_cat_name) values (" . $nextid . ", '" . @mysql_real_escape_string($groupname) . "')";
			//echo $sql . "<br>\n";
			@mysql_query($sql, $dbc);
			
			//echo "Done<br>\n";
			
		}
		else
		{
			//echo "There are " . $count . " groups with this name";
		}
		
		header("Location: auxcats.php");
	}
}

//print_r ($_POST);
?>


