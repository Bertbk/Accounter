<?php
include_once('/lib/get_db.php');

function get_payment_by_hashid($account_id_arg, $payment_hashid_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payment_hashid = htmlspecialchars($payment_hashid_arg);
	
	try
	{
		$myquery = 'SELECT * FROM payments WHERE account_id=:account_id AND hashid=:payment_hashid';
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
		return $reply;
	}
	else
	{
		return array();
	}
}