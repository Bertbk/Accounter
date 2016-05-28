<?php
include_once(__DIR__.'/get_db.php');
include_once(LIBPATH.'/payments/get_payments.php');
include_once(LIBPATH.'/participants/get_participants.php');

function compute_solution($account_id_arg)
{
	/*
	$Refunds is the returned value providing a refund solution between the participants:
	Refunds['uid'][$vid] is the amount participants of id 'uid' must give back to participants of id 'vid' (id of table participants).
	Refunds[-1][...] stores some usefull values. -1 cannot be an index, so there shouldn't be overlap.
	*/
/*	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	
	$my_contribs = get_participants($account_id );
	$my_payments = get_payments($account_id );
	
	if(empty($my_contribs) || empty($my_payments))
	{
		return array();
	}
	
	$Refunds = array(array()); //who must give money to who ?
	$Debts = array(); // Debt everyone has (to the group)
	
	$total_payment = 0; //Amount of money to share
	$nb_of_people = 0; //number of parts
	
	foreach($my_contribs as $contrib)
	{
		$nb_of_people += $contrib['number_of_parts'];
		$Debts[$contrib['id']] = 0;
		foreach($my_contribs as $contrib2)
		{
			$Refunds[$contrib['id']][$contrib2['id']] = 0;
		}
	}
	
	foreach ($my_payments as $payment)
	{
		$my_pay = $payment['cost'];
		if(!is_null($payment['receiver_id']))
		{//Local payment
			$Refunds[$payment['receiver_id']][$payment['payer_id']] += $my_pay;
		}
		else{//global payment
			$total_payment += $my_pay;
			$Debts[$payment['payer_id']] -= $my_pay;
		}
	}
	
	//Now share the bill !
	$debt_of_all = $total_payment / $nb_of_people;
	foreach($my_contribs as $contrib)
	{
		$uid = $contrib['id'];
		$Debts[$uid] += $debt_of_all * (int)$contrib['number_of_parts'];
		//Debts is not what everyone should pay (positive) or should receive (negative)
		if($Debts[$uid] <= 0)
		{
			continue; // This guy should receive money, not pay
		}
		else{
			foreach($my_contribs as $other)
			{
				$vid = $other['id'];
				if($vid == $uid){continue;}//it's me !
				else if($Debts[$vid] > 0){continue;} //(s)he is in debt!
				else{
					$to_refund = min(abs($Debts[$vid]), $Debts[$uid]);
					$Refunds[$uid][$vid] +=$to_refund;
					$Debts[$vid] += $to_refund;
					$Debts[$uid] -= $to_refund;
					if($Debts[$uid] == 0){break;}
				}		
			}
		}
	}
	
	//Last loop to avoid 'two direction' refund (A must pay B and B must pay A)
	foreach($my_contribs as $contrib)
	{
		$uid = $contrib['id'];
		foreach($my_contribs as $other)
		{
			$vid = $other['id'];
			if($uid == $vid){continue;}
			$u_to_v = $Refunds[$uid][$vid];
			$v_to_u = $Refunds[$vid][$uid];
			if($u_to_v > 0 && $v_to_u > 0)
			{
				if($u_to_v > $v_to_u)
				{
					$Refunds[$uid][$vid] = $u_to_v - $v_to_u;
					$Refunds[$vid][$uid] = 0;
				}
				else
				{
					$Refunds[$vid][$uid] = $v_to_u - $u_to_v;
					$Refunds[$uid][$vid] = 0;
				}
			}
		}
	}
	
	//Usefull values
	$Refunds[-1]['total'] = $total_payment;
	$Refunds[-1]['single'] = $debt_of_all ;
	$Refunds[-1]['nparts'] = $nb_of_people ;
	*/
	$Refunds = array();
	return $Refunds;
}
?>