<?php
include_once('/lib/get_db.php');

function set_payment($account_id_arg, $payer_id_arg, $cost_arg, $receiver_id_arg, $description_arg = "", $date_arg = "0")
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$payer_id = (int)$payer_id_arg;
	$cost = (float)$cost_arg;
	$receiver_id = (int)$receiver_id_arg;
	$description = htmlspecialchars($description_arg);
	$the_date = (int)$date_arg;
	
	echo '<p>'.$account_id.'</p>';
	echo '<p>'.$payer_id.'</p>';
	echo '<p>'.$cost.'</p>';
	echo '<p>'.$receiver_id.'</p>';
	echo '<p>'.$description.'</p>';
	echo '<p>'.$the_date.'</p>';


	try
	{
		$myquery = 'INSERT INTO payments(id, account_id, payer_id, cost, receiver_id, description, date) VALUES(NULL, :account_id, :payer_id, :cost, :receiver_id, :description, :the_date)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':payer_id', $payer_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':cost', $cost, PDO::PARAM_STR);
		$prepare_query->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':description', $description, PDO::PARAM_STR);
		$prepare_query->bindValue(':the_date', $the_date, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}