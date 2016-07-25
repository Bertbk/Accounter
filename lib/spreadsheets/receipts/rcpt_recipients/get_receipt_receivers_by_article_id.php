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
Return an array of the receipt_receivers of an article
joined with data of the associated participant (name, Color, hashid)
*/
include_once(__DIR__.'/../get_db.php');

function get_receipt_receivers_by_article_id($account_id_arg, $receipt_id_arg, $article_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$receipt_id = (int)$receipt_id_arg;
	$article_id = (int)$article_id_arg;
	
	$reply = array();

	try
	{
		$myquery = 'SELECT '.TABLE_RECEIPT_RECEIVERS.'.*, '.TABLE_PARTICIPANTS.'.name AS name, 
		'.TABLE_PARTICIPANTS.'.nb_of_people AS nb_of_people, 
		'.TABLE_PARTICIPANTS.'.color AS color,
		'.TABLE_PARTICIPANTS.'.hashid AS participant_hashid
		FROM '.TABLE_RECEIPT_RECEIVERS.'  
		LEFT JOIN '.TABLE_PARTICIPANTS.' ON '.TABLE_PARTICIPANTS.'.id='.TABLE_RECEIPT_RECEIVERS.'.participant_id 
		WHERE '.TABLE_RECEIPT_RECEIVERS.'.account_id=:account_id AND '.TABLE_RECEIPT_RECEIVERS.'.receipt_id=:receipt_id 
		AND '.TABLE_RECEIPT_RECEIVERS.'.article_id=:article_id' ;
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':receipt_id', $receipt_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		return 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	
	return $reply;
}
