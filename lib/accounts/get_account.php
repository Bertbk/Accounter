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
Lib: Return the account of the particular (public) hashid .
The returned object contained all the parameter of the row of the accounts SQL table
 */
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');

function get_account($hash_id_arg)
{
	
	if(validate_hashid($hash_id_arg)== false)
	{
		return array();
	}
	$hash_id = $hash_id_arg;
	
	$db = get_db();
	
	try
	{
		$myquery = 'SELECT * FROM '.TABLE_ACCOUNTS.' 
		 WHERE hashid=:hash_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hash_id', $hash_id, PDO::PARAM_STR);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}

	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	
	if(is_array($reply) && sizeof($reply) > 0)
	{
		return $reply[0];
	}
	else
	{
		return array();
	}
}
