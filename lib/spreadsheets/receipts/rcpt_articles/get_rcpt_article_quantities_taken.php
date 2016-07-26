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
Returns the quantities actually "booked" for each articles

$reply[$article['id']] = Sum(quantity of recipients);
*/
include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipients_by_article_id.php');

function get_rcpt_article_quantities_taken($account_id_arg, $spreadsheet_id_arg, $article_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg	;
	$article_id = (int)$article_id_arg	;
	$quantity = (float)0;
	$my_recipients = get_rcpt_recipients_by_article_id($account_id, $spreadsheet_id, $article_id);
	foreach($my_recipients as $recipient)
	{
		$quantity += (float)$recipient['quantity'];
	}
	return $quantity;
}
