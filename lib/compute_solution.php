<?php
include_once('/lib/get_db.php');
include_once('/lib/get_payments.php');
include_once('/lib/get_contributors.php');

function compute_solution($account_id_arg)
{
	/*
	$Refunds is the returned value providing a refund solution between the contributors:
	Refunds['uid'][$vid] is the amount Contributors of id 'uid' must give back to Contributors of id 'vid' (id of table Contributors).
	Refunds[-1][...] stores some usefull values. -1 cannot be an index, so there shouldn't be overlap.
	*/
	$db = get_db();
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$account_id = (int)$account_id_arg;
	
	$my_contribs = get_contributors($account_id );
	$my_payments = get_payments($account_id );
	
	$Refunds = array(array()); //who must give money to who ?
	$Debts = array(); // Debt everyone has (to the group)
	
	$total_payment = 0; //Amount of money to share
	$n_parts = 0; //number of parts
	
	foreach($my_contribs as $contrib)
	{
		$n_parts += $contrib['number_of_parts'];
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
	$debt_of_all = $total_payment / $n_parts;
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
	
	//Usefull values
	$Refunds[-1]['total'] = $total_payment;
	$Refunds[-1]['single'] = $debt_of_all ;
	$Refunds[-1]['nparts'] = $n_parts ;
	
	return $Refunds;
}