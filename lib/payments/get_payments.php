<?php
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/payments/get_payments_by_bill_id.php');

/*
Return an array of every payments of the account, sorted by bills :
$reply is an array of size = number of bills.
$reply['bill_id'] = array of payments associated to the bill.
$reply[i] also contains name of payer and receiver.
*/
function get_payments($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	//Get the bills of the account
	$my_bills = get_bills($account_id);
	
	//returned value
	$reply = array();
	
	foreach ($mybills as $bill)
	{
		$reply[$bill['id']] = get_payments_by_bill_id($account_id_arg, $bill_id_arg);		
	}

	if(!empty($reply))
	{
		return $reply;
	}
	else
	{
		return array();
	}
}
