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
If exists, returns the budget_participant from its account_id, spreadsheet_id and member_id
*/
include_once(__DIR__.'/../../../get_db.php');

function get_bdgt_participant_by_member_id($account_id_arg, $bdgt_id_arg, $member_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bdgt_id = (int)$bdgt_id_arg;
	$member_id = (int)$member_id_arg;
	
	$reply = array();

	try
	{
		$myquery = 'SELECT '.TABLE_BDGT_PARTICIPANTS.' 
		FROM '.TABLE_BDGT_PARTICIPANTS.'  
		WHERE account_id=:account_id AND bdgt_id=:bdgt_id AND member_id=:member_id' ;
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bdgt_id', $bdgt_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':member_id', $member_id, PDO::PARAM_INT);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		return array();
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	
	return $reply;
}
