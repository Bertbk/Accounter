<?php 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participant_by_hashid.php');
include_once(LIBPATH.'/participants/get_participant_by_id.php');

include_once(LIBPATH.'/bills/get_bill_by_id.php');

include_once(LIBPATH.'/bill_participants/get_bill_participant_by_hashid.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');
include_once(LIBPATH.'/bill_participants/update_bill_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$redirect_link ="" ;

if(isset($_POST['submit_update_bill_participant']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_bill_participant' => 'Please provide a participation',
		'p_participant' => 'Please provide a participant',
		'p_hashid_participant' => 'Please provide a participant',
		'p_percent_of_use' => 'Please provide a percentage'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_hashid_bill_participant' => 'Participation is not valid',
		'p_participant' => 'Participant is not valid',
		'p_hashid_participant' => 'Participant is not valid',
		'p_percent_of_use' => 'Percent is not valid'
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

	// BILL_PARTICIPANT
	$key = 'p_hashid_bill_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_bill_participant = $_POST[$key];
			}
	}
	//Get the bill_participant
	if(empty($errArray))
	{		
		$bill_participant = get_bill_participant_by_hashid($account['id'], $hashid_bill_participant);
		if(empty($bill_participant))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if the accounts match between bill and account
	if(empty($errArray))
	{
		if($bill_participant['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This participation does not belong to this account.');
		}
	}
	
	//NEW PARTICIPANT
	$key = 'p_hashid_participant';
	 if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_participant = $_POST[$key];
			}
	}
	//Get the participant
	if(empty($errArray))
	{		
		$new_participant = get_participant_by_hashid($account['id'], $hashid_participant);
		if(empty($new_participant))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
		
	// NEW PERCENT OF USE
	$key = 'p_percent_of_use';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_percent_of_use = (float)$_POST[$key];
		if($new_percent_of_use < 0 ||$new_percent_of_use > 100)
		{
			array_push($errArray, $ErrorMessage['p_percent_of_use']);
		}
	}

	//Get the (current) bill and the current participant
	$bill = get_bill_by_id($account['id'], $bill_participant['bill_id']);
	$participant = get_participant_by_id($account['id'], $bill_participant['participant_id']);
		
	//Check if the accounts match
	if(empty($errArray))
	{
		if($new_participant['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This participant does not belong to this account.');
		}
		if($new_participant['account_id'] !== $bill['account_id'])
		{
			array_push($errArray, 'Participant and bill do not belong to the same account');
		}
	}
		
	//Check if the bill_participant is not already affected to the bill
	if(empty($errArray) && $new_participant['id'] != $participant['id'])
	{
		$registred_bill_part = get_bill_participants_by_bill_id($account['id'], $bill['id']);
		foreach ($registred_bill_part as $bill_part)
		{
				if($bill_part['participant_id'] == $new_participant['id'])
				{
					{array_push($errArray, 'Participation already registred!'); 	}
				}
		}
	}

	//Update the bill_participant
	if(empty($errArray))
	{
		$success = update_bill_participant($account['id'], $bill_participant['id'], $new_participant['id'], $new_percent_of_use);	
		if(!$success)
		{array_push($errArray, 'Server error: Problem while attempting to update a participation'); 	}
	}
}

		
if(!(empty($errArray)))
{
	$_SESSION['errors'] = $errArray;
}

if(empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
}
header('location: '.$redirect_link);