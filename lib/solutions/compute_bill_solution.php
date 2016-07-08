<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 

/* compute a (refund) solution for a particular bill

	$Refunds is the returned value providing a refund solution between the participants:
- Refunds[$uid][$vid] is the amount participants of id $uid must give back to participants of id $vid
- $uid and $vid belong to the SQL table participants
- Refunds[-1][...] stores some usefull values. -1 cannot be an index, so there shouldn't be overlap
*/
 
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bill_by_id.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');
include_once(LIBPATH.'/payments/get_payments_by_bill_id.php');



function compute_bill_solution($account_id_arg, $bill_id_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;

	$Refunds = array(array()); //who must give money to who ?
	
	//First, compare bill and account
	$bill = get_bill_by_id($account_id, $bill_id);
	if(empty($bill)){return $Refunds;}
	if($bill['account_id']!=$account_id){return $Refunds;}
	
	//Now get the participants
	$my_payments = get_payments_by_bill_id($account_id, $bill_id);
	$my_bill_participants = get_bill_participants_by_bill_id($account_id, $bill_id);
	
	$total_payment = 0; //Amount of money to share
	$nb_of_people = 0; //number of people
	$nb_of_parts = 0; //number of part (sum of percentage)
	
	//Init Refunds to 0
	foreach($my_bill_participants as $contrib)
	{
		$nb_of_people += (int)$contrib['nb_of_people'];
		$nb_of_parts += (float)$contrib['percent_of_usage'] *  (float)$contrib['nb_of_people'];
		foreach($my_bill_participants as $contrib2)
		{
			$Refunds[$contrib['participant_id']][$contrib2['participant_id']] = 0;
		}
	}
	
	//Set the payments
	foreach ($my_payments as $payment)
	{
		$my_pay = number_format((float)$payment['cost'], 2, '.', '');
		$uid = $payment['real_recv_id'];
		$vid = $payment['real_payer_id'];
		
		if(!is_null($uid))
		{//Local payment
			$Refunds[$uid][$vid] += $my_pay;
			$Refunds[$vid][$uid] -= $my_pay;
		}
		else{//Global payment
			$one_part = (float)((float)$my_pay / (float)$nb_of_parts);
			foreach($my_bill_participants as $contrib)
			{
				$uuid = $contrib['participant_id'];
				if($uuid == $vid){continue;}
				$contrib_part = $one_part * (float)$contrib['percent_of_usage'] *  (float)$contrib['nb_of_people'];
				$Refunds[$uuid][$vid] += $contrib_part;
				$Refunds[$vid][$uuid] -= $contrib_part;
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
	
	return $Refunds;
}
?>