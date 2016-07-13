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
Deletes a article providing its id and the associated account id.
A participant is here a row in the articless SQL table 

Warning: a article points to a receipt_participant, not to a participant.
*/
include_once(__DIR__.'/../get_db.php');

function delete_article($account_id_arg, $article_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$article_id = (int)$article_id_arg;
		
	$isgood= false;
	try
	{
		$myquery = 'DELETE FROM '.TABLE_RECEIPT_ARTICLES.' 
		WHERE id=:article_id AND account_id=:account_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
	
	return $isgood;
}