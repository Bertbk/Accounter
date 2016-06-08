<?php
include_once(__DIR__.'/../get_db.php');

function get_account($hash_id_arg)
{
	$hash_id = filter_var(htmlspecialchars($hash_id_arg), FILTER_SANITIZE_STRING);
	if(!is_string($hash_id) || !preg_match("/^[a-z0-9]{32}$/", $hash_id))
	{
		return array();
	}
	
	$db = get_db();
	
	try
	{
		$myquery = 'SELECT * FROM '.TABLE_ACCOUNTS.' 
		 WHERE hashid=:hash_id';
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
