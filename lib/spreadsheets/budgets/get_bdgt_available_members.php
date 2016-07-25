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

$reply[$bdgt['id']][$member['id']] = $member;
$member are the data collected on the row of member SQL table

Attention, the returned array contains MEMBERS and not "bdgt_participant"
*/
include_once(__DIR__.'/../../get_db.php');

include_once(LIBPATH.'/members/get_members.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheets.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participant_by_member_id.php');

function get_bdgt_available_members($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_bdgts = get_spreadsheets_by_type($account_id, "budget");
	$my_members = get_members($account_id);
		
	$reply = array();
	foreach($my_members as $member)
	{
		foreach($my_bdgts as $bdgt)
		{
			$find_participant = get_bdgt_participant_by_member_id($accout_id, $bdgt['id'], $member['id']);
			if(!(empty($find_participant)))
			{
				$reply[$bdgt['id']][$member['id']] = $member;
			}
		}
	}
	return $reply;
}
