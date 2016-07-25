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
Return an array of the bdgt_participants of the account, sorted by bdgts
*/
include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheets_by_type.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participants_by_bdgt_id.php');

/*
Return every bdgt_participants of every bdgt. The reply is an array such that
$reply[bdgt_id] = array of bdgt_participants + name of participants + color  + hashid
*/
function get_bdgt_participants($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_bdgts = get_spreadsheets_by_type($account_id, "budget");
	
	$reply = array();

	foreach($my_bdgts as $bdgt)
	{
		$reply[$bdgt['id']] = get_bdgt_participants_by_bdgt_id($account_id, $bdgt['id']);
	}
	return $reply;
}
