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
Delete a row in the receipt_payers SQL table providing its id 
*/

include_once(__DIR__.'/../get_db.php');

function delete_receipt_payer($account_id_arg, $receipt_payer_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payer_id = (int)$receipt_payer_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM '.TABLE_RECEIPT_PAYERS.'  
		 WHERE id=:payer_id AND account_id=:account_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':payer_id', $payer_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	 return 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
	
}