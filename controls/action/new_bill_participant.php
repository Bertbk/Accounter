<?php 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participant_by_hashid.php');

include_once(LIBPATH.'/bills/get_bill_by_hashid.php');

include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');
include_once(LIBPATH.'/bill_participants/set_bill_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_bill_participant']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_bill' => 'Please provide a bill',
		'p_participant' => 'Please provide a participant',
		'p_hashid_participant' => 'Please provide a participant',
		'p_percent_of_use' => 'Please provide a percentage'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_hashid_bill' => 'Bill is not valid',
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

	// BILL
	$key = 'p_hashid_bill';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_bill = $_POST[$key];
			}
	}
	//Get the bill
	if(empty($errArray))
	{		
		$bill = get_bill_by_hashid($account['id'], $hashid_bill);
		if(empty($bill))
		{	array_push($errArray, $ErrorMessage['p_hashid_bill']); }
	}
	
	//Check if the accounts match between bill and account
	if(empty($errArray))
	{
		if($bill['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This bill does not belong to this account.');
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
		$key = 'p_hashid_participant';
		 if(empty($particip[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			if(validate_hashid($particip[$key])== false)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
			else{
				$hashid_participant = $particip[$key];
				}
		}
		//Get the participant
		if(empty($errArray2))
		{		
			$participant = get_participant_by_hashid($account['id'], $hashid_participant);
			if(empty($participant))
			{	array_push($errArray2, $ErrorMessage['p_hashid_participant']); }
		}
		
		// PERCENT OF USE
		$key = 'p_percent_of_use';
		if(empty($particip[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$percent_of_use = (float)$particip[$key];
			if($percent_of_use < 0 ||$percent_of_use > 100)
			{
				array_push($errArray2, $ErrorMessage['p_percent_of_use']);
			}
		}
		
		//Hash id for the new bill_participant
		$hashid_bill_participant = "";
		if(empty($errArray2))
		{	
			$hashid_bill_participant = create_hashid();
			if(is_null($hashid_bill_participant))
				{ array_push($errArray2, "Server error: problem while creating hashid.");}
		}

		
		//Check if the accounts match
		if(empty($errArray2))
		{
			if($participant['account_id'] !== $account['id'])
			{
				array_push($errArray2, 'This participant does not belong to this account.');
			}
			if($participant['account_id'] !== $bill['account_id'])
			{
				array_push($errArray2, 'Participant and bill do not belong to the same account');
			}
		}
		
		//Check if the bill_participant is not already affected to the bill
		if(empty($errArray2))
		{
			$registred_bill_part = get_bill_participants_by_bill_id($account['id'], $bill['id']);
			foreach ($registred_bill_part as $bill_part)
			{
					if($bill_part['participant_id'] == $participant['id'])
					{
						{array_push($errArray2, 'Participation already registred!'); 	}
					}
			}
		}

		//Save the bill_participant
		if(empty($errArray2))
		{
			$success = set_bill_participant($account['id'], $hashid_bill_participant, $bill['id'], $participant['id'], $percent_of_use);	
			if(!$success)
			{array_push($errArray2, 'Server error: Problem while attempting to add a participation'); 	}
			else
			{
				array_push($successArray, 'Participation has been successfully added');
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

if(empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
}
header('location: '.$redirect_link);