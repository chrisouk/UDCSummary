<?php

/**
 * @author Chris Overfield
 * @copyright 2009
 */

function GetLanguageValues($desc, $lang, &$fielddesc)
{
    $fielddesc = "";
    $fieldlang = 1;
    $splitvalues = explode("~", $desc);
    if (count($splitvalues) > 0)
    {
        $fieldlang = $splitvalues[0];
        $fielddesc = $splitvalues[1];
        //echo $fieldlang . "|" . $fielddesc . "\n";
    }

    if ($lang == $fieldlang)
    {
        //echo "|true<br>\n";
        return true;
    }
    else
    {
        //echo "|false<br>\n";
        return false;
    }
}

?>