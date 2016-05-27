<?php
include_once(__DIR__.'/../get_db.php');

function get_bill_by_title($account_id_arg, $bill_title_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_title = htmlspecialchars($bill_title_arg);
	
	try
	{
		$myquery = 'SELECT * FROM bills WHERE account_id=:account_id AND upper(title)=upper(:bill_title)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bill_title', $bill_title, PDO::PARAM_STR); 
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
