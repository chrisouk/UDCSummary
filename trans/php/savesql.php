<?php
    session_cache_expire("120");
    session_start();
    require_once("checksession.php");
    checksession();
    
/**
 * @author Chris Overfield
 * @copyright 2009
 */

function CheckCommentsNotification($formob)
{
	if (!isset($_SESSION['oldeditorcomments']) || $formob->editor_comments != $_SESSION['oldeditorcomments'])
	{
		$subject = "Translation editor comment: " . $formob->notation . " by " . ucfirst($_SESSION['userid']);
		$emailbody = "Class: " . $formob->notation . "  " . $formob->caption . "\n\n";
		$emailbody .= "New: " . $formob->editor_comments . "\n\n";
		$emailbody .= "Old: " . $_SESSION['oldeditorcomments'] . "\n\n";

		$emailbody = wordwrap($emailbody, 70);
	
		//echo $mailstring . "<br>\n";
		$headers = 'From: udcs@udcc.org' . "\r\n" .
				   'Reply-To: udcs@udcc.org' . "\r\n" .
				   'X-Mailer: PHP/' . phpversion();
	
		//echo $subject . "<br>\n";
		//echo $emailbody . "<br>\n";
		//mail("udcs@udcc.org", $subject, $emailbody, $headers);	
	}	
}

function SaveSQL($sqlarray, UDCForm &$formob, $dbc, $dummy = false)
{
	// Save the SQL
	$error = "";
	
	if (!$dummy && !mysql_query("BEGIN", $dbc))
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
				
			if (!$dummy && !mysql_query($sql, $dbc))
			{
				$error = mysql_error($dbc);
				array_push($formob->validation_errors, "SQL Error: " . $error);
				if (!mysql_query("ROLLBACK", $dbc))
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
					# Check to see if we need to send a comments email
					//echo "OK: " . $sql . "<br>\n";
				}
			}
		}
	}
		
	$returnvalue = false;		
	if ($error == "")
	{
		if (!$dummy && !mysql_query("COMMIT", $dbc))
		{
			array_push($formob->validation_errors, "Failed to commit SQL to database"); 
		}
		mysql_query("COMMIT");
		
		//echo "Checking for comments<br>\n";
		//CheckCommentsNotification($formob);
        
        $statsarray = array();
        $formob->UpdateFieldStats($statsarray);
        
		foreach($statsarray as $i => $sql)
		{
			//if ($dummy)
				//echo $sql . "<br>\n";
				
			if (!$dummy && !mysql_query($sql, $dbc))
			{
				$error = mysql_error($dbc);
				array_push($formob->validation_errors, "SQL Error: " . $error);
				if (!mysql_query("ROLLBACK", $dbc))
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
					# Check to see if we need to send a comments email
					//echo "OK: " . $sql . "<br>\n";
				}
			}
		}
                
        mysql_query("COMMIT");
        
		$returnvalue = true;
	}
	else
	{
		mysql_query("ROLLBACK");
	}
	
	return $returnvalue;	
}


?>