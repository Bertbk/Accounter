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
Lib: Add a row in the bdgt_participants SQL table
 */
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bdgts/get_bdgt_by_id.php');
include_once(LIBPATH.'/participants/get_participant_by_id.php');
include_once(LIBPATH.'/bdgt_participants/get_bdgt_participants_by_bdgt_id.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


function set_bdgt_participant($account_id_arg, $hashid_arg, $bdgt_id_arg, $participant_id_arg, $percent_of_use_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$bdgt_id = (int)$bdgt_id_arg;
	$participant_id = (int)$participant_id_arg;
	$percent_of_use = (float)$percent_of_use_arg;
	
	//Check
	$the_bdgt = get_bdgt_by_id($account_id, $bdgt_id);
	if(empty($the_bdgt)){return false;}
	$the_participant = get_participant_by_id($account_id, $participant_id);
	if(empty($the_participant)){return false;}
	//Same account ? (double check)
	if($the_participant['account_id'] != $the_bdgt['account_id'])
	{return false;}

	if(validate_hashid($hashid) === false)
	{return false;}

	//check that the entry is not already existant
	$bdgt_participants = get_bdgt_participants_by_bdgt_id($account_id, $bdgt_id);
	foreach ($bdgt_participants as $bdgt_part)
	{
			if($bdgt_part['participant_id'] == $participant_id)
			{
				return false;
			}
	}
	

	$percent_of_use = is_null($percent_of_use)?100:$percent_of_use;
	
	if($percent_of_use > 100 || $percent_of_use < 0)
	{
		return false;
	}
	
	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_BDGT_PARTICIPANTS.'(id, account_id, bdgt_id, hashid, participant_id, percent_of_usage) 
		VALUES(NULL, :account_id, :bdgt_id, :hashid, :participant_id, :percent_of_use)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bdgt_id', $bdgt_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':participant_id', $participant_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':percent_of_use', $percent_of_use, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}