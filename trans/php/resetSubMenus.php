<?php

    session_start();
    
    $notation = "";
    if (isset($_GET['notation']))
        $notation = urldecode($_GET['notation']);

    unset($_SESSION['submenu']);
        
    $_SESSION['menuchoice'] = $notation;
    unset($_SESSION['search_results']);
    $notation = str_replace("+", "$$1$$", $notation);
    echo $notation;
    
?>