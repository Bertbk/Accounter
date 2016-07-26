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
$reply[$receipt['id']][$article['id']][$member['id']] = Array of $member;
$member are the data collected on the row of members SQL table

Warning, the returned array are not "rcpt_recipient" but "member"
*/
include_once(__DIR__.'/../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheets_by_type.php');
include_once(LIBPATH.'/members/get_members.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_articles_by_spreadsheet_id.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipient_by_member_id.php');

function get_available_rcpt_recipients($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_receipts = get_spreadsheets_by_type($account_id, "receipt");
	$my_members = get_members($account_id);
		
	$reply = array(array());
	
	foreach($my_receipts as $receipt)
	{
		$my_articles = get_rcpt_articles_by_spreadsheet_id($account_id, $receipt['id']);
		foreach ($my_articles as $article)
		{
			$reply[$receipt['id']][$article['id']] = array();
			$max_quantity = (float)$article['quantity'];
			$current_quantity = (float)0;
			$recipients = get_rcpt_recipients_by_article_id($account_id, $receipt['id'], $article['id']);
			foreach ($recipients as $recip)
			{
				$current_quantity += $recip['quantity'];
			}
			if($current_quantity < $max_quantity)
			{
				foreach($my_members as $member)
				{
					$registered_particip = get_rcpt_recipient_by_member_id($account_id, $receipt['id'], $article['id'], $member['id']);
					if(empty($registered_particip))
					{
						$reply[$receipt['id']][$article['id']][$member['id']] = $member;
					}
				}
			}
		}
	}
	
	return $reply;
}
