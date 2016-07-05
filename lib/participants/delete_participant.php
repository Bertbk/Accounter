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
Delete a participant providing its id and its associated account id.
A participant is a row in the participants SQL table.
*/
include_once(__DIR__.'/../get_db.php');

function delete_participant($account_id_arg, $participant_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$participant_id = (int)$participant_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM  '.TABLE_PARTICIPANTS.'  
		 WHERE id=:participant_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':participant_id', $participant_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	
	return $isgood;
}