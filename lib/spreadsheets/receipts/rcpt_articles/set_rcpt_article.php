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
Create a article for a receipt sheet, ie a row in the rcpt_articles SQL table 
*/
include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');

function set_rcpt_article($account_id_arg, $hashid_arg, $spreadsheet_id_arg, $price_arg, $product_arg, $quantity_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	$hashid = $hashid_arg;
	$price = (float)$price_arg;
	$product = $product_arg;
	$quantity = (float)$quantity_arg;
	
	if(validate_hashid($hashid) == false)
	{return 'Problem with hashid: '.$hashid;}

	if($price < 0 
	|| $quantity < 0)
	{return false;}
	
	$isgood = false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_RCPT_ARTICLES.' (id, hashid, account_id, spreadsheet_id, product, quantity, price) 
		VALUES(NULL, :hashid, :account_id, :spreadsheet_id, :product, :quantity, :price)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
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
