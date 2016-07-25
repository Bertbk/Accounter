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
Check the data before asking the SQL to create a new member
 */

 
require_once __DIR__.'/../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/members/get_member_by_name.php');
include_once(LIBPATH.'/members/set_member.php');

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
		'p_new_member' => 'Please provide at least one member',
		'p_name' => 'Please provide a name',
		'p_nb_of_people' => 'Please provide a number of people'
 );
 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_new_member' => 'members are not valid',
	'p_name' => 'Name is not valid',
	'p_nb_of_people' => 'Number of people is not valid',
	'p_email' => 'Email address is not valid'
 );


if(isset($_POST['submit_new_member']))
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

	//members (possibly multiple)
	$key = 'p_new_member';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	
	if(empty($errArray))
	{
		foreach($_POST['p_new_member'] as $caca=>$member)
		{
			$errArray2 = array(); // Error array for each member
			
			//Name
			$key = 'p_name';
			if(empty($member[$key])) { //If empty
				continue;
			}
			else{
				$name_of_member = $member[$key];
			}
			
			//Number of people
			$key = 'p_nb_of_people';
			if(empty($member[$key])) { //If empty
				array_push($errArray2, $ErrorEmptyMessage[$key]);
			}
			else{
				$nb_of_people = filter_var($member[$key], FILTER_VALIDATE_INT);
				if($nb_of_people === false)
				{array_push($errArray2, $ErrorMessage[$key]);}
				else if($nb_of_people < 1)
				{array_push($errArray2, $ErrorMessage[$key]);}
			}
			
			//Hash id for the new member
			$hashid_member = "";
			if(empty($errArray2))
			{	
				$hashid_member = create_hashid();
				if(is_null($hashid_member))
					{ array_push($errArray2, "Server error: problem while creating hashid.");}
			}
			
			//Check if two members have the same name
			if(empty($errArray2))
			{
				$does_this_guy_exists = get_member_by_name($account['id'], $name_of_member);
				if(!empty($does_this_guy_exists))
				{array_push($errArray2, 'A member has the same name'); 	}
			}
			
			//Save the member
			if(empty($errArray2))
			{
				$success = set_member($account['id'], $hashid_member, $name_of_member, $nb_of_people);	
				if($success !== true)
					{array_push($errArray2, 'Server error: Problem while attempting to add a member'); 	}
				else
					{
						array_push($successArray, 'member '.$name_of_member.' has been successfully added');
					}
			}
			
			//Merge the errors
			if(!empty($errArray2))
			{
				$errArray = array_merge($errArray, $errArray2);
			}
		} //Foreach member
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
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin#members';
}
header('location: '.$redirect_link);
exit;