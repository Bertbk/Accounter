<?php
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/bill_participants/get_bill_participant_by_id.php');

function set_payment($account_id_arg, $hashid_arg, $bill_id_arg, $payer_id_arg, $cost_arg, $receiver_id_arg, $description_arg, $date_of_payment_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$bill_id = (int)$bill_id_arg;
	$payer_id = (int)$payer_id_arg;
	$cost = (float)$cost_arg;
	$receiver_id = (is_null($receiver_id_arg)||empty($receiver_id_arg))?null:(int)$receiver_id_arg;
	$description = $description_arg;
	$date_of_payment = $date_of_payment_arg;
	
	$receiver_id = empty($receiver_id) ? null:$receiver_id;
	$description = empty($description) ? null:$description;
	$date_of_payment = empty($date_of_payment) ? null:$date_of_payment;
	
	//Change style of date to match sql
	if(!is_null($date_of_payment))
	{
		$date_of_payment = str_replace('/', '-',$date_of_payment);
		$date_parsed = date_parse($date_of_payment);
		if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
			$date_of_payment = null;
		}
	}
	
	if(!validate_hashid($hashid))
	{return false;}
	
	if($receiver_id == -1)
	// {		$receiver_id = null;	}

	if($payer_id === $receiver_id)
	{
		return false;
	}
	
	$participation_payer = get_bill_participant_by_id($account_id, $payer_id);
	if($participation_payer['bill_id'] != $bill_id )
	{return false;}
	if(!is_null($receiver_id))
	{
		$participation_recv = get_bill_participant_by_id($account_id, $receiver_id);
		if($participation_recv['bill_id'] != $bill_id )
		{return false;}
	}
	
	$isgood = false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_PAYMENTS.' (id, hashid, account_id, bill_id, payer_id, cost, receiver_id, description, date_of_payment) 
		VALUES(NULL, :hashid, :account_id, :bill_id, :payer_id, :cost, :receiver_id, :description, :date_of_payment)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
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
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
