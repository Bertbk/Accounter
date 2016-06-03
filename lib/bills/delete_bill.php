<?php
include_once(__DIR__.'/../get_db.php');

function delete_bill($account_id_arg, $bill_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM  '.TABLE_BILLS.' 
		 WHERE id=:bill_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
	
	return $isgood;
}