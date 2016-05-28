<?php
include_once(__DIR__.'/../get_db.php');

function set_bill_participant($bill_id_arg, $participant_id_arg, $percent_of_use_arg = "")
{
	$db = get_db();

	$bill_id = (int)$bill_id_arg;
	$participant_id = (int)$participant_id_arg;
	$percent_of_use = (float)$percent_of_use_arg;
	
	//Hashid
	do {
		$hashid = bin2hex(openssl_random_pseudo_bytes(8));
	}
	while(!$hashid);

	$percent_of_use = is_null($percent_of_use)?100:$percent_of_use;
	
	if($percent_of_use > 100 || $percent_of_use < 0)
	{
		return false;		
	}
	
	//We should check if everything is ok ?
	// Are the accound id equal ?
	// Is the entry already in database ?
	
	
	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO bill_participants(id, bill_id, hashid, participant_id, percent_of_usage) 
		VALUES(NULL, :bill_id, :hashid, :participant_id, :percent_of_use)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':participant_id', $participant_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':percent_of_use', $percent_of_use, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}