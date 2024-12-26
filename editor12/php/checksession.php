<?php

function checksession()
{
	if(!isset($_SESSION['userid']))
	{
		header("Location: ../login.htm");
	}
}

?>