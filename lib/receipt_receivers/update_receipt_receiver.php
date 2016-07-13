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
Lib: Update a receipt_receiver (a row in receipt_receivers Table).
return true if everything went fine
return error message or false otherwise
 */
 
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/receipt_receivers/get_receipt_receivers_by_receipt_id.php');
include_once(LIBPATH.'/receipt_receivers/get_receipt_receiver_by_id.php');


function update_receipt_receiver($account_id_arg, $receipt_receiver_id_arg, $quantity_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	$receipt_receiver_id = (int)$receipt_receiver_id_arg;
	$new_quantity = (float)$quantity_arg;
	
	//Get current receipt_receiver
	$receipt_part_to_edit = get_receipt_receiver_by_id($account_id, $receipt_receiver_id);
	if(empty($receipt_part_to_edit))
	{		return false;	}
	
	//Check new percentage
	if(is_null($new_quantity) ||$new_quantity < 0)
	{return false;}
	
	//Check if nothing to do
	if($new_quantity === $receipt_part_to_edit['quantity'])
	{		return true;}
	
	$isgood= false;
	try
	{		
		$myquery = 'UPDATE '.TABLE_RECEIPT_RECEIVERS.' 
		SET quantity=:new_quantity
		WHERE id=:receipt_receiver_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_quantity', $new_quantity, PDO::PARAM_STR);
		$prepare_query->bindValue(':receipt_receiver_id', $receipt_receiver_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return $e->getMessage();
	}
	return $isgood;
}
