<?php
include_once(__DIR__.'/../get_db.php');

/*
Return an array of the bill_participants of a bill + the name of the participant
*/
function get_bill_participants_from_bill($bill_id_arg)
{
	$db = get_db();

	$bill_id = (int)$bill_id_arg;
	
	$reply = array();

	try
	{
		$myquery = 'SELECT bill_participants.*, participants.name AS name
		FROM bill_participants 
		LEFT  JOIN participants ON participants.id=bill_participants.participant_id 
		WHERE bill_id=:bill_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	
	if(!empty($reply))
	{
		return $reply;
	}
	else
	{
		return array();
	}
}
