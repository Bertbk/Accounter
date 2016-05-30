<?php
include_once(__DIR__.'/../get_db.php');

function delete_payment($account_id_arg, $payment_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payment_id = (int)$payment_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM payments 
		WHERE id=:payment_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
	
	return $isgood;
}