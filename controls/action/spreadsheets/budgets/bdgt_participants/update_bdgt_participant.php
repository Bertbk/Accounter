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
Check the data before asking the SQL to update a bdgt_participant
 */


require_once __DIR__.'/../../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/members/get_member_by_hashid.php');
include_once(LIBPATH.'/members/get_member_by_id.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_id.php');

include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participant_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participants_by_spreadsheet_id.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/update_bdgt_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_bdgt_participant' => 'Please provide a participation',
		'p_member' => 'Please provide a member',
		'p_percent_of_benefit' => 'Please provide a percentage'
   );
	 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_bdgt_participant' => 'Participation is not valid',
	'p_member' => 'Member is not valid',
	'p_percent_of_benefit' => 'Percent is not valid',
	'p_anchor' => 'Anchor not valid'
 );


//Get the account
$key = 'p_hashid_account';
if(empty($_POST[$key])) { //If empty
	array_push($errArray, $ErrorEmptyMessage[$key]);
}
else{
	if(validate_hashid_admin($_POST[$key])== false)
	{
		array_push($errArray, $ErrorMessage[$key]);
	}
	else{
		$hashid_admin = $_POST[$key];
		}
}
//Get the account
if(empty($errArray))
{		
	$account = get_account_admin($hashid_admin);
	if(empty($account))
	{	array_push($errArray, $ErrorMessage['p_hashid_account']); }
}

//REDIRECTION LINK
if(!isset($account) ||empty($account))
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

if(isset($_POST['submit_update_bdgt_participant']))
{
	// bdgt_participant
	$key = 'p_hashid_bdgt_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_bdgt_participant = $_POST[$key];
			}
	}
	//Get the bdgt_participant
	if(empty($errArray))
	{		
		$bdgt_participant = get_bdgt_participant_by_hashid($account['id'], $hashid_bdgt_participant);
		if(empty($bdgt_participant))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if the accounts match between spreadsheet and account
	if(empty($errArray))
	{
		if($bdgt_participant['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This participation does not belong to this account.');
		}
	}
			
	// NEW PERCENT OF USE
	$key = 'p_percent_of_benefit';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_percent_of_benefit = (float)$_POST[$key];
		if($new_percent_of_benefit < 0 ||$new_percent_of_benefit > 100)
		{
			array_push($errArray, $ErrorMessage['p_percent_of_benefit']);
		}
	}
	
	//Update the bdgt_participant
	if(empty($errArray))
	{
		$success = update_bdgt_participant($account['id'], $bdgt_participant['id'], $new_percent_of_benefit);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to update a participant'); 	}
	else
		{
			array_push($successArray, 'Member has been successfully updated');
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

header('location: '.$redirect_link);
exit;