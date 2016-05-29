<?php
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bill_by_id.php');
include_once(LIBPATH.'/participants/get_participant_by_id.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');


function delete_bill_participant($account_id_arg, $bill_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_part_id = (int)$bill_id_arg;
	
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM bill_participants 
		WHERE id=:bill_part_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':bill_part_id', $bill_part_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
	
	return $isgood;
}