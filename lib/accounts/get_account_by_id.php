<?php
include_once(__DIR__.'/../get_db.php');

function get_account_by_id($account_id_arg)
{
	$account_id = (int)$account_id_arg;

	$db = get_db();
	
	try
	{
		$myquery = 'SELECT * FROM '.TABLE_ACCOUNTS.' 
		 WHERE id=:account_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
//		echo 'Fail to connect: ' . $e->getMessage();
	}

	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	
	if(is_array($reply) && sizeof($reply) > 0)
	{
		return $reply[0];
	}
	else
	{
		return array();
	}
}
