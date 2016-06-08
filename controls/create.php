<?php
require_once __DIR__.'/../config-app.php';
include_once(LIBPATH.'/accounts/create_new_account.php');
include_once(LIBPATH.'/email/send_email_new_account.php');

$create_success = false;

if(isset($_POST['submit']))
{
	$title_of_account = filter_input(INPUT_POST, 'p_title_of_account', FILTER_SANITIZE_STRING);
	$contact_email = filter_input(INPUT_POST, 'p_contact_email', FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL);
	$description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
	$description = (empty($description))?null:$description;
	//Build first hashid
	do {
		$hashid = bin2hex(openssl_random_pseudo_bytes(8));
	}
	while(!$hashid);
	//Build second hashid
	do {
		$hashid_admin = bin2hex(openssl_random_pseudo_bytes(8));
	}
	while(!$hashid_admin);
	$hashid_admin = $hashid.$hashid_admin;

	$create_success = create_new_account($hashid, $hashid_admin, $title_of_account, $contact_email, $description);
	if(!$create_success)
	{
		echo '<p> Problem while creating account. Please try again</p>';
	}
	$email_sent = send_email_new_account($hashid);
	unset($_POST);
	$redirect_to_account_created = 'Location:'.BASEURL.'/account_created.php?hash='.$hashid.'&hash_admin='.$hashid_admin;
	$header_str = $redirect_to_account_created;
	header($header_str);
}

include_once(ABSPATH.'/templates/create.php');
