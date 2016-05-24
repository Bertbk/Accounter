<?php
include_once('/lib/create_new_account.php');

$isdone = false;

if(isset($_POST['submit']))
{
	$title_of_account = filter_input(INPUT_POST, 'title_of_account', FILTER_SANITIZE_STRING);
	$contact_email = filter_input(INPUT_POST, 'contact_email', FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL);
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

	$isdone = create_new_account($hashid, $hashid_admin, $title_of_account, $contact_email);
	if(!$isdone)
	{
		echo '<p> Problem while creating account. Please try again</p>';
	}
	else
	{
		$link_contrib = '/DivideTheBill/account/'.$hashid;
		$link_admin = '/DivideTheBill/account/'.$hashid_admin.'/admin';		
	}
	unset($_POST);
	$header_str = 'Location:/DivideTheBill/account_created.php?hash='.$hashid.'&hash_admin='.$hashid_admin;
	header($header_str);
}

include_once('/templates/create.php');
?>