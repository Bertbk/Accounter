<?php
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/update_account.php');
include_once(LIBPATH.'/accounts/get_account_admin.php');

//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

//ACCOUNT
$key = 'p_hashid_account';
if(empty($_POST[$key]))
{ //If empty
	array_push($errArray, $ErrorEmptyMessage[$key]);
}
else{
	if(validate_hashid_admin($_POST[$key]) == false)
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
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
}

if(isset($_POST['submit_cancel']))
{
	header('location:'.$redirect_link);
	exit;
}
if(isset($_POST['submit_update_account']))
{
	$ErrorMessage = array(
		'p_title_of_account' => 'Title is not valid',
		'p_author' => 'Author is not valid',
		'p_contact_email' => 'Email address is not valid',
		'p_description' => 'Description is not valid',
		"p_date_of_expiration" => 'Date of expiration not valid'
   );
	 	
	//TITLE
	$key = 'p_title_of_account';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_account_title = $_POST[$key];
	}

	//AUTHOR
	$key = 'p_author';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_account_author = $_POST[$key];
	}
	
	//CONTACT EMAIL
	$key = 'p_contact_email';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$account_email_tmp = filter_input(INPUT_POST, $key, FILTER_SANITIZE_EMAIL);
		$new_account_email = filter_var($account_email_tmp, FILTER_VALIDATE_EMAIL);
		if($new_account_email  == false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
	}
	
	//DESCRIPTION
	$key = 'p_description';
	if(empty($_POST[$key])) { //If empty
		$new_account_description = null;
	}
	else{
		$new_account_description = $_POST[$key];
	}
	
	//DATE OF EXPIRATIOn
	$key='p_date_of_expiration';
	if(empty($_POST[$key])) { //If empty
		if(empty($account['date_of_expiration']))
		{
			$date_of_expiration_tmp = new DateTime();
			$date_of_expiration_tmp->modify('+6 months');
			$new_date_of_expiration = date_format($date_of_expiration_tmp, 'Y-m-d');
		}
		else{
			$new_date_of_expiration = $account['date_of_expiration'];
		}
	}
	else{
		$new_date_of_expiration = $_POST[$key];
		$myDateTime = DateTime::createFromFormat('d/m/Y', $new_date_of_expiration);
		$new_date_of_expiration = $myDateTime->format('Y-m-d');
		$date_parsed = date_parse($new_date_of_expiration);
		if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year']))
		{
			array_push($warnArray, $WarningMessage[$key]);
			$date_of_expiration_tmp = new DateTime();
			$date_of_expiration_tmp->modify('+6 months');
			$new_date_of_expiration = date_format($date_of_expiration_tmp, 'Y-m-d');
		}
	}
		
	//Send to SQL
	if(empty($errArray))
	{
		$update_success = update_account($account['id'], $new_account_title, $new_account_author, $new_account_email, $new_account_description, $new_date_of_expiration);
		if(!$update_success)
		{
			array_push($errArray, 'Problem while updating account');
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
