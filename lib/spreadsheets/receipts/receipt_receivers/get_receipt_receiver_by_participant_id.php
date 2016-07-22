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
Return the receipt_receiver providing its id.
A receipt_receiver is a rown in receipt_receivers SQL table
*/

include_once(__DIR__.'/../get_db.php');

function get_receipt_receiver_by_participant_id($account_id_arg, $receipt_id_arg, $article_id_arg, $participant_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$receipt_id = (int)$receipt_id_arg;
	$article_id = (int)$article_id_arg;
	$participant_id = (int)$participant_id_arg;

	try
	{
		$myquery = 'SELECT * FROM '.TABLE_RECEIPT_RECEIVERS.' 
		 WHERE account_id=:account_id AND participant_id=:participant_id
		 AND receipt_id=:receipt_id AND article_id=:article_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':participant_id', $participant_id, PDO::PARAM_INT);
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
	if(!empty($reply))
	{
		return $reply[0];
	}
	else
	{
		return array();
	}
}
