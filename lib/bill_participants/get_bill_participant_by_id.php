<?php
include_once(__DIR__.'/../get_db.php');

function get_bill_participant_by_id($account_id_arg, $bill_participant_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_participant_id = (int)$bill_participant_id_arg;
	
	try
	{
		$myquery = 'SELECT * FROM bill_participants WHERE account_id=:account_id AND id=:bill_participant_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bill_participant_id', $account_id, PDO::PARAM_INT);
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
		return $reply[0];
	}
	else
	{
		return array();
	}
}
