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
Check the data before asking the SQL to delete every spreadsheets of an account
The SQL should be done so that every dependent spreadsheet_participants and payments are also deleted
 */

require_once __DIR__.'/../../inc/init.php';

require_once(LIBPATH.'/accounts/get_account_admin.php');
require_once(LIBPATH.'/accounts/delete_account.php');

require_once(LIBPATH.'/spreadsheets/get_spreadsheets.php');
require_once(LIBPATH.'/spreadsheets/delete_spreadsheet.php');

require_once(LIBPATH.'/hashid/validate_hashid.php');

require_once __DIR__.'/../init_action.php';


if(isset($_POST['submit_remove_all_spreadsheets']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account not valid'
   );

	$spreadsheets = get_spreadsheets($account['id']);
	//Delete the participants
	foreach($spreadsheets as $spreadsheet)
	{
		$success = delete_spreadsheet($account['id'], $spreadsheet['id']);	
		if($success === true)
			{	array_push($successArray, 'Spreadsheet has been successfully deleted');}
		else
			{array_push($errArray, 'Server error: Problem while attempting to delete a spreadsheet: '.$success); 	}
	}
}
		
require_once __DIR__.'/../end_action.php';
