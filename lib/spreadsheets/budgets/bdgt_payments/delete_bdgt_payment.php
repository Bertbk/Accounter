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
Deletes a payment providing its id and the associated account id.
A participant is here a row in the paymentss SQL table 

Warning: a payment points to a bill_participant, not to a participant.
*/
include_once(__DIR__.'/../../../get_db.php');

function delete_bdgt_payment($account_id_arg, $payment_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payment_id = (int)$payment_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM '.TABLE_BDGT_PAYMENTS.' 
		WHERE id=:payment_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
	
	return $isgood;
}