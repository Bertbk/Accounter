<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 

/* Compute a second solution for the global problem
This function takes in argument the returned solution from compute_solution !
- $Refunds[$uid][$vid] = money U must give to V
- $uid (resp. $vid) is the id in Participant Table
 */
 include_once(__DIR__.'/compute_bill_solutions.php');

function compute_opt_solution($account_id_arg, $Refunds)
{
	//Compute the balance of every participant
	//$balance[$uid] is the money $uid must give (so if negative, he is a creditor)
	//Note that sum($balance) = 0
	$balance = Array();
	
	$account_id = (int)$account_id_arg;
	$my_participants = get_participants($account_id);

	foreach($my_participants as $particip)
	{
		$uid = $particip['id'];
		$balance[$uid] = 0;
	}
	
	
	foreach($Refunds as $uid => $row)
	{
		if($uid == -1)
		{continue;}
		foreach($row as $vid => $refund)
		{
			//Only upper triangle of the matrix
			if($refund <= 0 || $vid == $uid || $vid == -1){continue;}
			$balance[$uid] += (float)$refund;
			$balance[$vid] -= (float)$refund;
		}
	}
	
	//All debitors send their due money to the debitor who is the most in debt
	//This participant is called the Banquer
	//Find the Banquer
	$banquer = null;
	
	foreach($balance as $uid => $bal)
	{
		if($bal > 0)
		{
			if(is_null($banquer))
				{	$banquer = $uid;	}
			else if((float)$bal > (float)$balance[$banquer])
			{	$banquer = $uid;}
		}
	}
	//If every balance is zero then everyone is happy
	if(is_null($banquer))
	{return Array(Array());}

	$balance[$banquer] = 0;
	
	//Every debitor send money to banquer
	foreach($balance as $uid => $bal)
	{
		if($bal == 0)
		{continue;}
		else if((float)$bal > 0){
			//Debitor send money to banquer
			$Refunds_opt[$uid][$banquer] = $bal;
			$balance[$uid] = 0;
		}
		else
		{
			$Refunds_opt[$banquer][$uid] = abs($bal);
			$balance[$uid] = 0;
		}
	}
		
	//That's it.
	return $Refunds_opt;
}