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
Lib: Update a receipt_payer (a row in rcpt_payers Table).
return true if everything went fine
return error message or false otherwise
 */
 
include_once(__DIR__.'/../../../get_db.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payers_by_spreadsheet_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payer_by_id.php');


function update_rcpt_payer($account_id_arg, $spreadsheet_id_arg, $receipt_payer_id_arg, $percent_of_payment_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	$receipt_payer_id = (int)$receipt_payer_id_arg;
	$new_percent_of_payment = (float)$percent_of_payment_arg;
	$new_percent_of_payment = is_null($new_percent_of_payment)?100:$new_percent_of_payment;
	$new_percent_of_payment = empty($new_percent_of_payment)?100:$new_percent_of_payment;
	
	//Get current receipt_payer
	$receipt_part_to_edit = get_receipt_payer_by_id($account_id, $receipt_payer_id);
	if(empty($receipt_part_to_edit))
	{		return false;	}
	
	//Check new percentage
	if($new_percent_of_payment > 100 || $new_percent_of_payment < 0)
	{return false;}
	

	//Check if nothing to do
	if($new_percent_of_payment === $receipt_part_to_edit['percent_of_payment'])
	{		return true;}
	
	$isgood= false;
	try
	{		
		$myquery = 'UPDATE '.TABLE_RCPT_PAYERS.' 
		SET percent_of_payment=:new_percent_of_payment
		WHERE id=:receipt_payer_id AND account_id=:account_id AND spreadsheet_id=:spreadsheet_id' ;
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_percent_of_payment', $new_percent_of_payment, PDO::PARAM_STR);
		$prepare_query->bindValue(':receipt_payer_id', $receipt_payer_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return $e->getMessage();
	}
	return $isgood;
}
