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
Delete a user providing its id and its associated account id.
A user is a row in the users SQL table.
*/
include_once(__DIR__.'/../get_db.php');

function delete_user($account_id_arg, $user_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$user_id = (int)$user_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM  '.TABLE_USERS.'  
		 WHERE id=:user_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	
	return $isgood;
}