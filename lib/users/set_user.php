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
Add a user, that is a row in the users SQL table.
*/
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/users/get_user_by_name.php');
include_once(LIBPATH.'/users/get_users.php');

include_once(LIBPATH.'/colors/give_me_next_color.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');

function set_user($account_id_arg, $hashid_arg, $name_of_user_arg, $nb_of_people_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$name_of_user = $name_of_user_arg;
	$nb_of_people = (int)$nb_of_people_arg;
	
	if(validate_hashid($hashid) == false)
	{return false;}
	
	//Check if a user with the same name already exists
	$does_this_guy_exists = get_user_by_name($account_id, $name_of_user);
	if(!empty($does_this_guy_exists))
	{		return false;	}
	
	$the_users = get_users($account_id);
	$my_color = give_me_next_color($the_users, 'user');
	//When color will come from users, check the reg ex
	
	try
	{
		$myquery = 'INSERT INTO  '.TABLE_USERS.' (id, hashid,  account_id, name, nb_of_people, color) 
		VALUES(NULL, :hashid, :account_id, :name_of_user, :nb_of_people, :my_color)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':name_of_user', $name_of_user, PDO::PARAM_STR);
		$prepare_query->bindValue(':nb_of_people', $nb_of_people, PDO::PARAM_INT);
		$prepare_query->bindValue(':my_color', $my_color, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		//echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}