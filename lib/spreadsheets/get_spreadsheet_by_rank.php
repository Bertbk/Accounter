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
Return a spreadsheet providing its rank and its associated account id.
A spreadsheet is a row in the spreadsheets SQL table.
*/
include_once(__DIR__.'/../get_db.php');

function get_spreadsheet_by_rank($account_id_arg, $spreadsheet_rank_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_rank = (int)$spreadsheet_rank_arg;

	try
	{
		$myquery = 'SELECT * FROM  '.TABLE_SPREADSHEETS.'
   		WHERE account_id=:account_id AND rank=:spreadsheet_rank';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_rank', $spreadsheet_rank, PDO::PARAM_INT);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		return array();
//		echo 'Fail to connect: ' . $e->getMessage();
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
