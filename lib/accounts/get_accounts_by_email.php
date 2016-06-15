<?php
include_once(__DIR__.'/../get_db.php');

function get_accounts_by_email($email_arg)
{
	$db = get_db();
	
	$my_email = filter_var($email_arg, FILTER_VALIDATE_EMAIL);
	
	//Check is email is "valid"
	if(!$my_email)
	{		return array();	}

	try
	{
		$myquery = 'SELECT * 
		FROM '.TABLE_ACCOUNTS.' 
		WHERE email=:my_email';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':my_email', $my_email, PDO::PARAM_STR);
		$prepare_query->execute();
		$reply = $prepare_query->fetchAll();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect : ' . $e->getMessage();
	}
	$prepare_query->closeCursor();
	return $reply;
}
