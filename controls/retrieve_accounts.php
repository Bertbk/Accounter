<?php 
require_once __DIR__.'/../config-app.php';

$errorMessage = array(
'no_account' => 'Sorry, no account associated with this email address has been found. 
	<br> Please try again with another email address',
'email_not_sent' => 'Sorry, the email couldn\'t be sent',
'email_field_empty' => 'Please fill the email field',
'email_field_not_valid' => 'Email field is not valid'	
);

$errArray = array();
//GET ACCOUNTS BY EMAIL ADDRESS
$ArrayOfAccounts = array();
$is_sent = false;
if(isset($_POST['submit_email']))
{
	$p_email = null;
	if(empty($_POST['p_email']))
	{
		array_push($errArray, $errorMessage['no_account']);
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
		if($is_sent)
		{
			header('location: '.BASEURL.'/accounts_sent.php');
		}
		else
		{
		array_push($errArray, $errorMessage['email_not_sent']);
		}
	}
	else
	{
		array_push($errArray, $errorMessage['no_account']);
	}
}
else
{
		array_push($errArray, $errorMessage['email_field_empty']);
}


include_once(ABSPATH.'/templates/retrieve_accounts.php');