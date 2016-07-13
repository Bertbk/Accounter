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
Return the receipt_receiver providing its hashid.
A receipt_receiver is a rown in receipt_receivers SQL table
*/
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/hashid/validate_hashid.php');

function get_receipt_receiver_by_hashid($account_id_arg, $receipt_receiver_hashid_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$receipt_receiver_hashid = $receipt_receiver_hashid_arg;
	if(validate_hashid($receipt_receiver_hashid)==false)
	{return false;}
	
	try
	{
		$myquery = 'SELECT * FROM '.TABLE_RECEIPT_RECEIVERS.' 
		 WHERE account_id=:account_id AND hashid=:receipt_receiver_hashid';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':receipt_receiver_hashid', $receipt_receiver_hashid, PDO::PARAM_STR);
		$prepare_query->execute();
	}
	catch (Exception $e)
	{
		return echo 'Fail to connect: ' . $e->getMessage();
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
