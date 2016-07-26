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
Lib: Update a rcpt_recipient (a row in rcpt_recipients Table).
return true if everything went fine
return error message or false otherwise
 */
 
include_once(__DIR__.'/../../../get_db.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipient_by_id.php');


function update_rcpt_recipient($account_id_arg, $rcpt_recipient_id_arg, $quantity_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	$rcpt_recipient_id = (int)$rcpt_recipient_id_arg;
	$new_quantity = (float)$quantity_arg;
	
	//Get current rcpt_recipient
	$spreadsheet_part_to_edit = get_rcpt_recipient_by_id($account_id, $rcpt_recipient_id);
	if(empty($spreadsheet_part_to_edit))
	{		return false;	}
	
	//Check new percentage
	if(is_null($new_quantity) ||$new_quantity < 0)
	{return false;}
	
	//Check if nothing to do
	if($new_quantity === $spreadsheet_part_to_edit['quantity'])
	{		return true;}
	
	$isgood= false;
	try
	{		
		$myquery = 'UPDATE '.TABLE_RCPT_RECIPIENTS.' 
		SET quantity=:new_quantity
		WHERE id=:rcpt_recipient_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_quantity', $new_quantity, PDO::PARAM_STR);
		$prepare_query->bindValue(':rcpt_recipient_id', $rcpt_recipient_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return $e->getMessage();
	}
	return $isgood;
}
