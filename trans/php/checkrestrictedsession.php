<?php

function checkrestrictedsession()
{
	if(!isset($_SESSION['userid']))
	{
		header("Location: ../login.htm");
        exit();
	}
    
    $userid = trim($_SESSION['userid']);
    if ($userid == "")
    {
        header("Location: ../login.htm");
        exit();
    }
    
    if (strcmp($userid, "aida") != 0 && strcmp($userid, "chris") != 0)
	{
		header("Location: ../login.htm");
        exit();
	}
}

?>