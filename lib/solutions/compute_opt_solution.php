<?php

/* Compute a second solution for the global problem 
Possibly with less transfers

This function uses the returned parameter from compute_solution !
*/

function compute_opt_solution($Refunds)
{
	//Compute the balance of every participant
	//$balance[$uid] is the money $uid must give (so if negative, he is a creditor)
	//Note that sum($balance) = 0
	$balance = Array();
	foreach($Refunds as $uid => $row)
	{
		$balance[$uid] = 0;
	}
	
	$Refunds_opt = Array(Array());
	
	foreach($Refunds as $uid => $row)
	{
		if($uid == -1)
		{continue;}
		foreach($row as $vid => $refund)
		{
			if($vid == $uid || $vid == -1){continue;}
			$balance[$uid] += $refund;
			$balance[$vid] -= $refund;
		}
	}
	
	//All debitors send their due money to the debitor who is the most in debt
	//This participant is called the Banquer
	//Find the Banquer
	$banquer = -1;
	
	foreach($balance as $uid => $bal)
	{
		if($bal > 0)
		{
			if($banquer == -1)
				{	$banquer = $uid;	}
			else if($bal > $balance[$banquer])
			{	$banquer = $uid;}
		}
	}
	
	//If every balance is zero then everyone is happy
	if($banquer == -1)
	{return Array(Array());}
	
	//Every debitor send money to banquer
	foreach($balance as $uid => $bal)
	{
		if($bal <= 0)
		{continue;}
		if($uid == $banquer)
		{$balance[$uid] = 0;}
		else{
			//Debitor send money to banquer
			$Refunds_opt[$uid][$banquer] = $balance[$uid];
			$balance[$uid] = 0;
		}
	}
	
	//The Banquer now refunds every creditors with their balance
	//Every debitor send money to banquer
	foreach($balance as $uid => $bal)
	{
		if($bal == 0 ||$uid == $banquer)
		{continue;}
		else{
			//Creditor get some money from the banquer
			$Refunds_opt[$banquer][$uid] = abs($balance[$uid]);
			$balance[$uid] = 0;
		}
	}
		
	//That's it.
	return $Refunds_opt;
}