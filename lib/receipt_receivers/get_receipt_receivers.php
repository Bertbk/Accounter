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
Return an array of the receipt_receivers of the account, sorted by receipts
*/
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/receipts/get_receipts.php');
include_once(LIBPATH.'/receipt_receivers/get_receipt_receivers_by_article_id.php');

include_once(LIBPATH.'/articles/get_articles_by_receipt_id.php');

/*
Return every receipt_receivers of every receipt. The reply is an array of array such that
$reply[receipt_id][article_id] = array of receipt_receivers + name of participants + nb of people + color  + hashid
*/
function get_receipt_receivers($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_receipts = get_receipts($account_id);
	
	$reply = array(array());

	foreach($my_receipts as $receipt)
	{
		$my_articles = get_articles_by_receipt_id($account_id, $receipt['id']);
		foreach($my_articles as $article)
			{
				$reply[$receipt['id']][$article['id']] = get_receipt_receivers_by_article_id($account_id, $receipt['id'], $article['id']);
			}
	}
	return $reply;
}
