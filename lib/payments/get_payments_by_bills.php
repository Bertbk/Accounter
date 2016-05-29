<?php
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/payments/get_payments_by_bill_id.php');
include_once(LIBPATH.'/bills/get_bills.php');

/*
Return an array of every payments for every bill:
$reply is an array of size = number of payments.
$reply['bill_id'] = array obtained by get_payments_by_bill_id(..., $bill_id)
*/
function get_payments_by_bills($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$bills = get_bills($account_id);
	
	if(empty($bills)){return array();}

	$reply = array();
	
	foreach ($bills as $bill)
	{
		$reply[$bill['id']] = get_payments_by_bill_id($account_id, $bill['id']);
	}
	return $reply;
	
}
