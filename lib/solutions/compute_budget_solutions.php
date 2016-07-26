<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 

/* Launch and store compute_budget_solution for every budget
 */
 
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/solutions/compute_budget_solution.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheets_by_type.php');

function compute_budget_solutions($account_id_arg)
{
	$db = get_db();
	
	$account_id = (int)$account_id_arg;
	
	$the_budgets = get_spreadsheets_by_type($account_id, "budget");
	
	$Refunds = array(array(array()));
	if(empty($the_budgets) || !isset($the_budgets)){return $Refunds;}
	foreach($the_budgets as $budget)
	{
		$Refunds[$budget['id']] = compute_budget_solution($account_id, $budget['id']);		
	}
	
	return $Refunds;
}
?>