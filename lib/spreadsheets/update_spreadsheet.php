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
Update a spreadsheet providing its id and its associated account id.
A spreadsheet is a row in the spreadsheets SQL table.
*/
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_id.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_title.php');


function update_spreadsheet($account_id_arg, $spreadsheet_id_arg, $title_spreadsheet, $description_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	$new_title_of_spreadsheet = $title_spreadsheet;
	$new_description = $description_arg;
	$new_description = (empty($new_description))?null:$new_description;

	$spreadsheet_to_edit = get_spreadsheet_by_id($account_id, $spreadsheet_id);
	if(empty($spreadsheet_to_edit))
	{
		return false;
	}
	
	//Nothing to change?
	if($new_title_of_spreadsheet === $spreadsheet_to_edit['title'] 
	&& $new_description == $spreadsheet_to_edit['description'])
	{
		return false;
	}

	//If the title, we have to check if it's free
	if($new_title_of_spreadsheet != $spreadsheet_to_edit['title'])
	{
		$isthetitlefree = get_spreadsheet_by_title($account_id, $new_title_of_spreadsheet);
		if(!empty($isthetitlefree))
		{			return false;		}
	}

	try
	{
		$myquery = 'UPDATE '.TABLE_SPREADSHEETS.'  
		SET title=:new_title_of_spreadsheet, description=:new_description
		WHERE id=:spreadsheet_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_title_of_spreadsheet', $new_title_of_spreadsheet, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_description', $new_description, (is_null($new_description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
