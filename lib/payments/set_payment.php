<?php
include_once(__DIR__.'/../get_db.php');

function set_payment($account_id_arg, $payer_id_arg, $cost_arg, $receiver_id_arg="", $description_arg="", $date_creation_arg="")
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payer_id = (int)$payer_id_arg;
	$cost = (float)$cost_arg;
	$receiver_id = (is_null($receiver_id_arg)||empty($receiver_id_arg))?null:(int)$receiver_id_arg;
	$description = htmlspecialchars($description_arg);
	$date_creation = htmlspecialchars($date_creation_arg);
	
	$receiver_id = empty($receiver_id) ? null:$receiver_id;
	$description = empty($description) ? null:$description;
	$date_creation = empty($date_creation) ? null:$date_creation;
	//Change style of date to match sql
	if(!is_null($date_creation))
	{
		$date_creation = str_replace('/', '-',$date_creation);
	}
	if($payer_id === $receiver_id)
	{
		return false;
	}
	
	//-1 receiver id
	if($receiver_id == -1)
	{
		$receiver_id=null;
	}

	//Hashid
	do {
		$hashid = bin2hex(openssl_random_pseudo_bytes(8));
	}
	while(!$hashid);

	
	try
	{
		$myquery = 'INSERT INTO payments (id, account_id, hashid, payer_id, cost, receiver_id, description, date_creation) 
		VALUES(NULL, :account_id, :hashid, :payer_id, :cost, :receiver_id, :description, :date_creation)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':payer_id', $payer_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':cost', $cost, PDO::PARAM_STR);
		if(is_null($receiver_id))
		{
			$prepare_query->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
		}
		else
		{
			$prepare_query->bindValue(':receiver_id', $receiver_id, PDO::PARAM_NULL);
		}
		if(is_null($description))
		{
			$prepare_query->bindValue(':description', $description, PDO::PARAM_NULL);
		}
		else
		{
			$prepare_query->bindValue(':description', $description, PDO::PARAM_STR);
		}
		if(is_null($date_creation))
		{
			$prepare_query->bindValue(':date_creation', $date_creation, PDO::PARAM_NULL);
		}
		else
		{
			$prepare_query->bindValue(':date_creation', $date_creation, PDO::PARAM_STR);
		}
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
