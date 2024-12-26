<?php

/**
 * @author Chris Overfield
 * @copyright 2009
 */

function CheckAuxTag($notation)
{
    if (strlen($notation) > 1)
    {
        if (substr($notation, 0, 2) == "--")
        {
            return "";
        }
    }
    
    return $notation;
}

?>