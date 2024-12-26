<?php

function ResetLevels(&$arr, $startlevel, $defaultlevel)
{
	foreach($arr as $key => $value)
	{
		if ($key > $startlevel)
		{
			$value = $defaultlevel;
		}
	}
}

?>