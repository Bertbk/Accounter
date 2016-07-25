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
Return a payment providing its id and the account id.
A participant is here a row in the paymentss SQL table 

Warning: a payment points to a bill_participant, not to a participant.
*/

include_once(__DIR__.'/../get_db.php');

function get_payment_by_id($account_id_arg, $payment_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payment_id = (int)$payment_id_arg;
	
	try
	{
		$myquery = 'SELECT * FROM '.TABLE_PAYMENTS.'
		WHERE account_id=:account_id AND id=:payment_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':payment_id', $payment_id, PDO::PARAM_STR);
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
