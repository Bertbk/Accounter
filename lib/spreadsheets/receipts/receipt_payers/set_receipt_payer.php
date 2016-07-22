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
Lib: Add a row in the receipt_payers SQL table
 */
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/receipts/get_receipt_by_id.php');
include_once(LIBPATH.'/participants/get_participant_by_id.php');
include_once(LIBPATH.'/receipt_payers/get_receipt_payers_by_receipt_id.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


function set_receipt_payer($account_id_arg, $hashid_arg, $receipt_id_arg, $participant_id_arg, $percent_of_payment_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$receipt_id = (int)$receipt_id_arg;
	$participant_id = (int)$participant_id_arg;
	$percent_of_payment = (float)$percent_of_payment_arg;
	
	//Check
	$the_receipt = get_receipt_by_id($account_id, $receipt_id);
	if(empty($the_receipt)){return false;}
	$the_participant = get_participant_by_id($account_id, $participant_id);
	if(empty($the_participant)){return false;}
	//Same account ? (double check)
	if($the_participant['account_id'] !== $the_receipt['account_id'])
	{return false;}

	if(validate_hashid($hashid) === false)
	{return false;}

	//check that the entry is not already existant
	$receipt_payers = get_receipt_payers_by_receipt_id($account_id, $receipt_id);
	foreach ($receipt_payers as $receipt_part)
	{
			if($receipt_part['participant_id'] == $participant_id)
			{
				return false;
			}
	}
	
	$percent_of_payment = is_null($percent_of_payment)?100:$percent_of_payment;
	if($percent_of_payment > 100 || $percent_of_payment < 0)
	{
		return false;
	}
	
	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_RECEIPT_PAYERS.'(id, account_id, receipt_id, hashid, participant_id, percent_of_payment) 
		VALUES(NULL, :account_id, :receipt_id, :hashid, :participant_id, :percent_of_payment)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':receipt_id', $receipt_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':participant_id', $participant_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':percent_of_payment', $percent_of_payment, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	 return 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}