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
Check the data before asking the SQL to create a new user
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/users/get_user_by_name.php');
include_once(LIBPATH.'/users/set_user.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_new_user' => 'Please provide at least one user',
		'p_name' => 'Please provide a name',
		'p_nb_of_people' => 'Please provide a number of people'
 );
 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_new_user' => 'users are not valid',
	'p_name' => 'Name is not valid',
	'p_nb_of_people' => 'Number of people is not valid',
	'p_email' => 'Email address is not valid'
 );


if(isset($_POST['submit_new_user']))
{
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

	//users (possibly multiple)
	$key = 'p_new_user';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	
	if(empty($errArray))
	{
		foreach($_POST['p_new_user'] as $caca=>$user)
		{
			$errArray2 = array(); // Error array for each user
			
			//Name
			$key = 'p_name';
			if(empty($user[$key])) { //If empty
				continue;
			}
			else{
				$name_of_user = $user[$key];
			}
			
			//Number of people
			$key = 'p_nb_of_people';
			if(empty($user[$key])) { //If empty
				array_push($errArray2, $ErrorEmptyMessage[$key]);
			}
			else{
				$nb_of_people = filter_var($user[$key], FILTER_VALIDATE_INT);
				if($nb_of_people === false)
				{array_push($errArray2, $ErrorMessage[$key]);}
				else if($nb_of_people < 1)
				{array_push($errArray2, $ErrorMessage[$key]);}
			}
			
			//Hash id for the new user
			$hashid_user = "";
			if(empty($errArray2))
			{	
				$hashid_user = create_hashid();
				if(is_null($hashid_user))
					{ array_push($errArray2, "Server error: problem while creating hashid.");}
			}
			
			//Check if two users have the same name
			if(empty($errArray2))
			{
				$does_this_guy_exists = get_user_by_name($account['id'], $name_of_user);
				if(!empty($does_this_guy_exists))
				{array_push($errArray2, 'A user has the same name'); 	}
			}
			
			//Save the user
			if(empty($errArray2))
			{
				$success = set_user($account['id'], $hashid_user, $name_of_user, $nb_of_people);	
				if($success !== true)
					{array_push($errArray2, 'Server error: Problem while attempting to add a user'); 	}
				else
					{
						array_push($successArray, 'user '.$name_of_user.' has been successfully added');
					}
			}
			
			//Merge the errors
			if(!empty($errArray2))
			{
				$errArray = array_merge($errArray, $errArray2);
			}
		} //Foreach user
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
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin#users';
}
header('location: '.$redirect_link);
exit;