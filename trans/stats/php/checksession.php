<?php
    $_SESSION['expiry'] = session_cache_expire("120");
	session_start();

    function checksession()
    {
    	if(!isset($_SESSION['userid']) || trim($_SESSION['userid']) == "")
    	{
            session_destroy();
    		header("Location: ../../login.htm");
    	}

    	if(!isset($_SESSION['deflang']) || trim($_SESSION['deflang']) == "")
    	{
            session_destroy();
    		header("Location: ../../login.htm");
    	}
    }


?>