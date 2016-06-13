<?php 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participant_by_name.php');
include_once(LIBPATH.'/participants/set_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$create_success = false;
$errArray = array(); //error messages
$link_account ="" ;

if(isset($_POST['submit_new_participant']))
{
	$ErrorEmptyMessage = array(
		'p_name_of_participant' => 'Please provide a name',
		'p_nb_of_people' => 'Please provide a number of people',
		'p_hashid_account' => 'No acount number provided'
   );
	 
	$ErrorMessage = array(
		'p_name_of_participant' => 'Name is not valid',
		'p_nb_of_people' => 'Number of people is not valid',
		'p_hashid_account' => 'Account number not valid',
		'p_email' => 'Email address is not valid'
   );

	 
	$required_fields = array(
	'p_name_of_participant' => FILTER_SANITIZE_STRING,
	'p_nb_of_people' => array('filter'  => FILTER_VALIDATE_INT,
                           'options' => array('min_range' => 1)
														),
	'p_hashid_account' => array('filter' => FILTER_CALLBACK,
															'options'=>'filter_valid_hashid'														
														)
	);
	
	$opt_fields = array(
	'p_email' => FILTER_VALIDATE_EMAIL
	);

	//Manual loop on the parameters
	$key = 'p_name_of_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, ErrorEmptyMessage[$key]);
	}
	else{
		$result[$key] = $_POST[$key];
	}
	
	$key = 'p_nb_of_people';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, ErrorEmptyMessage[$key]);
	}
	else{
		$result[$key] = filter_var($result[$key], FILTER_VALIDATE_INT);
		if($result[$key] === false)
		{array_push($errArray, $ErrorMessage[$key]);}
	}
	
	$key = 'p_hashid_account';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, ErrorEmptyMessage[$key]);
	}
	else{
		$result[$key] = $_POST[$key];
		if(validate_hashid_admin($result[$key])== false)
		{array_push($errArray, $ErrorMessage[$key]);}
	}
	
	$key = 'p_email';
	if(!empty($_POST[$key]))
	{
		$result[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_EMAIL);
		$result[$key] = filter_var($result[$key], FILTER_VALIDATE_EMAIL);
		if($result[$key] === false)
		{array_push($errArray, $ErrorMessage[$key]);}
	}
	else{$result[$key] = null;}

	//Get the account
	if(empty($errArray))
	{		
		$account = get_account_admin($result['p_hashid_account']);
		if(empty($account))
		{	array_push($errArray, $ErrorMessage['p_hashid_account']); }
	}
	
	//Hash id for the new participant
	$hashid_participant = "";
	if(empty($errArray))
	{	
		$hashid_participant = create_hashid();
		if(is_null($hashid_participant))
			{ array_push($errArray, 'Server error: problem while creating hashid.');}
	}

	//Check if two participants have the same name
	if(empty($errArray))
	{
		$does_this_guy_exists = get_participant_by_name($account['id'], $result['p_name_of_participant']);
		if(!empty($does_this_guy_exists))
		{array_push($errArray, $ErrorMessage['A participant has the same name']); 	}
	}

	//Save the participant
	if(empty($errArray))
	{
		$success = set_participant($account_id_arg, $hash_id_arg, $name_of_participant_arg, $nb_of_people_arg, $email_arg);	
		if(!$success)
		{array_push($errArray, $ErrorMessage['Server error.']); 	}
	}
}

if(!(empty($errArray))
{
	$_SESSION['errors'] = $errArray;
}
		

$link_account = BASEURL.'/'$account['hashid_admin'].'/admin';
header('location: '.$link_account);