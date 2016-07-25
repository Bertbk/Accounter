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
include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_title.php');

function get_spreadsheets($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;

	try
	{
		$myquery = 'SELECT * FROM  '.TABLE_SPREADSHEETS.'
		 WHERE account_id=:account_id ';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
	}
	catch (Exception $e)
	{
	 return 'Fail to connect: ' . $e->getMessage();
	}
	$reply = $prepare_query->fetchAll();
	$prepare_query->closeCursor();
	if(!empty($reply))
	{
		return $reply;
	}
	else
	{
		return array();
	}
}
