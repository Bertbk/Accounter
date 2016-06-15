<?php
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/payments/get_payment_by_id.php');
include_once(LIBPATH.'/bill_participants/get_bill_participant_by_id.php');


function update_payment($account_id_arg, $bill_id_arg, $payment_id_arg, $payer_id_arg, $cost_arg, 
			$receiver_id_arg, $description_arg, $date_of_payment_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$new_bill_id = (int)$bill_id_arg;
	$payment_id = (int)$payment_id_arg;
	$new_payer_id = (int)$payer_id_arg;
	$new_cost = (float)$cost_arg;
	$new_receiver_id = (is_null($receiver_id_arg)||empty($receiver_id_arg))?null:(int)$receiver_id_arg;
	$new_description = $description_arg;
	$new_date_of_payment = $date_of_payment_arg;

	$new_receiver_id = empty($new_receiver_id) ? null:$new_receiver_id;
	$new_description = empty($new_description) ? null:$new_description;
	$new_date_of_payment = empty($new_date_of_payment) ? null:$new_date_of_payment;
	
	if($new_receiver_id == -1)
	{		$new_receiver_id=null;	}
	
	//Get current payment
	$payment_to_edit = get_payment_by_id($account_id, $payment_id);
	
	if(empty($payment_to_edit))
	{		return false;	}
	
	//Change style of date to match sql
	if(!is_null($new_date_of_payment))
	{
		$new_date_of_payment = str_replace('/', '-',$new_date_of_payment);
		$date_parsed = date_parse($new_date_of_payment);
		if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
			$new_date_of_payment = null;
		}
	}
	
	if($new_payer_id === $new_receiver_id)
	{		return false;	}
	
	//Check if nothing to do
	if($new_bill_id === $payment_to_edit['bill_id']
	&& $new_payer_id === $payment_to_edit['payer_id']
	&& $new_cost === $payment_to_edit['cost']
	&& $new_receiver_id === $payment_to_edit['receiver_id']
	&& $new_description === $payment_to_edit['description']
	&& $new_date_of_payment == $payment_to_edit['date_of_payment']
	)
	{
		return true;
	}
	
	// If moving to another bill, check if people exists
	$new_participation_payer = get_bill_participant_by_id($account_id, $new_payer_id);
	if($new_participation_payer['bill_id'] != $new_bill_id )
	{return false;}
	if(!is_null($receiver_id))
	{
		$new_participation_recv = get_bill_participant_by_id($account_id, $new_receiver_id);
		if($new_participation_recv['bill_id'] != $new_bill_id )
		{return false;}
	}
	
	
	$isgood= false;
	try
	{
		$myquery = 'UPDATE '.TABLE_PAYMENTS.' 
		SET bill_id=:new_bill_id, payer_id=:new_payer_id, cost=:new_cost, receiver_id=:new_receiver_id, 
		description=:new_description, date_of_payment=:new_date_of_payment
		WHERE id=:payment_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_bill_id', $new_bill_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':new_payer_id', $new_payer_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':new_cost', $new_cost, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_receiver_id', $new_receiver_id, ((is_null($new_receiver_id))?(PDO::PARAM_NULL):(PDO::PARAM_INT)));
		$prepare_query->bindValue(':new_description', $new_description, ((is_null($new_description))?(PDO::PARAM_NULL):(PDO::PARAM_STR)));
		$prepare_query->bindValue(':new_date_of_payment', $new_date_of_payment, ((is_null($new_date_of_payment))?(PDO::PARAM_NULL):(PDO::PARAM_STR)));
		$prepare_query->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
