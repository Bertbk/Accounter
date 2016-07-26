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
Return an array of the rcpt_recipients of the account, sorted by spreadsheets and article
*/
include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheets_by_type.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipients_by_article_id.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_articles_by_spreadsheet_id.php');

/*
Return every rcpt_recipients of every spreadsheet. The reply is an array of array such that
$reply[spreadsheet_id][article_id] = array of rcpt_recipients + name of members + nb of people + color  + hashid
*/
function get_rcpt_recipients($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_spreadsheets = get_spreadsheets_by_type($account_id, "receipt");
	
	$reply = array(array());

	foreach($my_spreadsheets as $spreadsheet)
	{
		$my_articles = get_rcpt_articles_by_spreadsheet_id($account_id, $spreadsheet['id']);
		foreach($my_articles as $article)
			{
				$reply[$spreadsheet['id']][$article['id']] = get_rcpt_recipients_by_article_id($account_id, $spreadsheet['id'], $article['id']);
			}
	}
	return $reply;
}
