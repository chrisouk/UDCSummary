<?php

	/**
	 * acc.php
	 *
	 * @author: Chris Overfield
	 * @copyright 2012
	 */

	echo "Clearing cache<br>\n";
	$status = "Successfully";
	if (!apc_clear_cache())
	{
		$status = "Failed to";
	}
	echo $status . " clear cache<br>\n";

?>