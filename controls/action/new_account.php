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
Check the data before asking the SQL to create a new account */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/create_new_account.php');
include_once(LIBPATH.'/email/send_email_new_account.php');
include_once(LIBPATH.'/hashid/create_hashid.php');

//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_account']))
{
	$ErrorMessage = array(
		'p_title_of_account' => 'Title is not valid',
		'p_author' => 'Author is not valid',
		'p_contact_email' => 'Email address is not valid',
		'p_description' => 'Description is not valid'
   );
	 
	//Manual treatments of arguments
	
	//TITLE
	$key = 'p_title_of_account';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$account_title = $_POST[$key];
	}

	//AUTHOR
	$key = 'p_author';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$account_author = $_POST[$key];
	}
	
	//CONTACT EMAIL
	$key = 'p_contact_email';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$account_email_tmp = filter_input(INPUT_POST, $key, FILTER_SANITIZE_EMAIL);
		$account_email = filter_var($account_email_tmp, FILTER_VALIDATE_EMAIL);
		if($account_email  == false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
	}
	
	//DESCRIPTION
	$key = 'p_description';
	if(empty($_POST[$key])) { //If empty
		$account_description = null;
	}
	else{
		$account_description = $_POST[$key];
	}
	
	
	//Hash id
	if(empty($errArray))
	{
		$hashid = create_hashid();
		$hashid_admin = create_hashid();
		if(is_null($hashid) ||is_null($hashid_admin))
		{
			array_push($errArray, 'Server error: problem while creating hashid.');
		}
		else
		{
			$hashid_admin = $hashid.$hashid_admin;
		}
	}
	
	//Create date
	$date_of_creation = new DateTime();
	$date_of_expiration = new DateTime();
	$date_of_expiration->modify('+6 months');

	//Send to SQL
	if(empty($errArray))
	{
		$create_success = create_new_account($hashid, $hashid_admin, $account_title, $account_author, $account_email, $account_description, date_format($date_of_creation, 'Y-m-d'), date_format($date_of_expiration, 'Y-m-d'));
		if(!$create_success)
		{
			array_push($errArray, 'Problem while creating account. Please try again');
		}
		else{
			$email_sent = send_email_new_account($hashid);
			if($email_sent == false)
			{
				array_push($warnArray, 'Problem while sending email. Account has been created though.');
			}
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

if(empty($errArray))
{
	$redirect_link = BASEURL.'/account_created.php?hash='.$hashid.'&hash_admin='.$hashid_admin;
}
else{
	$redirect_link = BASEURL.'/create.php';
}

header('location: '.$redirect_link);
exit;
