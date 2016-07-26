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
Returns the quantities actually "booked" for each articles, stored by each receipts.

$reply[$receipt['id']][$article['id']] = Sum(quantity of recipients);
*/
include_once(__DIR__.'/../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheets_by_type.php');
include_once(LIBPATH.'/spreadsheets/receipts/get_rcpt_quantities_taken.php');

function get_all_rcpt_quantities_taken($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;

	$my_spreadsheets = get_spreadsheets_by_type($account_id, "receipt");
	
	$reply = array();
	foreach($my_spreadsheets as $spreadsheet)
	{
		$reply[$spreadsheet['id']] = get_rcpt_quantities_taken($account_id, $spreadsheet['id']) ;
	}
	return $reply;
}
