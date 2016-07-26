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
Return an array of every articles of the account, sorted by spreadsheets :
$reply is an array of size = number of spreadsheets.
$reply['spreadsheet_id'] = array of articles associated to the spreadsheet.
*/

include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheets_by_type.php');
include_once(LIBPATH.'/articles/get_articles_by_spreadsheet_id.php');


function get_rcpt_articles($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	//Get the spreadsheets of the account
	$my_receipts = get_spreadsheets_by_type($account_id, "receipt");
	
	//returned value
	$reply = array();
	
	foreach ($my_receipts as $receipt)
	{
		$reply[$receipt['id']] = get_articles_by_spreadsheet_id($account_id_arg, $receipt['id']);		
	}

	return $reply;
}
