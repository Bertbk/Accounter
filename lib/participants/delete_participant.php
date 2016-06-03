<?php
include_once(__DIR__.'/../get_db.php');

function delete_participant($account_id_arg, $participant_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$participant_id = (int)$participant_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM  '.TABLE_PARTICIPANTS.'  
		 WHERE id=:participant_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':participant_id', $participant_id, PDO::PARAM_INT);
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