<?php 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participant_by_name.php');
include_once(LIBPATH.'/participants/set_participant.php');

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
		'p_new_participant' => 'Please provide at least one participant',
		'p_name' => 'Please provide a name',
		'p_nb_of_people' => 'Please provide a number of people'
 );
 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_new_participant' => 'Participants are not valid',
	'p_name' => 'Name is not valid',
	'p_nb_of_people' => 'Number of people is not valid',
	'p_email' => 'Email address is not valid'
 );


if(isset($_POST['submit_new_participant']))
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

	//Participants (possibly multiple)
	$key = 'p_new_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	
	if(empty($errArray))
	{
		foreach($_POST['p_new_participant'] as $caca=>$participant)
		{
			$errArray2 = array(); // Error array for each participant
			
			//Name
			$key = 'p_name';
			if(empty($participant[$key])) { //If empty
				continue;
			}
			else{
				$name_of_participant = $participant[$key];
			}
			
			//Number of people
			$key = 'p_nb_of_people';
			if(empty($participant[$key])) { //If empty
				array_push($errArray2, $ErrorEmptyMessage[$key]);
			}
			else{
				$nb_of_people = filter_var($participant[$key], FILTER_VALIDATE_INT);
				if($nb_of_people === false)
				{array_push($errArray2, $ErrorMessage[$key]);}
				else if($nb_of_people < 1)
				{array_push($errArray2, $ErrorMessage[$key]);}
			}
			
			//Hash id for the new participant
			$hashid_participant = "";
			if(empty($errArray2))
			{	
				$hashid_participant = create_hashid();
				if(is_null($hashid_participant))
					{ array_push($errArray2, "Server error: problem while creating hashid.");}
			}
			
			//Check if two participants have the same name
			if(empty($errArray2))
			{
				$does_this_guy_exists = get_participant_by_name($account['id'], $name_of_participant);
				if(!empty($does_this_guy_exists))
				{array_push($errArray2, 'A participant has the same name'); 	}
			}
			
			//Save the participant
			if(empty($errArray2))
			{
				$success = set_participant($account['id'], $hashid_participant, $name_of_participant, $nb_of_people);	
				if(!$success)
					{array_push($errArray2, 'Server error: Problem while attempting to add a participant'); 	}
				else
					{
						array_push($successArray, 'Participant '.$name_of_participant.' has been successfully added');
					}
			}
			
			//Merge the errors
			if(!empty($errArray2))
			{
				$errArray = array_merge($errArray, $errArray2);
			}
		} //Foreach participant
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
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin#participants';
}
header('location: '.$redirect_link);
exit;