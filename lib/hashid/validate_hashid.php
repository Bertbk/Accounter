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
Validate by regular expression a hashid or a hashid used for admin link

*/
include_once(__DIR__.'/get_hashid_size.php');

function validate_hashid($hashid_arg)
{
	$hashid = $hashid_arg;
	$my_size = (int)get_hashid_size();
	
	return preg_match("/^[a-z0-9]{".$my_size."}$/", $hashid);
}

function validate_hashid_admin($hashid_arg)
{
	$hashid = $hashid_arg;
	$my_size = 2*(int)get_hashid_size();
	
	return preg_match("/^[a-z0-9]{".$my_size."}$/", $hashid);
}