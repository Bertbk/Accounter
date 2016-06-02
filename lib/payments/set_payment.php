<?php
include_once(__DIR__.'/../get_db.php');

function set_payment($account_id_arg, $bill_id_arg, $payer_id_arg, $cost_arg, $receiver_id_arg="", $description_arg="", $date_of_payment_arg="")
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;
	$payer_id = (int)$payer_id_arg;
	$cost = (float)$cost_arg;
	$receiver_id = (is_null($receiver_id_arg)||empty($receiver_id_arg))?null:(int)$receiver_id_arg;
	$description = htmlspecialchars($description_arg);
	$date_of_payment = htmlspecialchars($date_of_payment_arg);
	
	$receiver_id = empty($receiver_id) ? null:$receiver_id;
	$description = empty($description) ? null:$description;
	$date_of_payment = empty($date_of_payment) ? null:$date_of_payment;
	//Change style of date to match sql
	if(!is_null($date_of_payment))
	{
		$date_of_payment = str_replace('/', '-',$date_of_payment);
	}
	
	if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date_of_payment))
	{
		 $date_of_payment = null;
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
		$myquery = 'INSERT INTO payments (id, account_id, bill_id, hashid, payer_id, cost, receiver_id, description, date_of_payment) 
		VALUES(NULL, :account_id, :bill_id, :hashid, :payer_id, :cost, :receiver_id, :description, :date_of_payment)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
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
		if(is_null($date_of_payment))
		{
			$prepare_query->bindValue(':date_of_payment', $date_of_payment, PDO::PARAM_NULL);
		}
		else
		{
			$prepare_query->bindValue(':date_of_payment', $date_of_payment, PDO::PARAM_STR);
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
