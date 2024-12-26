<?php

	if (!isset($_SESSION))
		session_start();

	require_once("checksession.php");
    checksession();
    
/**
 * @author Chris Overfield
 * @copyright 2009
 */

function GetAjaxNotation($notation)
{
	return str_replace("\"", "%22", $notation);
}

function GetAuxiliaryNotation($delim, $notation)
{
    // Don't include anything past the closing bracket
    $auxnotationarray = explode(")", $notation, 2);
	$auxnotation = "<span class=\"auxiliary\">";
	#$auxnotation .= addslashes($delim . $auxnotationarray[0]);
	$auxnotation .= $delim . $auxnotationarray[0];
	$auxnotation .= "</span>";  
    if (count($auxnotationarray) > 1)
    {
      
        $auxnotation .= ")" . $auxnotationarray[1];
    }
    
    return $auxnotation;
}

function IsNotMinusZeroAux($aux)
{
    if (strlen($aux) > 1)
    {
        switch(substr($aux,0,2))
        {
            case "02":
            case "03":
            case "04":
            case "05":
                return true;
            default:
                return false;
        }
    }
    
    return false;
}

function GetDisplayNotation($notation, $large)
{
	return "<div class=\"leftpaneitem\"><strong>" . $notation . "</strong></div>";

	/*
    $delim = "";
    $aux = false;
    $notationarray = array();
    $recordline = "";

//    $nodetype = "largenodetag";
//    if ($large)
//    {
        $nodetype = "nodetag";
//    }
    if (strlen($notation) > 1 && substr($notation, 0, 2) == "--")
    {
        return "<span class=\"nodetag\">";
    }   
    $specialaux = false;
    if ($notation[0] < '0' || $notation[0] > '9')
    {
        $recordline = "<span class=\"nodetag\"><span class=\"specialauxiliary\">";
        $specialaux = true;
    }
   
    $minuspos = strpos($notation, "-");
    if ($minuspos != FALSE)
    {
        if ($minuspos < strlen($notation))
        {
            $auxchar = substr($notation, $minuspos+1, 1); 
            if ($auxchar >= '0' && $auxchar <= '9')
            {
                $aux = true;
                
                if (strlen($auxchar) > 1)
                {
                    if (IsNotMinusZeroAux($auxchar))
                    {
                        $aux = false;                        
                    }
                }
                
                if ($aux)
                {
            		$notationarray = explode("-", $notation, 2);
            		$delim = "-";
                }
            }
        	else
        	{
        		array_push($notationarray, $notation);
        	}		        
         }
        else
        {
        	array_push($notationarray, $notation);
        }		        
    }
	else if (strpos($notation, ".0") > 0)
	{
		$notationarray = explode(".0", $notation, 2);
		$delim = ".0";
		$aux = true;							
	}
	else if (strpos($notation, "`") > 0)
	{
		$notationarray = explode("`", $notation, 2);
		$delim = "`";
		$aux = true;
	}
	else
	{
		array_push($notationarray, $notation);
	}				

    if (!$specialaux)
    {		
	   $recordline .= "<span class=\"" . $nodetype . "\">";
    }							
	if ($aux)
	{
		if (count($notationarray) == 2)
		{
            // Don't include stuff after a closing bracket
            $auxnotation = GetAuxiliaryNotation($delim, $notationarray[1]);
			$recordline .= $notationarray[0];
            $recordline .= $auxnotation; 
		}
		else
		{
			$recordline .= $notationarray[0];
		}
	}
	else
	{
		$recordline .= $notationarray[0];
	}    
    
    if ($specialaux)
    {
        $recordline .= "</span>";
    }
//    else
//    {
//        $recordline .= "</span>";
//    }
    
    return $recordline;
	*/
}

?>