<?php
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/participants/get_participants.php');
include_once(LIBPATH.'/bill_participants/is_this_participant_in_bill.php');


/*
Relatively the same as get_bill_participants but give the participants that are NOT in the bills
(they are free to join!)
EXCEPT that the array contains Participant and NOT bill_participants/is_this_participant_in_bill
$reply[$bill['id']][$participant['id']] = $participant;
*/
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
