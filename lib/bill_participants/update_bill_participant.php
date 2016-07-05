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
Lib: Update a bill_participant (a row in bill_participants Table).
return true if everything went fine
return error message or false otherwise
 */
 
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');
include_once(LIBPATH.'/bill_participants/get_bill_participant_by_id.php');


function update_bill_participant($account_id_arg, $bill_participant_id_arg, $percent_of_usage_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	$bill_participant_id = (int)$bill_participant_id_arg;
	$new_percent_of_usage = (float)$percent_of_usage_arg;
	$new_percent_of_usage = is_null($new_percent_of_usage)?100:$new_percent_of_usage;
	$new_percent_of_usage = empty($new_percent_of_usage)?100:$new_percent_of_usage;
	
	//Get current bill_participant
	$bill_part_to_edit = get_bill_participant_by_id($account_id, $bill_participant_id);
	if(empty($bill_part_to_edit))
	{		return false;	}
	
	//Check new percentage
	if($new_percent_of_usage > 100 || $new_percent_of_usage < 0)
	{return false;}
	

	//Check if nothing to do
	if($new_percent_of_usage === $bill_part_to_edit['percent_of_usage'])
	{		return true;}
	
	$isgood= false;
	try
	{		
		$myquery = 'UPDATE '.TABLE_BILL_PARTICIPANTS.' 
		SET percent_of_usage=:new_percent_of_usage
		WHERE id=:bill_participant_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_percent_of_usage', $new_percent_of_usage, PDO::PARAM_STR);
		$prepare_query->bindValue(':bill_participant_id', $bill_participant_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return $e->getMessage();
	}
	return $isgood;
}
