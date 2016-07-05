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
Returns the participants that are NOT in the bills
(they are free to join!)

$reply[$bill['id']][$participant['id']] = $participant;
$participant are the data collected on the row of participants SQL table

Attention, the returned array are not "Bill_participant" but "Participant"
*/
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/participants/get_participants.php');
include_once(LIBPATH.'/bill_participants/is_this_participant_in_bill.php');



function get_free_bill_participants($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_bills = get_bills($account_id);
	$my_participants = get_participants($account_id);
		
	$reply = array();
	foreach($my_participants as $participant)
	{
		foreach($my_bills as $bill)
		{
			$is_in_this_bill = is_this_participant_in_bill($account_id, $bill['id'], $participant['id']);
			if(!$is_in_this_bill)
			{
				$reply[$bill['id']][$participant['id']] = $participant;
			}
		}
	}
	return $reply;
}
