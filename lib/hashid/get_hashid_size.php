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
Return the size of the hashid used in the software.

*/
function get_openssl_size()
{
	return 8;
}

function get_hashid_size()
{
	return (get_openssl_size()*2);
}