<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 

/* Compute a solution for the global problem
- $Refunds[$uid][$vid] = money U must give to V
- $uid and $vid belong are the id in Participant Table
 */

 include_once(__DIR__.'/compute_budget_solutions.php');
 include_once(__DIR__.'/compute_receipt_solutions.php');
include_once(LIBPATH.'/members/get_members.php');

function compute_solution($account_id_arg)
{	
	$account_id = (int)$account_id_arg;
	$Refunds = array(array()); //who must give money to who ?

	$budget_solutions = compute_budget_solutions($account_id);
	$receipt_solutions = compute_receipt_solutions($account_id);
	if(empty($budget_solutions) && empty($receipt_solutions)){return $Refunds;}

	$my_members = get_members($account_id);
	if(empty($my_members)){return $Refunds;}
	
	//Init debt to zero
	foreach($my_members as $contrib)
	{
		$uid = $contrib['id'];
		foreach($my_members as $other)
		{
			$vid = $other['id'];
			if($uid == $vid){continue;}
			$Refunds[$uid][$vid] = 0;
		}
	}
	
	//Store debt computed previously
	foreach ($budget_solutions as $key => $budget_sol)
	{
		if($key < 1){continue;}
		foreach($my_members as $contrib)
		{
			$uid = $contrib['id'];
			if(!isset($budget_sol[$uid])){continue;}
			foreach($my_members as $other)
			{
				$vid = $other['id'];
				if($uid == $vid){continue;}
				if(!isset($budget_sol[$uid][$vid])){continue;}
				$Refunds[$uid][$vid] += $budget_sol[$uid][$vid];
			}
		}
	}
	foreach ($receipt_solutions as $key => $receipt_sol)
	{
		if($key < 1){continue;}
		foreach($my_members as $contrib)
		{
			$uid = $contrib['id'];
			if(!isset($receipt_sol[$uid])){continue;}
			foreach($my_members as $other)
			{
				$vid = $other['id'];
				if($uid == $vid){continue;}
				if(!isset($receipt_sol[$uid][$vid])){continue;}
				$Refunds[$uid][$vid] += $receipt_sol[$uid][$vid];
			}
		}
	}
		
	//Last loop to avoid 'two direction' refund (A must pay B and B must pay A)
	foreach($my_members as $contrib)
	{
		$uid = $contrib['id'];
		foreach($my_members as $other)
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
	
	//send solution	
	return $Refunds;
}
?>