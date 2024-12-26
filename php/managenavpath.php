<?php

/**
 * @author Chris Overfield
 * @copyright 2009
 */
 
function GetNavPathString($rtl)
{
    $navpath = "";
    $navpos = 0;
    
    if (isset($_SESSION['navpath']))
    {
        $navpath = $_SESSION['navpath'];
    }    

    if (isset($_SESSION['navpos']))
    {
        $navpos = $_SESSION['navpos'];
    }    

    $pathelements = explode("#", $navpath);
    if (count($pathelements) == 1)
    {
        return "";
    }
    
    $navstring = "<div id=\"navpath\">";
    
    $elementcount = count($pathelements);
    for ($i=0; $i<$elementcount; $i++)
    {
        # Add individual path elements
        if ($i > 0)
        {
            # $navstring .= " <div class=\"pathelement\"><img src=\"../images/arrow.gif\" border=\"0\"></div> ";
            $navstring .= " <div class=\"pathelement\" style=\"color:#999999; line-height: 1.3em\">";
			if ($rtl)
			{
				$navstring .= "<";
			}
			else
			{
				$navstring .= ">";
			}

			$navstring .= "</div> ";
        }
        
        $navstyle = "simplenav";
        if ($i == $navpos)
        {
            $navstyle = "currentnav";
        }

        $navstring .= " <div class=\"pathelement\"><a class=\"" . $navstyle . "\" href=\"javascript:openrecord('" . urlencode($pathelements[$i]) . "', " . $i . ", false, '";
		if ($rtl == true)
			$navstring .= "Y";
		else
			$navstring .= "N";

		$navstring .= "')\">" . $pathelements[$i] . "</a></div> ";
    }
       
    $navstring .= "<div class=\"nextprevnav\">";
    if ($navpos < $elementcount-1)
    {
        $navstring .= " <div class=\"pathnextprev\"><a class=\"" . $navstyle . "\" href=\"javascript:openrecord('" . urlencode($pathelements[$navpos+1]) . "', " . ($navpos+1) . 
                      ", false, '";

		if ($rtl == true)
			$navstring .= "Y";
		else
			$navstring .= "N";

		$navstring .= "')\"><img src=\"../images/next.jpg\" border=\"0\"></a></div> ";
    }

    if ($navpos > 0)
    {
        $navstring .= " <div class=\"pathnextprev\"><a class=\"" . $navstyle . "\" href=\"javascript:openrecord('" . urlencode($pathelements[$navpos-1]) . "', " . ($navpos-1) . 
                      ", false, '";
		if ($rtl == true)
			$navstring .= "Y";
		else
			$navstring .= "N";

		$navstring .= "')\"><img src=\"../images/prev.jpg\" border=\"0\"></a></div> ";
    }
    
    $navstring .= "</div>";
    $navstring .= "</div>";
    
    return $navstring;
} 

function ManageNavPath($navcount, $navpos)
{
    if ($navpos < 0 || $navpos >= $navcount)
    {
        return;
    }
    
    $_SESSION['navpos'] = $navpos;
}

function AddNavPath($id, $navpath, $navpos)
{
    $navpatharray = explode("#", $navpath);
    $newnavarray = array();
    
    for ($i=0; $i<=$navpos; $i++)
    {
        array_push($newnavarray, $navpatharray[$i]);
    }
    
    array_push($newnavarray, $id);
    
    return implode("#", $newnavarray);
}

?>
