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
Updates a article providing its hashid and the associated account id.
A participant is here a row in the articless SQL table 
*/
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/articles/get_article_by_id.php');


function update_article($account_id_arg, $receipt_id_arg, $article_id_arg, $price_arg, $product_arg, $quantity_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$article_id = (int)$article_id_arg;
	$receipt_id = (int)$receipt_id_arg;	
	$new_price = (float)$price_arg;
	$new_product = $product_arg;
	$new_quantity = (float)$quantity_arg;
	
	//Get current article
	$article_to_edit = get_article_by_id($account_id, $article_id);
	
//	if(empty($article_to_edit))
//	{		return false;	}
	
	//Check if nothing to do
	if($new_price == $article_to_edit['price']
	&& $new_product == $article_to_edit['product']
	&& $new_quantity == $article_to_edit['quantity']
	)
	{
		return true;
	}
	
	$isgood= false;
	try
	{
		$myquery = 'UPDATE '.TABLE_RECEIPT_ARTICLES.' 
		SET price=:new_price,	product=:new_product, quantity=:new_quantity
		WHERE id=:article_id AND account_id=:account_id AND receipt_id=:receipt_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_price', $new_price, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_product', $new_product, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_quantity', $new_quantity, PDO::PARAM_STR);
		$prepare_query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':receipt_id', $receipt_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
