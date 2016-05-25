<?php
include_once('/lib/get_db.php');

function set_payment($account_id_arg, $payer_id_arg, $cost_arg, $receiver_id_arg, $description_arg="", $date_creation_arg="")
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payer_id = (int)$payer_id_arg;
	$cost = (float)$cost_arg;
	$receiver_id = (int)$receiver_id_arg;
	$description = htmlspecialchars($description_arg);
	$date_creation = htmlspecialchars($date_creation_arg);
	
	$description = empty($description) ? null:$description;
	$date_creation = empty($date_creation) ? null:$date_creation;
	
	if($payer_id === $receiver_id)
	{
		return true;
	}

	try
	{
		$myquery = 'INSERT INTO payments (id, account_id, payer_id, cost, receiver_id, description, date_creation) VALUES(NULL, :account_id, :payer_id, :cost, :receiver_id, :description, :date_creation)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':payer_id', $payer_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':cost', $cost, PDO::PARAM_STR);
		$prepare_query->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
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