<?php
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/bills/get_bill_by_id.php');
include_once(LIBPATH.'/bills/get_bill_by_title.php');


function update_bill($account_id_arg, $bill_id_arg, $title_bill, $description_arg = "")
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;
	$new_title_of_bill = $title_bill;
	$new_description = $description_arg;
	$new_description = (empty($new_description))?null:$new_description;

	$bill_to_edit = get_bill_by_id($account_id, $bill_id);
	if(empty($bill_to_edit))
	{
		return false;
	}
	
	//Nothing to change?
	if($new_title_of_bill === $bill_to_edit['title'] 
	&& $new_description == $bill_to_edit['description'])
	{
		return false;
	}

	//If the title, we have to check if it's free
	if($new_title_of_bill != $bill_to_edit['title'])
	{
		$isthetitlefree = get_bill_by_title($account_id, $new_title_of_bill);
		if(!empty($isthetitlefree))
		{			return false;		}
	}

	try
	{
		$myquery = 'UPDATE '.TABLE_BILLS.'  
		SET title=:new_title_of_bill, description=:new_description
		WHERE id=:bill_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_title_of_bill', $new_title_of_bill, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_description', $new_description, (is_null($new_description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
