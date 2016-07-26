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
Delete a row in the rcpt_recipients SQL table providing its id 
*/

include_once(__DIR__.'/../../../get_db.php');

function delete_rcpt_recipient($account_id_arg, $rcpt_recipient_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$receiver_id = (int)$rcpt_recipient_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM '.TABLE_RCPT_RECIPIENTS.'  
		 WHERE id=:receiver_id AND account_id=:account_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
	 return 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
	
}