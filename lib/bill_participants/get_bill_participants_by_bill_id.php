<?php
include_once(__DIR__.'/../get_db.php');

/*
Return an array of the bill_participants of a bill + the name of the participant + Color + hashid
*/
function get_bill_participants_by_bill_id($account_id_arg, $bill_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;
	
	$reply = array();

	try
	{
		$myquery = 'SELECT '.TABLE_BILL_PARTICIPANTS.'.*, '.TABLE_PARTICIPANTS.'.name AS name, 
		'.TABLE_PARTICIPANTS.'.nb_of_people AS nb_of_people, 
		'.TABLE_PARTICIPANTS.'.color AS color,
		'.TABLE_PARTICIPANTS.'.hashid AS participant_hashid
		FROM '.TABLE_BILL_PARTICIPANTS.'  
		LEFT JOIN '.TABLE_PARTICIPANTS.' ON '.TABLE_PARTICIPANTS.'.id='.TABLE_BILL_PARTICIPANTS.'.participant_id 
		WHERE '.TABLE_BILL_PARTICIPANTS.'.account_id=:account_id AND '.TABLE_BILL_PARTICIPANTS.'.bill_id=:bill_id' ;
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	
	return $reply;
}
