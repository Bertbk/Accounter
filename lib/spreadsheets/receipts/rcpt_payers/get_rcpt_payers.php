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
include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheets_by_type.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payers_by_spreadsheet_id.php');

/*
Return every receipt_payers of every receipt. The reply is an array such that
$reply[receipt_id] = array of receipt_payers + name of participants + color  + hashid
*/
function get_rcpt_payers($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_receipts = get_spreadsheets_by_type($account_id, "receipt");
	
	$reply = array();

	foreach($my_receipts as $receipt)
	{
		$reply[$receipt['id']] = get_rcpt_payers_by_spreadsheet_id($account_id, $receipt['id']);
	}
	return $reply;
}
