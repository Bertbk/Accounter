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
Delete a row in the bdgt_participants SQL table providing its id
*/

include_once(__DIR__.'/../../../get_db.php');

function delete_bdgt_participant($account_id_arg, $bdgt_part_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bdgt_part_id = (int)$bdgt_part_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM '.TABLE_BDGT_PARTICIPANTS.'  
		 WHERE id=:bdgt_part_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':bdgt_part_id', $bdgt_part_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	 return 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
	
}