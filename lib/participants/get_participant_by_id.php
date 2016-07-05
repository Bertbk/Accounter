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
Return a participant providing its id and its associated account id.
A participant is a row in the participants SQL table.
*/
include_once(__DIR__.'/../get_db.php');

function get_participant_by_id($account_id_arg, $contrib_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$contrib_id = (int)$contrib_id_arg;

	try
	{
		$myquery = 'SELECT * FROM  '.TABLE_PARTICIPANTS.' 
		 WHERE account_id=:account_id AND id=:contrib_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':contrib_id', $contrib_id, PDO::PARAM_INT);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	if(!empty($reply))
	{
		return $reply[0];
	}
	else
	{
		return array();
	}
}
