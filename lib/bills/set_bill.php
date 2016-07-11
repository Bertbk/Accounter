<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 
/*
Add a bill, ie: a row in the bills SQL table.
*/

include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/bills/get_bill_by_title.php');
include_once(LIBPATH.'/bills/get_bills.php');

include_once(LIBPATH.'/colors/give_me_next_color.php');

function set_bill($account_id_arg, $hashid_bill_arg, $title_bill_arg, $description_arg="")
{
	$db = get_db();

	$account_id  = (int)$account_id_arg;
	$hashid_bill = $hashid_bill_arg;
	$title_bill  = $title_bill_arg;
	$description = $description_arg;
	$description = (empty($description))?null:$description;
	
	$does_this_bill_exists = get_bill_by_title($account_id, $title_bill);
	if(!empty($does_this_bill_exists))
	{
		return false;
	}
	
	$the_bills = get_bills($account_id);
	$my_color = give_me_next_color($the_bills, 'bill');
	//When color will come from users, check the reg ex
	
	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_BILLS.'(id, account_id, hashid, title, description, color) 
		VALUES(NULL, :account_id, :hashid_bill, :title_bill, :description, :my_color)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid_bill', $hashid_bill, PDO::PARAM_STR);
		$prepare_query->bindValue(':title_bill', $title_bill, PDO::PARAM_STR);
		$prepare_query->bindValue(':description', $description, (is_null($description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':my_color', $my_color, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}