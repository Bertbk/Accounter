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
Returns every payments of a bill with id $bill_id_arg of the account of id $account_id_arg.
A participant is here a row in the paymentss SQL table 
JOINED WITH: payer name and color, and receiver name/color (participants SQL table).

Warning: a payment points to a bill_participant, not to a participant.
*/

include_once(__DIR__.'/../get_db.php');

function get_payments_by_bill_id($account_id_arg, $bill_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;

	$reply = array();
	try
	{
		$myquery = 'SELECT '.TABLE_PAYMENTS.'.*, 
		bill_part1.participant_id AS real_payer_id,
		bill_part2.participant_id AS real_recv_id,
		contribs1.name AS payer_name, contribs1.color AS payer_color,
		contribs2.name AS receiver_name, contribs2.color AS receiver_color
		FROM '.TABLE_PAYMENTS.' 
		LEFT  JOIN '.TABLE_BILL_PARTICIPANTS.' bill_part1 ON bill_part1.id='.TABLE_PAYMENTS.'.payer_id 
		LEFT  JOIN '.TABLE_BILL_PARTICIPANTS.' bill_part2 ON bill_part2.id='.TABLE_PAYMENTS.'.receiver_id
		LEFT  JOIN '.TABLE_PARTICIPANTS.' contribs1 ON contribs1.id=bill_part1.participant_id 
		LEFT  JOIN '.TABLE_PARTICIPANTS.' contribs2 ON contribs2.id=bill_part2.participant_id
		WHERE '.TABLE_PAYMENTS.'.account_id=:account_id AND '.TABLE_PAYMENTS.'.bill_id=:bill_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
	}
	catch (Exception $e)
	{
//		echo 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	return $reply;
	
}
