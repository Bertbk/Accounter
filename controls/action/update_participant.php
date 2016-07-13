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
Check the data before asking the SQL to update a participant 
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participant_by_hashid.php');
include_once(LIBPATH.'/participants/get_participant_by_name.php');
include_once(LIBPATH.'/participants/update_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_participant' => 'Please provide a participant',
		'p_name_of_participant' => 'Please provide a name',
		'p_nb_of_people' => 'Please provide a number of people'
   );
	 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_participant' => 'Participant is not valid',
	'p_name_of_participant' => 'Name is not valid',
	'p_nb_of_people' => 'Number of people is not valid',
	'p_email' => 'Email address is not valid'
 );
 

//ACCOUNT
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
if(empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin#participants';
}

if(isset($_POST['submit_cancel']))
{
	header('location:'.$link_to_account_admin);
	exit;
}
else if(isset($_POST['submit_update_participant']))
{
	//PARTICIPANT
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
		$participant = get_participant_by_hashid($account['id'], $hashid_participant);
		if(empty($participant))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if accounts match
	if(empty($errArray))
	{		
		if($participant['account_id'] !== $account['id'])
		{	array_push($errArray, 'Accounts mismatch.'); }
	}

	//Get the (new) name of participant
	$key = 'p_name_of_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_name_of_participant = $_POST[$key];
	}
	
	//New number of people
	$key = 'p_nb_of_people';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_nb_of_people = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
		if($new_nb_of_people === false)
		{array_push($errArray, $ErrorMessage[$key]);}
		else if($new_nb_of_people < 0)
		{array_push($errArray, $ErrorMessage[$key]);}
	}
	
	//New email
	$key = 'p_email';
	if(!empty($_POST[$key]))
	{
		$new_email = filter_input(INPUT_POST, $key, FILTER_SANITIZE_EMAIL);
		$new_email = filter_var($new_email, FILTER_VALIDATE_EMAIL);
		if($new_email === false)
		{array_push($errArray, $ErrorMessage[$key]);}
	}
	else{$new_email = null;}

	
	//Check if two participants have the same name
	if(empty($errArray) && $new_name_of_participant !== $participant['name'])
	{
		$does_this_guy_exists = get_participant_by_name($account['id'], $new_name_of_participant);
		if(!empty($does_this_guy_exists))
		{array_push($errArray, 'Another participant has the same name'); 	}
	}
	
	//Save the participant
	if(empty($errArray))
	{
		$success = update_participant($account['id'], $participant['id'], $new_name_of_participant, $new_nb_of_people, $new_email);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to update a participant'); 	}
	else
		{
			array_push($successArray, 'Participant has been successfully updated');
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