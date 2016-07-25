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
Return every payments associated to the account of id $account_id_arg.
A participant is here a row in the paymentss SQL table 
JOINED WITH: payer name and color, and receiver name/color (participants SQL table).

Warning: a payment points to a bill_participant, not to a participant.
*/

include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/payments/get_payments_by_bill_id.php');

/*
Return an array of every payments of the account, sorted by bills :
$reply is an array of size = number of bills.
$reply['bill_id'] = array of payments associated to the bill.
$reply['bill_id'] also contains name of payer and receiver + their (real) id (not the one of bill_participant)
*/
function get_payments($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	//Get the bills of the account
	$my_bills = get_bills($account_id);
	
	//returned value
	$reply = array();
	
	foreach ($my_bills as $bill)
	{
		$reply[$bill['id']] = get_payments_by_bill_id($account_id_arg, $bill['id']);		
	}

	return $reply;
}
