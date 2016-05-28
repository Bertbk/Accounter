<?php
include_once(__DIR__.'/../get_db.php');

/*
Return an array of every payments of the bill :
$reply is an array of size = number of payments.
$reply['id'] = array of payments associated to the bill. It also contains payment name and receiver name.
*/
function get_payments_by_bill_id($account_id_arg, $bill_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;

	$reply = array();
	try
	{
		$myquery = 'SELECT payments.*, contribs1.name AS payer_name, 
		contribs2.name AS receiver_name FROM payments 
		LEFT  JOIN participants contribs1 ON contribs1.id=payments.payer_id 
		LEFT  JOIN participants contribs2 ON contribs2.id=payments.receiver_id
		WHERE payments.account_id=:account_id AND payments.bill_id=:bill_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	return $reply;
	
}
