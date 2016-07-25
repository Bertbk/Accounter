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
Lib: Providing a participant id, a receipt id and an account id, check if the SQL table
receipt_payers contain a row with these three parameter.
 */
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/receipt_payers/get_receipt_payers_by_receipt_id.php');

/*
Return true if the participant is assigned to the receipt
*/
function is_this_payer_in_receipt($account_id_arg, $receipt_id_arg, $participant_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$receipt_id = (int)$receipt_id_arg;
	$participant_id = (int)$participant_id_arg;
	
	$receipt_payers = get_receipt_payers_by_receipt_id($account_id, $receipt_id);
	
	foreach($receipt_payers as $receipt_payer)
	{
		if($receipt_payer['participant_id'] == $participant_id)
		{
			return true;
		}
	}
	return false;
}
