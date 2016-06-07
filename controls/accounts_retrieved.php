<?php 
require_once __DIR__.'/../config-app.php';
include_once(LIBPATH.'/accounts/get_accounts_by_email.php');
include_once(LIBPATH.'/email/send_email_with_accounts.php');

//GET ACCOUNTS BY EMAIL ADDRESS
$ArrayOfAccounts = array();
$is_sent = false;
if(isset($_POST['submit_email']))
{
	$p_email = filter_input(INPUT_POST, 'p_email', FILTER_SANITIZE_EMAIL);
	if(isset($p_email))
	{
		?>
<script>alert('caca');</script>		
		<?php
		$ArrayOfAccounts = get_accounts_by_email($p_email);
	}
	//Send to email
	if(!empty($ArrayOfAccounts))
	{
		$is_sent = send_email_with_accounts($p_email, $ArrayOfAccounts);
$is_sent = true;
		if($is_sent)
		{
			header('location: '.BASEURL.'/accounts_sent.php');
		}
		
	}
}
//Problem with the email
if(!$is_sent)
{
	header('location: '.BASEURL.'/retrieve_accounts.php?pb=1');
}