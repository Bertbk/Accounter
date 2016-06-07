<?php
function send_email_with_accounts($email_arg, $arrayOfAccounts_arg)
{
	$my_email = htmlspecialchars($email_arg);
	$my_email = filter_var($my_email, FILTER_VALIDATE_EMAIL);
	
	//Check is email is "valid"
	if(!$my_email)
	{
		return false;
	}

	$arrayOfAccounts = htmlspecialchars($arrayOfAccounts_arg);
	
	if(is_null($arrayOfAccounts) || empty($arrayOfAccounts))
	{return false;}

	//send email
	
	
	
	return true;
}
