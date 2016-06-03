<?php
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bill_by_id.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');
include_once(LIBPATH.'/payments/get_payments_by_bill_id.php');


/* compute a (refund) solution for a particular bill

	$Refunds is the returned value providing a refund solution between the participants:
	Refunds['uid'][$vid] is the amount participants of id 'uid' must give back to participants of id 'vid' (id of table participants).
	Refunds[-1][...] stores some usefull values. -1 cannot be an index, so there shouldn't be overlap.
*/

function compute_bill_solution($account_id_arg, $bill_id_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;

	$Refunds = array(array()); //who must give money to who ?
	$Refunds[-1]['total'] = 0;
	$Refunds[-1]['single'] = 0 ;
	$Refunds[-1]['nb_of_people'] = 0 ;
	$Refunds[-1]['nb_of_parts'] = 0 ;	
	
	//First, compare bill and account
	$bill = get_bill_by_id($account_id, $bill_id);
	if(empty($bill)){return $Refunds;}
	if($bill['account_id']!=$account_id){return $Refunds;}
	
	//Now get the participants
	$my_payments = get_payments_by_bill_id($account_id, $bill_id);
	$my_bill_participants = get_bill_participants_by_bill_id($account_id, $bill_id);
	
	$Debts = array(); // Debt everyone has (to the group)
	
	$total_payment = 0; //Amount of money to share
	$nb_of_people = 0; //number of people
	$nb_of_parts = 0; //number of part (sum of percentage)
	
	//Init debt towards the group
	foreach($my_bill_participants as $contrib)
	{
		$nb_of_people += (int)$contrib['nb_of_people'];
		$nb_of_parts += (float)$contrib['percent_of_usage'] *  (float)$contrib['nb_of_people'];
		$Debts[$contrib['participant_id']] = 0;
		foreach($my_bill_participants as $contrib2)
		{
			$Refunds[$contrib['participant_id']][$contrib2['participant_id']] = 0;
		}
	}
	
	//Init debt between users
	foreach ($my_payments as $payment)
	{
		$my_pay = number_format((float)$payment['cost'], 2, '.', '');
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
	//Warning, nb_of_parts != nb_of_people (except if all have 100% of usage)
	$debt_of_all = 0;
	if($nb_of_parts > 0 )
	{
		$debt_of_all = ($total_payment / $nb_of_parts);
		foreach($my_bill_participants as $contrib)
		{
			$uid = $contrib['participant_id'];
			$my_part = (int)$contrib['nb_of_people'] * (float)$contrib['percent_of_usage'] ;
			$Debts[$uid] +=($debt_of_all * $my_part);
			//Debts is not what everyone should pay (positive) or should receive (negative)
			if($Debts[$uid] <= 0)
			{
				continue; // This guy should receive money, not pay
			}
			else{
				foreach($my_bill_participants as $other)
				{
					$vid = $other['participant_id'];
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
	}
	
	//Last loop to avoid 'two direction' refund (A must pay B and B must pay A)
	foreach($my_bill_participants as $contrib)
	{
		$uid = $contrib['participant_id'];
		foreach($my_bill_participants as $other)
		{
			$vid = $other['participant_id'];
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
	$Refunds[-1]['single'] = $debt_of_all;
	$Refunds[-1]['nb_of_people'] = (int)$nb_of_people ;
	$Refunds[-1]['nb_of_parts'] = (int)$nb_of_parts ;
	
	return $Refunds;
}
?>