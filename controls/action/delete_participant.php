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
Check the data before asking the SQL to delete a participant
The SQL should be done so that every depending bill_participant and payment are also deleted
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participant_by_hashid.php');
include_once(LIBPATH.'/participants/delete_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_delete_participant']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_hashid_participant' => 'No participant provided'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account not valid',
		'p_hashid_participant' => 'Participant not valid'
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

	//PARTICIPANT
	$key = 'p_hashid_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key]) == false)
		{array_push($errArray, $ErrorMessage[$key]);}
	else{
		$hashid_participant = $_POST[$key];		
		}
	}
	//Get the participant
	if(empty($errArray))
	{		
		$participant = get_participant_by_hashid($account['id'], $hashid_participant);
		if(empty($participant))
		{	array_push($errArray, $ErrorMessage['p_hashid_participant']); }
	}

	//Check if accounts match
	if(empty($errArray))
	{		
		if($participant['account_id'] !== $account['id'])
		{	array_push($errArray, $ErrorMessage['Accounts mismatch']); }
	}
	
	//Delete the participant
	if(empty($errArray))
	{
		$success = delete_participant($account['id'], $participant['id']);	
		if(!$success)
		{array_push($errArray, 'Server error: Problem while attempting to delete a participant'); 	}
			else
			{
				array_push($successArray, 'Participant has been successfully deleted');
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

if(empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin#participants';
}
header('location: '.$redirect_link);
exit;