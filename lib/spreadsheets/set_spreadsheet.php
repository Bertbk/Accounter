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
Add a spreadsheet, ie: a row in the spreadsheets SQL table.
*/

include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_title.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheets.php');

include_once(LIBPATH.'/colors/give_me_next_color.php');

function set_spreadsheet($account_id_arg, $hashid_spreadsheet_arg, $type_of_spreadsheet_arg, $title_spreadsheet_arg, $description_arg="")
{
	$db = get_db();

	$account_id  = (int)$account_id_arg;
	$hashid_spreadsheet = $hashid_spreadsheet_arg;
	$title_spreadsheet  = $title_spreadsheet_arg;
	$type_spreadsheet  = $type_of_spreadsheet_arg;
	$description = $description_arg;
	$description = (empty($description))?null:$description;
	
	$does_this_spreadsheet_exists = get_spreadsheet_by_title($account_id, $title_spreadsheet);
	if(!empty($does_this_spreadsheet_exists))
	{
		return false;
	}
	
	$the_spreadsheets = get_spreadsheets($account_id);
	$my_color = give_me_next_color($the_spreadsheets, 'spreadsheet');
	//When color will come from users, check the reg ex

	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_SPREADSHEETS.'(id, account_id, hashid, type, title, description, color) 
		VALUES(NULL, :account_id, :hashid_spreadsheet, :type_spreadsheet, :title_spreadsheet, :description, :my_color)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid_spreadsheet', $hashid_spreadsheet, PDO::PARAM_STR);
		$prepare_query->bindValue(':title_spreadsheet', $title_spreadsheet, PDO::PARAM_STR);
		$prepare_query->bindValue(':type_spreadsheet', $type_spreadsheet, PDO::PARAM_STR);
		$prepare_query->bindValue(':description', $description, (is_null($description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':my_color', $my_color, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return $e;
	}
	return $isgood;
}