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
Check the data before asking the SQL to assign a participant to a spreadsheet
 */
 
require_once __DIR__.'/../../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/members/get_member_by_hashid.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');

include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participant_by_member_id.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/set_bdgt_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_bdgt_participant']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_spreadsheet' => 'Please provide a spreadsheet',
		'p_participant' => 'Please provide a participant',
		'p_hashid_member' => 'Please provide a participant',
		'p_percent_of_benefit' => 'Please provide a percentage'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_hashid_spreadsheet' => 'spreadsheet is not valid',
		'p_participant' => 'Participant is not valid',
		'p_hashid_member' => 'Participant is not valid',
		'p_percent_of_benefit' => 'Percent is not valid',
		'p_anchor' => 'Anchor not valid'
   );

	//Manual treatments of arguments
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

	// spreadsheet
	$key = 'p_hashid_spreadsheet';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
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
		else{
			if($spreadsheet['type_of_sheet'] !== 'budget')
			{	array_push($errArray, $ErrorMessage[$key]); }
		}
	}
	
	//Check if the accounts match between spreadsheet and account
	if(empty($errArray))
	{
		if($spreadsheet['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This spreadsheet does not belong to this account.');
		}
	}
	
	// PARTICIPANT (possibly multiples !)
	$key = 'p_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	if(empty($errArray))
	{
//Loop now on every participants
	 foreach ($_POST['p_participant'] as $particip)
	 {
		$errArray2 = array(); // Error array for each participant
		$key = 'p_hashid_member';
		 if(empty($particip[$key])) { //If empty
			continue;
		}
		else{
			if(validate_hashid($particip[$key])== false)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
			else{
				$hashid_member = $particip[$key];
				}
		}
		//Get the participant
		if(empty($errArray2))
		{		
			$member = get_member_by_hashid($account['id'], $hashid_member);
			if(empty($member))
			{	array_push($errArray2, $ErrorMessage['p_hashid_member']); }
		}
		
		// PERCENT OF USE
		$key = 'p_percent_of_benefit';
		if(!isset($particip[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$percent_of_benefit = (float)$particip[$key];
			if($percent_of_benefit < 0 
				|| $percent_of_benefit > 100)
			{
				array_push($errArray2, $ErrorMessage[$key].': '.$percent_of_benefit);
			}
		}
		
		//Hash id for the new bdgt_participant
		$hashid_bdgt_participant = "";
		if(empty($errArray2))
		{	
			$hashid_bdgt_participant = create_hashid();
			if(is_null($hashid_bdgt_participant))
				{ array_push($errArray2, "Server error: problem while creating hashid.");}
		}

		
		//Check if the accounts match
		if(empty($errArray2))
		{
			if($member['account_id'] !== $account['id'])
			{
				array_push($errArray2, 'This member does not belong to this account.');
			}
			if($member['account_id'] !== $spreadsheet['account_id'])
			{
				array_push($errArray2, 'Member and spreadsheet do not belong to the same account');
			}
		}
		
		//Check if the bdgt_participant is not already affected to the spreadsheet
		if(empty($errArray2))
		{
			$registred_bdgt_part = get_bdgt_participant_by_member_id($account['id'], $spreadsheet['id'], $member['id']);
			if(!empty($registred_bdgt_part))
			{
				{array_push($errArray2, 'Member already assigned to this budget sheet!'); 	}
			}
		}
	
		//Save the bdgt_participant
		if(empty($errArray2))
		{
			$success = set_bdgt_participant($account['id'], $hashid_bdgt_participant, $spreadsheet['id'], $member['id'], $percent_of_benefit);	
			if($success !== true)
			{array_push($errArray2, 'Server error: Problem while attempting to add a participant '.$success); 	}
			else
			{
				array_push($successArray, 'participant has been successfully added');
			}
		}
		//Merge the errors
		if(!empty($errArray2))
		{
			$errArray = array_merge($errArray, $errArray2);
		}
	 }//Loop on participant

 }//If statement
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
header('location: '.$redirect_link);
exit;