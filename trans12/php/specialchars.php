<?php

	function specialchars($str)
	{
		return $str;
		$newstr = ereg_replace("®","«", $str);
		$newstr = ereg_replace("¯","»", $newstr);
		return $newstr;
	}
	
	function specialcharstree($str)
	{
		return $str;
		$newstr = ereg_replace("®","«", $str);
		$newstr = ereg_replace("¯","»", $newstr);
		$newstr = ereg_replace("'","\'", $newstr);
		return $newstr;
	}
	
?>