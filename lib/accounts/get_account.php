<?php
include_once(__DIR__.'/../get_db.php');

function get_account($hash_id_arg)
{
	$hash_id = htmlspecialchars($hash_id_arg);

	if(!is_string($hash_id) || strlen($hash_id) != 16)
	{
		return array();
	}
	
	$db = get_db();
	
	try
	{
		$myquery = 'SELECT * FROM accounts WHERE hashid=:hash_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hash_id', $hash_id, PDO::PARAM_STR);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
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
