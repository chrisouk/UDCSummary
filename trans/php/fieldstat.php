<?php

    session_start();

    $op = $_GET['operation'];
    $fieldid = $_GET['fieldid'];
    
    if (!isset($_SESSION['fieldstat']))
    {
        $_SESSION['fieldstat'] = "";
    }
    
    $sessionstat =  $_SESSION['fieldstat'];
    
    if ($op == "false")
    {
        $removestring = $fieldid . ",";
        $sessionstat = str_replace($removestring, "", $sessionstat);
        $sessionstat = str_replace($fieldid, "", $sessionstat);
    }
    else
    {
        if ($sessionstat != "")
        {
            $sessionstat .= ", " . $fieldid;
        }
        else
        {
            $sessionstat = $fieldid;
        }
    }
    
    $sessionstat = trim($sessionstat, ", ");
    $_SESSION['fieldstat'] = $sessionstat;
    
    
    echo $sessionstat;     
?>