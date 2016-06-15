<?php 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/bill_participants/get_bill_participant_by_hashid.php');
include_once(LIBPATH.'/bill_participants/delete_bill_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$redirect_link ="" ;

// the "_x" is here because the button is an image
if(isset($_POST['submit_delete_bill_participant_x']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_hashid_bill_participant' => 'No participation provided'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account not valid',
		'p_hashid_bill_participant' => 'Participation not valid'
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
		{	array_push($errArray, $ErrorMessage['p_hashid_account']); }
	}

	//BILL PARTICIPANT
	$key = 'p_hashid_bill_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key]) == false)
		{array_push($errArray, $ErrorMessage[$key]);}
	else{
		$hashid_bill_participant = $_POST[$key];		
		}
	}
	//Get the bill participant
	if(empty($errArray))
	{		
		$bill_participant = get_bill_participant_by_hashid($account['id'], $hashid_bill_participant);
		if(empty($bill_participant))
		{	array_push($errArray, $ErrorMessage['p_hashid_bill_participant']); }
	}

	//Check if accounts match
	if(empty($errArray))
	{		
		if($bill_participant['account_id'] !== $account['id'])
		{	array_push($errArray, $ErrorMessage['Accounts mismatch']); }
	}
	
	//Delete the bill participant
	if(empty($errArray))
	{
		$success = delete_bill_participant($account['id'], $bill_participant['id']);	
		if(!$success)
		{array_push($errArray, 'Server error: Problem while attempting to delete a participation'); 	}
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