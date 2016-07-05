<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 
/*
Returns a hashid of size fixed by get_hashid_size();

*/
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