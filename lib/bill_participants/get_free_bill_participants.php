<?php
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');
include_once(LIBPATH.'/participants/get_participants.php');

/*
Same as get_bill_participants but give the participants that are NOT in the bills
(they are free to join!)
*/
function get_free_bill_participants($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_bills = get_bills($account_id);
	$my_participants = get_participants($account_id);
	
	$bill_participants = get_bill_participants($account_id);
	
	$reply = array();
	foreach($my_participants as $participant)
	{
		foreach($my_bills as $bill)
		{
			$bill_parts = $bill_participants[$bill['id']];
			$is_in_this_bill = false;
			foreach($bill_parts as $bill_part)
			{                              
				if($bill_part['participant_id'] == $participant['id'])
				{
					$is_in_this_bill = true;
					break;
				}
			}
			if(!$is_in_this_bill)
			{
				$reply[$bill['id']][$participant['id']] = $participant;
			}
		}
	}
	return $reply;
}
