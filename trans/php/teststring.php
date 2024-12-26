<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    $test = ""; 
    if(isset($_GET['test']))
    {
        $test = $_GET['test'];
    }
    
    echo "Received [" . $test . "]<br>\n";
    
    return urlencode($test);
?>