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
Return an array of every spreadsheets associated to the account of provided account id.
A spreadsheet is a row in the spreadsheets SQL table.
*/

include_once(__DIR__.'/../get_db.php');

function set_spreadsheet_rank($account_id_arg, $spreadsheet_id_arg, $rank_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	$rank = (int)$rank_arg;

	try
	{
		$myquery = 'UPDATE '.TABLE_SPREADSHEETS.' 
		SET rank=:rank 
		WHERE id=:spreadsheet_id AND account_id=:account_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':rank', $rank, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
	}
	catch (Exception $e)
	{
	 return 'Fail to connect: ' . $e->getMessage();
	}
	return '';
}
