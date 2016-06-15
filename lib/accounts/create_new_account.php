<?php
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/hashid/validate_hashid.php');

function create_new_account($hashid_arg, $hashid_admin_arg, $title_of_account_arg, $contact_email_arg, $description_arg ="")
{
	$db = get_db();

	$hashid = $hashid_arg;
	if(validate_hashid($hash_id)== false)
	{
		return array();
	}
	
	$hashid_admin = $hashid_admin_arg;
	if(validate_hashid_admin($hashid_admin)== false)
	{
		return array();
	}

	$title_of_account = $title_of_account_arg;
	$contact_email = $contact_email_arg;
	$description = $description_arg;
	$description = (empty($description))?null:$description;

	try
	{
		$myquery = 'INSERT INTO '.TABLE_ACCOUNTS.'(id, hashid, hashid_admin, title, email, description) 
		VALUES(NULL, :hashid, :hashid_admin, :title, :email, :description)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':hashid_admin', $hashid_admin, PDO::PARAM_STR);
		$prepare_query->bindValue(':title', $title_of_account, PDO::PARAM_STR);
		$prepare_query->bindValue(':email', $contact_email, PDO::PARAM_STR);
		$prepare_query->bindValue(':description', $description, (is_null($description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
//		echo 'Fail to connect: ' . $e->getMessage();
	}
	
	return $isgood;
}
