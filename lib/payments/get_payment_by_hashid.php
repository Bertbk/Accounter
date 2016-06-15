<?php
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/hashid/validate_hashid.php');

function get_payment_by_hashid($account_id_arg, $payment_hashid_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payment_hashid = $payment_hashid_arg;
	
	if(!validate_hashid($payment_hashid))
	{return false;}
	
	try
	{
		$myquery = 'SELECT * FROM '.TABLE_PAYMENTS.'
		WHERE account_id=:account_id AND hashid=:payment_hashid';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':payment_hashid', $payment_hashid, PDO::PARAM_STR);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	if(!empty($reply))
	{
		return $reply[0];
	}
	else
	{
		return array();
	}
}
