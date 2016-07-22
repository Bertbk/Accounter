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
Check the data before asking the SQL to update a user 
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/users/get_user_by_hashid.php');
include_once(LIBPATH.'/users/get_user_by_name.php');
include_once(LIBPATH.'/users/update_user.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_user' => 'Please provide a user',
		'p_name_of_user' => 'Please provide a name',
		'p_nb_of_people' => 'Please provide a number of people'
   );
	 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_user' => 'user is not valid',
	'p_name_of_user' => 'Name is not valid',
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
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin#users';
}

if(isset($_POST['submit_cancel']))
{
	header('location:'.$link_to_account_admin);
	exit;
}
else if(isset($_POST['submit_update_user']))
{
	//user
	$key = 'p_hashid_user';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_user = $_POST[$key];
			}
	}
	//Get the user
	if(empty($errArray))
	{		
		$user = get_user_by_hashid($account['id'], $hashid_user);
		if(empty($user))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if accounts match
	if(empty($errArray))
	{		
		if($user['account_id'] !== $account['id'])
		{	array_push($errArray, 'Accounts mismatch.'); }
	}

	//Get the (new) name of user
	$key = 'p_name_of_user';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_name_of_user = $_POST[$key];
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

	
	//Check if two users have the same name
	if(empty($errArray) && $new_name_of_user !== $user['name'])
	{
		$does_this_guy_exists = get_user_by_name($account['id'], $new_name_of_user);
		if(!empty($does_this_guy_exists))
		{array_push($errArray, 'Another user has the same name'); 	}
	}
	
	//Save the user
	if(empty($errArray))
	{
		$success = update_user($account['id'], $user['id'], $new_name_of_user, $new_nb_of_people, $new_email);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to update a user'); 	}
	else
		{
			array_push($successArray, 'user has been successfully updated');
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