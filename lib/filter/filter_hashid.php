<?php
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/hashid/validate_hashid.php');


function filter_valid_hashid($hashid_arg)
{
	$hashid = htmlspecialchars($hashid_arg);
	$isgood = validate_hashid($hashid);
	if($isgood)
	{
		return $hashid;
	}
	else
	{
		return false;
	}
}