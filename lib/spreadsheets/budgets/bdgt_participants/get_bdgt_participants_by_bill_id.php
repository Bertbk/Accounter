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
Return an array of the bdgt_participants of a bdgt (from the bdgt id)
joined with data of the associated member (name, color, hashid)
*/
include_once(__DIR__.'/../../../get_db.php');

function get_bdgt_participants_by_bdgt_id($account_id_arg, $bdgt_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bdgt_id = (int)$bdgt_id_arg;
	
	$reply = array();

	try
	{
		$myquery = 'SELECT '.TABLE_BDGT_PARTICIPANTS.'.*, '.TABLE_MEMBERS.'.name AS name, 
		'.TABLE_MEMBERS.'.nb_of_people AS nb_of_people, 
		'.TABLE_MEMBERS.'.color AS color,
		'.TABLE_MEMBERS.'.hashid AS participant_hashid
		FROM '.TABLE_BDGT_PARTICIPANTS.'  
		LEFT JOIN '.TABLE_MEMBERS.' ON '.TABLE_MEMBERS.'.id='.TABLE_BDGT_PARTICIPANTS.'.participant_id 
		WHERE '.TABLE_BDGT_PARTICIPANTS.'.account_id=:account_id AND '.TABLE_BDGT_PARTICIPANTS.'.bdgt_id=:bdgt_id' ;
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bdgt_id', $bdgt_id, PDO::PARAM_INT);
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
