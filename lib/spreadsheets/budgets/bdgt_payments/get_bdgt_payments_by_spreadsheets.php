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
Returns every payments of an account (by its id), stored by spreadsheets.
A participant is here a row in the paymentss SQL table 
JOINED WITH: payer name and color, and receiver name/color (participants SQL table).

Warning: a payment points to a spreadsheet_participant, not to a participant.
*/
include_once(__DIR__.'/../../../get_db.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_payments/get_bdgt_payments_by_spreadsheet_id.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheets_by_type.php');

/*
Return an array of every payments for every spreadsheet:
$reply is an array of size = number of payments.
$reply['spreadsheet_id'] = array obtained by get_payments_by_spreadsheet_id(..., $spreadsheet_id)
*/
function get_payments_by_spreadsheets($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$budgets = get_spreadsheets_by_type($account_id, "budget");
	
	if(empty($budgets)){return array();}

	$reply = array();
	
	foreach ($budgets as $budget)
	{
		$reply[$budget['id']] = get_bdgt_payments_by_spreadsheet_id($account_id, $budget['id']);
	}
	return $reply;
	
}
