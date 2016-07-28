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
Check the data before asking the SQL to delete every payments of a spreadsheet
 */

 require_once __DIR__.'/../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');
include_once(LIBPATH.'/accounts/delete_account.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');

include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participants_by_spreadsheet_id.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/delete_bdgt_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_remove_all_bdgt_participants']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_hashid_spreadsheet' => 'No spreadsheet provided'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account not valid',
		'p_hashid_spreadsheet' => 'Spreadsheet not valid',
		'p_anchor' => 'Anchor not valid'
   );

	//ACCOUNT
	$key = 'p_hashid_account';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid_admin($_POST[$key])== false)
		{array_push($errArray, $ErrorMessage[$key]);}
		else{
			$hashid_admin = $_POST[$key];
			}
	}
	//Get the account
	if(empty($errArray))
	{
		$account = get_account_admin($hashid_admin);
		if(empty($account))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}

	//spreadsheet
	$key = 'p_hashid_spreadsheet';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{array_push($errArray, $ErrorMessage[$key]);}
		else{
			$hashid_spreadsheet = $_POST[$key];
			}
	}
	//Get the spreadsheet
	if(empty($errArray))
	{
		$spreadsheet = get_spreadsheet_by_hashid($account['id'], $hashid_spreadsheet);
		if(empty($spreadsheet))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if spreadsheet belongs to the account
	if(empty($errArray))
	{
		if($spreadsheet['account_id'] !== $account['id'])
		{
			array_push($errArray, "This spreadsheet does not belong to this account!");
		}		
	}
		
	if(empty($errArray))
	{
		$bdgt_participants = get_bdgt_participants_by_spreadsheet_id($account['id'], $spreadsheet['id']);
		//Delete the participants
		foreach($bdgt_participants as $bdgt_participant)
		{
			$success = delete_bdgt_participant($account['id'], $bdgt_participant['id']);	
			if($success === true)
				{	array_push($successArray, 'Participation has been successfully deleted');}
			else
				{array_push($errArray, 'Server error: Problem while attempting to delete a participation: '.$success); 	}
		}
	}
}

if(!(empty($errArray)))
{
	$_SESSION['errors'] = $errArray;
}
if(!(empty($warnArray)))
{
	$_SESSION['warnings'] = $warnArray;
}
if(!(empty($successArray)))
{
	$_SESSION['success'] = $successArray;
}

if(!isset($account) || empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
	//Anchor
	if(empty($errArray))
	{		
		$key = 'p_anchor';
		if(isset($_POST[$key])) {
			$anchor = htmlspecialchars($_POST[$key]);
			$redirect_link = $redirect_link.$anchor ;
		}
	}
}

header('location: '.$redirect_link);
exit;