<?php
include_once(__DIR__.'/../get_db.php');

function get_payments($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;

	try
	{
		$myquery = 'SELECT payments.*, contribs1.name AS payer_name, 
		contribs2.name AS receiver_name FROM payments 
		LEFT  JOIN participants contribs1 ON contribs1.id=payments.payer_id 
		LEFT  JOIN participants contribs2 ON contribs2.id=payments.receiver_id
		WHERE payments.account_id=:account_id ';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		if(!$isgood)
		{echo '<p>PROBLEM '.$account_id.'</p>';}
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
