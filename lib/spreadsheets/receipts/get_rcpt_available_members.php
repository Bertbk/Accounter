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
Returns the participants that are NOT in the budgets sheet
(they are available to join the budget!)

$reply[$rcpt['id']][$member['id']] = $member;
$member are the data collected on the row of member SQL table

Attention, the returned array contains MEMBERS and not "rcpt_payer"
*/
include_once(__DIR__.'/../../get_db.php');

include_once(LIBPATH.'/members/get_members.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheets.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payer_by_member_id.php');

function get_rcpt_available_members($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_rcpts = get_spreadsheets_by_type($account_id, "receipt");
	$my_members = get_members($account_id);
		
	$reply = array();
	foreach($my_rcpts as $rcpt)
	{
		$reply[$rcpt['id']] = array();
		foreach($my_members as $member)
		{
			$find_payer = get_rcpt_payer_by_member_id($account_id, $rcpt['id'], $member['id']);
			if((empty($find_payer)))
			{
				$reply[$rcpt['id']][$member['id']] = $member;
			}
		}
	}
	return $reply;
}
