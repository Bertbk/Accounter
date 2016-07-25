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
Add a receipt, ie: a row in the receipts SQL table.
*/

include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/receipts/get_receipt_by_title.php');
include_once(LIBPATH.'/receipts/get_receipts.php');
include_once(LIBPATH.'/bills/get_bills.php');

include_once(LIBPATH.'/colors/give_me_next_color.php');

function set_receipt($account_id_arg, $hashid_receipt_arg, $title_receipt_arg, $description_arg="")
{
	$db = get_db();

	$account_id  = (int)$account_id_arg;
	$hashid_receipt = $hashid_receipt_arg;
	$title_receipt  = $title_receipt_arg;
	$description = $description_arg;
	$description = (empty($description))?null:$description;
	
	$does_this_receipt_exists = get_receipt_by_title($account_id, $title_receipt);
	if(!empty($does_this_receipt_exists))
	{
		return false;
	}
	
	$the_bills = get_bills($account_id);
	$the_receipts = get_receipts($account_id);
	$my_color = give_me_next_color(array_merge($the_bills, $the_receipts), 'bill');
	//When color will come from users, check the reg ex

	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_RECEIPTS.'(id, account_id, hashid, title, description, color) 
		VALUES(NULL, :account_id, :hashid_receipt, :title_receipt, :description, :my_color)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid_receipt', $hashid_receipt, PDO::PARAM_STR);
		$prepare_query->bindValue(':title_receipt', $title_receipt, PDO::PARAM_STR);
		$prepare_query->bindValue(':description', $description, (is_null($description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':my_color', $my_color, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return $e;
	}
	return $isgood;
}