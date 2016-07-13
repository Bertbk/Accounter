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
Return an array of the receipt_payers of the account, sorted by receipts
*/
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/receipts/get_receipts.php');
include_once(LIBPATH.'/receipt_payers/get_receipt_payers_by_receipt_id.php');

/*
Return every receipt_payers of every receipt. The reply is an array such that
$reply[receipt_id] = array of receipt_payers + name of participants + color  + hashid
*/
function get_receipt_payers($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_receipts = get_receipts($account_id);
	
	$reply = array();

	foreach($my_receipts as $receipt)
	{
		$reply[$receipt['id']] = get_receipt_payers_by_receipt_id($account_id, $receipt['id']);
	}
	return $reply;
}
