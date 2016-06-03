<?php
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');

/*
Return true if the participant is assigned to the bill
*/
function is_this_participant_in_bill($account_id_arg, $bill_id_arg, $participant_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;
	$participant_id = (int)$participant_id_arg;
	
	$bill_participants = get_bill_participants_by_bill_id($account_id, $bill_id);
	
	foreach($bill_participants as $bill_participant)
	{
		if($bill_participant['participant_id'] == $participant_id)
		{
			return true;
		}
	}
	return false;
}
