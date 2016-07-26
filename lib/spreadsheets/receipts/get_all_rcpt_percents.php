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
Returns the percentage of payment of every receipt sheet.
*/
include_once(__DIR__.'/../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheets.php');
include_once(LIBPATH.'/spreadsheets/receipts/get_rcpt_percents.php');


function get_all_rcpt_percents($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_rcpts = get_spreadsheets_by_type($account_id, "receipt");
		
	$reply = array();
	foreach($my_rcpts as $rcpt)
	{
		$reply[$rcpt['id']] = get_rcpt_percents($account_id, $rcpt['id']);
	}
	return $reply;
}
