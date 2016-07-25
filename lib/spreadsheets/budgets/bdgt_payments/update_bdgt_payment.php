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
Updates a payment providing its hashid and the associated account id.
A participant is here a row in the paymentss SQL table 

Warning: a payment points to a spreadsheet_participant, not to a participant.
*/
include_once(__DIR__.'/../../../get_db.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_payments/get_bdgt_payment_by_id.php');


function update_bdgt_payment($account_id_arg, $spreadsheet_id_arg, $payment_id_arg, $creditor_id_arg, $amount_arg, 
			$debtor_id_arg, $description_arg, $date_of_payment_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	$payment_id = (int)$payment_id_arg;
	$new_creditor_id = (int)$creditor_id_arg;
	$new_amount = (float)$amount_arg;
	$new_debtor_id = (is_null($debtor_id_arg)||empty($debtor_id_arg))?null:(int)$debtor_id_arg;
	$new_description = $description_arg;
	$new_date_of_payment = $date_of_payment_arg;

	$new_debtor_id = empty($new_debtor_id) ? null:$new_debtor_id;
	$new_description = empty($new_description) ? null:$new_description;
	$new_date_of_payment = empty($new_date_of_payment) ? null:$new_date_of_payment;
	
	if($new_debtor_id == -1)
	{		$new_debtor_id=null;	}
	
	//Get current payment
	$payment_to_edit = get_bdgt_payment_by_id($account_id, $payment_id);
	if(empty($payment_to_edit))
	{		return false;	}
	
	//Change style of date to match sql
	if(!is_null($new_date_of_payment))
	{
		$date_parsed = date_parse($new_date_of_payment);
		if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
			$new_date_of_payment = null;
		}
	}
	
	if($new_creditor_id == $new_debtor_id)
	{		return false;	}
	
	//Check if nothing to do
	if($new_creditor_id == $payment_to_edit['creditor_id']
	&& $new_amount == $payment_to_edit['amount']
	&& $new_debtor_id == $payment_to_edit['debtor_id']
	&& $new_description == $payment_to_edit['description']
	&& $new_date_of_payment == $payment_to_edit['date_of_payment']
	)
	{
		return true;
	}
	
	
	$isgood= false;
	try
	{
		$myquery = 'UPDATE '.TABLE_BDGT_PAYMENTS.' 
		SET creditor_id=:new_creditor_id, amount=:new_amount, debtor_id=:new_debtor_id, 
		description=:new_description, date_of_payment=:new_date_of_payment 
		WHERE id=:payment_id AND account_id=:account_id AND spreadsheet_id=:spreadsheet_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_creditor_id', $new_creditor_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':new_amount', $new_amount, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_debtor_id', $new_debtor_id, ((is_null($new_debtor_id))?(PDO::PARAM_NULL):(PDO::PARAM_INT)));
		$prepare_query->bindValue(':new_description', $new_description, ((is_null($new_description))?(PDO::PARAM_NULL):(PDO::PARAM_STR)));
		$prepare_query->bindValue(':new_date_of_payment', $new_date_of_payment, ((is_null($new_date_of_payment))?(PDO::PARAM_NULL):(PDO::PARAM_STR)));
		$prepare_query->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return false;
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
