<?php
include_once(__DIR__.'/../get_db.php');

function get_account_admin($hash_id_admin_arg)
{
	$hash_id_admin = htmlspecialchars($hash_id_admin_arg);
	if(!is_string($hash_id_admin) || strlen($hash_id_admin) != 32)
	{
		return array();
	}
	
	$db = get_db();
	
	try
	{
		$myquery = 'SELECT * FROM '.TABLE_ACCOUNTS.'
		 WHERE hashid_admin=:hash_id_admin';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hash_id_admin', $hash_id_admin, PDO::PARAM_STR);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect : ' . $e->getMessage();
	}

	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	if(is_array($reply ) && sizeof($reply) > 0)
	{
		return $reply[0];
	}
	else
	{
		return array();
	}
}
