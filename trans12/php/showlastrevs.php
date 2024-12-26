<?php

    session_start();

    $date = $_GET['auditdate'];
    $op = $_GET['operation'];
    
    if (!isset($_SESSION['showlastrevs']))
    {
        $_SESSION['showlastrevs'] = "";
    }
    
    if ($op == "true")
    {
    	$_SESSION['showlastrevs'] = $date;
    }
    else
    {
    	$_SESSION['showlastrevs'] = '';
    }
    
?>