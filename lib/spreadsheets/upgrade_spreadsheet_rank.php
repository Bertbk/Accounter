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
Set rank-- to a spreadsheet, and move the other one.
*/

require_once(__DIR__.'/../get_db.php');
require_once(__DIR__.'/get_spreadsheet_by_id.php');
require_once(__DIR__.'/get_spreadsheet_by_rank.php');
require_once(__DIR__.'/set_spreadsheet_rank.php');

function upgrade_spreadsheet_rank($account_id_arg, $spreadsheet_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;	
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	
	$my_spreadsheet = get_spreadsheet_by_id($account_id, $spreadsheet_id);
	$my_rank = (int)$my_spreadsheet['rank'];
	
	if($my_rank > 0)
	{
		$spreadsheet_to_move = get_spreadsheet_by_rank($account_id, (int)($my_rank - 1));	
		$spreadsheet_id_to_move = $spreadsheet_to_move['id'];
		set_spreadsheet_rank($account_id, $spreadsheet_id, (int)($my_rank - 1));
		set_spreadsheet_rank($account_id, $spreadsheet_id_to_move, (int)($my_rank));
	}
	
}
