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
Lib: Add a row in the receipt_receivers SQL table
 */
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/receipts/get_receipt_by_id.php');
include_once(LIBPATH.'/participants/get_participant_by_id.php');
include_once(LIBPATH.'/articles/get_article_by_id.php');
include_once(LIBPATH.'/receipt_receivers/get_receipt_receivers_by_article_id.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


function set_receipt_receiver($account_id_arg, $hashid_arg, $receipt_id_arg, $participant_id_arg, $article_id_arg, $quantity_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$receipt_id = (int)$receipt_id_arg;
	$participant_id = (int)$participant_id_arg;	
	$article_id = (int)$article_id_arg;
	$quantity = (float)$quantity_arg;
	
	//Check
	$the_receipt = get_receipt_by_id($account_id, $receipt_id);
	if(empty($the_receipt)){return false;}
	$the_participant = get_participant_by_id($account_id, $participant_id);
	if(empty($the_participant)){return false;}
	$the_article = get_article_by_id($account_id, $article_id);
	if(empty($the_article)){return false;}
	//Same account ? (double check)
	if($the_participant['account_id'] !== $the_receipt['account_id'])
	{return 'accounts mismatch';}
	if($the_participant['account_id'] !== $the_article['account_id'])
	{return 'accounts mismatch';}
	if($the_article['account_id'] !== $the_receipt['account_id'])
	{return 'accounts mismatch';}

	if(validate_hashid($hashid) === false)
	{return 'Hashid wrong';}

	//check that the entry is not already existant
	$receipt_receivers = get_receipt_receivers_by_article_id($account_id, $receipt_id, $article_id);
	$total_quantity = $quantity;
	
	foreach ($receipt_receivers as $receipt_part)
	{
			if($receipt_part['participant_id'] == $participant_id)
			{
				return 'participant already registered';
			}
			$total_quantity += $receipt_part['quantity'];
	}
	if($total_quantity > $the_article['quantity'])
	{return 'too much quantity';}
	
	if(is_null(quantity) || $quantity < 0)
	{
		return 'quantity not valid';
	}
	
	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_RECEIPT_RECEIVERS.'(id, hashid, account_id, receipt_id, article_id, participant_id, quantity) 
		VALUES(NULL, :hashid, :account_id, :receipt_id, :article_id, :participant_id, :quantity)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':receipt_id', $receipt_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':participant_id', $participant_id, PDO::PARAM_INT);
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