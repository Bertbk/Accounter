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
Return every articles associated to the account of id $account_id_arg.
A participant is here a row in the articless SQL table 
JOINED WITH: payer name and color, and receiver name/color (participants SQL table).

Warning: a article points to a receipt_participant, not to a participant.
*/

include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/receipts/get_receipts.php');
include_once(LIBPATH.'/articles/get_articles_by_receipt_id.php');

/*
Return an array of every articles of the account, sorted by receipts :
$reply is an array of size = number of receipts.
$reply['receipt_id'] = array of articles associated to the receipt.
$reply['receipt_id'] also contains name of payer and receiver + their (real) id (not the one of receipt_participant)
*/
function get_articles($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	//Get the receipts of the account
	$my_receipts = get_receipts($account_id);
	
	//returned value
	$reply = array();
	
	foreach ($my_receipts as $receipt)
	{
		$reply[$receipt['id']] = get_articles_by_receipt_id($account_id_arg, $receipt['id']);		
	}

	return $reply;
}
