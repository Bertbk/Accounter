<?php
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/accounts/get_account_by_id.php');

function update_account($account_id_arg, $title_of_account_arg, $author_arg, $contact_email_arg, $description_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$new_title = $title_of_account_arg;
	$new_author = $author_arg;
	$new_email = $contact_email_arg;
	if(isset($description_arg))
	{
		$new_description = $description_arg;
	}		else{
		$new_description = null;
	}

	$account = get_account_by_id($account_id);
	
	if(empty($account))
	{return false;}
	
	if($new_title === $account['title']
	&& $new_author === $account['author']
	&& $new_email === $account['email']	
	&& $new_description === $account['description']	
	)
	{return true;}
	
	try
	{
		$myquery = 'UPDATE  '.TABLE_ACCOUNTS.' 
		 SET title=:new_title, author=:new_author, email=:new_email, description=:new_description
		WHERE id=:account_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_title', $new_title, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_author', $new_author, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_email', $new_email, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_description', $new_description, (is_null($new_description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
//		echo 'Fail to connect: ' . $e->getMessage();
	}
	
	return $isgood;
}
