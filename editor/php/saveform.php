<?php

include_once('udcform.php');

function SaveForm($formvars)
{
	if ($formvars->mfn == 0)
	{
		// New form
		$sql = $formvars->GetInsertSQL();
	}
	else
	{
		$formvars->GetUpdateSQL();
	}
	
	
}


?>