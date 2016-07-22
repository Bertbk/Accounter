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
include_once(LIBPATH.'/receipt_receivers/get_receipt_receiver_by_participant_id.php');

function get_free_receipt_receivers($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_receipts = get_receipts($account_id);
	$my_participants = get_participants($account_id);
		
	$reply = array(array());
	
	foreach($my_receipts as $receipt)
	{
		$my_articles = get_articles_by_receipt_id($account_id, $receipt['id']);
		foreach ($my_articles as $article)
		{
			$reply[$receipt['id']][$article['id']] = array();
			$max_quantity = $article['quantity'];
			$current_quantity = 0;
			$recipients = get_receipt_receivers_by_article_id($account_id, $receipt['id'], $article['id']);
			foreach ($recipients as $recip)
			{
				$current_quantity += $recip['quantity'];
			}
			if($current_quantity < $max_quantity)
			{
				foreach($my_participants as $participant)
				{
					$registered_particip = get_receipt_receiver_by_participant_id($account_id, $receipt['id'], $article['id'], $participant['id']);
					if(empty($registered_particip))
					{
						$reply[$receipt['id']][$article['id']][$participant['id']] = $participant;
					}
				}
			}
		}
	}
	
	return $reply;
}
