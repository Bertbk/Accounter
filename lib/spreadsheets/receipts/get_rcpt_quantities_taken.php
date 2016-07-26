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
include_once(__DIR__.'/../../get_db.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_articles_by_spreadsheet_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_article_quantities_taken.php');

function get_rcpt_quantities_taken($account_id_arg, $spreadsheet_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg	;

	$my_rcpt_articles = get_rcpt_articles_by_spreadsheet_id($account_id, $spreadsheet_id);
	
	$reply = array();
	foreach($my_rcpt_articles as $article)
	{
		$reply[$article['id']] = get_rcpt_article_quantities_taken($account_id, $spreadsheet_id, $article['id']);
	}
	return $reply;
}
