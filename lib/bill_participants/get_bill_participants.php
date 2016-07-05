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
Return an array of the bill_participants of the account, sorted by bills
*/
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');

/*
Return every bill_participants of every bill. The reply is an array such that
$reply[bill_id] = array of bill_participants + name of participants + color  + hashid
*/
function get_bill_participants($account_id_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	
	$my_bills = get_bills($account_id);
	
	$reply = array();

	foreach($my_bills as $bill)
	{
		$reply[$bill['id']] = get_bill_participants_by_bill_id($account_id, $bill['id']);
	}
	return $reply;
}
