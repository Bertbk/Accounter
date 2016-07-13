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
Returns every articles of an account (by its id), stored by receipts.
A participant is here a row in the articless SQL table 
JOINED WITH: payer name and color, and receiver name/color (participants SQL table).

Warning: a article points to a receipt_participant, not to a participant.
*/
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/articles/get_articles_by_receipt_id.php');
include_once(LIBPATH.'/receipts/get_receipts.php');

/*
Return an array of every articles for every receipt:
$reply is an array of size = number of articles.
$reply['receipt_id'] = array obtained by get_articles_by_receipt_id(..., $receipt_id)
*/
function get_articles_by_receipts($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$receipts = get_receipts($account_id);
	
	if(empty($receipts)){return array();}

	$reply = array();
	
	foreach ($receipts as $receipt)
	{
		$reply[$receipt['id']] = get_articles_by_receipt_id($account_id, $receipt['id']);
	}
	return $reply;
	
}
