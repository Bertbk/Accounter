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
Returns every articles of a receipt with id $receipt_id_arg of the account of id $account_id_arg.
A participant is here a row in the articless SQL table 
JOINED WITH: payer name and color, and receiver name/color (participants SQL table).

Warning: a article points to a receipt_participant, not to a participant.
*/

include_once(__DIR__.'/../get_db.php');

function get_articles_by_receipt_id($account_id_arg, $receipt_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$receipt_id = (int)$receipt_id_arg;

	$reply = array();
	try
	{
		$myquery = 'SELECT * FROM '.TABLE_RECEIPT_ARTICLES.' 
		WHERE account_id=:account_id AND receipt_id=:receipt_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':receipt_id', $receipt_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
	}
	catch (Exception $e)
	{
		return 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	return $reply;
	
}
