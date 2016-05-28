<?php
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/solutions/compute_bill_solution.php');
include_once(LIBPATH.'/bills/get_bills.php');

function compute_bill_solutions($account_id_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	
	$the_bills = get_bills($account_id);
	
	$Refunds = array(array(array()));
	if(empty($the_bills) || !isset($the_bills)){return $Refunds;}
	foreach($the_bills as $bill)
	{
		$Refunds[$bill['id']] = compute_bill_solution($account_id, $bill['id']);		
	}
	
	return $Refunds;
}
?>