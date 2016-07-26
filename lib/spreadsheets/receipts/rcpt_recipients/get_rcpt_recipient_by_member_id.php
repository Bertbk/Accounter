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
Return the rcpt_recipient providing its id.
A rcpt_recipient is a rown in rcpt_recipients SQL table
*/

include_once(__DIR__.'/../../../get_db.php');

function get_rcpt_recipient_by_member_id($account_id_arg, $spreadsheet_id_arg, $article_id_arg, $member_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	$article_id = (int)$article_id_arg;
	$member_id = (int)$member_id_arg;

	try
	{
		$myquery = 'SELECT * FROM '.TABLE_RCPT_RECIPIENTS.' 
		 WHERE account_id=:account_id AND member_id=:member_id
		 AND spreadsheet_id=:spreadsheet_id AND article_id=:article_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':member_id', $member_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		return 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	if(!empty($reply))
	{
		return $reply[0];
	}
	else
	{
		return array();
	}
}
