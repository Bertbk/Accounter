<?php
include_once('/lib/get_db.php');

function set_contributor($account_id_arg, $name_of_contrib_arg, $nb_of_parts_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$name_of_contrib = htmlspecialchars($name_of_contrib_arg);
	$nb_of_parts = (int)$nb_of_parts_arg;

	try
	{
		$myquery_add_contrib = 'INSERT INTO contributors(id, account_id, name, number_of_parts) VALUES(NULL, :account_id, :name_of_contrib, :nb_of_parts)';
		$prepare_query_add_contrib = $db->prepare($myquery_add_contrib);
		$prepare_query_add_contrib->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query_add_contrib->bindValue(':name_of_contrib', $name_of_contrib, PDO::PARAM_STR);
		$prepare_query_add_contrib->bindValue(':nb_of_parts', $nb_of_parts, PDO::PARAM_INT);
		$isgood = $prepare_query_add_contrib->execute();
		$prepare_query_add_contrib->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}