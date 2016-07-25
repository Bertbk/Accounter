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

Warning: a payment points to a spreadsheet_participant, not to a participant.
*/

include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheets.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_payments/get_bdgt_payments_by_spreadsheet_id.php');

/*
Return an array of every payments of the account, sorted by spreadsheets :
$reply is an array of size = number of spreadsheets.
$reply['spreadsheet_id'] = array of payments associated to the spreadsheet.
$reply['spreadsheet_id'] also contains name of payer and receiver + their (real) id (not the one of spreadsheet_participant)
*/
function get_bdgt_payments($account_id_arg)
{
	$db = get_db();
	$account_id = (int)$account_id_arg;
	
	//Get the spreadsheets of the account
	$my_budgets = get_spreadsheets_by_type($account_id, "budget");
	
	//returned value
	$reply = array();
	
	foreach ($my_budgets as $spreadsheet)
	{
		$reply[$spreadsheet['id']] = get_bdgt_payments_by_spreadsheet_id($account_id_arg, $spreadsheet['id']);		
	}

	return $reply;
}
