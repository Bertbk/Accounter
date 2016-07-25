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
Lib: Update a bdgt_participant (a row in bdgt_participants Table).
return true if everything went fine
return error message or false otherwise
 */
 
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participants_by_bdgt_id.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participant_by_id.php');


function update_bdgt_participant($account_id_arg, $bdgt_participant_id_arg, $percent_of_usage_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	$bdgt_participant_id = (int)$bdgt_participant_id_arg;
	$new_percent_of_usage = (float)$percent_of_usage_arg;
	$new_percent_of_usage = is_null($new_percent_of_usage)?100:$new_percent_of_usage;
	$new_percent_of_usage = empty($new_percent_of_usage)?100:$new_percent_of_usage;
	
	//Get current bdgt_participant
	$bdgt_part_to_edit = get_bdgt_participant_by_id($account_id, $bdgt_participant_id);
	if(empty($bdgt_part_to_edit))
	{		return false;	}
	
	//Check new percentage
	if($new_percent_of_usage > 100 || $new_percent_of_usage < 0)
	{return false;}
	

	//Check if nothing to do
	if($new_percent_of_usage === $bdgt_part_to_edit['percent_of_usage'])
	{		return true;}
	
	$isgood= false;
	try
	{		
		$myquery = 'UPDATE '.TABLE_BDGT_PARTICIPANTS.' 
		SET percent_of_usage=:new_percent_of_usage
		WHERE id=:bdgt_participant_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_percent_of_usage', $new_percent_of_usage, PDO::PARAM_STR);
		$prepare_query->bindValue(':bdgt_participant_id', $bdgt_participant_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return $e->getMessage();
	}
	return $isgood;
}
