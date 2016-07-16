<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 

/* Launch and store compute_receipt_solution for every receipt
 */
 
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/solutions/compute_receipt_solution.php');
include_once(LIBPATH.'/receipts/get_receipts.php');

function compute_receipt_solutions($account_id_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	
	$the_receipts = get_receipts($account_id);
	
	$Refunds = array(array(array()));
	if(empty($the_receipts) || !isset($the_receipts)){return $Refunds;}
	foreach($the_receipts as $receipt)
	{
		$Refunds[$receipt['id']] = compute_receipt_solution($account_id, $receipt['id']);		
	}
	
	return $Refunds;
}
?>