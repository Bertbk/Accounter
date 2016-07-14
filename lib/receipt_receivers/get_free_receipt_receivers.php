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
Returns the participants that are NOT in the receipts
(they are free to join!)

$reply[$receipt['id']][$article['id']][$participant['id']] = Array of $participant;
$participant are the data collected on the row of participants SQL table

Warning, the returned array are not "receipt_receiver" but "Participant"
*/
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/receipts/get_receipts.php');
include_once(LIBPATH.'/participants/get_participants.php');
include_once(LIBPATH.'/receipt_receivers/is_this_receiver_in_article.php');

function get_free_receipt_receivers($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_receipts = get_receipts($account_id);
	$my_participants = get_participants($account_id);
		
	$reply = array(array(array()));
	foreach($my_receipts as $receipt)
	{
		$my_articles = get_articles_by_receipt_id($account_id, $receipt['id']);
		foreach ($my_articles as $article)
		{
			foreach($my_participants as $participant)
			{
					$is_in_this_receipt = is_this_receiver_in_article($account_id, $receipt['id'], $article['id'], $participant['id']);
					if(!$is_in_this_receipt)
					{
						$reply[$receipt['id']][$article['id']][$participant['id']] = $participant;
					}
				}
		}
	}
	
	return $reply;
}
