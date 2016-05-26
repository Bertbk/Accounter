<?php
include_once('/lib/get_db.php');

function get_contributor_by_id($account_id_arg, $contrib_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$contrib_id = (int)$contrib_id_arg;

	try
	{
		$myquery = 'SELECT * FROM contributors WHERE account_id=:account_id AND id=:contrib_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':contrib_id', $contrib_id, PDO::PARAM_INT);
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
?>