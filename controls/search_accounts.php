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
Control page: search for accounts in the SQL providing an email address
 */

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__.'/../config-app.php';
require_once LIBPATH.'/accounts/get_accounts_by_email.php';
require_once LIBPATH.'/email/send_email_with_accounts.php';

//Check token (avoid CSRF)
$token = $_SESSION['token'];
if ($_POST['p_token'] !== $_SESSION['token'])
{
	header('location: '.BASEURL);
	exit;
}	
unset($_SESSION['token']);
unset($_SESSION['token_time']);



$errorMessage = array(
'no_account' => 'Sorry, no account associated with the following email address has been found: ',
'email_not_sent' => 'Sorry, the email couldn\'t be sent',
'email_field_empty' => 'Please fill the email field',
'email_field_not_valid' => 'Email field is not valid'	
);

$errArray = array();
$warnArray = array();
$successArray = array();
//GET ACCOUNTS BY EMAIL ADDRESS
$ArrayOfAccounts = array();
$is_sent = false;
if(isset($_POST['submit_email']))
{
	$p_email = null;
	if(empty($_POST['p_email']))
	{
		array_push($errArray, $errorMessage['email_field_empty']);
	}
	if(filter_input(INPUT_POST, 'p_email', FILTER_VALIDATE_EMAIL) === false)
	{
		array_push($errArray, $errorMessage['email_field_not_valid']);
	}
	$p_email = filter_input(INPUT_POST, 'p_email', FILTER_SANITIZE_EMAIL);
	$ArrayOfAccounts = get_accounts_by_email($p_email);
	//Send to email
	if(!empty($ArrayOfAccounts))
	{
		$is_sent = send_email_with_accounts($p_email, $ArrayOfAccounts);
		if($is_sent === true)
		{
			array_push($successArray, 'Accounts have been sent!');
			header('location: '.BASEURL.'/accounts_sent.php');
			exit;
		}
		else
		{
			array_push($errArray, $errorMessage['email_not_sent'].' '.$is_sent);
		}
	}
	else
	{
		array_push($errArray, $errorMessage['no_account'].htmlspecialchars($p_email));
	}
}
else
{
		array_push($errArray, $errorMessage['email_field_empty']);
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

header('location: '.BASEURL.'/retrieve_accounts.php');
exit;
