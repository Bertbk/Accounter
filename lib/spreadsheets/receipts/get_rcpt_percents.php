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
Returns the participants that are NOT in the budgets sheet
(they are available to join the budget!)

$reply[$rcpt['id']][$member['id']] = $member;
$member are the data collected on the row of member SQL table

Attention, the returned array contains MEMBERS and not "rcpt_payer"
*/
include_once(__DIR__.'/../../get_db.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payers_by_spreadsheet_id.php');

function get_rcpt_percents($account_id_arg, $spreadsheet_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg	;

	$my_rcpt_payers = get_rcpt_payers_by_spreadsheet_id($account_id, $spreadsheet_id);
	$this_percent = (float)0;
	
	foreach($my_rcpt_payers as $payer)
	{
		$this_percent += (float)$payer['percent_of_payment'];
	}
	return $this_percent;
}
