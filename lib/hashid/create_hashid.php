<?php
include_once(__DIR__.'/get_hashid_size.php');

function create_hashid()
{
	$my_size = get_openssl_size();
	$cpt = 0;
	$cpt_max= 10000;
	//Build first hashid
	do {
		$hashid = bin2hex(openssl_random_pseudo_bytes($my_size));
		$cpt ++;
	}
	while(!$hashid && $cpt < $cpt_max);

	if($cpt == $cpt_max)
	{
		return null;
	}
	else
	{
		return $hashid;
	}
}