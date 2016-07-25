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
Create a payment, ie a row in the paymentss SQL table 

Warning: a payment points to a spreadsheet_participant, not to a participant.
*/
include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participant_by_id.php');

function set_payment($account_id_arg, $hashid_arg, $spreadsheet_id_arg, $creditor_id_arg, $amount_arg, $debtor_id_arg, $description_arg, $date_of_payment_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	$creditor_id = (int)$creditor_id_arg;
	$amount = (float)$amount_arg;
	$debtor_id = (is_null($debtor_id_arg)||empty($debtor_id_arg))?null:(int)$debtor_id_arg;
	$description = $description_arg;
	$date_of_payment = $date_of_payment_arg;
	
	$debtor_id = empty($debtor_id) ? null:$debtor_id;
	$description = empty($description) ? null:$description;
	$date_of_payment = empty($date_of_payment) ? null:$date_of_payment;
	
	//Change style of date to match sql
	if(!is_null($date_of_payment))
	{
		$date_parsed = date_parse($date_of_payment);
		if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
			$date_of_payment = null;
		}
	}
	
	if(!validate_hashid($hashid))
	{return false;}
	
//	if($debtor_id == -1)
	// {		$debtor_id = null;	}

	if($creditor_id === $debtor_id)
	{
		return false;
	}
	
	if((float)$amount == (float)0)
	{return false;}
	
	$creditor = get_bdgt_participant_by_id($account_id, $creditor_id);
	if($creditor['spreadsheet_id'] != $spreadsheet_id )
	{return false;}
	if(!is_null($debtor_id))
	{
		$debtor = get_bdgt_participant_by_id($account_id, $debtor_id);
		if($debtor['spreadsheet_id'] != $spreadsheet_id )
		{return false;}
	}
	
	$isgood = false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_BDGT_PAYMENTS.' (id, hashid, account_id, spreadsheet_id, creditor_id, amount, debtor_id, description, date_of_payment) 
		VALUES(NULL, :hashid, :account_id, :spreadsheet_id, :creditor_id, :amount, :debtor_id, :description, :date_of_payment)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':creditor_id', $creditor_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':amount', $amount, PDO::PARAM_STR);
		$prepare_query->bindValue(':debtor_id', $debtor_id, is_null($debtor_id)?(PDO::PARAM_NULL):(PDO::PARAM_INT));
		$prepare_query->bindValue(':description', $description, is_null($description)?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':date_of_payment', $date_of_payment, is_null($date_of_payment)?(PDO::PARAM_NULL):(PDO::PARAM_STR));
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
