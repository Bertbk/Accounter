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
Update a receipt providing its id and its associated account id.
A receipt is a row in the receipts SQL table.
*/
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/receipts/get_receipt_by_id.php');
include_once(LIBPATH.'/receipts/get_receipt_by_title.php');


function update_receipt($account_id_arg, $receipt_id_arg, $title_receipt, $description_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$receipt_id = (int)$receipt_id_arg;
	$new_title_of_receipt = $title_receipt;
	$new_description = $description_arg;
	$new_description = (empty($new_description))?null:$new_description;

	$receipt_to_edit = get_receipt_by_id($account_id, $receipt_id);
	if(empty($receipt_to_edit))
	{
		return false;
	}
	
	//Nothing to change?
	if($new_title_of_receipt === $receipt_to_edit['title'] 
	&& $new_description == $receipt_to_edit['description'])
	{
		return false;
	}

	//If the title, we have to check if it's free
	if($new_title_of_receipt != $receipt_to_edit['title'])
	{
		$isthetitlefree = get_receipt_by_title($account_id, $new_title_of_receipt);
		if(!empty($isthetitlefree))
		{			return false;		}
	}

	try
	{
		$myquery = 'UPDATE '.TABLE_receiptS.'  
		SET title=:new_title_of_receipt, description=:new_description
		WHERE id=:receipt_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_title_of_receipt', $new_title_of_receipt, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_description', $new_description, (is_null($new_description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':receipt_id', $receipt_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
