<?php

/**
 * @author Chris Overfield
 * @copyright 2009
 */

function SaveSQL($sqlarray, &$formob, $dbc, $dummy = false)
{
	// Save the SQL
	$error = "";
	
	if (!$dummy && !@mysql_query("BEGIN", $dbc))
	{
		// Begin a transaction
		$error = "Failed to begin a database transaction";
	}
	else 
	{
		foreach($sqlarray as $i => $sql)
		{
			if ($dummy)
				echo $sql . "<br>\n";
				
			if (!$dummy && !@mysql_query($sql, $dbc))
			{
				$error = @mysql_error($dbc);
				array_push($formob->validation_errors, "SQL Error: " . $error);
				if (!@mysql_query("ROLLBACK", $dbc))
				{
					array_push($formob->validation_errors, "Failed to rollback SQL!!"); 
				}				
				echo "BAD: " . $sql . "(". $error . ")<br>\n";
				break; 					
			}
			else
			{
				if (!$dummy)
				{
					//echo "OK: " . $sql . "<br>\n";
				}
			}
		}
	}
				
	if ($error == "")
	{
		if (!$dummy && !@mysql_query("COMMIT", $dbc))
		{
			array_push($formob->validation_errors, "Failed to commit SQL to database"); 
		}
		@mysql_query("COMMIT");
	}
	else
	{
		@mysql_query("ROLLBACK");
	}
	
}


?>