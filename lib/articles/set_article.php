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
Create a article, ie a row in the articless SQL table 

Warning: a article points to a receipt_participant, not to a participant.
*/
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/receipt_participants/get_receipt_participant_by_id.php');

function set_article($account_id_arg, $hashid_arg, $receipt_id_arg, $price_arg, $product_arg, $quantity_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$price = (float)$price_arg;
	$product = $product_arg;
	$quantity = $quantity_arg;
	
	if(!validate_hashid($hashid))
	{return false;}
	
	$isgood = false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_RECEIPT_ARTICLES.' (id, hashid, account_id, receipt_id, price, product, quantity) 
		VALUES(NULL, :hashid, :account_id, :receipt_id, :price, :product, :quantity)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':receipt_id', $receipt_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':price', $price, PDO::PARAM_STR);
		$prepare_query->bindValue(':product', $product, PDO::PARAM_STR);
		$prepare_query->bindValue(':quantity', $quantity, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
