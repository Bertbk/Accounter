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
Returns every payments of a spreadsheet with id $spreadsheet_id_arg of the account of id $account_id_arg.
A payment is here a row in the payments SQL table 
JOINED WITH: creditor name and color, and debtor name/color (members SQL table).

Warning: a payment points to a bdgt_participant, not to a member.
*/

include_once(__DIR__.'/../../../get_db.php');

function get_bdgt_payments_by_spreadsheet_id($account_id_arg, $spreadsheet_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;

	$reply = array();
	try
	{
		$myquery = 'SELECT '.TABLE_BDGT_PAYMENTS.'.*, 
		bdgt_participant1.member_id AS creditor_member_id,
		bdgt_participant2.member_id AS debtor_member_id,
		members1.name AS creditor_name, members1.color AS creditor_color,
		members2.name AS debtor_name, members2.color AS debtor_color
		FROM '.TABLE_BDGT_PAYMENTS.' 
		LEFT  JOIN '.TABLE_BDGT_PARTICIPANTS.' bdgt_participant1 ON bdgt_participant1.id='.TABLE_BDGT_PAYMENTS.'.creditor_id 
		LEFT  JOIN '.TABLE_BDGT_PARTICIPANTS.' bdgt_participant2 ON bdgt_participant2.id='.TABLE_BDGT_PAYMENTS.'.debtor_id
		LEFT  JOIN '.TABLE_MEMBERS.' members1 ON members1.id=bdgt_participant1.member_id 
		LEFT  JOIN '.TABLE_MEMBERS.' members2 ON members2.id=bdgt_participant2.member_id
		WHERE '.TABLE_BDGT_PAYMENTS.'.account_id=:account_id AND '.TABLE_BDGT_PAYMENTS.'.spreadsheet_id=:spreadsheet_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
	}
	catch (Exception $e)
	{
		return array();
//		echo 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	return $reply;
	
}
